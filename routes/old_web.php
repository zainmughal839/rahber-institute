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

// ========== Login Page ==========
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// ========== AUTH ROUTES ==========
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Sessions
    Route::get('/sessions/all', [SessionController::class, 'all'])->name('sessions.all');
    Route::resource('sessions', SessionController::class);

    Route::get('/sessions/index', [SessionController::class, 'index'])
        ->middleware('permission:session.index')
        ->name('sessions.index');

    Route::get('/sessions/create', [SessionController::class, 'create'])
        // ->middleware('permission:session.create')
        ->name('sessions.create');

    Route::post('/sessions', [SessionController::class, 'store'])
        ->middleware('permission:session.create')
        ->name('sessions.store');

    Route::get('/sessions/{id}/edit', [SessionController::class, 'edit'])
        ->middleware('permission:session.update')
        ->name('sessions.edit');

    Route::put('/sessions/{id}', [SessionController::class, 'update'])
        ->middleware('permission:session.update')
        ->name('sessions.update');

    Route::delete('/sessions/{id}', [SessionController::class, 'destroy'])
        ->middleware('permission:session.delete')
        ->name('sessions.destroy');

    Route::get('/sessions/index', [SessionController::class, 'index'])
    ->middleware('permission:session.index')
    ->name('sessions.index');

    // Programs
    Route::get('/programs/all', [ProgramController::class, 'all'])->name('programs.all');
    Route::resource('programs', ProgramController::class);

    Route::get('/programs/index', [SessionController::class, 'index'])
        ->middleware('permission:program.index')
        ->name('programs.index');

    Route::get('/programs/create', [SessionController::class, 'create'])
        ->middleware('permission:program.create')
        ->name('programs.create');

    Route::post('/programs', [SessionController::class, 'store'])
        ->middleware('permission:program.create')
        ->name('programs.store');

    Route::get('/programs/{id}/edit', [SessionController::class, 'edit'])
        ->middleware('permission:program.update')
        ->name('programs.edit');

    Route::put('/programs/{id}', [SessionController::class, 'update'])
        ->middleware('permission:program.update')
        ->name('programs.update');

    Route::delete('/programs/{id}', [SessionController::class, 'destroy'])
        ->middleware('permission:program.delete')
        ->name('programs.destroy');

    Route::get('/programs/index', [SessionController::class, 'index'])
    ->middleware('permission:program.index')
    ->name('programs.index');

    // Session + Program Assignment
    Route::resource('session_program', SessionProgramController::class);

    // Users
    Route::resource('users', UserController::class)->except(['create', 'show']);
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');

    // Roles
    Route::resource('roles', RoleController::class);

    // Permissions
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
});