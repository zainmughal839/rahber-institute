<?php

namespace App\Providers;

use App\Repositories\Eloquent\ClassTeacherRepository;
use App\Repositories\Eloquent\PermissionRepository;
use App\Repositories\Eloquent\ProgramRepository;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Eloquent\SessionRepository;
use App\Repositories\Eloquent\StudentRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\ClassTeacherRepositoryInterface;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Repositories\Interfaces\ProgramRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\SessionRepositoryInterface;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // user
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        // session
        $this->app->bind(
            SessionRepositoryInterface::class,
            SessionRepository::class
        );

        // programn
        $this->app->bind(
            ProgramRepositoryInterface::class,
            ProgramRepository::class
        );

        // session & program
        $this->app->bind(
            \App\Repositories\Interfaces\SessionProgramRepositoryInterface::class,
            \App\Repositories\Eloquent\SessionProgramRepository::class
        );

        // Role & Permission
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);

        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);

        // teacher
        $this->app->bind(
            \App\Repositories\Interfaces\TeacherRepositoryInterface::class,
            \App\Repositories\Eloquent\TeacherRepository::class
        );

        // / student category
        $this->app->bind(
            \App\Repositories\Interfaces\StuCategoryRepositoryInterface::class,
            \App\Repositories\Eloquent\StuCategoryRepository::class
        );

        // subject
        $this->app->bind(
            \App\Repositories\Interfaces\SubjectRepositoryInterface::class,
            \App\Repositories\Eloquent\SubjectRepository::class
        );

        // / subject + Session program
        $this->app->bind(
            \App\Repositories\Interfaces\ClassSubjectRepositoryInterface::class,
            \App\Repositories\Eloquent\ClassSubjectRepository::class
        );

        // class teacher

        $this->app->bind(ClassTeacherRepositoryInterface::class, ClassTeacherRepository::class);

        // user teacher assigment
        $this->app->bind(
            \App\Repositories\Interfaces\UserAssignmentRepositoryInterface::class,
            \App\Repositories\Eloquent\UserAssignmentRepository::class
        );

        // task cat
        $this->app->bind(
        \App\Repositories\Interfaces\TaskCatRepositoryInterface::class,
        \App\Repositories\Eloquent\TaskCatRepository::class
    );

    // task
     $this->app->bind(
        \App\Repositories\Interfaces\TaskRepositoryInterface::class,
        \App\Repositories\Eloquent\TaskRepository::class
    );
    }

    /**
     * Bootstrap any application services.
     */
    // app/Providers/AuthServiceProvider.php

    public function boot()
    {
        $this->registerPolicies();

        // Ye magic line — Student/Teacher ke liye Spatie permissions disable
        Gate::before(function ($user, $ability) {
            if (session('is_panel_user') === true) {
                return false; // Sab @can = false → panel users ko kuch nahi dikhega
            }

            // Admin ke liye normal Spatie chalega
            return null;
        });
    }
}