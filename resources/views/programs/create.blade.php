@extends('layout.master')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi {{ isset($program) ? 'bi-pencil-square' : 'bi-plus-circle' }} me-2"></i>
                            {{ isset($program) ? 'Edit Program' : 'Add New Program' }}
                        </h3>
                        @can('program.index')
                        <div class="card-tools">
                            <a href="{{ route('programs.index') }}" class="btn btn-light btn-sm shadow-sm">
                                <i class="bi bi-list-ul me-1"></i> All Programs
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

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form method="POST"
                    action="{{ isset($program) ? route('programs.update', $program->id) : route('programs.store') }}"
                    class="form-horizontal">
                    @csrf
                    @if(isset($program))
                    @method('PUT')
                    @endif

                    <div class="card-body">
                        <div class="row">

                            <!-- Program Name -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">
                                        Program Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', isset($program) ? $program->name : '') }}"
                                        placeholder="e.g. Bachelor of Science in Computer Science" required>
                                    @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Short Name -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Short Name</label>
                                    <input type="text" name="shortname"
                                        class="form-control @error('shortname') is-invalid @enderror"
                                        value="{{ old('shortname', isset($program) ? $program->shortname : '') }}"
                                        placeholder="e.g. BS-CS">
                                    @error('shortname')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Program Code -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Program Code</label>
                                    <input type="text" name="program_code"
                                        class="form-control @error('program_code') is-invalid @enderror"
                                        value="{{ old('program_code', isset($program) ? $program->program_code : '') }}"
                                        placeholder="e.g. BSCS-2024">
                                    @error('program_code')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Description</label>
                                    <textarea name="description" rows="4"
                                        class="form-control @error('description') is-invalid @enderror"
                                        placeholder="Write a brief description...">{{ old('description', isset($program) ? $program->description : '') }}</textarea>
                                    @error('description')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('programs.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to List
                            </a>
                            <button type="submit" class="btn btn-success btn-l px-3">
                                <i class="bi {{ isset($program) ? 'bi-check2-all' : 'bi-save' }}"></i>
                                {{ isset($program) ? 'Update' : 'Submit' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection