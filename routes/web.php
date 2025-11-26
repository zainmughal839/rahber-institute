<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SessionProgramController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ========== PUBLIC ROUTES (Login) ==========
Route::get('/', [AuthController::class, 'showLoginForm']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// ========== ALL PROTECTED ROUTES (LOGIN KE BAAD) ==========
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', fn () => view('dashboard.dashboard'))->name('dashboard');

    // Profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // ==================== SESSIONS (Permission Based) ====================
    // SESSIONS - FULLY PROTECTED
    Route::middleware('permission:session.view')->group(function () {
        Route::get('/sessions', [SessionController::class, 'index'])->name('sessions.index');
        Route::get('/sessions/all', [SessionController::class, 'all'])->name('sessions.all');
    });

    Route::middleware('permission:session.create')->group(function () {
        Route::get('/sessions/create', [SessionController::class, 'create'])->name('sessions.create');
        Route::post('/sessions', [SessionController::class, 'store'])->name('sessions.store');
    });

    Route::middleware('permission:session.update')->group(function () {
        Route::get('/sessions/{session}/edit', [SessionController::class, 'edit'])->name('sessions.edit');
        Route::put('/sessions/{session}', [SessionController::class, 'update'])->name('sessions.update');
    });

    Route::middleware('permission:session.delete')->delete('/sessions/{session}', [SessionController::class, 'destroy'])->name('sessions.destroy');
    // ==================== PROGRAMS (Abhi open rakha hai - baad mein permission laga dena) ====================
    Route::get('/programs/all', [ProgramController::class, 'all'])->name('programs.all');
    Route::resource('programs', ProgramController::class);

    // ==================== SESSION + PROGRAM ASSIGNMENT ====================
    Route::resource('session_program', SessionProgramController::class);

    // ==================== USERS (Permission laga do baad mein) ====================
    Route::resource('users', UserController::class)->except(['create', 'show']);
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');

    // ==================== ROLES ====================
    Route::resource('roles', RoleController::class);

    // ==================== PERMISSIONS (Only Admin) ====================
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
});