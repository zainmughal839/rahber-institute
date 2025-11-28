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
        $this->middleware('auth');
    }

    public function index()
    {
        $roles = $this->roles->paginate(15);

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $perms = $this->perms->all(); // now matching blade variable

        return view('roles.create', compact('perms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:80', 'alpha_dash', Rule::unique('roles', 'name')],
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $this->roles->create($request->all());

        return redirect()->back()->with('success', 'created ROle successfully.');
    }

    public function edit($id)
    {
        $role = $this->roles->find($id);

        $perms = $this->perms->all();
        $assigned = $role->permissions->pluck('name')->toArray();

        return view('roles.create', compact('role', 'perms', 'assigned'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:80', 'alpha_dash', Rule::unique('roles', 'name')->ignore($id)],
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $this->roles->update($id, $request->all());

        return redirect()->back()->with('success', 'updated Role successfully.');
    }

    public function destroy($id)
    {
        $this->roles->delete($id);

        return redirect()->route('roles.index')->with('success', 'Role deleted.');
    }
}
