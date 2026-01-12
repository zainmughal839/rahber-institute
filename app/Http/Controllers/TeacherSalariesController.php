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

    public function __construct()
    {
        $this->middleware('permission:teacher-salary.index')->only(['index']);
        $this->middleware('permission:teacher-salary.create')->only(['create','store']);
        $this->middleware('permission:teacher-salary.update')->only(['edit','update']);
        $this->middleware('permission:teacher-salary.delete')->only(['destroy']);
    }


    public function create()
    {
        $teachers = Teacher::all();
        $nextVoucherNo = $this->generateTeacherVoucherNo();

        return view('teacher-salaries.create', compact('teachers', 'nextVoucherNo'));
    }

    public function index(Request $request)
    {
        $teachers = Teacher::all();

        $salaries = TeacherLedger::with('teacher')
            ->where('type', 'salary_payment')
            ->when($request->teacher_id, fn($q) => $q->where('teacher_id', $request->teacher_id))
            ->when($request->from_date, fn($q) => $q->whereDate('created_at', '>=', $request->from_date))
            ->when($request->to_date, fn($q) => $q->whereDate('created_at', '<=', $request->to_date))
            ->latest()->paginate(10);

        return view('teacher-salaries.index', compact('salaries', 'teachers'));
    }

    public function getData(Request $request)
    {
        $type = $request->query('type');
        $teacherId = $request->query('teacher_id');

        if ($type === 'salary') {
            $salary = Teacher::where('id', $teacherId)->value('salary') ?? 0;
            return response()->json(['salary' => $salary]);
        }

        if ($type === 'demerits') {
            $start = $request->query('start');
            $end = $request->query('end');

            $demerits = Task::where('teacher_id', $teacherId)
                ->whereBetween('task_end', [$start, $end])
                ->sum('d_married_points');

            return response()->json(['demerits' => $demerits]);
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
            'demerits' => 'required|numeric|min:0'
        ]);

        $period = $request->start_date . ' to ' . $request->end_date;
        $title = "Teacher Salary ({$period})";
        $description = "D-Married Points: {$request->demerits} | Deduction: {$request->cutting_amount}";

        $voucherNo = $this->generateTeacherVoucherNo();

        DB::transaction(function () use ($request, $title, $description, $voucherNo) {

            // ALL LEDGER
            AllLedger::create([
                'teacher_id'      => $request->teacher_id,
                'voucher_no'      => $voucherNo,
                'amount'          => $request->final_amount,
                'type'            => 'debit',
                'ledger_category' => 'salary',
                'title'           => $title,
                'description'     => $description,
            ]);

            // TEACHER LEDGER: salary payment
            TeacherLedger::create([
                'teacher_id' => $request->teacher_id,
                'voucher_no' => $voucherNo,
                'amount'     => $request->final_amount,
                'type'       => 'salary_payment',
                'title'      => $title,
                'description'=> $description,
            ]);

            // TEACHER LEDGER: demerits
            if ($request->demerits > 0) {
                TeacherLedger::create([
                    'teacher_id' => $request->teacher_id,
                    'voucher_no' => $voucherNo,
                    'amount'     => $request->demerits,
                    'type'       => 'demerit',
                    'title'      => 'D-Married Points Deduction',
                    'description'=> 'Auto deduction from salary',
                ]);
            }
        });

        return redirect()->route('teacher-salaries.create')
            ->with('success', "Salary paid successfully! Voucher: {$voucherNo}");
    }

    private function generateTeacherVoucherNo()
    {
        $last = TeacherLedger::where('voucher_no', 'like', 'TP-%')->orderByDesc('id')->first();
        $next = $last ? intval(substr($last->voucher_no, 3)) + 1 : 1;
        return 'TP-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $salary = TeacherLedger::findOrFail($id);

            // Delete from AllLedger
            AllLedger::where('voucher_no', $salary->voucher_no)
                ->where('ledger_category', 'salary')
                ->delete();

            // Delete all TeacherLedger entries of same voucher
            TeacherLedger::where('voucher_no', $salary->voucher_no)->delete();
        });

        return response()->json(['success' => true]);
    }
}
