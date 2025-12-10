<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Session;

class DisablePermissionsForAssignedUsers
{
    public function handle($request, \Closure $next)
    {
        // if student/teacher login → block all @can checks
        if (Session::get('disable_permissions') === true) {
            app()->bind('Illuminate\Contracts\Auth\Access\Gate', function () {
                return new class {
                    public function check()
                    {
                        return false;
                    }

                    public function any()
                    {
                        return false;
                    }
                };
            });
        }

        return $next($request);
    }
}