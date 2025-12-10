<?php

namespace App\Http\Controllers;

use App\Models\ClassSubject;
use App\Models\Teacher;
use App\Repositories\Interfaces\ClassTeacherRepositoryInterface;
use Illuminate\Http\Request;

class ClassTeacherController extends Controller
{
    protected $repo;

    public function __construct(ClassTeacherRepositoryInterface $repo)
    {
        $this->repo = $repo;

        $this->middleware('permission:class-teacher.index')->only(['index', 'all']);
        $this->middleware('permission:class-teacher.create')->only(['create', 'store']);
        $this->middleware('permission:class-teacher.update')->only(['edit', 'update']);
        $this->middleware('permission:class-teacher.delete')->only('destroy');
    }

    public function index()
    {
        $data = $this->repo->paginate(10);
        $showAll = false;

        return view('class_teacher.index', compact('data', 'showAll'));
    }

    public function all()
    {
        $data = $this->repo->all();
        $showAll = true;

        return view('class_teacher.index', compact('data', 'showAll'));
    }

    public function show($id)
    {
        $record = $this->repo->find($id);

        return view('class_teacher.show', compact('record'));
    }

    public function create()
    {
        $teachers = Teacher::orderBy('name')->get();
        $classSubjects = ClassSubject::with('subjects', 'sessionProgram.session', 'sessionProgram.program')->get();

        return view('class_teacher.create', compact('teachers', 'classSubjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_subjects_id' => 'required|exists:class_subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'status' => 'required|in:active,inactive',
            'desc' => 'nullable|string',
        ]);

        $this->repo->store($request->only(['class_subjects_id', 'teacher_id', 'status', 'desc']));

        return redirect()->route('class-teacher.index')->with('success', 'Teacher assigned to class successfully.');
    }

    public function edit($id)
    {
        $record = $this->repo->find($id);
        $teachers = Teacher::orderBy('name')->get();
        $classSubjects = ClassSubject::with('subjects', 'sessionProgram.session', 'sessionProgram.program')->get();

        return view('class_teacher.create', compact('record', 'teachers', 'classSubjects'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'class_subjects_id' => 'required|exists:class_subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'status' => 'required|in:active,inactive',
            'desc' => 'nullable|string',
        ]);

        $this->repo->update($id, $request->only(['class_subjects_id', 'teacher_id', 'status', 'desc']));

        return redirect()->route('class-teacher.index')->with('success', 'Assignment updated.');
    }

    public function destroy($id)
    {
        $this->repo->delete($id);

        return back()->with('success', 'Assignment deleted.');
    }
}