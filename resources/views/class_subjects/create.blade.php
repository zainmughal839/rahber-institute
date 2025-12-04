@extends('layout.master')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline shadow-lg border-0">

                <!-- Header -->
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi {{ isset($class) ? 'bi-pencil-square' : 'bi-plus-circle' }} me-2"></i>
                            {{ isset($class) ? 'Edit Class' : 'Add New Class' }}
                        </h3>
                        @can('class-subject.index')
                        <div class="card-tools">
                            <a href="{{ route('class-subjects.index') }}" class="btn btn-light btn-sm shadow-sm">
                                <i class="bi bi-list-ul me-1"></i> All Classes
                            </a>
                        </div>
                        @endcan
                    </div>

                </div>

                <!-- Validation Errors -->
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible m-3">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <h4><i class="bi bi-exclamation-triangle"></i> Please fix the errors:</h4>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Form -->
                <form method="POST"
                    action="{{ isset($class) ? route('class-subjects.update', $class->id) : route('class-subjects.store') }}"
                    class="form-horizontal">
                    @csrf
                    @if(isset($class)) @method('PUT') @endif

                    <div class="card-body">
                        <div class="row g-4">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Class Name</label>
                                <input type="text" name="class_name" class="form-control"
                                    value="{{ old('class_name', $class->class_name ?? '') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Subjects (You can select multiple)</label>
                                <select name="subject_id[]" class="form-control select2" multiple required>
                                    @foreach($subjects as $s)
                                    <option value="{{ $s->id }}" @if(isset($class) && $class->
                                        subjects->pluck('id')->contains($s->id)) selected @endif>
                                        {{ $s->book_name }} ({{ $s->book_short_name }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Session & Program</label>
                                <select name="session_program_id" class="form-control" required>
                                    <option value="">Select</option>
                                    @foreach($sessionPrograms as $sp)
                                    <option value="{{ $sp->id }}"
                                        {{ (isset($class) && $class->session_program_id == $sp->id) ? 'selected' : '' }}>
                                        {{-- Show sessions_name first --}}
                                        Session: {{ $sp->session->sessions_name ?? 'N/A' }} —
                                        {{ \Carbon\Carbon::parse($sp->session->start_date)->format('d M, Y') }}
                                        -
                                        {{ \Carbon\Carbon::parse($sp->session->end_date)->format('d M, Y') }} |
                                        Program: {{ $sp->program->name ?? 'N/A' }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="active"
                                        {{ (isset($class) && $class->status=='active') ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive"
                                        {{ (isset($class) && $class->status=='inactive') ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="desc" rows="4"
                                    class="form-control">{{ old('desc', $class->desc ?? '') }}</textarea>
                            </div>

                        </div>
                    </div>

                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            @can('class-subject.index')
                            <a href="{{ route('class-subjects.index') }}" class="btn btn-secondary"><i
                                    class="bi bi-arrow-left"></i> Back</a>
                            @endcan
                            <button type="submit" class="btn btn-success"><i class="bi bi-save"></i>
                                {{ isset($class) ? 'Update' : 'Save' }}</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>




@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<script>
$('.select2').select2({
    placeholder: "Select one or multiple subjects",
    allowClear: true,
    width: '100%'
});
</script>
@endpush