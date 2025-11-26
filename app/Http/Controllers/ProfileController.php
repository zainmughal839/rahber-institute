<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function edit()
    {
        $user = $this->userRepo->find(Auth::id());

        return view('profile.userediting', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $this->userRepo->find(Auth::id());

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('newpassword') && Hash::check($request->oldpassword, $user->password)) {
            $data['password'] = Hash::make($request->newpassword);
        }

        $this->userRepo->update($user->id, $data);

        return redirect()->back()->with('success', 'Profile updated successfully');
    }
}
