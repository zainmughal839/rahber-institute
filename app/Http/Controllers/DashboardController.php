<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\User;

class DashboardController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index()
    {
        // Logged-in user
        $user = $this->userRepo->findByEmail(Auth::user()->email);

        // ===== Dashboard Stats =====
        $totalStudents = Student::count();

        // Active students (rollnum exists)
        $activeStudents = Student::whereNotNull('rollnum')->count();

        // Inactive students (no rollnum)
        $inactiveStudents = Student::whereNull('rollnum')->count();

        // Today admissions
        $todayStudents = Student::whereDate('created_at', now()->toDateString())->count();

        // Total system users
        $totalUsers = User::count();

        return view('dashboard.dashboard', compact(
            'user',
            'totalStudents',
            'activeStudents',
            'inactiveStudents',
            'todayStudents',
            'totalUsers'
        ));
    }
}
