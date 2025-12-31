{{-- resources/views/mcq/assign/partials/result.blade.php --}}

@php
use App\Models\SubjectiveAnswer;
use App\Models\SubjectiveAnswerImage;
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

</style>
<div class="paper pt-5">
      <!-- Header -->
        <div class="header1">
            <h1>RAHBER INSTITUTE OF MEDICAL SCIENCES SIALKOT</h1>
            <p>MAG Town Hamza Colony Kashmir Road Sialkot</p>
            <p>0333-1672626 | 0324-9001008</p>
        </div>
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
                <strong>{{ $paper->result_date?->tz('Asia/Karachi')->format('d M Y - h:i A') }}</strong>
            </td>
        </tr>
    @endif
</table>

<!-- MCQ Questions -->
<div class="mcqs">
    @foreach($paper->questions as $q)
        <div class="mcq">
            <p class="question">{{ $loop->iteration }}. {{ $q->question }}</p>
            <div class="options">
                @php
                    $opts = ['a' => $q->option_a, 'b' => $q->option_b, 'c' => $q->option_c, 'd' => $q->option_d];
                    $values = array_values($opts);
                    shuffle($values);
                    $display_options = [
                        ['label' => 'a', 'text' => $values[0] ?? ''],
                        ['label' => 'b', 'text' => $values[1] ?? ''],
                        ['label' => 'c', 'text' => $values[2] ?? ''],
                        ['label' => 'd', 'text' => $values[3] ?? ''],
                    ];
                @endphp

                @foreach($display_options as $disp)
                    @php
                        $opt = $disp['label'];
                        $text = $disp['text'];
                        $ans = $answers->get($q->id);
                        $isSelected = $ans && $ans->selected_option === $opt;
                        $isCorrect = $opt === $q->correct_option;
                        $class = $canSeeResult
                            ? ($isCorrect ? 'correct' : ($isSelected && !$isCorrect ? 'wrong' : ''))
                            : '';
                    @endphp
                    <div class="option {{ $class }}">
                        <input type="radio" name="q{{ $q->id }}" value="{{ $opt }}"
                               id="q{{ $q->id }}_{{ $opt }}"
                               @if($attempt && $isSelected) checked @endif
                               disabled>
                        <label for="q{{ $q->id }}_{{ $opt }}" style="width:100%;">
                            <strong>{{ strtoupper($opt) }}.</strong> {{ $text }}
                        </label>
                    </div>
                @endforeach
            </div>
            @if($canSeeResult && !empty($q->reason))
                <div class="reason"><strong>Explanation:</strong> {{ $q->reason }}</div>
            @endif
        </div>
    @endforeach
</div>

<!-- Subjective Results -->
@if($paper->subjectiveQuestions->count() > 0)
    <hr class="my-4 border-top border-secondary">
    <h4 class="mt-3 text-primary fw-bold mb-3">Subjective Questions Results</h4>

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

            @if($subAns && $subAns->studentImages->count() > 0)
                <div class="mt-2">
                    <strong>Student Submitted Images:</strong>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @foreach($subAns->studentImages as $img)
                            <img src="{{ asset('storage/' . $img->image_path) }}"
                                 style="height:140px; border:1px solid #ddd; border-radius:8px;">
                        @endforeach
                    </div>
                </div>
            @endif

            @if($subAns && $subAns->teacherImages->count() > 0)
                <div class="mt-3 pt-3 border-top">
                    <strong class="text-success">Teacher Feedback Images:</strong>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @foreach($subAns->teacherImages as $img)
                            <a href="{{ asset('storage/' . $img->image_path) }}" target="_blank">
                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                     style="height:140px; border:2px solid #28a745; border-radius:8px;">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mt-3">
                <label class="fw-semibold">Obtained Marks:</label>
                <input type="text" class="form-control w-50 bg-white" 
                       value="{{ $subAns->obtained_marks ?? 0 }} / {{ $sq->marks }}" disabled>
            </div>
        </div>
    @endforeach
@endif

<!-- Print Button -->
<div class="text-center mt-4 no-print">
    <button type="button" class="btn btn-primary btn-lg px-5" onclick="window.print()">
        <i class="fas fa-print me-2"></i> Print Result
    </button>
</div>
</div>