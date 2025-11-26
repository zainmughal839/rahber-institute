@extends('layout.master')

@section('content')
<div class="container-fluid py-4">

    <!-- Welcome Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <h1 class="fw-bold text-primary">
                        Welcome back, {{ auth()->user()->name }}!
                    </h1>
                    <p class="lead text-muted">
                        Your Role:
                        <strong class="text-uppercase">
                            {{ auth()->user()->roles->pluck('display_name')->implode(', ') ?: 'No Role' }}
                        </strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Grid -->
    <div class="row">

        <!-- Sessions -->
        @can('sessions.index')
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 hover-shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Academic Sessions
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\Session::count() }} Active
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar3 fs-2 text-primary"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('sessions.index') }}" class="small text-primary fw-bold">
                        View All Sessions →
                    </a>
                </div>
            </div>
        </div>
        @endcan

        <!-- Programs -->
        @can('programs.index')
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 hover-shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Programs Offered
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\Program::count() }} Total
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-mortarboard fs-2 text-success"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('programs.index') }}" class="small text-success fw-bold">
                        View Programs →
                    </a>
                </div>
            </div>
        </div>
        @endcan

        <!-- Users (Only for admin/teacher) -->
        @can('users.index')
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 hover-shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\User::count() }} Registered
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fs-2 text-warning"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('users.index') }}" class="small text-warning fw-bold">
                        Manage Users →
                    </a>
                </div>
            </div>
        </div>
        @endcan

        <!-- Roles & Permissions (Only Super Admin) -->
        @can('roles.index')
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2 hover-shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Role Management
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\Role::count() }} Roles
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-shield-lock fs-2 text-danger"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('roles.index') }}" class="small text-danger fw-bold">
                        Manage Roles →
                    </a>
                </div>
            </div>
        </div>
        @endcan

    </div>

    <!-- No Permissions Message (For Students) -->
    @if(auth()->check() && auth()->user()->roles->isEmpty())
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info text-center py-5">
                <i class="bi bi-info-circle fs-1"></i>
                <h4>No permissions assigned yet</h4>
                <p>Please contact administrator to assign a role.</p>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

{{-- Optional: Hover Effect --}}
@section('styles')
<style>
.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
}

.border-left-primary {
    border-left: 5px solid #4e73df !important;
}

.border-left-success {
    border-left: 5px solid #1cc88a !important;
}

.border-left-warning {
    border-left: 5px solid #f6c23e !important;
}

.border-left-danger {
    border-left: 5px solid #e74a3b !important;
}
</style>
@endsection