@extends('layout.master')

@section('content')
<div class="container-fluid py-4">
    <div class="card card-primary card-outline shadow-lg border-0">

        <!-- Header -->
        <div class="card-header bg-primary text-white ">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bold mb-0">
                    <i class="bi {{ isset($task) ? 'bi-pencil-square' : 'bi-plus-circle' }} me-2"></i>
                    {{ isset($task) ? 'Edit Task' : 'Create New Task' }}
                </h3>

                @can('task.index')
                <a href="{{ route('tasks.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-list-ul me-1"></i> All Tasks
                </a>
                @endcan
            </div>
        </div>

        <div class="card-body">
            @if($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <form method="POST" action="{{ isset($task) ? route('tasks.update', $task->id) : route('tasks.store') }}">
                @csrf
                @if(isset($task)) @method('PUT') @endif

                @if(isset($task))
                    <script>
                        window.editTask = {
                            programIds: @json($task->programs->pluck('id')),
                            studentIds: @json($task->students->pluck('id')),
                            categoryIds: @json($task->studentCategories->pluck('id')),
                            classIds: @json($task->classes->pluck('id')),
                            sessionProgramId: {{ $task->session_program_id }}
                        };
                    </script>
                @endif

                <div class="row g-3">

                    <!-- Audience -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Audience</label>
                        <select name="audience[]" id="audience-select" class="form-control my-select" multiple required>
                            @php $audiences = ['teacher'=>'Teacher','student'=>'Student'] @endphp
                            @foreach($audiences as $key=>$label)
                            <option value="{{ $key }}"
                                {{ (isset($task) && in_array($key, $task->audience ?? [])) ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Select one or both</small>
                    </div>

                    <!-- Teacher Fields -->
                    <div class="col-md-6 teacher-field">
                        <label class="form-label fw-semibold">Teacher</label>
                        <select name="teacher_id" class="form-select">
                            <option value="">-- Select Teacher --</option>
                            @foreach($teachers as $t)
                            <option value="{{ $t->id }}"
                                {{ (old('teacher_id', $task->teacher_id ?? '') == $t->id) ? 'selected' : '' }}>
                                {{ $t->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 teacher-field">
                        <label class="form-label fw-semibold">Task Category</label>
                        <select name="task_cat_id" class="form-select">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $c)
                            <option value="{{ $c->id }}"
                                {{ (old('task_cat_id', $task->task_cat_id ?? '') == $c->id) ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 teacher-field">
                        <label class="form-label fw-semibold">D-Married Points</label>
                        <input
                            type="number"
                            name="d_married_points"
                            id="d-married-points"
                            class="form-control"
                            value="{{ old('d_married_points', $task->d_married_points ?? '') }}"
                            min="0"
                        >
                        <small class="text-muted">
                            Auto from selected category, or you can edit manually
                        </small>
                    </div>


                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Task Name</label>
                        <input type="text" name="title" class="form-control"
                            value="{{ old('title', $task->title ?? '') }}">
                    </div>

                    <div class="col-md-6 teacher-field">
                        <label class="form-label fw-semibold">Task Start</label>
                        <input type="datetime-local" name="task_start" class="form-control"
                            value="{{ old('task_start', isset($task) && $task->task_start ? $task->task_start->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <div class="col-md-6 teacher-field">
                        <label class="form-label fw-semibold">Task End</label>
                        <input type="datetime-local" name="task_end" class="form-control"
                            value="{{ old('task_end', isset($task) && $task->task_end ? $task->task_end->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <div class="col-md-6 teacher-field">
                        <label class="form-label fw-semibold">Teacher Heading</label>
                        <input type="text" name="teacher_heading" class="form-control"
                            value="{{ old('teacher_heading', $task->teacher_heading ?? '') }}">
                    </div>

                    <div class="col-12 teacher-field">
                        <label class="form-label fw-semibold">Teacher Description</label>
                        <textarea name="teacher_desc"
                            class="form-control">{{ old('teacher_desc', $task->teacher_desc ?? '') }}</textarea>
                    </div>

                    <div class="student-field">
                        <hr>

                        <label class="form-label fw-semibold">Student Details</label>
                    </div>

                    <!-- Student Fields -->
                    <div class="col-md-6 student-field">
                        <label class="form-label fw-semibold">Student Category</label>
                        <select name="stu_category_ids[]" id="stu-category-select" class="my-select"
                            style="width: 100%;" multiple>
                            @foreach($studentCategories as $sc)
                            <option value="{{ $sc->id }}" @if(isset($task) && $task->
                                studentCategories->pluck('id')->contains($sc->id))
                                selected
                                @endif
                                >
                                {{ $sc->name }}
                            </option>
                            @endforeach
                        </select>

                    </div>

                    <div class="col-md-6 student-field">
                        <label class="form-label fw-semibold">Session Program</label>
                        <select name="session_program_id" class="form-select">
                            <option value="">-- Select Session Program --</option>
                            @foreach($sessionPrograms as $sp)
                            <option value="{{ $sp->id }}"
                                {{ (old('session_program_id', $task->session_program_id ?? '') == $sp->id) ? 'selected' : '' }}>
                                {{ $sp->session->sessions_name ?? 'N/A' }} |
                                {{ $sp->programs->pluck('name')->join(', ') }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 student-field">
                        <label class="form-label fw-semibold">Program</label>
                        <select name="program_ids[]" id="program-select" class="my-select" style="width: 100%;"
                            multiple>

                            <option value="">-- Select Program --</option>
                        </select>
                    </div>

                    <div class="col-md-6 student-field">
                        <label class="form-label fw-semibold">Class</label>
                        <select name="class_ids[]" id="class-select" class="my-select" style="width: 100%;" multiple>
                            <option value="">-- Select Class --</option>
                        </select>
                    </div>

                    <div class="col-md-12 student-field">
                        <label class="form-label fw-semibold">Select Students</label>

                        <div class="border rounded p-2" style="max-height: 220px; overflow-y:auto">

                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="select-all-students">
                                <label class="form-check-label fw-bold" for="select-all-students">
                                    Select All
                                </label>
                            </div>

                            <div id="student-checkbox-list">
                                <small class="text-muted">Please select program first</small>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-6 student-field">
                        <label class="form-label fw-semibold">Paper Date</label>
                        <input type="datetime-local" name="paper_date" class="form-control"
                            value="{{ old('paper_date', isset($task) && $task->paper_date ? $task->paper_date->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <div class="col-md-6 student-field">
                        <label class="form-label fw-semibold">Student Heading</label>
                        <input type="text" name="student_heading" class="form-control"
                            value="{{ old('student_heading', $task->student_heading ?? '') }}">
                    </div>

                    <div class="col-12 student-field">
                        <label class="form-label fw-semibold">Student Description</label>
                        <textarea name="student_desc"
                            class="form-control">{{ old('student_desc', $task->student_desc ?? '') }}</textarea>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" id="has_test" name="has_test" class="form-check-input"
                                {{ old('has_test', $task->has_test ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold">Has Test Details</label>
                        </div>
                    </div>

                    <div class="test-fields mt-3 {{ old('has_test', $task->has_test ?? false) ? '' : 'd-none' }}">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Test Category</label>
                                <select name="test_category_id" class="form-select">
                                    <option value="">-- Select --</option>
                                    @foreach($testCategories as $tc)
                                    <option value="{{ $tc->id }}"
                                        {{ old('test_category_id', $task->test_category_id ?? '') == $tc->id ? 'selected':'' }}>
                                        {{ $tc->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Test Type</label><br>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="test_type" value="oral"
                                        {{ old('test_type', $task->test_type ?? '') == 'oral' ? 'checked' : '' }}>

                                    <label class="form-check-label">Oral</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="test_type" value="written"
                                        {{ old('test_type', $task->test_type ?? '') == 'written' ? 'checked' : '' }}>

                                    <label class="form-check-label">Written</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Test Title</label>
                                <input type="text" name="test_title" class="form-control"
                                    value="{{ old('test_title', $task->test_title ?? '') }}">

                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Test Description</label>
                                <textarea name="test_desc"
                                    class="form-control">{{ old('test_desc', $task->test_desc ?? '') }}</textarea>

                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Result Announce</label>
                                <input type="datetime-local" name="result_announce_at" class="form-control"
                                    value="{{ old('result_announce_at', isset($task) && $task->result_announce_at ? $task->result_announce_at->format('Y-m-d\TH:i') : '') }}">

                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Paper Submit</label>
                                <input type="datetime-local" name="paper_submit_at" class="form-control"
                                    value="{{ old('paper_submit_at', isset($task) && $task->paper_submit_at ? $task->paper_submit_at->format('Y-m-d\TH:i') : '') }}">

                            </div>

                            <div class="col-md-6">
                                <label>Total Marks</label>
                                <input type="number" name="total_marks" class="form-control"
                                    value="{{ old('total_marks', $task->total_marks ?? '') }}">

                            </div>

                            <div class="col-md-6">
                                <label>Passing Marks</label>
                                <input type="number" name="passing_marks" class="form-control"
                                    value="{{ old('passing_marks', $task->passing_marks ?? '') }}">

                            </div>

                        </div>
                    </div>

                </div>

                <div class="card-footer bg-light border-top mt-3 d-flex justify-content-between">
                    @can('task.index')
                    <a href="{{ route('tasks.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i>
                        Back</a>
                    @endcan
                    <button type="submit" class="btn btn-success">
                        <i class="bi {{ isset($task) ? 'bi-check2-all' : 'bi-save' }}"></i>
                        {{ isset($task) ? 'Update' : 'Create' }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const catSelect   = document.querySelector('select[name="task_cat_id"]');
    const pointsInput = document.getElementById('d-married-points');

    if (!catSelect || !pointsInput) return;

    let manuallyEdited = false;

    // User manually types
    pointsInput.addEventListener('input', function () {
        manuallyEdited = true;
    });

    // Category change → auto-fill only if NOT manually edited
    catSelect.addEventListener('change', function () {

        const catId = this.value;
        if (!catId) {
            if (!manuallyEdited) {
                pointsInput.value = 0;
            }
            return;
        }

        if (manuallyEdited) return;

        fetch(`/task-cat/${catId}/points`)
            .then(res => res.json())
            .then(data => {
                pointsInput.value = data.d_married_points ?? 0;
            })
            .catch(() => {
                pointsInput.value = 0;
            });
    });

});
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hasTest = document.getElementById('has_test');
        const testFields = document.querySelector('.test-fields');

        function toggleTestFields() {
            testFields.classList.toggle('d-none', !hasTest.checked);
        }
        toggleTestFields(); // 🔥 page load (edit fix)
        hasTest.addEventListener('change', toggleTestFields);
    });
</script>

@endsection