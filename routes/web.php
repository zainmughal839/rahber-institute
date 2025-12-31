<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassSubjectController;
use App\Http\Controllers\ClassTeacherController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TaskCatController;
use App\Http\Controllers\SessionProgramController;
use App\Http\Controllers\StuCategoryController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\MCQPaperController;
use App\Http\Controllers\MCQQuestionController;
use App\Http\Controllers\TestCatController;
use App\Http\Controllers\McqCategoryController;
use App\Http\Controllers\McqBankController;
use App\Http\Controllers\PaperAssignController;

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

    //  ======================= Session & Program ==========================================
    Route::get('/session_program/all', [SessionProgramController::class, 'index'])
        ->middleware('permission:session_program.index')
        ->name('session_program.all');

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

    //  ======================= User ==========================================
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

    //  ======================= Role ==========================================
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

    //  ======================= Permissions ==========================================
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');

    //  ======================= Student ==========================================
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



Route::get('session-program-programs/{id}', [\App\Http\Controllers\StudentController::class, 'getProgramsBySessionProgram'])
    ->name('session_program.programs');


    Route::get('/ajax/get-programs/{spId}', [TaskController::class, 'getPrograms']);
Route::get('/ajax/get-students/{programId}', [TaskController::class, 'getStudents']);



Route::post('/ajax/students/filter', [TaskController::class, 'filterStudents'])
    ->name('ajax.students.filter');


    Route::get('ajax/programs/{sp}', [StudentController::class,'getProgramsBySessionProgram']);
Route::get('ajax/classes/{program}', [StudentController::class,'getClassesByProgram']);
Route::get(
    'ajax/program-fees/{sessionProgram}/{program}',
    [StudentController::class, 'getProgramFees']
);



    //  ======================= Teacher ==========================================
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
    ->middleware('permission:teacher.index')
    ->name('teachers.show');

    Route::get('/teachers/{id}/ledger', [TeacherController::class, 'ledger'])
        ->middleware('permission:teacher.index')
        ->name('teachers.ledger');

    Route::get('/teachers/ledger/all', [TeacherController::class, 'allLedger'])
    ->middleware('permission:teacher.index')
    ->name('teachers.all_ledger');

    //  ======================= Student Category ==========================================

    Route::resource('stu-category', StuCategoryController::class);

    //  ======================= Subject ==========================================
    Route::resource('subjects', SubjectController::class);

    //  ======================= Class Subject ==========================================
// Class Subjects CRUD
Route::prefix('class-subjects')->name('class-subjects.')->group(function () {
    Route::get('/', [ClassSubjectController::class, 'index'])->name('index');
    Route::get('/all', [ClassSubjectController::class, 'all'])->name('all');
    Route::get('/create', [ClassSubjectController::class, 'create'])->name('create');
    Route::post('/', [ClassSubjectController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ClassSubjectController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ClassSubjectController::class, 'update'])->name('update');
    Route::delete('/{id}', [ClassSubjectController::class, 'destroy'])->name('destroy');
});

// AJAX route: get programs by session program
Route::get('get-programs/{sessionProgram}', [ClassSubjectController::class, 'getPrograms']);



    // / class teacher
    Route::get('class-teacher/all', [ClassTeacherController::class, 'all'])->name('class-teacher.all');
    Route::resource('class-teacher', ClassTeacherController::class)->except(['show']);
    Route::get('class-teacher/{id}/show', [ClassTeacherController::class, 'show'])
    ->name('class-teacher.show');
    Route::get(
    'class-subject/{id}/subjects',
    [ClassTeacherController::class, 'getSubjects']
)->name('class-subject.subjects');



    //// USER ASSIGNMENTS
    Route::resource('user-assignments', App\Http\Controllers\UserAssignmentController::class)->names([
        'index' => 'user-assignments.index',
        'create' => 'user-assignments.create',
        'store' => 'user-assignments.store',
        'edit' => 'user-assignments.edit',
        'update' => 'user-assignments.update',
        'destroy' => 'user-assignments.destroy',
    ]);


    // task cat
    Route::resource('task-cat', TaskCatController::class);

    // test cat
    // Test Category
Route::resource('test-cat', TestCatController::class);


    // task
    Route::resource('tasks', TaskController::class);
    Route::get('tasks/{task}/view', [TaskController::class,'view'])
    ->name('tasks.view');

Route::post('tasks/{task}/response', [TaskController::class,'storeResponse'])
    ->name('tasks.response')
    ->middleware('permission:task.index'); 


    // announcement
    Route::resource('announcements', AnnouncementController::class);


    // MCQ Papers (CRUD)
    Route::prefix('mcqs/banks/{bank}')->group(function () {

    Route::get('questions', [McqQuestionController::class,'index'])
        ->name('mcq.banks.questions.index');

    Route::get('questions/create', [McqQuestionController::class,'create'])
        ->name('mcq.banks.questions.create');

    Route::post('questions', [McqQuestionController::class,'store'])
        ->name('mcq.banks.questions.store');

    Route::get('questions/{question}/edit', [McqQuestionController::class,'edit'])
        ->name('mcq.banks.questions.edit');

    Route::put('questions/{question}', [McqQuestionController::class,'update'])
        ->name('mcq.banks.questions.update');

    Route::delete('questions/{question}', [McqQuestionController::class,'destroy'])
        ->name('mcq.banks.questions.destroy');
});

  
// AJAX – task students
Route::get('/api/task/{task}/students', function (\App\Models\Task $task) {
    return $task->students()->select('id','name','rollnum')->get();
});

Route::get('/mcq/assign/{paper}/view', [PaperAssignController::class,'view'])
    ->name('mcq.assign.view');







    Route::post('mcq/paper/{paper}/submit', [McqPaperController::class, 'submit'])
     ->name('mcq.paper.submit');

Route::post('mcq/paper/{paper}/submit-subjective', [McqPaperController::class, 'submitSubjective'])
     ->name('mcq.paper.submit_subjective');

Route::post('mcq/assign/{paper}/grade-subjective', [PaperAssignController::class, 'gradeSubjective'])
     ->name('mcq.assign.grade-subjective');


     Route::post('/ajax/classes/filter', [TaskController::class, 'filterClasses'])->name('ajax.classes.filter');

     



});

Route::prefix('mcqs')
    ->middleware('auth')
    ->name('mcq.')
    ->group(function () {

    Route::resource('categories', McqCategoryController::class);
    Route::resource('banks', McqBankController::class);

    // Questions inside Bank
    Route::get('banks/{bank}/questions',
        [McqQuestionController::class,'index'])
        ->name('banks.questions.index');

    Route::get('banks/{bank}/questions/create',
        [McqQuestionController::class,'create'])
        ->name('banks.questions.create');

    Route::post('banks/{bank}/questions',
        [McqQuestionController::class,'store'])
        ->name('banks.questions.store');
});




// ===============================
// MCQ ASSIGN ROUTES (ALL PROTECTED BY AUTH)
// ===============================
Route::prefix('mcq/assign')
    ->middleware('auth')
    ->name('mcq.assign.')
    ->group(function () {

    Route::get('/', [PaperAssignController::class, 'index'])->name('index');

    Route::get('/create', [PaperAssignController::class, 'create'])->name('create');
    Route::post('/', [PaperAssignController::class, 'store'])->name('store'); // POST to /mcq/assign

    Route::get('/{paper}/edit', [PaperAssignController::class, 'edit'])->name('edit');
    Route::put('/{paper}', [PaperAssignController::class, 'update'])->name('update');
    Route::delete('/{paper}', [PaperAssignController::class, 'destroy'])->name('destroy');

    // VIEW ROUTE – This must be BEFORE the {paper} wildcard routes!
    Route::get('/{paper}/view', [PaperAssignController::class, 'view'])->name('view');

    // AJAX: Get questions from multiple banks
    Route::post('/banks/questions', [PaperAssignController::class, 'getQuestionsFromBanks'])
        ->name('banks.questions');

    // Optional: Single bank
    Route::get('/bank/{bank}/questions', [PaperAssignController::class, 'bankQuestions']);

    // Check Result Page
    Route::get('/check-result', [PaperAssignController::class, 'checkResult'])
        ->name('check-result');

    // AJAX: Get students for selected paper
    Route::get('/get-students', [PaperAssignController::class, 'getStudentsForPaper'])
        ->name('get-students');


     
});



/*
 * ------------------------------------------------------------
 *  modules zayada ki permisison controller main hai aur half ki
 *  routes ki file main ok.
 *  Author:  Zain Mughal
 *  Phone: +92 3258606798
 * ------------------------------------------------------------
 *
 *  Step 1 — Clear All Caches
 *  ----------------------------------------
 *  php artisan cache:clear
 *  php artisan config:clear
 *  php artisan permission:cache-reset
 *
 *  --------------------------------------------
 *   GitHub Upload Commit
 *   git add .
 *   git commit -m "Your update message"
 *   git push
 *
 * ------------------------------------------------------------
 */