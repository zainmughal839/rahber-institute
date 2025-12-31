{{-- resources/views/mcq/assign/check-result.blade.php (New File) --}}
@extends('layout.master')
@section('title', 'Check MCQ Result')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0 fw-bold"><i class="bi bi-search me-2"></i> Check Student Result</h3>
        </div>

        <div class="card-body ">
            <form id="resultForm" method="GET">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Select MCQ Paper</label>
                        <select name="paper_id" id="paperSelect" class="form-select" onchange="loadStudents()">
                            <option value="">-- Select Paper --</option>
                            @foreach($papers as $p)
                                <option value="{{ $p->id }}" {{ $selectedPaperId == $p->id ? 'selected' : '' }}>
                                    {{ $p->title }} (Task: {{ $p->task->title ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Select Student</label>
                        <select name="student_id" id="studentSelect" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Select Student --</option>
                            @foreach($students as $stu)
                                <option value="{{ $stu->id }}" {{ $selectedStudentId == $stu->id ? 'selected' : '' }}>
                                    {{ $stu->name }} (Roll: {{ $stu->rollnum ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

 @if($result)
    @include('mcq.assign.partials.result', [
        'paper' => $result->paper,
        'attempt' => $result->attempt,
        'currentStudent' => $result->currentStudent,
        'mcq_total' => $result->mcq_total,
        'totalMcqMarks' => $result->totalMcqMarks,
        'obtainedMcqMarks' => $result->obtainedMcqMarks,
        'totalSubjectiveMarks' => $result->totalSubjectiveMarks,
        'obtainedSubjectiveMarks' => $result->obtainedSubjectiveMarks,
        'totalOverallMarks' => $result->totalOverallMarks,
        'obtainedOverallMarks' => $result->obtainedOverallMarks,
        'answers' => $result->answers,
        'canSeeResult' => $result->canSeeResult,
    ])
@endif
        </div>
    </div>
</div>

<script>
function loadStudents() {
    const paperId = document.getElementById('paperSelect').value;
    const studentSelect = document.getElementById('studentSelect');

    studentSelect.innerHTML = '<option value="">-- Loading Students --</option>';

    if (!paperId) {
        studentSelect.innerHTML = '<option value="">-- Select Paper First --</option>';
        return;
    }

    fetch('/mcq/assign/get-students?paper_id=' + paperId)
        .then(res => res.json())
        .then(data => {
            studentSelect.innerHTML = '<option value="">-- Select Student --</option>';
            data.forEach(stu => {
                studentSelect.innerHTML += `<option value="${stu.id}">${stu.name}</option>`;
            });
        })
        .catch(err => {
            console.error(err);
            studentSelect.innerHTML = '<option value="">-- Error Loading Students --</option>';
        });
}

// Initial load if paper selected
if (document.getElementById('paperSelect').value) loadStudents();
</script>
@endsection