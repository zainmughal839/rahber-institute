@extends('layout.master')

@section('content')
<div class="app-content">
    <div class="container-fluid">

        <h3 class="mb-4 fw-bold">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard Overview
        </h3>

        <!-- Row 1 (4 Boxes) -->
        <div class="row g-4 mb-4">

            <!-- Total Students -->
            <div class="col-lg-3 col-md-6">
                <div class="small-box text-bg-primary shadow">
                    <div class="inner">
                        <h3>{{ $totalStudents ?? 0 }}</h3>
                        <p>Total Students</p>
                    </div>
                    <i class="bi bi-people-fill small-box-icon"></i>
                </div>
            </div>

            <!-- Active Students -->
            <div class="col-lg-3 col-md-6">
                <div class="small-box text-bg-success shadow">
                    <div class="inner">
                        <h3>{{ $activeStudents ?? 0 }}</h3>
                        <p>Active Students</p>
                    </div>
                    <i class="bi bi-person-check-fill small-box-icon"></i>
                </div>
            </div>

            <!-- Inactive Students -->
            <div class="col-lg-3 col-md-6">
                <div class="small-box text-bg-warning shadow">
                    <div class="inner">
                        <h3>{{ $inactiveStudents ?? 0 }}</h3>
                        <p>Inactive Students</p>
                    </div>
                    <i class="bi bi-person-x-fill small-box-icon"></i>
                </div>
            </div>

            <!-- Total Users -->
            <div class="col-lg-3 col-md-6">
                <div class="small-box text-bg-danger shadow">
                    <div class="inner">
<h3>{{ $totalUsers ?? 0 }}</h3>
                        <p>System Users</p>
                    </div>
                    <i class="bi bi-shield-lock-fill small-box-icon"></i>
                </div>
            </div>

        </div>

        <!-- Row 2 (2 Boxes) -->
        <div class="row g-4">

            <!-- Today Admissions -->
            <div class="col-lg-3 col-md-6">
                <div class="small-box text-bg-info shadow">
                    <div class="inner">
<h3>{{ $todayStudents ?? 0 }}</h3>
                        <p>Today Admissions</p>
                    </div>
                    <i class="bi bi-calendar-plus-fill small-box-icon"></i>
                </div>
            </div>

            <!-- Logged-in User -->
            <div class="col-lg-3 col-md-6">
                <div class="small-box text-bg-secondary shadow">
                    <div class="inner">
                        <h4 class="fw-bold mb-1">{{ $user->name  ?? 0}}</h4>
                        <p>Logged In User</p>
                    </div>
                    <i class="bi bi-person-circle small-box-icon"></i>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
