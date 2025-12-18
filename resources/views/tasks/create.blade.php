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
                        <label class="form-label fw-semibold">Task Title</label>
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
                        <select name="stu_category_ids[]" id="stu-category-select" class="my-select"  style="width: 100%;" multiple>
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
                        <select name="program_ids[]" id="program-select" class="my-select" style="width: 100%;" multiple>

                            <option value="">-- Select Program --</option>
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

                    <!-- Completed -->
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" name="is_completed" class="form-check-input" id="is_completed"
                                {{ old('is_completed', $task->is_completed ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_completed">Mark as Completed</label>
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

@endsection