<?php

namespace App\Http\Controllers;

use App\Models\AllLedger;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    protected $teachers;

    public function __construct(TeacherRepositoryInterface $teachers)
    {
        $this->teachers = $teachers;
    }

    public function index(Request $request)
    {
        // Agar Panel User (Teacher) hai → sirf apna record dikhao
        if (session('is_panel_user')) {
            $assignment = auth()->user()->userAssignment;

            if ($assignment && $assignment->panel_type === 'teacher' && $assignment->assignable_type === 'App\\Models\\Teacher') {
                $teacher = \App\Models\Teacher::findOrFail($assignment->assignable_id);
                $data = collect([$teacher]); // Sirf apna record

                return view('teachers.index', compact('data'));
            }
        }

        // Admin ke liye sab dikhao
        $data = $this->teachers->paginate(10);

        return view('teachers.index', compact('data'));
    }

    public function create()
    {
        // Only Admin can create
        if (session('is_panel_user')) {
            abort(403, 'Unauthorized');
        }

        return view('teachers.create');
    }


    public function store(Request $request)
{
    if (session('is_panel_user')) {
        abort(403);
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'cnic' => 'required|string|max:20|unique:teachers,cnic',
        'phone' => 'nullable|string',
        'email' => 'nullable|email|max:255|unique:teachers,email',
        'description' => 'nullable|string|max:280',
        'address' => 'nullable|string|max:500',
        'salary' => 'required|numeric|min:0',
        'picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'cnic_front_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'cnic_back_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    try {
        $input = $request->only([
            'name', 'cnic', 'phone', 'email',
            'description', 'address'
        ]);

        // ✅ Salary only in teachers table
        $input['salary'] = $request->salary;

        if ($request->hasFile('picture')) {
            $input['picture'] = $request->file('picture')->store('teachers/pictures', 'public');
        }
        if ($request->hasFile('cnic_front_image')) {
            $input['cnic_front_image'] = $request->file('cnic_front_image')->store('teachers/cnic', 'public');
        }
        if ($request->hasFile('cnic_back_image')) {
            $input['cnic_back_image'] = $request->file('cnic_back_image')->store('teachers/cnic', 'public');
        }

        $input['academic_details'] = $request->academic ?? [];

        $this->teachers->create($input);

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher created successfully.');

    } catch (QueryException $e) {
        return back()->withInput()->withErrors([
            'db_error' => 'Database error: '.$e->getMessage()
        ]);
    }
}


    public function show($id)
    {
        $teacher = $this->teachers->find($id);
        abort_if(!$teacher, 404);

        // Agar Panel User → sirf apna record dikhao
        if (session('is_panel_user')) {
            $assignment = auth()->user()->userAssignment;
            if (!$assignment || $assignment->assignable_id != $id || $assignment->panel_type !== 'teacher') {
                abort(403);
            }
        }

        $teacher->salary = AllLedger::where('teacher_id', $teacher->id)
            ->where('ledger_category', 'salary')
            ->latest()
            ->value('amount') ?? 0;

        return view('teachers.view', compact('teacher'));
    }

public function edit($id)
{
    if (session('is_panel_user')) {
        abort(403);
    }

    $teacher = $this->teachers->find($id);
    abort_if(!$teacher, 404);



    return view('teachers.edit', compact('teacher'));
}


    
public function update(Request $request, $id)
{
    if (session('is_panel_user')) {
        abort(403);
    }

    $teacher = $this->teachers->find($id);
    abort_if(!$teacher, 404);

    $request->validate([
        'name' => 'required|string|max:255',
        'cnic' => 'required|string|max:20|unique:teachers,cnic,'.$id,
        'email' => 'nullable|email|max:255|unique:teachers,email,'.$id,
        'phone' => 'nullable|string',
        'address' => 'nullable|string|max:500',
        'description' => 'nullable|string|max:280',
        'salary' => 'required|numeric|min:0',
    ]);

    try {
        $input = $request->only([
            'name', 'cnic', 'phone', 'email',
            'description', 'address'
        ]);

        // ✅ Salary update only here
        $input['salary'] = $request->salary;

        $this->teachers->update($id, $input);

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher updated successfully.');

    } catch (QueryException $e) {
        return back()->withErrors([
            'db_error' => $e->getMessage()
        ]);
    }
}


    public function destroy($id)
    {
        if (session('is_panel_user')) {
            abort(403);
        }

        $teacher = $this->teachers->find($id);
        abort_if(!$teacher, 404);

        // Delete images
        foreach (['picture', 'cnic_front_image', 'cnic_back_image'] as $field) {
            if ($teacher->$field) {
                Storage::disk('public')->delete($teacher->$field);
            }
        }
        if ($teacher->academic_details) {
            foreach ($teacher->academic_details as $a) {
                if (!empty($a['image'])) {
                    Storage::disk('public')->delete($a['image']);
                }
            }
        }

        AllLedger::where('teacher_id', $teacher->id)->delete();
        $teacher->forceDelete();

        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
    }

    public function ledger($id)
    {
        $teacher = $this->teachers->find($id);
        abort_if(!$teacher, 404);

        // Panel User → sirf apna ledger dikhao
        if (session('is_panel_user')) {
            $assignment = auth()->user()->userAssignment;
            if (!$assignment || $assignment->assignable_id != $id || $assignment->panel_type !== 'teacher') {
                abort(403);
            }
        }

        $ledgers = AllLedger::where('teacher_id', $teacher->id)
            ->orderBy('created_at', 'desc')
            ->get();

      

        return view('teachers.teacher_ledger', compact('teacher', 'ledgers'));
    }

    public function allLedger()
    {
        // Agar Panel Teacher → sirf apna ledger dikhao
        if (session('is_panel_user')) {
            $assignment = auth()->user()->userAssignment;
            if ($assignment && $assignment->panel_type === 'teacher') {
                $teacher = \App\Models\Teacher::findOrFail($assignment->assignable_id);
                $teachers = collect([$teacher]);
            }
        } else {
            $teachers = $this->teachers->all();
        }

        foreach ($teachers as $t) {
            $t->latest_salary = AllLedger::where('teacher_id', $t->id)
                ->where('ledger_category', 'salary')
                ->latest()
                ->value('amount') ?? 0;
        }

        return view('teachers.all_teacher_ledger', compact('teachers'));
    }
}