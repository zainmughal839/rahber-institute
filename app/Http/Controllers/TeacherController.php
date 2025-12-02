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
        $data = $this->teachers->paginate(10);

        return view('teachers.index', compact('data'));
    }

    public function create()
    {
        return view('teachers.create');
    }

    public function store(Request $request)
    {
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
            'academic.*.degree' => 'required|string|max:255',
            'academic.*.institute' => 'required|string|max:255',
            'academic.*.passing_year' => 'required|numeric',
            'academic_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $input = $request->only(['name', 'cnic', 'phone', 'email', 'description', 'address']);
            $input['salary'] = $request->salary;

            if ($request->hasFile('picture')) {
                if (!empty($teacher->picture)) {
                    Storage::disk('public')->delete($teacher->picture);
                }
                $input['picture'] = $request->file('picture')->store('teachers/pictures', 'public');
            }

            if ($request->hasFile('cnic_front_image')) {
                if (!empty($teacher->cnic_front_image)) {
                    Storage::disk('public')->delete($teacher->cnic_front_image);
                }
                $input['cnic_front_image'] = $request->file('cnic_front_image')->store('teachers/cnic', 'public');
            }

            if ($request->hasFile('cnic_back_image')) {
                if (!empty($teacher->cnic_back_image)) {
                    Storage::disk('public')->delete($teacher->cnic_back_image);
                }
                $input['cnic_back_image'] = $request->file('cnic_back_image')->store('teachers/cnic', 'public');
            }

            $academic = $request->academic ?? [];
            $academicImages = $request->academic_images ?? [];

            foreach ($academic as $i => &$row) {
                $row['image'] = isset($academicImages[$i])
                    ? $academicImages[$i]->store('teachers/degrees', 'public')
                    : null;
            }

            $input['academic_details'] = $academic;

            $teacher = $this->teachers->create($input);

            // Ledger Entry
            AllLedger::create([
                'student_id' => null,
                'teacher_id' => $teacher->id,
                'amount' => $request->salary,
                'type' => 'debit',
                'ledger_category' => 'salary',
                'description' => 'Monthly salary credited for teacher: '.$teacher->name,
            ]);

            return redirect()->route('teachers.index')->with('success', 'Teacher created successfully.');
        } catch (QueryException $e) {
            // Handle database errors like duplicate entry
            return back()->withInput()->withErrors(['db_error' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $teacher = $this->teachers->find($id);
        abort_if(!$teacher, 404);

        // Get latest salary from ledger
        $teacher->salary = AllLedger::where('teacher_id', $teacher->id)
            ->where('ledger_category', 'salary')
            ->latest()
            ->value('amount') ?? 0;

        return view('teachers.view', compact('teacher'));
    }

    public function edit($id)
    {
        $teacher = $this->teachers->find($id);
        abort_if(!$teacher, 404);

        $ledgerSalary = AllLedger::where('teacher_id', $id)
            ->where('ledger_category', 'salary')
            ->latest()
            ->value('amount');

        $teacher->salary = $ledgerSalary ?? 0;

        return view('teachers.edit', compact('teacher'));
    }

    public function update(Request $request, $id)
    {
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
            'picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'cnic_front_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'cnic_back_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'academic.*.degree' => 'required|string|max:255',
            'academic.*.institute' => 'required|string|max:255',
            'academic.*.passing_year' => 'required|numeric',
            'academic_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $input = $request->only(['name', 'cnic', 'phone', 'email', 'description', 'address']);
            $input['salary'] = $request->salary;

            if ($request->hasFile('picture')) {
                if (!empty($teacher->picture)) {
                    Storage::disk('public')->delete($teacher->picture);
                }
                $input['picture'] = $request->file('picture')->store('teachers/pictures', 'public');
            }

            if ($request->hasFile('cnic_front_image')) {
                if (!empty($teacher->cnic_front_image)) {
                    Storage::disk('public')->delete($teacher->cnic_front_image);
                }
                $input['cnic_front_image'] = $request->file('cnic_front_image')->store('teachers/cnic', 'public');
            }

            if ($request->hasFile('cnic_back_image')) {
                if (!empty($teacher->cnic_back_image)) {
                    Storage::disk('public')->delete($teacher->cnic_back_image);
                }
                $input['cnic_back_image'] = $request->file('cnic_back_image')->store('teachers/cnic', 'public');
            }

            $academic = $request->academic ?? [];
            $academicImages = $request->academic_images ?? [];
            $finalAcademic = [];

            foreach ($academic as $i => $a) {
                $new = [];
                $new['degree'] = $a['degree'];
                $new['institute'] = $a['institute'];
                $new['passing_year'] = $a['passing_year'];

                $oldImage = $a['old_image'] ?? null;

                if (isset($academicImages[$i])) {
                    if (!empty($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                    $new['image'] = $academicImages[$i]->store('teachers/degrees', 'public');
                } else {
                    $new['image'] = $oldImage ?? null;
                }

                $finalAcademic[] = $new;
            }

            $input['academic_details'] = $finalAcademic;

            $this->teachers->update($id, $input);

            AllLedger::where('teacher_id', $teacher->id)
                ->where('ledger_category', 'salary')
                ->update(['amount' => $request->salary]);

            return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');
        } catch (QueryException $e) {
            return back()->withInput()->withErrors(['db_error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $teacher = $this->teachers->find($id);
        abort_if(!$teacher, 404);

        // Delete profile images safely
        if (!empty($teacher->picture)) {
            Storage::disk('public')->delete($teacher->picture);
        }
        if (!empty($teacher->cnic_front_image)) {
            Storage::disk('public')->delete($teacher->cnic_front_image);
        }
        if (!empty($teacher->cnic_back_image)) {
            Storage::disk('public')->delete($teacher->cnic_back_image);
        }

        // Delete academic images safely
        if (!empty($teacher->academic_details)) {
            foreach ($teacher->academic_details as $a) {
                if (!empty($a['image'])) {
                    Storage::disk('public')->delete($a['image']);
                }
            }
        }

        // Delete all ledger entries
        AllLedger::where('teacher_id', $teacher->id)->delete();

        // Force delete teacher (hard delete)
        $teacher->forceDelete();

        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
    }

    public function ledger($id)
    {
        $teacher = $this->teachers->find($id);
        abort_if(!$teacher, 404);

        // Get all ledger entries for this teacher
        $ledgers = AllLedger::where('teacher_id', $teacher->id)
                    ->orderBy('created_at', 'asc')
                    ->get();

        // Get latest salary from ledger
        $teacher->salary = AllLedger::where('teacher_id', $teacher->id)
                            ->where('ledger_category', 'salary')
                            ->latest()
                            ->value('amount') ?? 0;

        return view('teachers.teacher_ledger', compact('teacher', 'ledgers'));
    }

    public function allLedger()
    {
        // Get all teachers
        $teachers = $this->teachers->all();

        // Add latest salary from ledger for each teacher
        foreach ($teachers as $t) {
            $t->latest_salary = AllLedger::where('teacher_id', $t->id)
                ->where('ledger_category', 'salary')
                ->latest()
                ->value('amount') ?? 0;
        }

        return view('teachers.all_teacher_ledger', compact('teachers'));
    }
}
