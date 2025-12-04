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
                            <i class="bi {{ isset($subject) ? 'bi-pencil-square' : 'bi-plus-circle' }} me-2"></i>
                            {{ isset($subject) ? 'Edit Subject' : 'Add New Subject' }}
                        </h3>

                        @can('subject.index')
                        <a href="{{ route('subjects.index') }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-list-ul me-1"></i> All Subjects
                        </a>
                        @endcan
                    </div>
                </div>

                <!-- Validation -->
                @if ($errors->any())
                <div class="alert alert-danger m-3 alert-dismissible">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <h5><i class="bi bi-exclamation-triangle"></i> Fix the following:</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Form -->
                <form method="POST"
                    action="{{ isset($subject) ? route('subjects.update', $subject->id) : route('subjects.store') }}">

                    @csrf
                    @if(isset($subject))
                    @method('PUT')
                    @endif

                    <div class="card-body">
                        <div class="row g-4">

                            <!-- Book Name -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Book Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="book_name"
                                    class="form-control @error('book_name') is-invalid @enderror"
                                    value="{{ old('book_name', $subject->book_name ?? '') }}"
                                    placeholder="e.g., Mathematics Book" required>
                                @error('book_name')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Short Name -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Short Name</label>
                                <input type="text" name="book_short_name" class="form-control"
                                    value="{{ old('book_short_name', $subject->book_short_name ?? '') }}"
                                    placeholder="e.g., Math">
                            </div>

                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to List
                            </a>
                            <button type="submit" class="btn btn-success px-3">
                                <i class="bi {{ isset($subject) ? 'bi-check2-all' : 'bi-save' }}"></i>
                                {{ isset($subject) ? 'Update Subject' : 'Save Subject' }}
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection