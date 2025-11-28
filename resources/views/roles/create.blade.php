{{-- resources/views/roles/create.blade.php --}}
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
                            <i class="bi {{ isset($role) ? 'bi-pencil-square' : 'bi-shield-lock' }} me-2"></i>
                            {{ isset($role) ? 'Edit Role' : 'Create New Role' }}
                        </h3>

                        @can('role.index')
                        <a href="{{ route('roles.index') }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-list-ul me-1"></i> All Roles
                        </a>
                        @endcan
                    </div>
                </div>

                <!-- Validation Errors -->
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible m-3">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <strong><i class="bi bi-exclamation-triangle"></i> Please fix the following:</strong>
                    <ul class="mb-0 mt-2">
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

                <!-- Form -->
                <form method="POST"
                    action="{{ isset($role) ? route('roles.update', $role->id) : route('roles.store') }}">
                    @csrf
                    @if(isset($role))
                    @method('PUT')
                    @endif

                    <div class="card-body">
                        <div class="row mb-4">
                            <!-- Role Name -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Role Key (Slug) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $role->name ?? '') }}" placeholder="e.g. admin" required>
                                </div>
                                @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <!-- Permission Section -->
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-shield-check"></i> Assign Permissions
                        </h5>

                        <div class="row">
                            @foreach($perms as $perm)
                            <div class="col-md-3 mb-3">
                                <div class="form-check border rounded p-2 shadow-sm small">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                        value="{{ $perm->name }}" id="perm{{ $perm->id }}"
                                        {{ in_array($perm->name, old('permissions', $assigned ?? [])) ? 'checked' : '' }}>

                                    <label class="form-check-label ms-1" for="perm{{ $perm->id }}">
                                        {{ $perm->display_name ?? ucfirst($perm->name) }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>

                    </div>

                    <!-- Footer Buttons -->
                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to List
                            </a>

                            <button type="submit" class="btn btn-success px-5">
                                <i class="bi {{ isset($role) ? 'bi-check2-all' : 'bi-save' }}"></i>
                                {{ isset($role) ? 'Update Role' : 'Save Role' }}
                            </button>
                        </div>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>
@endsection