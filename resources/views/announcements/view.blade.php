@extends('layout.master')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-lg border-0">

        <!-- Header -->
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="bi bi-megaphone me-2"></i>
                {{ $announcement->title }}
            </h4>
        </div>

        <div class="card-body">

            {{-- ADMIN / TEACHER --}}
            @if(in_array($mode,['admin','teacher']))
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="fw-semibold">Audience</label>
                    <input type="text" class="form-control" value="{{ implode(', ', $announcement->audience ?? []) }}"
                        disabled>
                </div>

                <div class="col-md-6">
                    <label class="fw-semibold">Teachers</label>
                    <input type="text" class="form-control"
                        value="{{ $announcement->teachers->pluck('name')->join(', ') ?: 'N/A' }}" disabled>
                </div>

                <div class="col-md-6">
                    <label class="fw-semibold">Session / Program</label>
                    <input type="text" class="form-control" value="{{ $announcement->sessionProgram->session->sessions_name ?? 'N/A' }}
               | {{ $announcement->sessionProgram->programs->pluck('name')->join(', ') }}" disabled>
                </div>

                <div class="col-md-6">
                    <label class="fw-semibold">Student Categories</label>
                    <input type="text" class="form-control"
                        value="{{ $announcement->studentCategories->pluck('name')->join(', ') ?: 'N/A' }}" disabled>
                </div>

                {{-- ✅ STUDENTS --}}
                <div class="col-md-6">
                    <label class="fw-semibold">Students</label>
                    <input type="text" class="form-control"
                        value="{{ $announcement->students->pluck('name')->join(', ') ?: 'All Students' }}" disabled>
                </div>

                <div class="col-12">
                    <label class="fw-semibold">Announcement Title</label>
                    <input type="text" class="form-control" value="{{ $announcement->title }}" disabled>
                </div>

                <div class="col-12">
                    <label class="fw-semibold">Description</label>
                    <textarea class="form-control" rows="4" disabled>{{ $announcement->teacher_desc }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="fw-semibold">Status</label><br>
                    @if($announcement->is_active)
                    <span class="badge bg-success">Active</span>
                    @else
                    <span class="badge bg-danger">Inactive</span>
                    @endif
                </div>

            </div>

            {{-- STUDENT MODE --}}
            @elseif($mode === 'student')
            <div class="row g-3">

                <div class="col-12">
                    <label class="fw-semibold">Announcement Title</label>
                    <input type="text" name="title" class="form-control"
                        value="{{ old('title', $announcement->title ?? '') }}" disabled>
                </div>

                <div class="col-12">
                    <label class="fw-semibold">Announcement</label>
                    <textarea class="form-control" rows="5" disabled>{{ $announcement->teacher_desc }}</textarea>
                </div>

                <div class="col-12 mt-3">
                    <div class="alert alert-info">
                        <i class="bi bi-eye"></i> You can view this announcement only.
                    </div>
                </div>
            </div>
            @endif

        </div>

        <div class="card-footer text-end">
            <a href="{{ route('announcements.index') }}" class="btn btn-secondary">
                Back
            </a>
        </div>

    </div>
</div>
@endsection