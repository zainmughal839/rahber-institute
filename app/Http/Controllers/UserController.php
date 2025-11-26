<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    protected $users;

    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $perPage = 10;
        $users = $this->users->paginate($perPage);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all(); // Fetch all roles for dropdown

        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id', // ye add kar do validation
        ]);

        $data['password'] = Hash::make($data['password']);
        $user = $this->users->create($data);

        // Role assign
        $user->roles()->sync([$request->role_id]); // ya $data['role_id']

        return redirect()->route('users.index')->with('success', 'User created with role!');
    }

    public function edit($id)
    {
        $user = $this->users->find($id);
        $roles = Role::all(); // Fetch all roles for dropdown

        return view('users.create', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = $this->users->find($id);
        if (!$user) {
            abort(404);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id', // Validate role_id
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $this->users->update($user->id, $data);

        // Sync roles
        $user->roles()->sync([$data['role_id']]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = $this->users->find($id);
        if (!$user) {
            abort(404);
        }

        // Detach roles before deleting
        $user->roles()->detach();
        $this->users->delete($id);

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function show($id)
    {
        $user = $this->users->find($id);
        if (!$user) {
            abort(404);
        }

        return view('users.show', compact('user'));
    }
}
