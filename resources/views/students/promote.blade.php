@extends('layout.master')

@section('content')
<div class="container-fluid py-4">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Promote Student Class</h4>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <strong>Student:</strong> {{ $student->name }}
                </div>
                <div class="col-md-6">
                    <strong>Roll No:</strong> {{ $student->rollnum ?? 'Not Assigned' }}
                </div>
                <div class="col-md-6 mt-2">
                    <strong>Father Name:</strong> {{ $student->father_name }}
                </div>
            </div>

            <form action="{{ route('students.promote', $student->id) }}" method="POST">
                @csrf

                <div class="row g-3">

                    <!-- Current Class - Readonly -->
                    <div class="col-md-6">
                        <label class="form-label">Current Class</label>
                        <input type="text" class="form-control bg-light" 
                               value="{{ $student->classSubject->class_name ?? 'Not Assigned' }}" 
                               readonly>
                    </div>

                    <!-- New Class -->
                    <div class="col-md-6">
                        <label class="form-label">New Class <span class="text-danger">*</span></label>
                        <select name="new_class_subject_id" class="form-control" required>
                            <option value="">-- Select New Class --</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}"
                                {{ old('new_class_subject_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->class_name }}
                            </option>
                            @endforeach
                        </select>
                        @error('new_class_subject_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <label class="form-label">Description / Reason</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-12 text-end mt-4">
                        <a href="{{ route('students.promote.select') }}" class="btn btn-secondary me-2">
                            Back
                        </a>
                        <button type="submit" class="btn btn-success">
                            Promote Student
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection