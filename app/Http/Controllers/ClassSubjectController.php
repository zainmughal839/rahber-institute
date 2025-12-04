<?php

namespace App\Http\Controllers;

use App\Models\ClassSubject;
use App\Models\SessionProgram;
use App\Models\Subject;
use App\Repositories\Interfaces\ClassSubjectRepositoryInterface;
use Illuminate\Http\Request;

class ClassSubjectController extends Controller
{
    protected $classes;

    public function __construct(ClassSubjectRepositoryInterface $classes)
    {
        $this->classes = $classes;

        // Permission middleware
        $this->middleware('permission:class-subject.index')->only('index', 'all');
        $this->middleware('permission:class-subject.create')->only('create', 'store');
        $this->middleware('permission:class-subject.update')->only('edit', 'update');
        $this->middleware('permission:class-subject.delete')->only('destroy');
    }

    // Default paginated index
    public function index()
    {
        $data = $this->classes->paginate(10);
        $showAll = false;

        return view('class_subjects.index', compact('data', 'showAll'));
    }

    // Show all records without pagination
    public function all()
    {
        $data = $this->classes->all(); // All records
        $showAll = true;

        return view('class_subjects.index', compact('data', 'showAll'));
    }

    public function create()
    {
        $subjects = Subject::all();
        $sessionPrograms = SessionProgram::with('session', 'program')->get();

        return view('class_subjects.create', compact('subjects', 'sessionPrograms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_name' => 'required',
            'session_program_id' => 'required|exists:session_program,id',
            'subject_id' => 'required|array',
        ]);

        $class = $this->classes->store([
            'class_name' => $request->class_name,
            'session_program_id' => $request->session_program_id,
            'status' => $request->status,
            'desc' => $request->desc,
        ]);

        // Attach subjects
        $class->subjects()->sync($request->subject_id);

        return redirect()->route('class-subjects.index')
            ->with('success', 'Class created successfully!');
    }

    public function edit($id)
    {
        $class = $this->classes->find($id);
        $subjects = Subject::all();
        $sessionPrograms = SessionProgram::with('session', 'program')->get();

        return view('class_subjects.create', compact('class', 'subjects', 'sessionPrograms'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'class_name' => 'required',
            'session_program_id' => 'required|exists:session_program,id',
            'subject_id' => 'required|array',
        ]);

        $this->classes->update(
            $id,
            $request->only('class_name', 'session_program_id', 'status', 'desc')
        );

        $class = ClassSubject::findOrFail($id);

        $class->subjects()->sync($request->subject_id);

        return redirect()->route('class-subjects.index')
            ->with('success', 'Class updated successfully!');
    }

    public function destroy($id)
    {
        $this->classes->delete($id);

        return back()->with('success', 'Class deleted successfully');
    }
}