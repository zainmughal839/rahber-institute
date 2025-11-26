<?php

if (!function_exists('has_permission')) {
    function has_permission($permission)
    {
        return auth()->check() && auth()->user()->hasPermission($permission);
    }
}
