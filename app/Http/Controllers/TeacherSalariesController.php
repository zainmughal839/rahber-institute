<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Task;
use App\Models\AllLedger;
use App\Models\TeacherLedger;
use Illuminate\Support\Facades\DB;


class TeacherSalariesController extends Controller
{
    public function create()
    {
        $teachers = Teacher::all();
        return view('teacher-salaries.create', compact('teachers'));
    }


public function print($id)
{
    $salary = TeacherLedger::with('teacher')
        ->where('type', 'salary_payment')
        ->findOrFail($id);

    return view('teacher-salaries.print', compact('salary'));
}


    
    public function index(Request $request)
{
    $teachers = Teacher::all();

    $salaries = TeacherLedger::with('teacher')
        ->where('type', 'salary_payment')
        ->when($request->teacher_id, fn($q) =>
            $q->where('teacher_id', $request->teacher_id)
        )
        ->when($request->from_date, fn($q) =>
            $q->whereDate('created_at', '>=', $request->from_date)
        )
        ->when($request->to_date, fn($q) =>
            $q->whereDate('created_at', '<=', $request->to_date)
        )
        ->latest()->paginate(10);

    return view('teacher-salaries.index', compact('salaries', 'teachers'));
}





public function getData(Request $request)
{
    $type = $request->query('type');

    /* ================= SALARY ================= */
    if ($type === 'salary') {

        $teacherId = $request->query('teacher_id');

        $salary = Teacher::where('id', $teacherId)
            ->value('salary') ?? 0;

        return response()->json([
            'salary' => $salary
        ]);
    }

    /* ================= DEMERITS ================= */
    if ($type === 'demerits') {

        $teacherId = $request->query('teacher_id');
        $start = $request->query('start');
        $end = $request->query('end');

        $demerits = Task::where('teacher_id', $teacherId)
            ->whereBetween('task_end', [$start, $end])
            ->sum('d_married_points');

        return response()->json([
            'demerits' => $demerits
        ]);
    }

    return response()->json([], 400);
}



  
public function store(Request $request)
{
    $request->validate([
        'teacher_id' => 'required|exists:teachers,id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'cutting_amount' => 'required|numeric|min:0',
        'final_amount' => 'required|numeric|min:0',
    ]);

    $demerits = Task::where('teacher_id', $request->teacher_id)
        ->whereBetween('task_end', [$request->start_date, $request->end_date])
        ->sum('d_married_points');

    $period = $request->start_date . ' to ' . $request->end_date;
    $title = "Teacher Salary ({$period})";

    $description = "D-Married Points: {$demerits} | Deduction: {$request->cutting_amount}";

    DB::transaction(function () use ($request, $title, $description, $demerits) {

        /* ================= ALL LEDGER ================= */
        AllLedger::create([
            'teacher_id' => $request->teacher_id,
            'amount' => $request->final_amount,
            'type' => 'debit',                 // ✅ FIX
            'ledger_category' => 'salary',     // ✅ FIX
            'title' => $title,
            'description' => $description,
        ]);

        /* ================= TEACHER LEDGER ================= */
        TeacherLedger::create([
            'teacher_id' => $request->teacher_id,
            'amount' => $request->final_amount,
            'type' => 'salary_payment',
            'title' => $title,
            'description' => $description,
        ]);

        /* ================= DEMERIT ENTRY (OPTIONAL BUT RECOMMENDED) ================= */
        if ($demerits > 0) {
            TeacherLedger::create([
                'teacher_id' => $request->teacher_id,
                'amount' => $demerits,
                'type' => 'demerit',
                'title' => 'D-Married Points Deduction',
                'description' => 'Auto deduction from salary',
            ]);
        }
    });

    return redirect()
        ->route('teacher-salaries.create')
        ->with('success', 'Salary paid successfully!');
}





}