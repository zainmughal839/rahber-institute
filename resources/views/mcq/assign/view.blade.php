{{-- resources/views/mcq/assign/view.blade.php --}}

@php
use App\Models\McqAttempt;
use App\Models\SubjectiveAnswer;
use App\Models\SubjectiveAnswerImage;  
@endphp

@extends('layout.master')
@section('title', 'MCQ Paper View')
@section('content')

@php
$attempt = $attempt ?? null;
$answers = $attempt ? $attempt->answers->keyBy('mcq_question_id') : collect();
$mcq_total = $paper->questions->count();

$currentStudent = null;
if ($mode === 'student') {
    $user = auth()->user();
    if (session('is_panel_user') && $user->userAssignment && $user->userAssignment->panel_type === 'student') {
        $currentStudent = \App\Models\Student::find($user->userAssignment->assignable_id);
    } elseif ($user->student ?? null) {
        $currentStudent = $user->student;
    }

}

$totalMinutes = $paper->per_mcqs_time ?? 60;
$totalSeconds = $totalMinutes * 60;

$subjectiveMinutes = $paper->subjective_time ?? 30;
$subjectiveSeconds = $subjectiveMinutes * 60;

// 👇 THESE MUST COME FROM CONTROLLER
$allowMcq = $allowMcq ?? false;
$allowSubjective = $allowSubjective ?? false;


$remainingMcqSeconds = $remainingMcqSeconds ?? $totalSeconds;
$remainingSubjectiveSeconds = $remainingSubjectiveSeconds ?? $subjectiveSeconds;

// Adjusted per-question time based on remaining MCQ time
$timePerQuestionSeconds = $mcq_total > 0 ? ceil($remainingMcqSeconds / $mcq_total) : 60;

// MARKS
$marksPerMcq = $paper->marks_per_mcq ?? 1;
$totalMcqMarks = $marksPerMcq * $mcq_total;
$obtainedMcqMarks = $attempt ? ($attempt->correct * $marksPerMcq) : 0;

$totalSubjectiveMarks = $paper->subjectiveQuestions->sum('marks');
$obtainedSubjectiveMarks = $attempt?->subjective_obtained ?? 0;

$totalOverallMarks = $totalMcqMarks + $totalSubjectiveMarks;
$obtainedOverallMarks = $obtainedMcqMarks + $obtainedSubjectiveMarks;



    // RESULT VISIBILITY - CORRECTED LOGIC
   $canSeeResult = false;

if ($mode === 'student' && $attempt) {

    // ✅ Paper is completed ONLY when status is subjective_done
    $isCompleted = $attempt->status === 'present';

    if ($isCompleted) {
        if ($paper->result_mode === 'immediate') {
            $canSeeResult = true;
        } elseif (
            $paper->result_mode === 'scheduled' &&
            $paper->result_date &&
            now()->greaterThanOrEqualTo($paper->result_date)
        ) {
            $canSeeResult = true;
        }
    }

} else {
    // Admin / Teacher
    $canSeeResult = true;
}





@endphp


<style>
    .paper {
        font-family: "Segoe UI", Arial, sans-serif;
        max-width: 1000px;
        margin: auto;
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        line-height: 1.5;
    }

    @media print {
        .no-print, #timerSection, #progressBar { display: none !important; }
        .paper { box-shadow: none; border: none; padding: 8px; }
    }

    .header1 {
        background: linear-gradient(135deg, #2c3e50, #34495e);
        color: #fff;
        text-align: center;
        padding: 16px;
        border-radius: 12px 12px 0 0;
        margin: -20px -20px 20px -20px;
    }

    .header1 h1 {
        font-size: 21px;
        margin: 0;
        font-weight: 700;
    }

    .header1 p {
        margin: 5px 0;
        font-size: 14px;
    }

    .student-info {
        display: flex;
        justify-content: space-between;
        background: #f1f3f5;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 14px;
        margin-bottom: 16px;
        border: 1px solid #e9ecef;
    }

    .student-info label {
        font-weight: 600;
        color: #495057;
    }

    .student-info .value {
        font-weight: 700;
        color: #2c3e50;
    }

    .score-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 20px;
        font-size: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border-radius: 10px;
        overflow: hidden;
    }

    .score-table th {
        background: #2c3e50;
        color: #fff;
        padding: 12px;
        text-align: center;
        font-weight: 600;
    }

    .score-table td {
        padding: 10px 12px;
        text-align: center;
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }

    .score-table tr:last-child td {
        border-bottom: none;
    }

    .result-row td {
        background: #d4edda !important;
        font-weight: 700;
        font-size: 17px;
    }

    .total-highlight {
        font-size: 30px !important;
        color: #28a745;
        font-weight: 800;
    }

    #timerSection {
        background: #212529;
        color: #fff;
        padding: 12px;
        text-align: center;
        border-radius: 8px;
        font-size: 19px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    #progressBar {
        height: 10px;
        background: #e9ecef;
        border-radius: 5px;
        overflow: hidden;
        margin-bottom: 12px;
    }

    #progressFill {
        height: 100%;
        background: linear-gradient(90deg, #28a745, #20c997);
        width: 0%;
        transition: width 0.4s ease;
    }

    .question-counter {
        text-align: center;
        font-weight: 700;
        font-size: 17px;
        color: #495057;
        margin-bottom: 10px;
    }

    .auto-submit-note {
        background: #fff3cd;
        border: 1px dashed #ffc107;
        padding: 10px;
        text-align: center;
        border-radius: 8px;
        font-size: 14px;
        margin-bottom: 14px;
        color: #856404;
    }

    .mcq {
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 18px;
        margin-bottom: 16px;
        background: #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }

    .mcq .question {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 14px;
        color: #1e3a8a;
    }

    .options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .option {
        display: flex;
        align-items: center;
        padding: 12px 14px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        background: #f8f9fa;
        transition: all 0.2s;
    }

    .option:hover {
        border-color: #adb5bd;
        background: #f1f3f5;
    }

    .option input {
        margin-right: 10px;
        transform: scale(1.2);
    }

    .option.correct {
        background: #d1eddd;
        border-color: #28a745;
        font-weight: 600;
        color: #155724;
    }

    .option.wrong {
        background: #f8d7da;
        border-color: #dc3545;
        font-weight: 600;
        color: #721c24;
    }

    .reason {
        margin-top: 14px;
        background: #e3f2fd;
        padding: 12px;
        border-left: 4px solid #2196f3;
        border-radius: 0 8px 8px 0;
        font-size: 14px;
        color: #0d47a1;
    }

    @if($mode === 'student' && !$attempt)
        .mcq { display: none; }
        .mcq.active { display: block; }
    @endif
</style>

<div class="container py-3">
    <div class="paper">
        <!-- Header -->
        <div class="header1">
            <h1>RAHBER INSTITUTE OF MEDICAL SCIENCES SIALKOT</h1>
            <p>MAG Town Hamza Colony Kashmir Road Sialkot</p>
            <p>0333-1672626 | 0324-9001008</p>
        </div>

        @if($paper->cover_image)
            <div class="text-center mb-3">
                <img src="{{ asset('storage/' . $paper->cover_image) }}"
                     style="max-height:280px; border-radius:12px; box-shadow:0 4px 16px rgba(0,0,0,0.1);">
            </div>
        @endif

        <!-- Student Info & Result Table -->
        @if($mode === 'student' && $paper->subjectiveQuestions->count() > 0)
            <div class="student-info">
                <div>
                    <label>Name:</label> <span class="value">{{ $currentStudent->name ?? 'N/A' }}</span><br>
                    <label>Father Name:</label> <span class="value">{{ $currentStudent->father_name ?? 'N/A' }}</span>
                </div>
                <div style="text-align: right;">
                    <label>Roll No:</label> <span class="value">{{ $currentStudent->rollnum ?? 'N/A' }}</span><br>
                    <label>Date:</label> <span class="value">{{ date('d F Y') }}</span>
                </div>
            </div>

            <table class="score-table">
                <tr>
                    <th colspan="2">{{ $paper->title }}</th>
                    <th>Total MCQs: {{ $mcq_total }}</th>
                    <th>Total Marks: {{ $totalOverallMarks }}</th>
                </tr>
                @if($attempt && $canSeeResult)
                    <tr>
                        <th>MCQ Marks</th>
                        <td>{{ $obtainedMcqMarks }} / {{ $totalMcqMarks }}</td>
                        <th>Subjective Marks</th>
                        <td>{{ $obtainedSubjectiveMarks }} / {{ $totalSubjectiveMarks }}</td>
                    </tr>
                    <tr class="result-row">
                        <td colspan="2"><strong>Total Obtained</strong></td>
                        <td colspan="2"><strong class="total-highlight">
                            {{ $obtainedOverallMarks }} / {{ $totalOverallMarks }}
                        </strong></td>
                    </tr>
                @elseif($attempt && !$canSeeResult)
                    <tr class="result-row bg-warning">
                        <td colspan="4" class="text-center fw-bold text-dark py-3">
                            Result will be announced on:<br>
                            <strong>{{ $paper->result_date?->format('d M Y - h:i A') }}</strong>
                        </td>
                    </tr>
                @endif
            </table>
        @else
            <div class="text-center mb-3">
                <h2>{{ $paper->title }}</h2>
                <p><strong>Total MCQs:</strong> {{ $mcq_total }} | <strong>Total Marks:</strong> {{ $totalOverallMarks }}</p>
            </div>
        @endif

        <!-- Exam Mode: Timer + Form -->
        @if($mode === 'student' && !$attempt && $allowMcq)
            <div id="timerSection" class="no-print">
                MCQ Time Remaining: <span id="mainTimer">{{ gmdate('H:i:s', $remainingMcqSeconds) }}</span>
            </div>
            <div id="progressBar" class="no-print"><div id="progressFill"></div></div>
            <div class="question-counter no-print">Question <span id="currentQuestion">1</span> of {{ $mcq_total }}</div>
            <div class="auto-submit-note no-print">
                <strong>Select answer → Next instantly!</strong><br>
                No selection → Auto next after {{ $timePerQuestionSeconds }} seconds
            </div>

            <form method="POST" action="{{ route('mcq.paper.submit', $paper->id) }}" id="mcqForm" enctype="multipart/form-data">
                @csrf
        @endif


        {{-- ABSENT VIEW --}}
        @if($mode === 'student' && $attempt && $attempt->status === 'absent')
            <div class="alert alert-danger text-center fw-bold fs-4 my-4">
                You were marked <span class="text-uppercase">ABSENT</span> for this paper.
                <br>
                <small class="fs-6">
                    You did not attempt the paper within the given time.
                </small>
            </div>

            {{-- Stop rendering MCQs & Subjective --}}
            @php return; @endphp
        @endif


        <!-- MCQ Questions -->
         <div class="mcqs">
            @foreach($paper->questions as $q)
                <div class="mcq {{ $mode === 'student' && !$attempt && $loop->first ? 'active' : '' }}" id="question-{{ $loop->index }}">
                    <p class="question">{{ $loop->iteration }}. {{ $q->question }}</p>
                    <div class="options">
                        @php
                            // ORIGINAL OPTIONS (FIXED ORDER)
                            $opts = [
                                'a' => $q->option_a,
                                'b' => $q->option_b,
                                'c' => $q->option_c,
                                'd' => $q->option_d,
                            ];

                            // If attempt exists, use saved order (else default a,b,c,d)
                            $order = $attempt && $attempt->question_order
                                ? ($attempt->question_order[$q->id] ?? ['a','b','c','d'])
                                : ['a','b','c','d'];
                        @endphp

                        @foreach($order as $opt)
                            @php
                                $text = $opts[$opt] ?? '';
                                $ans = $answers->get($q->id);
                                $isSelected = $ans && $ans->selected_option === $opt;
                                $isCorrect  = $opt === $q->correct_option;

                                $class = ($mode !== 'student' || ($attempt && $canSeeResult))
                                    ? ($isCorrect ? 'correct' : ($isSelected && !$isCorrect ? 'wrong' : ''))
                                    : '';
                            @endphp

                            <div class="option {{ $class }}">
                                <input type="radio"
                                    name="q{{ $q->id }}"
                                    value="{{ $opt }}"
                                    id="q{{ $q->id }}_{{ $opt }}"
                                    class="answer-radio"
                                    @if($mode === 'student' && !$attempt) required @endif
                                    @if($attempt && $isSelected) checked @endif
                                    @if($mode !== 'student' || $attempt) disabled @endif
                                >

                                <label for="q{{ $q->id }}_{{ $opt }}" style="width:100%; cursor:pointer;">
                                    <strong>{{ strtoupper($opt) }}.</strong> {{ $text }}
                                </label>
                            </div>
                        @endforeach


                       
                    </div>
                    @if(($mode !== 'student' || ($attempt && $canSeeResult)) && !empty($q->reason))
                        <div class="reason"><strong>Explanation:</strong> {{ $q->reason }}</div>
                    @endif
                </div>
            @endforeach
        </div>

        


      @if($mode === 'student' && $paper->subjectiveQuestions->count() > 0)
    <div class="alert alert-info text-center fw-semibold mt-3 no-print">
        <strong>Notice:</strong>
        After completing the MCQs, please refresh this page at
        <span class="text-primary">
            {{ now()->addSeconds($remainingMcqSeconds)->format('h:i A') }}
        </span>
        to proceed to the <strong>Subjective section</strong>.
    </div>
@endif



        <!-- Subjective Section -->
        
        @if($paper->subjectiveQuestions->count() > 0)
            <hr class="my-4 border-top border-secondary">
            <h5 class="mt-3 text-primary fw-bold mb-3">Subjective Questions</h5>

            <!-- Student Upload -->
            @if($mode === 'student' && $attempt && !$attempt->subjective_submitted && $allowSubjective)

                <div id="subjectiveTimerSection" class="no-print"
                    style="background:#3677e0; color:#ffff; padding:14px; text-align:center;
                            border-radius:8px; font-size:18px; font-weight:700; margin-bottom:20px;">
                    Subjective Time Remaining:
                    <span id="subjectiveTimer">{{ gmdate('H:i:s', $remainingSubjectiveSeconds) }}</span>
                    <p class="mb-0 text-black fw-bold">Subjective Complete Auto Submit Paper</p>
                </div>
                <!-- Same as before – no change needed -->
                <form method="POST"
                    action="{{ route('mcq.paper.submit_subjective', $paper->id) }}"
                    id="subjectiveForm"
                    enctype="multipart/form-data">

                    @csrf
                    @foreach($paper->subjectiveQuestions as $sq)
                        <div class="border rounded p-3 mb-3 bg-light shadow-sm">
                            <p class="fw-bold text-dark mb-2 fs-6">
                                {{ $loop->iteration }}. {{ $sq->question }}
                                <span class="badge bg-primary ms-2">{{ $sq->marks }} Marks</span>
                            </p>
                            <div class="mb-2">
                                <label class="form-label fw-medium">Upload Answer Images (optional)</label>
                                <input type="file" name="subjective[{{ $sq->id }}][images][]" class="form-control form-control-sm" multiple accept="image/ ">
                                <small class="text-muted">Multiple images allowed</small>
                            </div>
                        </div>
                    @endforeach
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-success btn-medium px-2">Submit Subjective Answers</button>
                    </div>
                </form>

            <!-- Teacher/Admin Grading -->
            @elseif($mode !== 'student')
                <!-- Full grading form – same as before -->
                @php
                    $students = $paper->students;
                    $selectedStudentId = request('student_id') ?? $students->first()?->id;
                    $selectedAttempt = $selectedStudentId 
                        ? McqAttempt::where('mcq_paper_id', $paper->id)->where('student_id', $selectedStudentId)->first()
                        : null;
                @endphp

                <form method="GET" action="{{ route('mcq.assign.view', $paper->id) }}" class="mb-4">
                    <label class="form-label fw-semibold">Select Student to Grade</label>
                    <select name="student_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">-- Select Student --</option>
                        @foreach($students as $stu)
                            <option value="{{ $stu->id }}" {{ $selectedStudentId == $stu->id ? 'selected' : '' }}>
                                {{ $stu->name }} (Roll: {{ $stu->rollnum }})
                            </option>
                        @endforeach
                    </select>
                </form>

                @if($selectedStudentId && $selectedAttempt)
                    <form method="POST" action="{{ route('mcq.assign.grade-subjective', $paper->id) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="student_id" value="{{ $selectedStudentId }}">

                        @foreach($paper->subjectiveQuestions as $sq)
                            @php
                                $subAns = SubjectiveAnswer::where('subjective_question_id', $sq->id)
                                    ->where('student_id', $selectedStudentId)
                                    ->with(['studentImages', 'teacherImages'])
                                    ->first();
                            @endphp
                            <div class="border rounded p-3 mb-4 bg-light shadow-sm">
                                <p class="fw-bold text-dark mb-2 fs-6">
                                    {{ $loop->iteration }}. {{ $sq->question }}
                                    <span class="badge bg-primary ms-2">{{ $sq->marks }} Marks</span>
                                </p>

                                <!-- Student Images -->
                                @if($subAns && $subAns->studentImages->count() > 0)
                                    <div class="mt-2">
                                        <strong>Student's Submitted Images:</strong>
                                        <div class="d-flex flex-wrap gap-2 mt-2">
                                            @foreach($subAns->studentImages as $img)
                                                <a href="{{ asset('storage/' . $img->image_path) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $img->image_path) }}"
                                                         style="height:140px; border:1px solid #ddd; border-radius:8px;">
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-2 text-muted"><em>No images submitted</em></div>
                                @endif

                                <!-- Current Teacher Feedback Images -->
                                @if($subAns && $subAns->teacherImages->count() > 0)
                                    <div class="mt-3">
                                        <strong class="text-success">Current Feedback Images:</strong>
                                        <div class="d-flex flex-wrap gap-2 mt-2">
                                            @foreach($subAns->teacherImages as $img)
                                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                                     style="height:120px; border:2px solid #28a745; border-radius:8px;">
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Assign Marks -->
                                <div class="mt-3">
                                    <label class="fw-semibold">Assign Marks (out of {{ $sq->marks }})</label>
                                    <input type="number" name="marks[{{ $sq->id }}]" class="form-control w-25 mt-1"
                                           min="0" max="{{ $sq->marks }}" value="{{ $subAns->obtained_marks ?? 0 }}" required>
                                </div>

                                <!-- New Feedback Upload -->
                                <div class="mt-3">
                                    <label class="form-label fw-medium">Upload New Feedback Images (optional)</label>
                                    <input type="file" name="feedback_images[{{ $sq->id }}][]" class="form-control form-control-sm" multiple accept="image/ ">
                                    <small class="text-muted">Add more corrected images</small>
                                </div>
                            </div>
                        @endforeach

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg px-5">Save Marks & Feedback</button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-info text-center">Please select a student to grade.</div>
                @endif

            <!-- Student View Result -->
            @elseif($mode === 'student' && $attempt && $attempt->subjective_submitted && $canSeeResult)
                @foreach($paper->subjectiveQuestions as $sq)
                    @php
                        $subAns = SubjectiveAnswer::where('subjective_question_id', $sq->id)
                            ->where('student_id', $currentStudent->id)
                            ->with(['studentImages', 'teacherImages'])
                            ->first();
                    @endphp
                    <div class="border rounded p-3 mb-3 bg-light shadow-sm">
                        <p class="fw-bold text-dark mb-2 fs-6">
                            {{ $loop->iteration }}. {{ $sq->question }}
                            <span class="badge bg-primary ms-2">{{ $sq->marks }} Marks</span>
                        </p>

                        <!-- Your Submitted Images -->
                        @if($subAns && $subAns->studentImages->count() > 0)
                            <div class="mt-2">
                                <strong>Your Submitted Images:</strong>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    @foreach($subAns->studentImages as $img)
                                        <img src="{{ asset('storage/' . $img->image_path) }}"
                                             style="height:140px; border:1px solid #ddd; border-radius:8px;">
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Teacher Feedback Images -->
                        @if($subAns && $subAns->teacherImages->count() > 0)
                            <div class="mt-3 pt-3 border-top">
                                <strong class="text-success">Teacher Feedback Images:</strong>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    @foreach($subAns->teacherImages as $img)
                                        <a href="{{ asset('storage/' . $img->image_path) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $img->image_path) }}"
                                                 style="height:140px; border:2px solid #28a745; border-radius:8px; object-fit:cover;">
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Obtained Marks -->
                        <div class="mt-3">
                            <label class="fw-semibold">Obtained Marks:</label>
                            <input type="text" class="form-control w-50 mt-1 bg-white" 
                                   value="{{ $subAns->obtained_marks ?? 0 }} / {{ $sq->marks }}" disabled>
                        </div>
                    </div>
                @endforeach
            @endif
        @endif


@if($mode === 'student' && $attempt && $attempt->status === 'present')
    <div class="text-center mt-2 py-2">
        <h3 style="color: #28a745; font-size: 18px; font-weight: 800;">Paper Submitted Successfully!</h3>
        @if(!$canSeeResult)
            <p class="mt-3 text-warning fw-bold">
                Result will be available on:<br>
                <strong>{{ $paper->result_date?->format('d M Y - h:i A') }}</strong>
            </p>
        @endif
    </div>
@endif

        <!-- Print & Back Buttons -->
        <div class="d-flex justify-content-between align-items-center mt-4 no-print">
            <button type="button" class="btn btn-primary btn-medium px-2" onclick="window.print()">
                <i class="fas fa-print me-1"></i>
                {{ $mode === 'student' && $attempt ? 'Print Result' : 'Print Paper' }}
            </button>
            <a href="{{ route('mcq.assign.index') }}" class="btn btn-secondary btn-medium px-2">
                ← Back to Papers
            </a>
        </div>
    </div>
</div>




<!-- Include SweetAlert2 CDN (put in your <head> or before scripts) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if($mode === 'student' && !$attempt && $allowMcq)
<script>
    const totalQuestions = {{ $mcq_total }};
    const timePerQuestion = {{ $timePerQuestionSeconds }};
    let currentQuestionIndex = 0;
    let remainingTotalTime = {{ $remainingMcqSeconds }};
    let questionTimeout;
    let mainTimerInterval;

    function showQuestion(index) {
        document.querySelectorAll('.mcq').forEach(q => q.classList.remove('active'));
        document.getElementById('question-' + index).classList.add('active');
        document.getElementById('currentQuestion').textContent = index + 1;

        const progress = ((index + 1) / totalQuestions) * 100;
        document.getElementById('progressFill').style.width = progress + '%';

        clearTimeout(questionTimeout);
        questionTimeout = setTimeout(goToNextQuestion, timePerQuestion * 1000);
    }

    function goToNextQuestion() {
        currentQuestionIndex++;
        if (currentQuestionIndex >= totalQuestions) {
            Swal.fire({
                title: 'Time is up!',
                text: 'Submitting your MCQ paper...',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 2000,
                didClose: () => {
                    document.getElementById('mcqForm').submit();
                }
            });
        } else {
            showQuestion(currentQuestionIndex);
        }
    }

    document.querySelectorAll('.answer-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            clearTimeout(questionTimeout);
            setTimeout(goToNextQuestion, 500);
        });
    });

    function startMainTimer() {
        mainTimerInterval = setInterval(() => {
            if (remainingTotalTime <= 0) {
                clearInterval(mainTimerInterval);
                clearTimeout(questionTimeout);
                Swal.fire({
                    title: 'MCQ Time is over!',
                    text: 'Submitting your paper...',
                    icon: 'warning',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 2000,
                    didClose: () => {
                        document.getElementById('mcqForm').submit();
                    }
                });
                return;
            }
            remainingTotalTime--;
            const h = Math.floor(remainingTotalTime / 3600);
            const m = Math.floor((remainingTotalTime % 3600) / 60);
            const s = remainingTotalTime % 60;
            document.getElementById('mainTimer').textContent =
                (h > 0 ? h + ':' : '') + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
        }, 1000);
    }

    showQuestion(0);
    startMainTimer();
</script>
@endif

@if($mode === 'student' && $attempt && !$attempt->subjective_submitted && $allowSubjective)
<script>
    let subjectiveTimeLeft = {{ $remainingSubjectiveSeconds }};

    const subjectiveTimerInterval = setInterval(() => {
        if (subjectiveTimeLeft <= 0) {
            clearInterval(subjectiveTimerInterval);
            Swal.fire({
                title: 'Subjective Time Ended!',
                text: 'Your paper is being submitted...',
                icon: 'warning',
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 2000,
                didClose: () => {
                    document.getElementById('subjectiveForm').submit();
                }
            });
            return;
        }

        subjectiveTimeLeft--;

        const h = Math.floor(subjectiveTimeLeft / 3600);
        const m = Math.floor((subjectiveTimeLeft % 3600) / 60);
        const s = subjectiveTimeLeft % 60;

        document.getElementById('subjectiveTimer').textContent =
            (h > 0 ? h + ':' : '') +
            String(m).padStart(2, '0') + ':' +
            String(s).padStart(2, '0');
    }, 1000);
</script>
@endif





@endsection