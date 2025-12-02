<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SessionProgramController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
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

    //  ======================= Session ==========================================
    Route::get('/sessions/all', [SessionController::class, 'all'])->name('sessions.all');
    Route::resource('sessions', SessionController::class);

    Route::get('/sessions', [SessionController::class, 'index'])
        ->middleware('permission:session.index')
        ->name('sessions.index');

    Route::get('/sessions/create', [SessionController::class, 'create'])
        ->middleware('permission:session.create')
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

    //  ======================= Program ==========================================
    Route::get('/programs/all', [ProgramController::class, 'all'])->name('programs.all');
    Route::resource('programs', ProgramController::class);

    Route::get('/programs', [ProgramController::class, 'index'])
        ->middleware('permission:program.index')
        ->name('programs.index');

    Route::get('/programs/create', [ProgramController::class, 'create'])
        ->middleware('permission:program.create')
        ->name('programs.create');

    Route::post('/programs', [ProgramController::class, 'store'])
        ->middleware('permission:program.create')
        ->name('programs.store');

    Route::get('/programs/{id}/edit', [ProgramController::class, 'edit'])
        ->middleware('permission:program.update')
        ->name('programs.edit');

    Route::put('/programs/{id}', [ProgramController::class, 'update'])
        ->middleware('permission:program.update')
        ->name('programs.update');

    Route::delete('/programs/{id}', [ProgramController::class, 'destroy'])
        ->middleware('permission:program.delete')
        ->name('programs.destroy');

    Route::get('/programs/index', [ProgramController::class, 'index'])
        ->middleware('permission:program.index')
        ->name('programs.index');

    // Session + Program Assignment
    Route::get('/session_program/all', [SessionProgramController::class, 'index'])
        ->middleware('permission:session_program.index')
        ->name('session_program.all');

    // Standard CRUD
    Route::get('/session_program', [SessionProgramController::class, 'index'])
        ->middleware('permission:session_program.index')
        ->name('session_program.index');

    Route::get('/session_program/create', [SessionProgramController::class, 'create'])
        ->middleware('permission:session_program.create')
        ->name('session_program.create');

    Route::post('/session_program', [SessionProgramController::class, 'store'])
        ->middleware('permission:session_program.create')
        ->name('session_program.store');

    Route::get('/session_program/{id}/edit', [SessionProgramController::class, 'edit'])
        ->middleware('permission:session_program.update')
        ->name('session_program.edit');

    Route::put('/session_program/{id}', [SessionProgramController::class, 'update'])
        ->middleware('permission:session_program.update')
        ->name('session_program.update');

    Route::delete('/session_program/{id}', [SessionProgramController::class, 'destroy'])
        ->middleware('permission:session_program.delete')
        ->name('session_program.destroy');

    // Users CRUD with permissions
    Route::get('/users', [UserController::class, 'index'])
        ->middleware('permission:user.index')
        ->name('users.index');

    Route::get('/users/create', [UserController::class, 'create'])
        ->middleware('permission:user.create')
        ->name('users.create');

    Route::post('/users', [UserController::class, 'store'])
        ->middleware('permission:user.create')
        ->name('users.store');

    Route::get('/users/{user}/edit', [UserController::class, 'edit'])
        ->middleware('permission:user.update')
        ->name('users.edit');

    Route::put('/users/{user}', [UserController::class, 'update'])
        ->middleware('permission:user.update')
        ->name('users.update');

    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->middleware('permission:user.delete')
        ->name('users.destroy');

    Route::get('/users/{user}', [UserController::class, 'show'])
        ->middleware('permission:user.index') // viewing user requires index permission
        ->name('users.show');

    // Roles CRUD with permissions
    Route::get('/roles', [RoleController::class, 'index'])
        ->middleware('permission:role.index')
        ->name('roles.index');

    Route::get('/roles/create', [RoleController::class, 'create'])
        ->middleware('permission:role.create')
        ->name('roles.create');

    Route::post('/roles', [RoleController::class, 'store'])
        ->middleware('permission:role.create')
        ->name('roles.store');

    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])
        ->middleware('permission:role.update')
        ->name('roles.edit');

    Route::put('/roles/{role}', [RoleController::class, 'update'])
        ->middleware('permission:role.update')
        ->name('roles.update');

    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])
        ->middleware('permission:role.delete')
        ->name('roles.destroy');

    // Permissions
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');

    // Students
    Route::get('/students', [StudentController::class, 'index'])
        ->middleware('permission:student.index')
        ->name('students.index');

    Route::get('/students/create', [StudentController::class, 'create'])
        ->middleware('permission:student.create')
        ->name('students.create');

    Route::post('/students', [StudentController::class, 'store'])
        ->middleware('permission:student.create')
        ->name('students.store');

    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])
        ->middleware('permission:student.update')
        ->name('students.edit');

    Route::put('/students/{student}', [StudentController::class, 'update'])
        ->middleware('permission:student.update')
        ->name('students.update');

    Route::delete('/students/{student}', [StudentController::class, 'destroy'])
        ->middleware('permission:student.delete')
        ->name('students.destroy');

    Route::get('/session-program-info/{id}', [StudentController::class, 'getSessionProgramInfo'])
        ->name('session-program.info');

    Route::get('/students/{student}/assign', [StudentController::class, 'showAssignForm'])
        ->name('students.assign.form');

    Route::post('/students/{student}/assign', [StudentController::class, 'assignSessionProgram'])
        ->name('students.assign');

    // Student Ledger
    Route::get('/students/{student}/ledger', [StudentController::class, 'ledger'])
        ->name('students.ledger');
    Route::get('/students/ledger/all', [StudentController::class, 'allAllLedger'])->name('students.ledger.all');

    // // Teacher

    Route::get('/teachers', [TeacherController::class, 'index'])
    ->middleware('permission:teacher.index')
        ->name('teachers.index');

    Route::get('/teachers/create', [TeacherController::class, 'create'])
    ->middleware('permission:teacher.create')
        ->name('teachers.create');

    Route::post('/teachers', [TeacherController::class, 'store'])
    ->middleware('permission:teacher.create')
        ->name('teachers.store');

    Route::get('/teachers/{id}/edit', [TeacherController::class, 'edit'])
    ->middleware('permission:teacher.update')
        ->name('teachers.edit');

    Route::put('/teachers/{id}', [TeacherController::class, 'update'])
    ->middleware('permission:teacher.update')
        ->name('teachers.update');

    Route::delete('/teachers/{id}', [TeacherController::class, 'destroy'])
    ->middleware('permission:teacher.delete')
        ->name('teachers.destroy');

    Route::get('/teachers/{id}', [TeacherController::class, 'show'])
    ->middleware('permission:teacher.index') // Add permission if needed
    ->name('teachers.show');
    // Teacher Ledger
    Route::get('/teachers/{id}/ledger', [TeacherController::class, 'ledger'])
        ->middleware('permission:teacher.index')
        ->name('teachers.ledger');

    Route::get('/teachers/ledger/all', [TeacherController::class, 'allLedger'])
    ->middleware('permission:teacher.index')
    ->name('teachers.all_ledger');
});
