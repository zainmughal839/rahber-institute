@extends('layout.master')

@section('content')
<div class="container-fluid py-4">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow">

        <div class="card-header bg-primary text-white">
            <h3 class="card-title fw-bold">
                <i class="bi bi-person-plus"></i>
                {{ isset($student) ? 'Edit Student — Step 1 (Personal Info)' : 'Register Student — Step 1 (Personal Info)' }}

            </h3>

            <div class="card-tools">
                @can('student.index')
                <a href="{{ route('students.index') }}" class="btn btn-light btn-sm shadow-sm">
                    <i class="bi bi-list-ul me-1"></i> All Student
                </a>
                @endcan
            </div>
        </div>

        {{-- FORM START --}}
        <form action="{{ isset($student) ? route('students.update', $student->id) : route('students.store') }}"
            method="POST" enctype="multipart/form-data"> {{-- ADD THIS --}}
            @csrf
            @if(isset($student))
            @method('PUT')
            @endif

            <div class="card-body">
                <div class="row g-3">

                    {{-- Student Name --}}
                    <div class="col-md-6">
                        <label class="form-label">Student Name *</label>
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $student->name ?? '') }}" required>
                    </div>

                    {{-- Father Name --}}
                    <div class="col-md-6">
                        <label class="form-label">Father Name *</label>
                        <input type="text" name="father_name" class="form-control"
                            value="{{ old('father_name', $student->father_name ?? '') }}" required>
                    </div>

                    {{-- CNIC --}}
                    <div class="col-md-6">
                        <label class="form-label">CNIC</label>
                        <input type="text" name="cnic" class="form-control"
                            value="{{ old('cnic', $student->cnic ?? '') }}">
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control"
                            value="{{ old('phone', $student->phone ?? '') }}">
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', $student->email ?? '') }}">
                    </div>

                    {{-- Address --}}
                    <div class="col-md-6">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control"
                            value="{{ old('address', $student->address ?? '') }}">
                    </div>

                    {{-- Student Category --}}
                    <div class="col-md-6">
                        <label class="form-label">Student Categories</label>

                        <select name="stu_category_ids[]" class="form-control my-select" multiple>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ in_array(
                                        $cat->id,
                                        old('stu_category_ids', isset($student)
                                            ? $student->categories->pluck('id')->toArray()
                                            : []
                                        )
                                    ) ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    {{-- Student Image --}}
                    <div class="col-md-6">
                        <label class="form-label">Student Image</label>
                        <input type="file" name="student_image" class="form-control" accept="image/*"
                            onchange="previewImage(event)">

                        {{-- Current Image and Preview side by side --}}
                        <div class="mt-3 d-flex gap-4 flex-wrap align-items-start">

                            {{-- Current Image --}}
                            @if(isset($student) && $student->student_image)
                            <div class="text-center">
                                <p class="mb-2 fw-bold text-primary">Current Image</p>
                                <img src="{{ asset('storage/' . $student->student_image) }}" alt="Current Student Image"
                                    style="max-width: 180px; max-height: 150px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); object-fit: cover;">
                            </div>
                            @endif

                            {{-- Preview (for new upload) --}}
                            <div class="text-center">
                                <p class="mb-2 fw-bold text-success">Preview (New Upload)</p>
                                <img id="imagePreview"
                                    src="{{ isset($student) && $student->student_image ? asset('storage/' . $student->student_image) : '' }}"
                                    style="max-width: 180px; max-height: 150px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); object-fit: cover; display: {{ isset($student) && $student->student_image ? 'block' : 'none' }};">
                                @if(!isset($student) || !$student->student_image)
                                <p class="text-muted small mt-2">No image selected yet</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control">
                        {{ old('description', $student->description ?? '') }}
                        </textarea>
                    </div>

                    {{-- Buttons --}}
                    <div class="col-12 text-end">
                        <button name="action" value="save_only" class="btn btn-secondary">
                            Save Only
                        </button>

                        <button name="action" value="next_step" class="btn btn-primary">
                            Save & Next Step
                        </button>
                    </div>

                </div>
            </div>
        </form>
        {{-- FORM END --}}

    </div>
</div>


<script>
    function previewImage(event) {
        const preview = document.getElementById('imagePreview');
        preview.src = URL.createObjectURL(event.target.files[0]);
        preview.style.display = 'block';
    }
</script>

@endsection