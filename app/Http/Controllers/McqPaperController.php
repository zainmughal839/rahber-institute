<?php
namespace App\Http\Controllers;
use App\Models\McqPaper;
use App\Models\McqAttempt;
use App\Models\McqAnswer;
use App\Models\SubjectiveAnswer;
use App\Models\SubjectiveAnswerImage;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\McqPaperRepositoryInterface;
class McqPaperController extends Controller
{
    protected $repo;
    public function __construct(McqPaperRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
    public function index(){
        $papers = $this->repo->all();
        return view('mcq.papers.index', compact('papers'));
    }
    public function create(){
        return view('mcq.papers.create');
    }
    public function store(Request $request){
        $validated = $request->validate([
            'title'=>'required|string|max:255',
            'description'=>'nullable|string',
            'status'=>'required|in:draft,published'
        ]);
        $validated['teacher_id'] = auth()->id();
        $this->repo->create($validated);
        return redirect()->route('mcq.papers.index')->with('success','Paper created successfully.');
    }
    public function edit($id){
        $paper = $this->repo->find($id);
        return view('mcq.papers.edit', compact('paper'));
    }
    public function update(Request $request,$id){
        $validated = $request->validate([
            'title'=>'required|string|max:255',
            'description'=>'nullable|string',
            'status'=>'required|in:draft,published'
        ]);
        $this->repo->update($id,$validated);
        return redirect()->route('mcq.papers.index')->with('success','Paper updated successfully.');
    }
    public function destroy($id){
        $this->repo->delete($id);
        return redirect()->route('mcq.papers.index')->with('success','Paper deleted successfully.');
    }
    public function manageQuestions($id){
        $paper = $this->repo->find($id);
        return view('mcq.questions.index', compact('paper'));
    }
    public function show(McqPaper $paper)
    {
        $student = auth()->user()->student;
        $attempt = null;

        if ($student) {
            $attempt = McqAttempt::where('mcq_paper_id', $paper->id)
                ->where('student_id', $student->id)
                ->with('answers')
                ->first();
        }

        return view('mcq.assign.view', [
            'paper'   => $paper,
            'mode'    => 'student',
            'attempt' => $attempt
        ]);
    }

    public function submit(Request $request, McqPaper $paper)
    {
        $user = auth()->user();
        $assignment = $user->userAssignment;
        $student = null;

        if (session('is_panel_user') && $assignment && $assignment->panel_type === 'student') {
            $studentId = $assignment->assignable_id;
            $student = \App\Models\Student::find($studentId);
        } elseif ($user->student ?? null) {
            $student = $user->student;
        }

        if (!$student) {
            return back()->with('error','Student not found');
        }
        // ❌ Prevent re-attempt
        $already = McqAttempt::where('mcq_paper_id',$paper->id)
            ->where('student_id',$student->id)
            ->exists();
        if ($already) {
            return back()->with('error','Paper already submitted');
        }
        $attempt = McqAttempt::create([
            'mcq_paper_id' => $paper->id,
            'student_id' => $student->id,
            'status' => 'present', // ✅ attendance
            'total_questions' => $paper->questions->count(),
            'correct' => 0,
            'wrong' => 0,
            'score' => 0,
            'subjective_submitted' => false,
        ]);

        $correct = 0;
        foreach ($paper->questions as $q) {
            $selected = $request->input("q{$q->id}");
            $isCorrect = $selected === $q->correct_option;
            McqAnswer::create([
                'mcq_attempt_id' => $attempt->id,
                'mcq_question_id' => $q->id,
                'selected_option' => $selected,
                'is_correct' => $isCorrect,
            ]);
            if ($isCorrect) $correct++;
        }
        $attempt->update([
            'correct' => $correct,
            'wrong' => $attempt->total_questions - $correct,
            'score' => $correct,
            'mcq_completed_at' => now(),   // ✅ MCQ completion indicator
        ]);


        
        return redirect()->route('mcq.assign.view',$paper->id)->with('success', 'MCQs submitted! Now submit subjective if any.');
    }

    public function submitSubjective(Request $request, McqPaper $paper)
    {
        $user = auth()->user();
        $assignment = $user->userAssignment;
        $student = null;

        if (session('is_panel_user') && $assignment && $assignment->panel_type === 'student') {
            $studentId = $assignment->assignable_id;
            $student = \App\Models\Student::find($studentId);
        } elseif ($user->student ?? null) {
            $student = $user->student;
        }

        if (!$student) {
            return back()->with('error','Student not found');
        }

        $attempt = McqAttempt::where('mcq_paper_id',$paper->id)
            ->where('student_id',$student->id)
            ->first();

        if (!$attempt || $attempt->subjective_submitted) {
            return back()->with('error','Invalid attempt');
        }

        if ($request->has('subjective')) {
        foreach ($request->subjective as $questionId => $data) {
            $answer = SubjectiveAnswer::create([
                'subjective_question_id' => $questionId,
                'student_id' => $student->id,
            ]);

            if (isset($data['images']) && is_array($data['images'])) {
                foreach ($data['images'] as $image) {
                    if ($image instanceof \Illuminate\Http\UploadedFile) {
                        $path = $image->store('subjective_answers', 'public');
                        SubjectiveAnswerImage::create([
                            'subjective_answer_id' => $answer->id,
                            'image_path' => $path,
                            'type' => 'student',  // ← Correctly tagged as student
                        ]);
                    }
                }
            }
        }
        }

        $attempt->update(['subjective_submitted' => true]);

        return redirect()->route('mcq.assign.view',$paper->id)->with('success', 'Subjective submitted!');
    }


    
}