{{-- resources/views/users/create.blade.php --}}
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
                            <i class="bi {{ isset($user) ? 'bi-pencil-square' : 'bi-person-plus' }} me-2"></i>
                            {{ isset($user) ? 'Edit User' : 'Add New User' }}
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('users.index') }}" class="btn btn-light btn-sm shadow-sm">
                                <i class="bi bi-list-ul me-1"></i> All Users
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
                    action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}"
                    class="form-horizontal">
                    @csrf
                    @if(isset($user))
                    @method('PUT')
                    @endif

                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Full Name -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $user->name ?? '') }}" placeholder="e.g. Ahmed Khan"
                                        required>
                                </div>
                                @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $user->email ?? '') }}"
                                        placeholder="e.g. ahmed@rahber.com" required>
                                </div>
                                @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Role -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Role <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                                    <select name="role_id" class="form-control @error('role_id') is-invalid @enderror"
                                        required>
                                        <option value="">-- Select Role --</option>
                                        @foreach($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ old('role_id', isset($user) && $user->roles->contains($role->id) ? $role->id : '') == $role->id ? 'selected' : '' }}>
                                            {{ $role->display_name ?? $role->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('role_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Password
                                    @if(isset($user))
                                    <small class="text-muted">(leave blank to keep same)</small>
                                    @endif
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="{{ isset($user) ? 'Enter new password' : 'Minimum 8 characters' }}"
                                        {{ isset($user) ? '' : 'required' }}>
                                </div>
                                @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Confirm Password
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        placeholder="{{ isset($user) ? 'Confirm new password' : 'Re-type password' }}"
                                        {{ isset($user) ? '' : 'required' }}>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to List
                            </a>
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="bi {{ isset($user) ? 'bi-check2-all' : 'bi-save' }}"></i>
                                {{ isset($user) ? 'Update User' : 'Save User' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection