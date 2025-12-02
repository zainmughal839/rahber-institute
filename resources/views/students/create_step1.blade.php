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
            method="POST">
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
                        <label class="form-label">Student Category</label>
                        <select name="stu_category_id" class="form-control">
                            <option value="">-- Select Category --</option>

                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('stu_category_id', $student->stu_category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
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
@endsection