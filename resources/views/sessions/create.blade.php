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
                            <i class="bi {{ isset($session) ? 'bi-pencil-square' : 'bi-plus-circle' }} me-2"></i>
                            {{ isset($session) ? 'Edit Session' : 'Add New Session' }}
                        </h3>
                        @can('session.index')
                        <div class="card-tools">
                            <a href="{{ route('sessions.index') }}" class="btn btn-light btn-sm shadow-sm">
                                <i class="bi bi-list-ul me-1"></i> All Sessions
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
                <div class="alert alert-success alert-dismissible m-3">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <strong><i class="bi bi-check-circle"></i></strong> {{ session('success') }}
                </div>
                @endif

                <!-- Form -->
                <form method="POST"
                    action="{{ isset($session) ? route('sessions.update', $session->id) : route('sessions.store') }}"
                    class="form-horizontal">
                    @csrf
                    @if(isset($session))
                    @method('PUT')
                    @endif

                    <div class="card-body">
                        <div class="row g-4">

                            <!-- Session Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">
                                        Session Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="sessions_name"
                                        class="form-control @error('sessions_name') is-invalid @enderror"
                                        placeholder="e.g. Session 2024-2025"
                                        value="{{ old('sessions_name', isset($session) ? $session->sessions_name : '') }}"
                                        required>

                                    @error('sessions_name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Start Year -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Start Year <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="start_date"
                                    class="form-control @error('start_date') is-invalid @enderror"
                                    placeholder="e.g. 2024" value="{{ old('start_date', $session->start_date ?? '') }}"
                                    required>
                            </div>

                            <!-- End Year -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    End Year <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="end_date"
                                    class="form-control @error('end_date') is-invalid @enderror" placeholder="e.g. 2025"
                                    value="{{ old('end_date', $session->end_date ?? '') }}" required>
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label fw-semibold"> Description
                                    </label>
                                    <textarea name="description" rows="4"
                                        class="form-control @error('description') is-invalid @enderror"
                                        placeholder="e.g. Academic Session 2024-2025">{{ old('description', isset($session) ? $session->description : '') }}</textarea>
                                    @error('description')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('sessions.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to List
                            </a>
                            <button type="submit" class="btn btn-success btn-l px-3">
                                <i class="bi {{ isset($session) ? 'bi-check2-all' : 'bi-save' }}"></i>
                                {{ isset($session) ? 'Update' : 'Save Session' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection