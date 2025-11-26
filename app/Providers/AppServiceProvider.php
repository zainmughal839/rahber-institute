<?php

namespace App\Providers;

use App\Repositories\Eloquent\PermissionRepository;
use App\Repositories\Eloquent\ProgramRepository;
// Import your interfaces and repositories
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Eloquent\SessionRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Repositories\Interfaces\ProgramRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\SessionRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Blade;
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

        // existing bindings...
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::if('can', function ($permission) {
            return auth()->check() && auth()->user()->hasPermission($permission);
        });
    }
}