@extends('layout.master')

@section('content')
<div class="container-fluid py-4">
    <div class="card card-primary card-outline shadow-lg border-0">

        <!-- Header -->
        <div class="card-header bg-primary text-white ">
            <h3 class="card-title fw-bold mb-0">
                <i class="bi {{ $mode === 'teacher' ? 'bi-pencil-square' : 'bi-eye' }} me-2"></i>
                {{ $task->title }}
            </h3>
        </div>

        <div class="card-body">

            {{-- TEACHER MODE: show everything --}}
            @if(in_array($mode,['teacher','admin']))

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Audience</label>
                        <input type="text" class="form-control" value="{{ implode(', ', $task->audience ?? []) }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Teacher</label>
                        <input type="text" class="form-control" value="{{ $task->teacher->name ?? 'N/A' }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Task Category</label>
                        <input type="text" class="form-control" value="{{ $task->category->name ?? 'N/A' }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Task Title</label>
                        <input type="text" class="form-control" value="{{ $task->title }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Task Start</label>
                        <input type="text" class="form-control" value="{{ $task->task_start }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Task End</label>
                        <input type="text" class="form-control" value="{{ $task->task_end }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Teacher Heading</label>
                        <input type="text" class="form-control" value="{{ $task->teacher_heading }}" disabled>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Teacher Description</label>
                        <textarea class="form-control" disabled>{{ $task->teacher_desc }}</textarea>
                    </div>

                    {{-- Student Fields --}}
                    <hr>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Student Category</label>
                        <input type="text" class="form-control" value="{{ $task->studentCategories->pluck('name')->join(', ') }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Session Program</label>
                        <input type="text" class="form-control" value="{{ $task->sessionProgram->session->sessions_name ?? 'N/A' }} | {{ $task->sessionProgram->programs->pluck('name')->join(', ') }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Paper Date</label>
                        <input type="text" class="form-control" value="{{ $task->paper_date }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Student Heading</label>
                        <input type="text" class="form-control" value="{{ $task->student_heading }}" disabled>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Student Description</label>
                        <textarea class="form-control" disabled>{{ $task->student_desc }}</textarea>
                    </div>

                    {{-- Teacher Response Form --}}
                    <div class="col-12 mt-3">
                        <form method="POST" action="{{ route('tasks.response', $task->id) }}">
    @csrf
    <label class="fw-bold">Response</label>

    <div class="form-check">
        <input class="form-check-input" type="radio"
               name="response_type" value="assignment_show"
               {{ ($response->response_type ?? '') == 'assignment_show' ? 'checked' : '' }}>
        <label class="form-check-label">Assignment Show</label>
    </div>

    <div class="form-check mb-3">
        <input class="form-check-input" type="radio"
               name="response_type" value="objection"
               {{ ($response->response_type ?? '') == 'objection' ? 'checked' : '' }}>
        <label class="form-check-label">Objection</label>
    </div>

    <textarea name="desc" class="form-control" placeholder="Remarks">{{ $response->desc ?? '' }}</textarea>

    <button class="btn btn-primary mt-3">Submit Response</button>
</form>

                    </div>

                </div>

            @elseif($mode === 'student')
                {{-- STUDENT MODE: show only Paper Date, Student Heading, Student Description --}}
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Paper Date</label>
                        <input type="text" class="form-control" value="{{ $task->paper_date }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Student Heading</label>
                        <input type="text" class="form-control" value="{{ $task->student_heading }}" disabled>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Student Description</label>
                        <textarea class="form-control" disabled>{{ $task->student_desc }}</textarea>
                    </div>

                    <div class="col-12 mt-3">
                        <div class="alert alert-info">
                            <i class="bi bi-eye"></i> You can view this task only.
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
