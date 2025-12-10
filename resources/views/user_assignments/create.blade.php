{{-- resources/views/user_assignments/create.blade.php --}}
@extends('layout.master_assign')
@section('content')

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
               
                <!-- Header -->
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                          {{ isset($assignment) ? 'Edit' : 'Create' }} Panel User Login
                        </h3>
                         @can('user-assignment.index')
                        <div class="card-tools">
                            <a href="{{ route('user-assignments.index') }}" class="btn btn-light btn-sm">
                        All Assignments
                    </a>
                        </div>
                        @endcan
                    </div>
                </div>


                <form method="POST"
                    action="{{ isset($assignment) ? route('user-assignments.update', $assignment->id) : route('user-assignments.store') }}">
                    @csrf
                    @if(isset($assignment)) @method('PUT') @endif

                    <div class="card-body">
                        <div class="row g-4">

                            <!-- Panel Type -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Panel Type <span class="text-danger">*</span></label>
                                <select name="panel_type" id="panel_type" class="form-select" required>
                                    <option value="">-- Select Panel --</option>
                                    <option value="student" {{ old('panel_type', $assignment->panel_type ?? '') == 'student' ? 'selected' : '' }}>Student Panel</option>
                                    <option value="teacher" {{ old('panel_type', $assignment->panel_type ?? '') == 'teacher' ? 'selected' : '' }}>Teacher Panel</option>
                                </select>
                            </div>

                            
                           <!-- Assign Role -->
<div class="col-md-4">
    <label class="form-label fw-bold">Assign Role <span class="text-danger">*</span></label>
    <select name="role_id" class="form-select" required>
        <option value="">-- Select Role --</option>
        @foreach($roles as $role)
        <option value="{{ $role->id }}"
            {{ old('role_id', isset($assignment) && $assignment->user ? ($assignment->user->roles->first()->id ?? '') : '') == $role->id ? 'selected' : '' }}>
            {{ ucfirst($role->name) }}
        </option>
        @endforeach
    </select>
    <small class="text-muted">Recommended: student_panel / teacher_panel</small>
</div>

                            <!-- Select Person (Dynamic + Already Assigned Hidden) -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Select Person <span class="text-danger">*</span></label>
                                <select name="assignable_id" id="assignable_select" class="form-select" required>
                                    <option value="">-- First select Panel Type --</option>
                                </select>
                                <input type="hidden" name="assignable_type" id="assignable_type">
                            </div>

<!-- Email - 100% Correct for both Create & Edit -->
<div class="col-md-6">
    <label class="form-label fw-bold">Login Email <span class="text-danger">*</span></label>
    <input type="email" name="email" id="email" class="form-control"
           value="{{ old('email', $assignment->user->email ?? '') }}"
           required>
    <small class="text-muted">
        @if(isset($assignment))
            Current login email: <strong>{{ $assignment->user->email ?? 'N/A' }}</strong>
        @else
            Auto-filled when person is selected
        @endif
    </small>
</div>


                            <!-- Password Section -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    {{ isset($assignment) ? 'Change Password (optional)' : 'Password' }}
                                    <span class="text-danger">*</span>
                                </label>

                                @if(isset($assignment))
                                    <!-- Old Password -->
                                    <div class="mb-3">
                                        <label class="form-label">Current Password <span class="text-danger">(required to change)</span></label>
                                        <div class="input-group">
                                            <input type="password" name="old_password" class="form-control" placeholder="Enter current password">
                                            <button type="button" class="btn btn-outline-secondary toggle-old">
                                                <i class="bi bi-eye-slash"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- New Password -->
                                    <div class="mb-3">
                                        <label class="form-label">New Password</label>
                                        <div class="input-group">
                                            <input type="password" name="password" id="password" class="form-control"
                                                   placeholder="Leave blank to keep old">
                                            <button type="button" class="btn btn-outline-success" id="generatePass">
                                                <i class="bi bi-magic"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary" id="togglePass">
                                                <i class="bi bi-eye-slash" id="eyeIcon"></i>
                                            </button>
                                        </div>
                                    </div>

                                    @if($assignment->plain_password)
                                    <div class="alert alert-info p-3">
                                        <strong>Current Password:</strong><br>
                                        <code class="text-primary fs-5">{{ $assignment->plain_password }}</code>
                                        <button type="button" class="btn btn-sm btn-outline-primary ms-3 copy-current">
                                            <i class="bi bi-clipboard"></i> Copy
                                        </button>
                                        <small class="d-block text-muted mt-2">Leave both fields blank to keep this password.</small>
                                    </div>
                                    @endif
                                @else
                                    <div class="input-group mb-3">
                                        <input type="password" name="password" id="password" class="form-control" required
                                               placeholder="Click Generate">
                                        <button type="button" class="btn btn-outline-success" id="generatePass">
                                            <i class="bi bi-magic"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" id="togglePass">
                                            <i class="bi bi-eye-slash" id="eyeIcon"></i>
                                        </button>
                                    </div>
                                @endif

                                <div id="passwordBox" class="border rounded p-3 bg-light mt-3" style="display:none;">
                                    <strong>Generated Password:</strong><br>
                                    <code id="plainPassword" class="text-primary fs-5">Click Generate</code>
                                    <button type="button" class="btn btn-sm btn-primary ms-3" id="copyBtn">
                                        <i class="bi bi-clipboard"></i> Copy
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-success btn-lg px-5">
                            {{ isset($assignment) ? 'Update' : 'Create' }} Panel Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection