@extends('layout.master')

@section('content')
<div class="container-fluid py-4">

    {{-- Success Alert --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card card-primary card-outline shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title fw-bold">
                <i class="bi bi-person-plus"></i>
                {{ isset($student) ? 'Edit Student' : 'Register Student' }}
            </h3>

            <div class="card-tools">
                @can('student.index')
                <a href="{{ route('students.index') }}" class="btn btn-light btn-sm shadow-sm">
                    <i class="bi bi-list-ul me-1"></i> All Student
                </a>
                @endcan
            </div>
        </div>

        <form action="{{ isset($student) ? route('students.update', $student->id) : route('students.store') }}"
            method="POST">
            @csrf
            @if(isset($student))
            @method('PUT')
            @endif

            <div class="card-body">
                <div class="row g-4">

                    {{-- Student Name --}}
                    <div class="col-md-6">
                        <label class="form-label">Student Name *</label>
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $student->name ?? '') }}" required>
                    </div>

                    {{-- Roll Number --}}
                    <div class="col-md-6">
                        <label class="form-label">Roll Number (Auto)</label>
                        <input type="text" name="rollnum" class="form-control bg-light"
                            value="{{ old('rollnum', $student->rollnum ?? $nextRoll ?? '') }}" readonly>
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

                    {{-- Fee --}}
                    <div class="col-md-6">
                        <label class="form-label">Fee *</label>
                        <input type="number" step="0.01" name="fees" id="fees" class="form-control"
                            value="{{ old('fees', $student->fees ?? '') }}" required>
                    </div>

                    {{-- Session Program --}}
                    <div class="col-md-6">
                        <label class="form-label">Session Program *</label>
                        <select name="session_program_id" id="session_program_id" class="form-control" required>
                            <option value="">-- Select Session Program --</option>
                            @foreach($sessionPrograms as $sp)
                            <option value="{{ $sp->id }}"
                                {{ (old('session_program_id', $student->session_program_id ?? '') == $sp->id) ? 'selected' : '' }}>
                                {{ $sp->session->start_date }} - {{ $sp->session->end_date }} ({{ $sp->program->name }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Auto Fee --}}
                    <div class="col-md-6">
                        <label class="form-label">Fee (Auto Load)</label>
                        <input type="text" id="auto_fee" class="form-control bg-light" readonly
                            value="{{ old('fees', $student->fees ?? '') }}">
                    </div>

                    {{-- Total Seats --}}
                    <div class="col-md-6">
                        <label class="form-label">Total Seats</label>
                        <input type="text" id="total_seats" class="form-control bg-light" readonly>
                    </div>

                    {{-- Available Seats --}}
                    <div class="col-md-6">
                        <label class="form-label">Available Seats</label>
                        <input type="text" id="available_seats" class="form-control bg-light" readonly>
                    </div>

                </div>
            </div>

            <div class="card-footer bg-light text-end">
                <button class="btn btn-success px-3">
                    <i class="bi bi-save"></i> {{ isset($student) ? 'Update Student' : 'Save Student' }}
                </button>
            </div>

        </form>
    </div>
</div>
@endsection