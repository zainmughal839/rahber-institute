<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    protected $roles;
    protected $perms;

    public function __construct(RoleRepositoryInterface $roles, PermissionRepositoryInterface $perms)
    {
        $this->roles = $roles;
        $this->perms = $perms;
        $this->middleware('auth'); // protect routes
    }

    public function index()
    {
        $roles = $this->roles->paginate(15);

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = $this->perms->all()->groupBy('module'); // grouped by module

        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:80', 'alpha_dash', Rule::unique('roles', 'name')],
            'display_name' => 'nullable|string|max:120',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $this->roles->create($request->all());

        return redirect()->route('roles.index')->with('success', 'Role created.');
    }

    public function edit($id)
    {
        $role = $this->roles->find($id);
        $permissions = $this->perms->all()->groupBy('module');
        $assigned = $role->permissions->pluck('id')->toArray();

        return view('roles.create', compact('role', 'permissions', 'assigned'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:80', 'alpha_dash', Rule::unique('roles', 'name')->ignore($id)],
            'display_name' => 'nullable|string|max:120',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $this->roles->update($id, $request->all());

        return redirect()->route('roles.index')->with('success', 'Role updated.');
    }

    public function destroy($id)
    {
        $this->roles->delete($id);

        return redirect()->route('roles.index')->with('success', 'Role deleted.');
    }
}
