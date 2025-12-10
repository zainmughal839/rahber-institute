<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Repositories\Interfaces\UserAssignmentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserAssignmentController extends Controller
{
    protected $repo;

    public function __construct(UserAssignmentRepositoryInterface $repo)
    {
        $this->repo = $repo;

        // -------------------------------
        // ADDING ROLE & PERMISSION CHECKS
        // -------------------------------
        $this->middleware('permission:user-assignment.index')->only(['index']);
        $this->middleware('permission:user-assignment.create')->only(['create', 'store']);
        $this->middleware('permission:user-assignment.update')->only(['edit', 'update']);
        $this->middleware('permission:user-assignment.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $filters = [
            'panel_type' => $request->get('panel_type'),
            'search' => $request->get('search'),
        ];

        $assignments = $this->repo->all($filters, 500);

        return view('user_assignments.index', compact('assignments'));
    }

    public function create()
    {
        $students = Student::orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();
        $roles = \Spatie\Permission\Models\Role::all();

        return view('user_assignments.create', compact('students', 'teachers', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'panel_type'    => 'required|in:student,teacher',
            'assignable_id' => 'required|integer',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:8',
            'role_id'       => 'required|exists:roles,id',
        ]);

        // Prevent duplicate login
        $model = $request->panel_type === 'student' ? \App\Models\Student::class : \App\Models\Teacher::class;
        $exists = \App\Models\UserAssignment::where('assignable_type', $model)
            ->where('assignable_id', $request->assignable_id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['assignable_id' => 'This person already has a login!'])->withInput();
        }

        // Create User
        $user = User::create([
            'name'     => 'Panel User - ' . ucfirst($request->panel_type),
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Assign Role
        $role = \Spatie\Permission\Models\Role::findOrFail($request->role_id);
        $user->assignRole($role);

        // Create Assignment
        $this->repo->create([
            'user_id'         => $user->id,
            'assignable_type' => $model,
            'assignable_id'   => $request->assignable_id,
            'panel_type'      => $request->panel_type,
            'email'           => $request->email,
            'password_set'    => 1,
            'plain_password'  => $request->password,
        ]);

        return back()->with('success', 'Panel User Created Successfully!');
    }

    public function edit($id)
    {
        $assignment = $this->repo->find($id);
        $students = Student::orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();
        $roles = \Spatie\Permission\Models\Role::all();

        return view('user_assignments.create', compact('assignment', 'students', 'teachers', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $assignment = $this->repo->find($id);
        $user = $assignment->user;

        $rules = [
            'panel_type'    => 'required|in:student,teacher',
            'assignable_id' => 'required|integer',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'role_id'       => 'required|exists:roles,id',
        ];

        if ($request->filled('password')) {
            $rules['old_password'] = ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('The current password is incorrect.');
                }
            }];
            $rules['password'] = 'required|min:8';
        }

        $request->validate($rules);

        // Update user
        $user->update([
            'email'    => $request->email,
            'password' => $request->filled('password') ? bcrypt($request->password) : $user->password,
        ]);

        // Update role
        $role = \Spatie\Permission\Models\Role::findOrFail($request->role_id);
        $user->syncRoles($role);

        // Update assignment
        $data = [
            'panel_type' => $request->panel_type,
            'email'      => $request->email,
        ];

        if ($request->filled('password')) {
            $data['plain_password'] = $request->password;
            $data['password_set'] = 1;
        }

        $this->repo->update($id, $data);

        return back()->with('success', 'Panel User Updated Successfully!');
    }

    public function destroy($id)
    {
        $assignment = $this->repo->find($id);

        if (!$assignment) {
            return redirect()->back()->with('error', 'Assignment not found!');
        }

        // Delete related user
        $user = User::find($assignment->user_id);
        if ($user) {
            $user->delete();
        }

        // Delete assignment
        $this->repo->delete($id);

        return redirect()->back()->with('success', 'User Assignment & User Deleted Successfully!');
    }
}
