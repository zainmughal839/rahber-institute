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
                        <div class="card-tools">
                            <a href="{{ route('roles.index') }}" class="btn btn-light btn-sm shadow-sm">
                                <i class="bi bi-list-ul me-1"></i> All Roles
                            </a>
                        </div>
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
                    action="{{ isset($role) ? route('roles.update', $role->id) : route('roles.store') }}"
                    class="form-horizontal">
                    @csrf
                    @if(isset($role))
                    @method('PUT')
                    @endif

                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Role Key -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Role Key (Slug) <span class="text-danger">*</span>
                                </label>
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

                            <!-- Display Name -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Display Name
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-text-left"></i></span>
                                    <input type="text" name="display_name"
                                        class="form-control @error('display_name') is-invalid @enderror"
                                        value="{{ old('display_name', $role->display_name ?? '') }}"
                                        placeholder="e.g. Administrator">
                                </div>
                                @error('display_name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    Description
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                                    <textarea name="description" rows="4"
                                        class="form-control @error('description') is-invalid @enderror"
                                        placeholder="e.g. Full access to all system features">{{ old('description', $role->description ?? '') }}</textarea>
                                </div>
                                @error('description')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Permissions -->
                            <div class="col-12">
                                <hr>
                                <h5 class="fw-semibold"><i class="bi bi-shield-check"></i> Assign Permissions</h5>
                                <div class="row g-4">
                                    @foreach($permissions as $group => $perms)
                                    <div class="col-md-6">
                                        <div class="card p-3 shadow-sm">
                                            <strong class="d-block mb-2 text-primary">{{ ucfirst($group) }}</strong>
                                            @foreach($perms as $perm)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                                    value="{{ $perm->id }}" id="perm{{ $perm->id }}"
                                                    {{ in_array($perm->id, old('permissions', $assigned ?? [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="perm{{ $perm->id }}">
                                                    {{ $perm->display_name ?? $perm->name }}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to List
                            </a>
                            <button type="submit" class="btn btn-success btn-lg px-5">
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