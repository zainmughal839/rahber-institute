@extends('layout.master')

@section('content')
<div class="container-fluid py-4">
    <div class="card card-primary card-outline shadow-lg border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="card-title fw-bold mb-0">
                <i class="bi {{ isset($record) ? 'bi-pencil-square' : 'bi-plus-circle' }} me-2"></i>
                {{ isset($record) ? 'Edit Assignment' : 'Assign Teacher to Class' }}
            </h3>
            @can('class-teacher.index')
            <div class="card-tools">
                <a href="{{ route('class-teacher.index') }}" class="btn btn-light btn-sm"><i
                        class="bi bi-list-ul me-1"></i> All Assignments</a>
            </div>
            @endcan
        </div>

        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <form method="POST"
                action="{{ isset($record) ? route('class-teacher.update', $record->id) : route('class-teacher.store') }}">
                @csrf
                @if(isset($record)) @method('PUT') @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Class (Session & Program)</label>
                        <select name="class_subjects_id" class="form-select" required>
                            <option value="">Select Class</option>
                            @foreach($classSubjects as $cs)
                            <option value="{{ $cs->id }}" @if(old('class_subjects_id', $record->class_subjects_id ?? '')
                                == $cs->id) selected @endif>
                                {{ $cs->class_name }}
                                @endforeach
                        </select>
                    </div>

                    <!-- <label class="form-label fw-semibold">Class (Session & Program)</label>
                        <select name="class_subjects_id" class="form-select" required>
                            <option value="">Select Class</option>
                            @foreach($classSubjects as $cs)
                            <option value="{{ $cs->id }}" @if(old('class_subjects_id', $record->class_subjects_id ?? '')
                                == $cs->id) selected @endif>
                                {{ $cs->class_name }} — {{ $cs->subject->book_name ?? '—' }}
                                ({{ \Carbon\Carbon::parse($cs->sessionProgram->session->start_date)->format('d M, Y') }}
                                - {{ \Carbon\Carbon::parse($cs->sessionProgram->session->end_date)->format('d M, Y') }})
                                | {{ $cs->sessionProgram->program->name ?? '—' }}
                            </option>
                            @endforeach
                        </select> -->

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Teacher</label>
                        <select name="teacher_id" class="form-select" required>
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $t)
                            <option value="{{ $t->id }}" @if(old('teacher_id', $record->teacher_id ?? '') == $t->id)
                                selected @endif>
                                {{ $t->name }} ({{ $t->cnic ?? '-' }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active" @if(old('status', $record->status ?? '') == 'active') selected
                                @endif>Active</option>
                            <option value="inactive" @if(old('status', $record->status ?? '') == 'inactive') selected
                                @endif>Inactive</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="desc" class="form-control"
                            rows="4">{{ old('desc', $record->desc ?? '') }}</textarea>
                    </div>
                </div>

                <div class="card-footer bg-light border-top mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        @can('class-teacher.index')
                        <a href="{{ route('class-teacher.index') }}" class="btn btn-secondary"><i
                                class="bi bi-arrow-left"></i> Back</a>
                        @endcan
                        <button type="submit" class="btn btn-success"><i
                                class="bi {{ isset($record) ? 'bi-check2-all' : 'bi-save' }}"></i>
                            {{ isset($record) ? 'Update' : 'Assign' }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection