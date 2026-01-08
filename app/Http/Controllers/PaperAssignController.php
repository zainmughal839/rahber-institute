<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\McqBank;
use App\Models\McqPaper;
use App\Models\Student;
use App\Models\McqAttempt;
use App\Models\SubjectiveAnswer;
use App\Models\SubjectiveAnswerImage;
use App\Models\SubjectiveQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;  


class PaperAssignController extends Controller
{

    public function __construct() {
        $this->middleware('permission:assign-paper.index')->only(['index']);
        $this->middleware('permission:assign-paper.create')->only(['create', 'store']);
        $this->middleware('permission:assign-paper.update')->only(['edit', 'update']);
        $this->middleware('permission:assign-paper.delete')->only('destroy');
        $this->middleware('permission:assign-paper.check-result')->only('checkResult');
    }


    public function index()
    {
        $user = auth()->user();
        $assignment = $user->userAssignment ?? null;

        $isStudentPanel = false;

        if (session('is_panel_user') && $assignment) {
            if ($assignment->panel_type === 'teacher') {
                $papers = McqPaper::withCount(['students', 'questions'])
                    ->where('teacher_id', $assignment->assignable_id)
                    ->latest()
                    ->get();
            } elseif ($assignment->panel_type === 'student') {
                $isStudentPanel = true; // ← Flag for Blade

                $papers = McqPaper::withCount(['students', 'questions'])
                    ->whereHas('students', function ($query) use ($assignment) {
                        $query->where('students.id', $assignment->assignable_id);
                    })
                    ->latest()
                    ->get();
            } else {
                $papers = collect();
            }
        } else {
            // Admin / Staff
            $papers = McqPaper::withCount(['students', 'questions'])
                ->latest()
                ->get();
        }

        return view('mcq.assign.index', compact('papers', 'isStudentPanel'));
    }


    public function create()
    {
        return view('mcq.assign.create', [
            'tasks' => Task::all(),
            'banks' => McqBank::all(),
            'paper' => null,
            
        ]);
    }

 
    public function edit(McqPaper $paper)
    {
        $paper->load(['questions', 'task', 'banks']); // ← Add 'banks'

        return view('mcq.assign.create', [
            'tasks' => Task::all(),
            'banks' => McqBank::all(),
            'paper' => $paper
        ]);
    }



    public function store(Request $request)
    {
        $request->validate([
        'task_id'        => [
                'required',
                'exists:tasks,id',
                // Ek task ke against sirf ek paper allowed
                function ($attribute, $value, $fail) {
                    if (McqPaper::where('task_id', $value)->exists()) {
                        $fail('This task already has an assigned MCQ paper. Only one paper per task is allowed.');
                    }
                },
            ],
            'mcq_bank_ids' => 'required|array|min:1',
            'mcq_bank_ids.*' => 'exists:mcq_banks,id',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string|max:500',
            'mcq_questions'  => 'required|array|min:1',
            'per_mcqs_num'   => 'required|integer|min:1',
            'per_mcqs_time'  => 'required|integer|min:1',
            'marks_per_mcq'  => 'required|numeric|min:0.5',
            'cover_image'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'subjective.*.question' => 'nullable|string',
            'subjective.*.marks'    => 'nullable|integer|min:1',
            'result_mode' => 'required|in:immediate,scheduled',
            'result_date' => 'nullable|required_if:result_mode,scheduled',

        ]);

        $data = $request->all();

        $data['result_date'] = null;

        if ($request->result_mode === 'scheduled' && $request->result_date) {
            $data['result_date'] = date('Y-m-d H:i:s', strtotime($request->result_date));
        }


        // ================= TEACHER ID FIX =================
        $teacherId = 1; // ✅ DEFAULT TEACHER ID

        if (session('is_panel_user')) {
            $assignment = auth()->user()->userAssignment;

            if ($assignment && $assignment->panel_type === 'teacher') {
                $teacherId = $assignment->assignable_id; // ✅ teachers.id
            }
        }

        $data['teacher_id'] = $teacherId;
            
        // ==================================================

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')
                ->store('paper_covers', 'public');
        }

        // ---------- Total Subjective Marks ----------
        $totalSubjMarks = 0;
        if ($request->has('subjective')) {
            foreach ($request->subjective as $item) {
                if (!empty($item['marks'])) {
                    $totalSubjMarks += (int) $item['marks'];
                }
            }
        }
        $data['total_subjective_marks'] = $totalSubjMarks;
        
        $data['subjective_time'] = $request->subjective_time;

        // ---------- Create Paper ----------
        $paper = McqPaper::create($data);

        // ---------- Sync MCQs ----------
        $paper->questions()->sync($request->mcq_questions);
        $paper->banks()->sync($request->mcq_bank_ids);

        // ---------- Sync Students ----------
        $task = Task::with('students')->findOrFail($request->task_id);
        $paper->students()->sync($task->students->pluck('id'));

        // ---------- Save Subjective Questions ----------
        if ($request->has('subjective')) {
            foreach ($request->subjective as $item) {
                if (!empty($item['question']) && !empty($item['marks'])) {
                    $paper->subjectiveQuestions()->create([
                        'question' => $item['question'],
                        'marks'    => $item['marks'],
                    ]);
                }
            }
        }

        return redirect()
            ->route('mcq.assign.index')
            ->with('success', 'MCQ Paper assigned successfully');
    }



    public function update(Request $request, McqPaper $paper)
    {
        $request->validate([
        'task_id' => [
                'required',
                'exists:tasks,id',
                // Update ke waqt: agar task change kar rahe ho to check karo ke wo task free hai
                function ($attribute, $value, $fail) use ($paper) {
                    if ($value != $paper->task_id && McqPaper::where('task_id', $value)->exists()) {
                        $fail('This task already has an assigned MCQ paper. Only one paper per task is allowed.');
                    }
                },
            ],
            'mcq_bank_ids' => 'required|array|min:1',
            'mcq_bank_ids.*' => 'exists:mcq_banks,id',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string|max:500',
            'mcq_questions'  => 'required|array|min:1',
            'per_mcqs_num'   => 'required|integer|min:1',
            'per_mcqs_time'  => 'required|integer|min:1',
            'marks_per_mcq' => 'required|numeric|min:0.5',
            'result_mode' => 'required|in:immediate,scheduled',
            'result_date' => 'nullable|date|required_if:result_mode,scheduled',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'subjective.*.question' => 'nullable|string',
            'subjective.*.marks'     => 'nullable|integer|min:1',
        ]);

        $data = $request->all();




        if ($request->hasFile('cover_image')) {
            // Delete old image
            if ($paper->cover_image) {
                Storage::disk('public')->delete($paper->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('paper_covers', 'public');
        }

        // Calculate total subjective marks
        $totalSubjMarks = 0;
        if ($request->has('subjective')) {
            foreach ($request->subjective as $item) {
                if (!empty($item['marks'])) $totalSubjMarks += (int)$item['marks'];
            }
        }
        $data['total_subjective_marks'] = $totalSubjMarks;

            
        $data['subjective_time'] = $request->subjective_time;

        $task = Task::with('students')->findOrFail($request->task_id);

        $data['result_date'] = null;

        if ($request->result_mode === 'scheduled' && $request->result_date) {
            $data['result_date'] = date('Y-m-d H:i:s', strtotime($request->result_date));
        }



        
        $paper->update($data);

        $paper->questions()->sync($request->mcq_questions);
        $paper->students()->sync($task->students->pluck('id'));
        $paper->banks()->sync($request->mcq_bank_ids);

        $existingIds = [];
        if ($request->has('subjective')) {
            foreach ($request->subjective as $index => $data) {
                if (!empty($data['question']) && !empty($data['marks'])) {
                    if (!empty($data['id'])) {
                        // Update existing
                        SubjectiveQuestion::where('id', $data['id'])
                            ->where('mcq_paper_id', $paper->id)
                            ->update([
                                'question' => $data['question'],
                                'marks'    => $data['marks'],
                            ]);
                        $existingIds[] = $data['id'];
                    } else {
                        // Create new
                        $new = $paper->subjectiveQuestions()->create([
                            'question' => $data['question'],
                            'marks'    => $data['marks'],
                        ]);
                        $existingIds[] = $new->id;
                    }
                }
            }
        }

        // Delete removed ones
        $paper->subjectiveQuestions()->whereNotIn('id', $existingIds)->delete();

        return redirect()->route('mcq.assign.index')
            ->with('success', 'Assignment updated successfully');
    }


    public function bankQuestions(McqBank $bank)
    {
        return response()->json(
            $bank->questions()
                ->select('id', 'question', 'option_a', 'option_b', 'option_c', 'option_d')
                ->get()
        );
    }


    public function getQuestionsFromBanks(Request $request)
    {
        // Validate input
        $request->validate([
            'bank_ids' => 'required|array',
            'bank_ids.*' => 'integer|exists:mcq_banks,id'
        ]);

        $bankIds = $request->input('bank_ids');

        // Fetch questions with bank name
        $questions = \App\Models\McqQuestion::with('bank')
            ->whereIn('mcq_bank_id', $bankIds)
            ->select('id', 'mcq_bank_id', 'question', 'option_a', 'option_b', 'option_c', 'option_d')
            ->get()
            ->map(function ($q) {
                return [
                    'id' => $q->id,
                    'question' => $q->question,
                    'option_a' => $q->option_a,
                    'option_b' => $q->option_b,
                    'option_c' => $q->option_c,
                    'option_d' => $q->option_d,
                    'bank_name' => $q->bank?->name ?? 'Unknown Bank'
                ];
            });

        return response()->json($questions);
    }

    public function destroy(McqPaper $paper)
    {
        $paper->students()->detach();
        $paper->questions()->detach();
        $paper->delete();

        return redirect()
            ->route('mcq.assign.index')
            ->with('success', 'MCQ Assignment deleted successfully');
    }


    public function view(McqPaper $paper, Student $student = null)
{
    $user = auth()->user();
    $assignment = $user->userAssignment;

    $paper->load([
        'questions',
        'students',
        'task',
        'subjectiveQuestions'
    ]);

    // 🔥 AUTO ABSENT MARKING
    markAbsentStudents($paper);

    // ================= PANEL USER =================
    if (session('is_panel_user') && $assignment) {

        // ================= STUDENT =================
        if ($assignment->panel_type === 'student') {

            $studentId = $assignment->assignable_id;
            $now = now();

            if (!$paper->students()->where('students.id', $studentId)->exists()) {
                abort(403);
            }

            $paperStart = Carbon::parse($paper->task->paper_date);
            $mcqEnd = $paperStart->copy()->addMinutes($paper->per_mcqs_time ?? 0);
            $fullEnd = $mcqEnd->copy()->addMinutes($paper->subjective_time ?? 0);

            $allowMcq = false;
            $allowSubjective = false;
            $remainingMcqSeconds = 0;
            $remainingSubjectiveSeconds = 0;

            $attempt = McqAttempt::where('mcq_paper_id', $paper->id)
                ->where('student_id', $studentId)
                ->first();

            // ================= MCQ PHASE =================
            if ($now->between($paperStart, $mcqEnd)) {
                $allowMcq = true;
                $remainingMcqSeconds = $now->diffInSeconds($mcqEnd);
            }

            // ================= SUBJECTIVE PHASE =================
            if ($now->between($mcqEnd, $fullEnd)) {
                $allowSubjective = true;
                $remainingSubjectiveSeconds = $now->diffInSeconds($fullEnd);

                // attempt auto create if MCQ done
                if (!$attempt) {
                    $attempt = McqAttempt::create([
                        'mcq_paper_id' => $paper->id,
                        'student_id' => $studentId,
                        'status' => 'present', // ✅ attendance only
                        'total_questions' => $paper->questions->count(),
                        'correct' => 0,
                        'wrong' => 0,
                        'score' => 0,
                        'subjective_started_at' => now(),
                        'subjective_submitted' => false,
                    ]);
                }
            }

            return view('mcq.assign.view', compact(
                'paper',
                'attempt',
                'allowMcq',
                'allowSubjective',
                'remainingMcqSeconds',
                'remainingSubjectiveSeconds'
            ))->with('mode', 'student');
        }

        // ================= TEACHER =================
        if ($assignment->panel_type === 'teacher') {
            abort_if($paper->teacher_id != $assignment->assignable_id, 403);

            return view('mcq.assign.view', [
                'paper' => $paper,
                'mode'  => 'teacher'
            ]);
        }
    }

    // ================= ADMIN =================
    return view('mcq.assign.view', [
        'paper' => $paper,
        'mode'  => 'admin'
    ]);
}




    public function gradeSubjective(Request $request, McqPaper $paper)
    {
        // Student ID ko request se lo (form hidden field se)
        $studentId = $request->input('student_id');

        if (!$studentId) {
            return back()->with('error', 'Student not found!');
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'marks' => 'required|array',
            'marks.*' => 'integer|min:0',
            'feedback_images.*.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $totalObtained = 0;

        foreach ($paper->subjectiveQuestions as $sq) {
            $marks = (int) ($request->marks[$sq->id] ?? 0);
            $totalObtained += $marks;

            $answer = SubjectiveAnswer::updateOrCreate(
                [
                    'subjective_question_id' => $sq->id,
                    'student_id' => $studentId,
                ],
                [
                    'obtained_marks' => $marks,
                ]
            );

            // Teacher Feedback Images
            if ($request->hasFile("feedback_images.{$sq->id}")) {
                foreach ($request->file("feedback_images.{$sq->id}") as $image) {
                    $path = $image->store('subjective_feedback', 'public');
                    SubjectiveAnswerImage::create([
                        'subjective_answer_id' => $answer->id,
                        'image_path' => $path,
                        'type' => 'teacher',  // ← Correctly tagged as teacher
                    ]);
                }
            }
        }

        // Update attempt with subjective marks
        $attempt = McqAttempt::where('mcq_paper_id', $paper->id)
                            ->where('student_id', $studentId)
                            ->first();

        if ($attempt) {
            $mcqMarks = $attempt->correct * $paper->marks_per_mcq;
            $attempt->update([
                'subjective_obtained' => $totalObtained,
                'total_obtained' => $mcqMarks + $totalObtained,
            ]);
        }

        return back()->with('success', 'Subjective marks and feedback saved successfully!');
    }



    public function checkResult(Request $request)
    {
        $papers = McqPaper::with('task')->latest()->get();

        $selectedPaperId = $request->input('paper_id');
        $selectedStudentId = $request->input('student_id');

        $students = collect();
        $result = null;

        if ($selectedPaperId) {
            $paper = McqPaper::with(['students'])->findOrFail($selectedPaperId);
            $students = $paper->students;
        }

        if ($selectedPaperId && $selectedStudentId) {
            $paper = McqPaper::with(['questions', 'subjectiveQuestions'])->findOrFail($selectedPaperId);
            
            $attempt = McqAttempt::where('mcq_paper_id', $paper->id)
                ->where('student_id', $selectedStudentId)
                ->with('answers')
                ->first();

            $student = Student::findOrFail($selectedStudentId);

            $mcq_total = $paper->questions->count();
            $marksPerMcq = $paper->marks_per_mcq ?? 1;
            $totalMcqMarks = $marksPerMcq * $mcq_total;
            $obtainedMcqMarks = $attempt ? ($attempt->correct * $marksPerMcq) : 0;

            $totalSubjectiveMarks = $paper->subjectiveQuestions->sum('marks');
            $obtainedSubjectiveMarks = $attempt?->subjective_obtained ?? 0;

            $totalOverallMarks = $totalMcqMarks + $totalSubjectiveMarks;
            $obtainedOverallMarks = $obtainedMcqMarks + $obtainedSubjectiveMarks;

            $answers = $attempt ? $attempt->answers->keyBy('mcq_question_id') : collect();

            $result = (object)[
                'paper' => $paper,
                'attempt' => $attempt,
                'currentStudent' => $student,
                'mcq_total' => $mcq_total,
                'totalMcqMarks' => $totalMcqMarks,
                'obtainedMcqMarks' => $obtainedMcqMarks,
                'totalSubjectiveMarks' => $totalSubjectiveMarks,
                'obtainedSubjectiveMarks' => $obtainedSubjectiveMarks,
                'totalOverallMarks' => $totalOverallMarks,
                'obtainedOverallMarks' => $obtainedOverallMarks,
                'answers' => $answers,
                'canSeeResult' => true,
            ];
        }

        return view('mcq.assign.check-result', compact('papers', 'students', 'result', 'selectedPaperId', 'selectedStudentId'));
    }


    // AJAX method to load students for a paper
    public function getStudentsForPaper(Request $request)
    {
        $paperId = $request->paper_id;
        $paper = McqPaper::findOrFail($paperId);
        $students = $paper->students->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->name . ' (Roll: ' . $student->rollnum . ')'
            ];
        });

        return response()->json($students);
    }
    

}