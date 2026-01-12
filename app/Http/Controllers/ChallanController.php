<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SessionProgram;
use App\Models\Program;
use App\Models\ClassSubject;
use App\Models\Student;
use App\Models\Challan;
use App\Models\AllLedger;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ChallanController extends Controller
{


    public function __construct()
    {
        $this->middleware('permission:challan.index')->only(['index']);
        $this->middleware('permission:challan.create')->only(['create','store']);
        $this->middleware('permission:challan.pay')->only(['pay']);
        $this->middleware('permission:challan.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = Challan::with('student');

        // ================= FILTERS =================

        if ($request->filled('challan_no')) {
            $query->where('challan_no', 'like', '%' . $request->challan_no . '%');
        }

        if ($request->filled('roll_no')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('rollnum', 'like', '%' . $request->roll_no . '%');
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('issue_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('issue_date', '<=', $request->to_date);
        }

        // ================= SHOW ALL / PAGINATION =================

        if ($request->has('show_all')) {
            $challans = $query->latest()->get();
            $showAll = true;
        } else {
            $challans = $query->latest()->paginate(15);
            $showAll = false;
        }

        return view('challans.index', compact('challans', 'showAll'));
    }

    public function create()
    {
        
        $sessions = SessionProgram::with('session')->get();
        return view('challans.create', compact('sessions'));
    }
    
    public function getData(Request $request)
    {
        // ================= PROGRAMS =================
        if ($request->type === 'programs') {

            $sessionProgram = SessionProgram::with('programs')
                ->find($request->session_program_id);

            return $sessionProgram?->programs
                ->select('id','name')
                ->values() ?? [];
        }

        // ================= CLASSES =================
        if ($request->type === 'classes') {

            return ClassSubject::where('session_program_id', $request->session_program_id)
                ->whereHas('programs', function ($q) use ($request) {
                    $q->where('program_id', $request->program_id);
                })
                ->select('id','class_name')
                ->orderBy('class_name')
                ->get();
        }

        // ================= STUDENTS =================
        if ($request->type === 'students') {

            return Student::where('class_subject_id', $request->class_id)
                ->with('program','ledgers')
                ->get()
                ->filter(function ($s) {
                    $total = $s->ledgers->where('ledger_category','total_fee')->sum('amount');
                    $paid  = $s->ledgers->where('type','credit')->sum('amount');
                    return $total > $paid;
                })
                ->map(function ($s) {

                    $total = $s->ledgers->where('ledger_category','total_fee')->sum('amount');
                    $paid  = $s->ledgers->where('type','credit')->sum('amount');
                    $remain = $total - $paid;
                    $installment = round($total / max(1,$s->program->divided_fees ?? 1),2);

                    return [
                        'id' => $s->id,
                        'name' => $s->name,
                        'rollnum' => $s->rollnum ?? 'N/A',
                        'total_fee' => $total,
                        'paid' => $paid,
                        'remaining' => $remain,
                        'installment' => min($installment,$remain),
                    ];
                })
                ->values();
        }

        return [];
    }

    public function searchUnpaid(Request $request)
    {
        $query = Challan::with('student')
            ->where('status', 'unpaid');

        if ($request->filled('challan_no')) {
            $query->where('challan_no', $request->challan_no);
        }

        if ($request->filled('roll_no')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('rollnum', $request->roll_no);
            });
        }

        return $query->orderBy('issue_date', 'desc')
            ->get()
            ->map(function ($challan) {
                return [
                    'id' => $challan->id,
                    'challan_no' => $challan->challan_no,
                    'amount' => $challan->amount,
                    'issue_date' => $challan->issue_date,
                    'due_date' => $challan->due_date,
                    'student' => [
                        'name' => $challan->student->name,
                        'rollnum' => $challan->student->rollnum ?? 'N/A',
                    ],
                ];
            });
    }

    public function markPaid($id)
    {
        $challan = Challan::with('student.program')->findOrFail($id);

        if ($challan->status === 'paid') {
            return back()->with('error', 'Challan already paid');
        }

        DB::transaction(function () use ($challan) {

            // ✅ Mark Paid
            $challan->update(['status' => 'paid']);

            // ✅ NOW PAYABLE → CREDIT (ledger)
            AllLedger::create([
                'student_id'      => $challan->student_id,
                'teacher_id'      => $challan->student->program->teacher_id ?? null,
                'amount'          => $challan->amount,
                'type'            => 'credit',
                'ledger_category' => 'fee_challan',
                'title'           => 'Challan Payment',
                'description_fee' => null,
                'challan_no'      => $challan->challan_no,
                'description'     => "Fee received against challan {$challan->challan_no}",
            ]);

        });

        return back()->with('success', 'Challan paid & ledger updated');
    }

    private function generateChallanNo()
    {
        
        $prefix = now()->format('ym');

        
        $last = Challan::where('challan_no', 'like', $prefix . '%')
            ->orderBy('challan_no', 'desc')
            ->first();

        if ($last) {
            $lastNumber = intval(substr($last->challan_no, -3));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        // 2601 + 001 = 2601001
        return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        $includeTuition    = $request->boolean('include_tuition');
        $includeAdditional = $request->boolean('include_additional');

        // ================= BASE VALIDATION =================
        $rules = [
            'session_program_id' => 'required',
            'program_id'         => 'required',
            'class_id'           => 'required',
            'issue_date'         => 'required|date',
            'due_date'           => 'required|date|after_or_equal:issue_date',
        ];

        // ================= TUITION VALIDATION =================
        if ($includeTuition) {
            $rules['students'] = 'required|array';
            $rules['students.*.selected'] = 'required';
            $rules['students.*.payable_amount'] = 'required|numeric|min:0';
        }

        // ================= ADDITIONAL FEES VALIDATION =================
        if ($includeAdditional) {
            $rules['extra_fees'] = 'required|array|min:1';
            $rules['extra_fees.*.title'] = 'required|string';
            $rules['extra_fees.*.description'] = 'nullable|string';
            $rules['extra_fees.*.amount'] = 'required|numeric|min:0';
        }


        $request->validate($rules);

        // ================= FILTER EXTRA FEES =================
        $extraFees = collect($request->extra_fees ?? [])
            ->filter(fn ($f) => !empty($f['title']) && $f['amount'] > 0)
            ->values()
            ->toArray();

        $createdCount = 0;

        DB::transaction(function () use ($request, $includeTuition, $includeAdditional, $extraFees, &$createdCount) {

            // ================= CASE 1: ONLY ADDITIONAL FEES =================
            if (!$includeTuition && $includeAdditional) {

                $students = Student::where('class_subject_id', $request->class_id)->get();


                foreach ($students as $student) {

                    $additionalTotal = array_sum(array_column($extraFees, 'amount'));
                    if ($additionalTotal <= 0) continue;

                    $challan = Challan::create([
                        'challan_no' => $this->generateChallanNo(),
                        'student_id' => $student->id,
                        'issue_date' => $request->issue_date,
                        'due_date'   => $request->due_date,
                        'amount'     => $additionalTotal,
                        'status'     => 'unpaid',
                    ]);

                    foreach ($extraFees as $fee) {
                        AllLedger::create([
                            'student_id'      => $student->id,
                            'amount'          => $fee['amount'],
                            'type'            => 'debit',
                            'ledger_category' => 'additional_fee',
                            'title'           => $fee['title'],
                            'description_fee' => $fee['description'] ?? null,
                            'challan_no'      => $challan->challan_no,
                            'description'     => 'Additional fee charged',
                        ]);


                    }

                    $createdCount++;
                }

                return;
            }

            // ================= CASE 2: TUITION (WITH / WITHOUT ADDITIONAL) =================
            foreach ($request->students ?? [] as $studentId => $data) {

                if ($includeTuition && !isset($data['selected'])) continue;

                $student = Student::find($studentId);
                if (!$student) continue;

                $tuitionAmount   = $includeTuition ? ($data['payable_amount'] ?? 0) : 0;
                $additionalTotal = $includeAdditional ? array_sum(array_column($extraFees, 'amount')) : 0;

                $totalAmount = $tuitionAmount + $additionalTotal;
                if ($totalAmount <= 0) continue;

                $challan = Challan::create([
                    'challan_no' => $this->generateChallanNo(),
                    'student_id' => $student->id,
                    'issue_date' => $request->issue_date,
                    'due_date'   => $request->due_date,
                    'amount'     => $totalAmount,
                    'status'     => 'unpaid',
                ]);

                if ($includeAdditional) {
                    foreach ($extraFees as $fee) {
                        AllLedger::create([
                            'student_id'      => $student->id,
                            'amount'          => $fee['amount'],
                            'type'            => 'debit',
                            'ledger_category' => 'additional_fee',
                            'title'           => $fee['title'],
                            'description_fee' => $fee['description'] ?? null,
                            'challan_no'      => $challan->challan_no,
                            'description'     => 'Additional fee charged',
                        ]);
                    }
                }

                $createdCount++;
            }
        });

        return redirect()->route('challans.index')
            ->with('success', "$createdCount challan(s) generated successfully!");
    }

    public function edit($id)
    {
        $challan = Challan::with('student')->findOrFail($id);
        return view('challans.edit', compact('challan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'issue_date' => 'required|date',
            'due_date'   => 'required|date|after_or_equal:issue_date',
            'amount'     => 'required|numeric|min:0',
        ]);

        $challan = Challan::findOrFail($id);

        if ($challan->status === 'paid') {
            return back()->with('error', 'Cannot edit a paid challan.');
        }

        $challan->update([
            'issue_date' => $request->issue_date,
            'due_date'   => $request->due_date,
            'amount'     => $request->amount,
        ]);

        return redirect()->route('challans.index')
            ->with('success', 'Challan updated successfully!');
    }

    public function pay()
    {
        $sessions = SessionProgram::with('session')->get();
        return view('challans.pay', compact('sessions'));
    }

    public function getUnpaid(Request $request)
    {
        return Challan::with('student')
            ->where('status', 'unpaid')
            ->whereHas('student', function ($q) use ($request) {
                $q->where('session_program_id', $request->session_program_id)
                ->where('program_id', $request->program_id)
                ->where('class_subject_id', $request->class_id);
            })
            ->select('id', 'challan_no', 'student_id', 'amount', 'issue_date', 'due_date')
            ->get();
    }

    public function batchMarkPaid(Request $request)
    {
        $request->validate([
            'challan_ids' => 'required|array|min:1',
            'challan_ids.*' => 'exists:challans,id',
        ]);

        $updatedCount = 0;

        DB::transaction(function () use ($request, &$updatedCount) {
            $challans = Challan::with('student.program')
                ->whereIn('id', $request->challan_ids)
                ->where('status', 'unpaid')
                ->get();

            foreach ($challans as $challan) {
                $challan->update(['status' => 'paid']);

                AllLedger::create([
                    'student_id'      => $challan->student_id,
                    'teacher_id'      => $challan->student->program->teacher_id ?? null,
                    'amount'          => $challan->amount,
                    'type'            => 'credit',
                    'ledger_category' => 'fee_challan',
                    'challan_no'      => $challan->challan_no,
                    'description'     => "Fee received against challan {$challan->challan_no}",
                ]);

                $updatedCount++;
            }
        });

        $message = $updatedCount > 0 
            ? "$updatedCount challan(s) marked as paid successfully!" 
            : "No challans were updated (already paid or invalid)";

        return redirect()->route('challans.index')->with('success', $message);
    }

    public function print($id)
    {
        $challan = Challan::with('student.program', 'student.classSubject')->findOrFail($id);
        return view('challans.print', compact('challan'));
    }

    public function printMultiple(Request $request)
    {
        $request->validate([
            'challan_ids'   => 'required|array|min:1',
            'challan_ids.*' => 'exists:challans,id',
        ]);

        $challans = Challan::with('student.program', 'student.classSubject')
            ->whereIn('id', $request->challan_ids)
            ->get();

        return view('challans.print-multiple', compact('challans'));
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            $challan = Challan::findOrFail($id);

            
            AllLedger::where('challan_no', $challan->challan_no)->delete();

            
            $challan->delete();
        });

        return back()->with('success', 'Challan and related ledger records deleted successfully.');
    }








}
