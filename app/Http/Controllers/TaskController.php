<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Teacher;
use App\Models\TaskCat;
use App\Models\TaskResponse;
use App\Models\TaskUserResponse;
use App\Models\StuCategory;
use App\Models\SessionProgram;
use App\Models\Student;
use App\Models\ClassSubject;
use App\Models\TestCat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:task.index')->only(['index', 'storeResponse']);
        $this->middleware('permission:task.create')->only(['create','store']);
        $this->middleware('permission:task.update')->only(['edit','update']);
        $this->middleware('permission:task.delete')->only(['destroy']);
    }

    /* ================= INDEX ================= */
   public function index()
{
    $user = auth()->user();
    $assignment = $user->userAssignment;

    // PANEL USER (Teacher / Student)
    if (session('is_panel_user') && $assignment) {

        // ================= TEACHER =================
        if ($assignment->panel_type === 'teacher') {

            $tasks = Task::with(['teacher','category','sessionProgram'])
                ->where('teacher_id', $assignment->assignable_id)
                ->paginate(15);

            return view('tasks.index', compact('tasks'));
        }

        // ================= STUDENT =================
        if ($assignment->panel_type === 'student') {

            $tasks = Task::with(['teacher','category','sessionProgram'])
                ->whereHas('students', function ($q) use ($assignment) {
                    $q->where('students.id', $assignment->assignable_id);
                })
                ->paginate(15);

            return view('tasks.index', compact('tasks'));
        }
    }

    // ================= ADMIN / STAFF =================
    $tasks = Task::with(['teacher','category','sessionProgram'])->paginate(15);

    return view('tasks.index', compact('tasks'));
}


    /* ================= CREATE ================= */
    public function create()
    {
        return view('tasks.create', [
            'teachers' => Teacher::orderBy('name')->get(),
            'categories' => TaskCat::orderBy('name')->get(),
            'sessionPrograms' => SessionProgram::with(['session','programs'])->get(),
            'studentCategories' => StuCategory::orderBy('name')->get(),
            'testCategories' => TestCat::orderBy('name')->get(),
            'classes' => ClassSubject::orderBy('class_name')->get(),
        ]);
    }

    /* ================= STORE ================= */
    public function store(Request $request)
    {
        $data = $request->validate([
            'audience'            => 'required|array',
            'teacher_id'          => 'nullable|exists:teachers,id',
            'task_cat_id'         => 'nullable|exists:task_cat,id',
            'session_program_id'  => 'nullable|exists:session_program,id',
            'title'               => 'nullable|string|max:255',
            'task_start'          => 'nullable|date',
            'task_end'            => 'nullable|date|after_or_equal:task_start',
            'paper_date'          => 'nullable|date',
            'teacher_heading'     => 'nullable|string',
            'teacher_desc'        => 'nullable|string',
            'student_heading'     => 'nullable|string',
            'student_desc'        => 'nullable|string',

            'test_category_id' => 'nullable|exists:test_cat,id',
            'test_type'        => 'nullable|in:oral,written',
            'test_title'       => 'nullable|string|max:255',
            'test_desc'        => 'nullable|string',
            'test_orientation' => 'nullable|string',
            'result_announce_at' => 'nullable|date',
            'paper_submit_at' => 'nullable|date',
            'total_marks'     => 'nullable|integer',
            'passing_marks'   => 'nullable|integer',

        ]);

        DB::transaction(function () use ($request, $data) {

            $data['is_completed'] = $request->boolean('is_completed');

            // ✅ THIS LINE WAS MISSING
            $data['has_test'] = $request->boolean('has_test');

            $task = Task::create($data);
            $task->classes()->sync($request->class_ids ?? []);


            /* PROGRAMS */
            if ($request->filled('program_ids')) {
                $task->programs()->sync($request->program_ids);
            }

            /* ✅ MULTIPLE STUDENT CATEGORIES */
            if ($request->filled('stu_category_ids')) {
                $task->studentCategories()->sync($request->stu_category_ids);
            }

            /* STUDENTS */
            if ($request->filled('student_ids')) {
                $task->students()->sync($request->student_ids);
            }
        });

        return redirect()->route('tasks.index')
            ->with('success','Task Created Successfully');
    }

    /* ================= EDIT ================= */

    public function edit(Task $task)
    {
        $task->load([
            'programs',
            'students',
            'studentCategories',
            'classes', // add this
            'sessionProgram.session',
            'sessionProgram.programs'
        ]);

        return view('tasks.create', [
            'task' => $task,
            'teachers' => Teacher::orderBy('name')->get(),
            'categories' => TaskCat::orderBy('name')->get(),
            'sessionPrograms' => SessionProgram::with(['session','programs'])->get(),
            'studentCategories' => StuCategory::orderBy('name')->get(),
            'testCategories' => TestCat::orderBy('name')->get(),
            'classes' => ClassSubject::orderBy('class_name')->get(),
        ]);
    }

    /* ================= UPDATE ================= */
    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'audience'            => 'required|array',
            'teacher_id'          => 'nullable|exists:teachers,id',
            'task_cat_id'         => 'nullable|exists:task_cat,id',
            'session_program_id'  => 'nullable|exists:session_program,id',
            'title'               => 'required|string|max:255',
            'task_start'          => 'nullable|date',
            'task_end'            => 'nullable|date|after_or_equal:task_start',
            'paper_date'          => 'nullable|date',
            'teacher_heading'     => 'nullable|string',
            'teacher_desc'        => 'nullable|string',
            'student_heading'     => 'nullable|string',
            'student_desc'        => 'nullable|string',

            'test_category_id' => 'nullable|exists:test_cat,id',
            'test_type'        => 'nullable|in:oral,written',
            'test_title'       => 'nullable|string|max:255',
            'test_desc'        => 'nullable|string',
            'test_orientation' => 'nullable|string',
            'result_announce_at' => 'nullable|date',
            'paper_submit_at' => 'nullable|date',
            'total_marks'     => 'nullable|integer',
            'passing_marks'   => 'nullable|integer',

        ]);

        DB::transaction(function () use ($request, $task, $data) {
            

            $data['has_test'] = $request->boolean('has_test');
            $task->update($data);

            
            /* SUBJECTS */
            $task->subjects()->sync($request->subject_ids ?? []);


            $task->programs()->sync($request->program_ids ?? []);

            /* ✅ UPDATE MULTIPLE CATEGORIES */
            $task->studentCategories()->sync($request->stu_category_ids ?? []);

            $task->students()->sync($request->student_ids ?? []);

            $task->classes()->sync($request->class_ids ?? []);
        });

        return redirect()->route('tasks.index')
            ->with('success','Task Updated Successfully');
    }


    public function filterClasses(Request $request)
{
    return ClassSubject::query()
        ->when($request->program_ids, function ($q) use ($request) {
            $q->whereHas('sessionProgram.programs', function ($sq) use ($request) {
                $sq->whereIn('programs.id', $request->program_ids);
            });
        })
        ->select('id', 'class_name')
        ->orderBy('class_name')
        ->get();
}


public function view(Task $task)
{
    $user = auth()->user();
    $assignment = $user->userAssignment;

    // ================= PANEL USER =================
    if (session('is_panel_user') && $assignment) {

        // ---------- STUDENT ----------
        if ($assignment->panel_type === 'student') {

            $studentId = $assignment->assignable_id;

            if (!$task->students()->where('students.id', $studentId)->exists()) {
                abort(403);
            }

            return view('tasks.view', [
                'task' => $task,
                'mode' => 'student',
                'response' => null
            ]);
        }

        // ---------- TEACHER ----------
        if ($assignment->panel_type === 'teacher') {

            if ($task->teacher_id != $assignment->assignable_id) {
                abort(403);
            }

            $response = TaskResponse::where('task_id', $task->id)
                ->where('teacher_id', $assignment->assignable_id)
                ->first();

            return view('tasks.view', [
                'task' => $task,
                'mode' => 'teacher',
                'response' => $response
            ]);
        }
    }

    // ================= ADMIN / NO STUDENT / NO TEACHER =================
    // DIRECT VIEW ACCESS
    $response = TaskResponse::where('task_id', $task->id)
                ->first();
                
    return view('tasks.view', [
        'task' => $task,
        'mode' => 'admin',
        'response' => $response
    ]);
}




public function storeResponse(Request $request, Task $task)
{
    $user = auth()->user();
    $assignment = $user->userAssignment;

    // ❌ Block only STUDENT
    if ($assignment && $assignment->panel_type === 'student') {
        abort(403);
    }

    $data = $request->validate([
        'response_type' => 'required|in:assignment_show,objection',
        'desc' => 'nullable|string'
    ]);

    // ✅ Decide teacher_id
    // Teacher → own response
    // Admin / Staff / Others → task ke teacher ka response
    $teacherId = ($assignment && $assignment->panel_type === 'teacher')
        ? $assignment->assignable_id
        : $task->teacher_id;

    TaskResponse::updateOrCreate(
        [
            'task_id' => $task->id,
            'teacher_id' => $teacherId
        ],
        [
            'response_type' => $data['response_type'],
            'desc' => $data['desc'] ?? null
        ]
    );

    return back()->with('success', 'Response submitted successfully');
}








    /* ================= DELETE ================= */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success','Task Deleted');
    }

    /* ================= AJAX ================= */
    public function getPrograms($spId)
    {
        return response()->json(
            SessionProgram::with('programs')->findOrFail($spId)->programs
        );
    }

    public function getStudents($programId)
    {
        return response()->json(
            Student::where('program_id', $programId)->get()
        );
    }

    public function filterStudents(Request $request)
    {
        return Student::query()
            ->when($request->program_ids, fn ($q) =>
                $q->whereIn('program_id', $request->program_ids)
            )
            ->when($request->category_ids, fn ($q) =>
                $q->whereIn('stu_category_id', $request->category_ids)
            )
            ->select('id','name','rollnum')
            ->orderBy('name')
            ->get();
    }
}