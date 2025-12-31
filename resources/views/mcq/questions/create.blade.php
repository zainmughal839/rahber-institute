@extends('layout.master')
@section('title', $editQuestion ? 'Edit MCQ' : 'Add MCQs')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-lg border-0">

        {{-- HEADER --}}
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bold mb-0">
                    <i class="bi bi-question-circle-fill me-1"></i>
                    {{ $editQuestion ? 'Edit MCQ' : 'Add MCQs' }} — {{ $bank->name }}
                </h3>

                <a href="{{ route('mcq.banks.questions.index', $bank->id) }}" class="btn btn-light btn-sm">
                    <i class="bi bi-list-ul"></i> All MCQs
                </a>
            </div>
        </div>

        <form method="POST"
              action="{{ $editQuestion
                  ? route('mcq.banks.questions.update', [$bank->id, $editQuestion->id])
                  : route('mcq.banks.questions.store', $bank->id) }}">
            @csrf
            @if($editQuestion)
                @method('PUT')
            @endif

            <div class="card-body" id="questionRepeater">

                {{-- QUESTION BLOCK --}}
                <div class="question-block border rounded-3 p-4 mb-4 bg-light position-relative">

                    {{-- REMOVE BUTTON --}}
                    <button type="button"
                        class="btn btn-outline-danger btn-sm removeRow position-absolute top-0 end-0 m-3 {{ $editQuestion ? 'd-none' : 'd-none' }}"
                        title="Remove Question">
                        <i class="bi bi-trash-fill"></i>
                    </button>

                    <label class="fw-semibold mb-1">Question</label>
                    <textarea name="questions[0][question]" class="form-control mb-3" rows="2"
                        placeholder="Enter question" required>{{ old('questions.0.question', $editQuestion->question ?? '') }}</textarea>

                    <div class="row g-3 mb-3">
                        @foreach(['a','b','c','d'] as $o)
                        <div class="col-md-3">
                            <label class="small fw-semibold">Option {{ strtoupper($o) }}</label>
                            <input type="text"
                                   name="questions[0][option_{{ $o }}]"
                                   class="form-control"
                                   placeholder="Option {{ strtoupper($o) }}"
                                   value="{{ old('questions.0.option_'.$o, $editQuestion->{'option_'.$o} ?? '') }}"
                                   required>
                        </div>
                        @endforeach
                    </div>

                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="small fw-semibold">Correct Answer</label>
                            <select name="questions[0][correct_option]" class="form-select" required>
                                <option value="">Select</option>
                                @foreach(['a','b','c','d'] as $o)
                                    <option value="{{ $o }}"
                                        {{ old('questions.0.correct_option', $editQuestion->correct_option ?? '') == $o ? 'selected' : '' }}>
                                        Option {{ strtoupper($o) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-9">
                            <label class="small fw-semibold">Reason (Optional)</label>
                            <input type="text" name="questions[0][reason]" class="form-control"
                                   placeholder="Explanation for correct answer"
                                   value="{{ old('questions.0.reason', $editQuestion->reason ?? '') }}">
                        </div>
                    </div>
                </div>
                {{-- END BLOCK --}}
            </div>

            <div class="card-footer bg-light border-top mt-3">
                <div class="d-flex justify-content-between align-items-center">
                    @if(!$editQuestion)
                        <button type="button" id="addRow" class="btn btn-outline-primary">
                            <i class="bi bi-plus-circle me-0"></i> Add Question
                        </button>
                    @endif
                    <button class="btn btn-success px-3 shadow-sm">
                        Save MCQs
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- SCRIPT --}}
<script>
let index = 1;
document.getElementById('addRow')?.addEventListener('click', function() {
    let original = document.querySelector('.question-block');
    let clone = original.cloneNode(true);
    clone.querySelectorAll('input, textarea, select').forEach(el => {
        el.name = el.name.replace(/\[\d+\]/, '[' + index + ']');
        el.value = '';
    });
    clone.querySelector('.removeRow').classList.remove('d-none');
    document.getElementById('questionRepeater').appendChild(clone);
    index++;
});

// REMOVE QUESTION
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.removeRow');
    if (btn) {
        btn.closest('.question-block').remove();
    }
});
</script>
@endsection
