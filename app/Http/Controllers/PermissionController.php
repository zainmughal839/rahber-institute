<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\PermissionRepositoryInterface;

class PermissionController extends Controller
{
    protected $permissions;

    public function __construct(PermissionRepositoryInterface $permissions)
    {
        $this->permissions = $permissions;
        $this->middleware('auth');
    }

    public function index()
    {
        $permissions = $this->permissions->all()->groupBy('group_name');

        return view('permissions.index', compact('permissions'));
    }
}
