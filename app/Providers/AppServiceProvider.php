<?php

namespace App\Providers;

use App\Repositories\Eloquent\PermissionRepository;
use App\Repositories\Eloquent\ProgramRepository;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Eloquent\SessionRepository;
use App\Repositories\Eloquent\StudentRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Repositories\Interfaces\ProgramRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\SessionRepositoryInterface;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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

        // in App\Providers\AppServiceProvider::register()
        $this->app->bind(
            \App\Repositories\Interfaces\TeacherRepositoryInterface::class,
            \App\Repositories\Eloquent\TeacherRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
