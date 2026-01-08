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
                    <input type="text" class="form-control"
                        value="{{ $task->studentCategories->pluck('name')->join(', ') }}" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Session Program</label>
                    <input type="text" class="form-control"
                        value="{{ $task->sessionProgram->session->sessions_name ?? 'N/A' }} | {{ $task->sessionProgram->programs->pluck('name')->join(', ') }}"
                        disabled>
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

                <hr>

                <div class="col-12">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bi bi-people-fill me-2"></i> Assigned Students
                    </h5>

                    @if($task->students && $task->students->count())
                    <div class="table-responsive">
                       <table class="table table-bordered table-hover align-middle">
    <thead class="table-light">
        <tr>
            <th width="60">#</th>
            <th>Name</th>
            <th>Roll No</th>
        </tr>
    </thead>
    <tbody>
        @foreach($task->students as $std)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $std->name }}</td>
            <td>{{ $std->rollnum ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

                    </div>
                    @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-circle"></i>
                        No students assigned to this task.
                    </div>
                    @endif
                </div>

                {{-- TEST DETAILS --}}
                @if($task->has_test)
                <hr>

                <div class="col-12">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="bi bi-journal-text me-2"></i> Test Details
                    </h5>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Test Category</label>
                    <input type="text" class="form-control" value="{{ $task->testCategory->name ?? 'N/A' }}" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Test Type</label>
                    <input type="text" class="form-control text-capitalize" value="{{ $task->test_type }}" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Test Title</label>
                    <input type="text" class="form-control" value="{{ $task->test_title }}" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Result Announce At</label>
                    <input type="text" class="form-control" value="{{ $task->result_announce_at ?? 'N/A' }}" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Paper Submit At</label>
                    <input type="text" class="form-control" value="{{ $task->paper_submit_at ?? 'N/A' }}" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Total Marks</label>
                    <input type="text" class="form-control" value="{{ $task->total_marks }}" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Passing Marks</label>
                    <input type="text" class="form-control" value="{{ $task->passing_marks }}" disabled>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Test Description</label>
                    <textarea class="form-control" disabled>{{ $task->test_desc }}</textarea>
                </div>
                @endif

                {{-- Teacher Response Form --}}
                <div class="col-12 mt-3">
                    <form method="POST" action="{{ route('tasks.response', $task->id) }}">
                        @csrf
                        <label class="fw-bold">Response</label>

                        <div class="form-check">
                            <input class="form-check-input" type="radio"
                                name="response_type"
                                value="complete"
                                {{ ($response->response_type ?? '') == 'complete' ? 'checked' : '' }}
                                {{ ($mode === 'teacher' && $task->task_end && now()->gt($task->task_end)) ? 'disabled' : '' }}>
                            <label class="form-check-label">Complete</label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio"
                                name="response_type"
                                value="not_complete"
                                {{ ($response->response_type ?? '') == 'not_complete' ? 'checked' : '' }}
                                {{ ($mode === 'teacher' && $task->task_end && now()->gt($task->task_end)) ? 'disabled' : '' }}>
                            <label class="form-check-label">Not Complete</label>
                        </div>

                        <div class="col-md-6 mt-2">
    <label class="form-label fw-semibold">D-Married Points</label>
    <input type="text" class="form-control"
        value="{{ $response->d_married_points ?? 0 }}"
        disabled>
</div>


                        <textarea name="desc" class="form-control"
                            placeholder="Remarks">{{ $response->desc ?? '' }}</textarea>

                            @if(!($mode === 'teacher' && $task->task_end && now()->gt($task->task_end)))
<button class="btn btn-primary mt-3">Submit Response</button>
@else
<div class="alert alert-warning mt-3">
    Deadline passed. Response is read-only.
</div>
@endif

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

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Test Type</label>
                    <input type="text" class="form-control text-capitalize" value="{{ $task->test_type }}" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Test Title</label>
                    <input type="text" class="form-control" value="{{ $task->test_title }}" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Result Announce At</label>
                    <input type="text" class="form-control" value="{{ $task->result_announce_at ?? 'N/A' }}" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Total Marks</label>
                    <input type="text" class="form-control" value="{{ $task->total_marks }}" disabled>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Passing Marks</label>
                    <input type="text" class="form-control" value="{{ $task->passing_marks }}" disabled>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Test Description</label>
                    <textarea class="form-control" disabled>{{ $task->test_desc }}</textarea>
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