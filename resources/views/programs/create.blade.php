@extends('layout.master')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline shadow-lg border-0">

                <!-- Header -->
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi {{ isset($program) ? 'bi-pencil-square' : 'bi-plus-circle' }} me-2"></i>
                            {{ isset($program) ? 'Edit Program' : 'Add New Program' }}
                        </h3>

                        @can('program.index')
                        <a href="{{ route('programs.index') }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-list-ul me-1"></i> All Programs
                        </a>
                        @endcan
                    </div>
                </div>

                <!-- Errors -->
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible m-3">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Success -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible m-3">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    {{ session('success') }}
                </div>
                @endif

                <!-- Form -->
                <form method="POST"
                    action="{{ isset($program) ? route('programs.update', $program->id) : route('programs.store') }}">
                    @csrf
                    @if(isset($program)) @method('PUT') @endif

                    <div class="card-body">
                        <div class="row">

                            <!-- Program Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    Program Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $program->name ?? '') }}" required>
                            </div>

                            <!-- Short Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Short Name</label>
                                <input type="text" name="shortname" class="form-control"
                                    value="{{ old('shortname', $program->shortname ?? '') }}">
                            </div>

                            <!-- Program Code -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Program Code</label>
                                <input type="text" name="program_code" class="form-control"
                                    value="{{ old('program_code', $program->program_code ?? '') }}">
                            </div>

                            <!-- Description -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" rows="4"
                                    class="form-control">{{ old('description', $program->description ?? '') }}</textarea>
                            </div>

                            <!-- Divided Fees -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    Number of Divided Fees
                                </label>
                                <input type="number"
                                    name="divided_fees"
                                    min="1"
                                    class="form-control"
                                    value="{{ old('divided_fees', $program->divided_fees ?? '') }}"
                                    placeholder="e.g. 2, 4, 6">
                            </div>


                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer bg-light border-top">

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('programs.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>

                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi {{ isset($program) ? 'bi-check2-all' : 'bi-save' }}"></i>
                                {{ isset($program) ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection