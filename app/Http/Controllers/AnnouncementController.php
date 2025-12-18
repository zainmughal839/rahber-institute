<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Teacher;
use App\Models\SessionProgram;
use App\Models\StuCategory;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnnouncementController extends Controller
{

      public function __construct()
    {
        $this->middleware('permission:announcement.index')->only(['index']);
        $this->middleware('permission:announcement.create')->only(['create','store']);
        $this->middleware('permission:announcement.update')->only(['edit','update']);
        $this->middleware('permission:announcement.delete')->only(['destroy']);
    }
    
    
    public function index()
{
    $user = auth()->user();
    $assignment = $user->userAssignment;

    // ================= PANEL USER =================
    if (session('is_panel_user') && $assignment) {

        /* ========== TEACHER ========== */
        if ($assignment->panel_type === 'teacher') {

            $announcements = Announcement::with([
                    'teachers',
                    'programs',
                    'students',
                    'sessionProgram'
                ])
                ->whereHas('teachers', function ($q) use ($assignment) {
                    $q->where('teachers.id', $assignment->assignable_id);
                })
                ->latest()
                ->paginate(15);

            return view('announcements.index', compact('announcements'));
        }

        /* ========== STUDENT ========== */
        if ($assignment->panel_type === 'student') {

            $announcements = Announcement::with([
                    'teachers',
                    'programs',
                    'students',
                    'sessionProgram'
                ])
                ->whereHas('students', function ($q) use ($assignment) {
                    $q->where('students.id', $assignment->assignable_id);
                })
                ->latest()
                ->paginate(15);

            return view('announcements.index', compact('announcements'));
        }
    }

    // ================= ADMIN / STAFF =================
    $announcements = Announcement::with([
            'teachers',
            'programs',
            'students',
            'sessionProgram'
        ])
        ->latest()
        ->paginate(15);

    return view('announcements.index', compact('announcements'));
}


    public function create()
    {
        return view('announcements.create', [
            'teachers'          => Teacher::orderBy('name')->get(),
            'sessionPrograms'   => SessionProgram::with(['session','programs'])->get(),
            'studentCategories' => StuCategory::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'audience'            => 'required|array',
            'title'               => 'required|string|max:255',
            'session_program_id'  => 'nullable|exists:session_program,id',
            'teacher_desc'        => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $data) {

            $data['is_active'] = $request->boolean('is_active');

            $announcement = Announcement::create($data);

            /* ===== TEACHERS ===== */
            $announcement->teachers()->sync($request->teacher_ids ?? []);

            /* ===== STUDENT SIDE LOGIC ===== */
            if (in_array('student', $request->audience)) {

                $programIds = $request->program_ids ?? [];

                // Agar program select nahi kiya → session program ke ALL programs
                if (empty($programIds) && $request->session_program_id) {
                    $programIds = SessionProgram::find($request->session_program_id)
                        ?->programs->pluck('id')->toArray() ?? [];
                }

                $announcement->programs()->sync($programIds);
                $announcement->studentCategories()->sync($request->stu_category_ids ?? []);

                // Students
                if (!empty($request->student_ids)) {
                    $announcement->students()->sync($request->student_ids);
                } else {
                    // ALL students of selected programs
                    $students = Student::whereIn('program_id', $programIds)->pluck('id');
                    $announcement->students()->sync($students);
                }
            }
        });

        return redirect()->route('announcements.index')
            ->with('success','Announcement Created Successfully');
    }

    public function edit(Announcement $announcement)
    {
        $announcement->load(['teachers','programs','students','studentCategories']);

        return view('announcements.create', [
            'announcement'       => $announcement,
            'teachers'          => Teacher::orderBy('name')->get(),
            'sessionPrograms'   => SessionProgram::with(['session','programs'])->get(),
            'studentCategories' => StuCategory::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $request->validate([
            'audience'            => 'required|array',
            'title'               => 'required|string|max:255',
            'session_program_id'  => 'nullable|exists:session_program,id',
            'teacher_desc'        => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $announcement, $data) {

            $data['is_active'] = $request->boolean('is_active');
            $announcement->update($data);

            $announcement->teachers()->sync($request->teacher_ids ?? []);
            $announcement->programs()->sync($request->program_ids ?? []);
            $announcement->studentCategories()->sync($request->stu_category_ids ?? []);
            $announcement->students()->sync($request->student_ids ?? []);
        });

        return redirect()->route('announcements.index')
            ->with('success','Announcement Updated Successfully');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('success','Announcement Deleted');
    }


    public function show(Announcement $announcement)
{
    $user = auth()->user();
    $assignment = $user->userAssignment;

    // Default mode
    $mode = 'admin';

    if (session('is_panel_user') && $assignment) {
        if ($assignment->panel_type === 'teacher') {
            $mode = 'teacher';
        } elseif ($assignment->panel_type === 'student') {
            $mode = 'student';
        }
    }

    // SECURITY: student can only view assigned announcement
    if ($mode === 'student') {
        $announcement->load('students');

        if (! $announcement->students->contains($assignment->assignable_id)) {
            abort(403);
        }
    }

    $announcement->load([
        'teachers',
        'programs',
        'students',
        'studentCategories',
        'sessionProgram.session',
        'sessionProgram.programs'
    ]);

    return view('announcements.view', compact('announcement','mode'));
}

}
