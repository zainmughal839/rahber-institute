@extends('layout.master')
@section('title', $paper ? 'Edit MCQ Assignment' : 'Create MCQ Assignment')
@php
use App\Models\McqPaper;  // ← YE TOP PAR ADD KARO
@endphp

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                 <h3 class="card-title fw-bold mb-0">
                    <i class="bi bi-journal-check me-2"></i>
                    {{ $paper ? 'Edit MCQ Assignment' : 'Create MCQ Assignment' }}
                </h3>
                @can('assign-paper.index')
                    <a href="{{ route('mcq.assign.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-list-ul me-1"></i> All Assignments
                    </a>
                @endcan
            </div>
        </div>

        <form method="POST" action="{{ $paper ? route('mcq.assign.update', $paper->id) : route('mcq.assign.store') }}">
            @csrf
            @if($paper) @method('PUT') @endif

            <div class="card-body p-4">
                <div class="row g-4">

                    <!-- Assignment Title -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Assignment Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control" placeholder="e.g., Mid Term Examination - Physics"
                            value="{{ old('title', $paper?->title) }}" required>
                    </div>

                    <!-- Description -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Description <small class="text-muted">(Optional)</small></label>
                        <input type="text" name="description" class="form-control" placeholder="Brief description for students"
                            value="{{ old('description', $paper?->description) }}">
                    </div>

                   

                    <!-- Task Assignment -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Link to Task <span class="text-danger">*</span></label>
                        <select name="task_id" class="form-select" required>
                            <option value="">-- Select Task --</option>
                            @foreach($tasks as $task)
                                @php
                                    // Edit mode mein apna paper allow karo, baaki used disable
                                    $isUsed = McqPaper::where('task_id', $task->id)
                                        ->when($paper, fn($q) => $q->where('id', '!=', $paper->id))
                                        ->exists();
                                @endphp
                                <option value="{{ $task->id }}"
                                    {{ old('task_id', $paper?->task_id) == $task->id ? 'selected' : '' }}
                                    {{ $isUsed && old('task_id', $paper?->task_id) != $task->id ? 'disabled' : '' }}>
                                    {{ $task->title }}
                                    @if($isUsed && old('task_id', $paper?->task_id) != $task->id)
                                        <em class="text-muted">(Already assigned to another paper)</em>
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Note: Only one MCQ paper can be assigned per task.</small>
                    </div>

                    
                    <!-- MCQ Banks (Multiple Select) -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">MCQ Categories <span class="text-danger">*</span></label>
                        <select class="form-select" id="bankSelect" name="mcq_bank_ids[]" multiple required style="height: 150px;">
                            @foreach($banks as $bank)
                                <option value="{{ $bank->id }}"
                                    {{ 
                                        (old('mcq_bank_ids') && is_array(old('mcq_bank_ids')) && in_array($bank->id, old('mcq_bank_ids')))
                                        || ($paper && $paper->banks->contains('id', $bank->id))
                                        ? 'selected' 
                                        : ''
                                    }}>
                                    {{ $bank->name }} 
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Select Multiple Options</small>
                    </div>

                    <!-- Questions Selection -->
                    <div class="col-12 mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-primary mb-0">Select Questions from Mcq Category</h5>
                            <div class="badge bg-primary fs-6 px-3 py-2">
                                <strong id="selectedCount">0</strong> / <span id="requiredCount">0</span> Selected
                            </div>
                        </div>

                        <div class="alert alert-info border-0 shadow-sm" id="selectionAlert">
                            <i class="bi bi-info-circle me-2"></i>
                            Select the exact questions you want to include in this assignment.
                        </div>

                        <div id="mcqQuestionsContainer" class="row g-3"></div>
                    </div>

                     <!-- Marks per MCQ -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Marks per Correct Answer <span class="text-danger">*</span></label>
                        <input type="number" id="marksPerMcq" name="marks_per_mcq" class="form-control" min="1" step="1" required
                            value="{{ old('marks_per_mcq', $paper?->marks_per_mcq ?? 1) }}" placeholder="e.g., 2">
                    </div>

                    <!-- Required Questions (Auto-filled) -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Required Questions <span class="text-danger">*</span></label>
                        <input type="number" id="requiredQuestions" name="per_mcqs_num" class="form-control fw-bold text-primary" min="1" required readonly
                            value="{{ old('per_mcqs_num', $paper?->per_mcqs_num ?? 0) }}" placeholder="Auto-filled">
                        <small class="text-muted">Based on selected questions below</small>
                    </div>

                    <!-- Time Limit -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Time Limit (minutes) <span class="text-danger">*</span></label>
                        <input type="number" name="per_mcqs_time" class="form-control" min="1" step="1" required
                            value="{{ old('per_mcqs_time', $paper?->per_mcqs_time ?? 10) }}" placeholder="e.g., 10">
                    </div>

                    <!-- Subjective Time -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Subjective Section Time (minutes) <span class="text-danger">*</span></label>
                        <input type="number" name="subjective_time" class="form-control" min="1" required
                            value="{{ old('subjective_time', $paper?->subjective_time ?? 30) }}" placeholder="e.g., 30">
                        <small class="text-muted">Time given after MCQ section ends</small>
                    </div>

                    <!-- Result Visibility -->
                    <div class="col-12">
                        <label class="form-label fw-semibold text-dark mb-3">Result Visibility Settings</label>
                        <div class="bg-light border rounded-3 p-4">
                            <div class="row align-items-center g-4">
                                <div class="col-md-5">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="result_mode" id="resultScheduled" value="scheduled"
                                            {{ old('result_mode', $paper?->result_mode) === 'scheduled' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="resultScheduled">
                                            Show on Specific Date/Time
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline me-4">
                                        <input class="form-check-input" type="radio" name="result_mode" id="resultImmediate" value="immediate"
                                            {{ old('result_mode', $paper?->result_mode ?? 'immediate') === 'immediate' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="resultImmediate">
                                            Show Result Immediately
                                        </label>
                                    </div>
                                    
                                </div>
                                <div class="col-md-7">
                                    <input type="datetime-local" name="result_date" id="resultDateInput" class="form-control"
                                        value="{{ old('result_date', $paper?->result_date?->format('Y-m-d\TH:i')) }}"
                                        {{ old('result_mode', $paper?->result_mode ?? 'immediate') === 'scheduled' ? 'required' : '' }}>
                                    <small class="text-muted">Students will see results only after this time</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subjective Questions -->
                    <div class="col-12 mt-5">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="enableSubjective"
                                {{ old('enable_subjective', $paper && $paper->subjectiveQuestions->count() > 0) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold fs-5 text-dark" for="enableSubjective">
                                Include Subjective (Written) Questions
                            </label>
                        </div>

                        <div id="subjectiveSection" class="mt-4" style="display: {{ old('enable_subjective', $paper && $paper->subjectiveQuestions->count() > 0) ? 'block' : 'none' }};">
                            <div id="subjectiveRepeater" class="space-y-3">
                                @if($paper && $paper->subjectiveQuestions->count() > 0)
                                    @foreach($paper->subjectiveQuestions as $sq)
                                        <div class="border rounded-3 p-4 bg-white shadow-sm subjective-item position-relative">
                                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 mt-2 me-2 rounded-circle" onclick="this.closest('.subjective-item').remove()">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            <label class="form-label fw-semibold">Question</label>
                                            <textarea name="subjective[{{ $loop->index }}][question]" class="form-control" rows="2" required>{{ old("subjective.{$loop->index}.question", $sq->question) }}</textarea>

                                            <div class="mt-3">
                                                <label class="form-label fw-semibold">Marks</label>
                                                <input type="number" name="subjective[{{ $loop->index }}][marks]" class="form-control w-25" min="1" required
                                                    value="{{ old("subjective.{$loop->index}.marks", $sq->marks) }}">
                                            </div>

                                            <input type="hidden" name="subjective[{{ $loop->index }}][id]" value="{{ $sq->id }}">
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <button type="button" class="btn btn-medium mt-3 bg-primary text-white" onclick="addSubjective()">
                                <i class="bi bi-plus me-1"></i> Add Subjective Question
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12 text-end mt-5 pt-4 border-top">
                        <button type="submit" class="btn btn-success btn-medium px-2 shadow">
                            <i class="bi bi-save me-1"></i>
                            {{ $paper ? 'Update Assignment' : 'Create Assignment' }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let selectedQuestions = @json($paper?->questions->pluck('id')->toArray() ?? []);
let subjIndex = {{ $paper?->subjectiveQuestions->count() ?? 0 }};

const selectedCountDisplay = document.getElementById('selectedCount');
const requiredQuestionsInput = document.getElementById('requiredQuestions');
const selectionAlert = document.getElementById('selectionAlert');

function updateSelectionUI() {
    const count = selectedQuestions.length;
    selectedCountDisplay.textContent = count;
    requiredQuestionsInput.value = count;

    if (count === 0) {
        selectionAlert.className = 'alert alert-warning border-0 shadow-sm';
        selectionAlert.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i> No questions selected yet. Please select questions below.';
    } else {
        selectionAlert.className = 'alert alert-success border-0 shadow-sm';
        selectionAlert.innerHTML = `<i class="bi bi-check-circle me-2"></i> <strong>${count}</strong> question(s) selected and ready for assignment.`;
    }
}



function toggleQuestion(checkbox, id) {
    id = parseInt(id);
    if (checkbox.checked) {
        if (!selectedQuestions.includes(id)) selectedQuestions.push(id);
    } else {
        selectedQuestions = selectedQuestions.filter(x => x !== id);
    }
    updateSelectionUI();
}

// Bank change - now multiple
document.getElementById('bankSelect').addEventListener('change', function() {
    const selectedBankIds = Array.from(this.selectedOptions).map(option => option.value).filter(id => id !== '');

    // Reset selected questions when banks change (optional - you can keep old ones if you want)
    selectedQuestions = [];
    updateSelectionUI();

    if (selectedBankIds.length === 0) {
        document.getElementById('mcqQuestionsContainer').innerHTML = 
            '<div class="col-12 text-center text-muted py-5">Please select one or more question banks.</div>';
        return;
    }

    loadQuestionsFromMultipleBanks(selectedBankIds);
});

// New function to load from multiple banks
function loadQuestionsFromMultipleBanks(bankIds) {
    const container = document.getElementById('mcqQuestionsContainer');
    container.innerHTML = '<div class="col-12 text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-3">Loading questions from selected banks...</p></div>';

    // Send all bank IDs to backend
    fetch('/mcq/assign/banks/questions', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ bank_ids: bankIds })
    })
    .then(res => res.json())
    .then(data => {
        container.innerHTML = '';
        if (data.length === 0) {
            container.innerHTML = '<div class="col-12 text-center text-muted py-5">No questions found in selected banks.</div>';
            return;
        }

        // Group by bank name for better UX
        const grouped = {};
        data.forEach(q => {
            if (!grouped[q.bank_name]) grouped[q.bank_name] = [];
            grouped[q.bank_name].push(q);
        });

        Object.keys(grouped).forEach(bankName => {
            container.innerHTML += `
                <div class="col-12 mt-4">
                    <h6 class="fw-bold text-primary border-bottom pb-2">${bankName}</h6>
                </div>
            `;

            grouped[bankName].forEach((q, index) => {
                const checked = selectedQuestions.includes(q.id) ? 'checked' : '';
                container.innerHTML += `
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border shadow-sm hover-lift">
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="mcq_questions[]" value="${q.id}" ${checked}
                                       onchange="toggleQuestion(this, ${q.id})">
                                <label class="form-check-label fw-semibold">
                                    ${index + 1}. ${q.question.length > 80 ? q.question.substring(0,80)+'...' : q.question}
                                </label>
                            </div>
                            <div class="small text-muted mt-3 ms-4">
                                <div>A) ${q.option_a}</div>
                                <div>B) ${q.option_b}</div>
                                <div>C) ${q.option_c}</div>
                                <div>D) ${q.option_d}</div>
                            </div>
                        </div>
                    </div>
                </div>`;
            });
        });

        updateSelectionUI();
    })
    .catch(() => {
        container.innerHTML = '<div class="col-12 text-center text-danger py-5">Failed to load questions.</div>';
    });
}

// Initial load on edit
@if($paper && $paper->banks->isNotEmpty())
    @php
        $bankIds = $paper->banks->pluck('id')->toArray();
    @endphp
    const initialBankIds = @json($bankIds);
    document.addEventListener('DOMContentLoaded', function() {
        // Pre-select banks
        const select = document.getElementById('bankSelect');
        initialBankIds.forEach(id => {
            const option = select.querySelector(`option[value="${id}"]`);
            if (option) option.selected = true;
        });
        loadQuestionsFromMultipleBanks(initialBankIds);
    });
@endif

// Result date toggle
document.querySelectorAll('input[name="result_mode"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const dateInput = document.getElementById('resultDateInput');
        if (this.value === 'scheduled') {
            dateInput.required = true;
            dateInput.closest('.col-md-7').style.opacity = '1';
        } else {
            dateInput.required = false;
            dateInput.closest('.col-md-7').style.opacity = '0.6';
        }
    });
});
document.querySelector('input[name="result_mode"]:checked')?.dispatchEvent(new Event('change'));

// Subjective Questions
function addSubjective() {
    subjIndex++;
    const html = `
        <div class="border rounded-3 p-4 bg-white shadow-sm subjective-item position-relative mt-3">
            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 mt-2 me-2 rounded-circle" onclick="this.closest('.subjective-item').remove()">
                <i class="bi bi-trash"></i>
            </button>
            <label class="form-label fw-semibold">Question</label>
            <textarea name="subjective[${subjIndex}][question]" class="form-control" rows="2" required placeholder="Enter subjective question..."></textarea>

            <div class="mt-3">
                <label class="form-label fw-semibold">Marks</label>
                <input type="number" name="subjective[${subjIndex}][marks]" class="form-control w-25" min="1" required placeholder="10">
            </div>
        </div>`;
    document.getElementById('subjectiveRepeater').insertAdjacentHTML('beforeend', html);
}

document.getElementById('enableSubjective').addEventListener('change', function() {
    const section = document.getElementById('subjectiveSection');
    section.style.display = this.checked ? 'block' : 'none';
    if (!this.checked) {
        document.getElementById('subjectiveRepeater').innerHTML = '';
    }
});
</script>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4158d0, #4158d0);
    }
    .hover-lift {
        transition: all 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
    }
    .card {
        transition: all 0.3s;
    }
</style>
@endsection