<?php

namespace App\Http\Controllers;

use App\Models\AllLedger;
use App\Models\SessionProgram;
use App\Models\Student;
use App\Repositories\Interfaces\SessionRepositoryInterface;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    protected $students;
    protected $sessions;

    public function __construct(
        StudentRepositoryInterface $students,
        SessionRepositoryInterface $sessions
    ) {
        $this->students = $students;
        $this->sessions = $sessions;

        // permission middleware (optional, add as needed)
        $this->middleware('permission:student.index')->only('index');
        $this->middleware('permission:student.create')->only(['create', 'store']);
        $this->middleware('permission:student.update')->only(['edit', 'update', 'showAssignForm', 'assignSessionProgram']);
        $this->middleware('permission:student.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        if ($request->has('all')) {
            $data = $this->students->allWithoutPagination();
            $showAll = true;

            return view('students.index', compact('data', 'showAll'));
        }

        $data = $this->students->paginate(10);

        return view('students.index', compact('data'));
    }

    /**
     * Step 1: show form to create student (personal details only).
     */
    public function create()
    {
        // compute nextRoll for preview (optional)
        $yearMonth = now()->format('ym'); // e.g. '2511'
        $lastStudent = Student::where('rollnum', 'LIKE', $yearMonth.'%')
            ->orderBy('rollnum', 'DESC')
            ->first();

        $lastNumber = $lastStudent ? intval(substr($lastStudent->rollnum, 4)) : 0;
        $nextRoll = $yearMonth.str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        $categories = \App\Models\StuCategory::all();

        return view('students.create_step1', compact('nextRoll', 'categories'));
    }

    /**
     * Store Step1 — create student personal record WITHOUT session_program_id, WITHOUT rollnum.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'cnic' => 'nullable|string|max:30',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'stu_category_id' => 'nullable|exists:stu_category,id',
        ]);

        // create student
        $student = $this->students->create($validated);

        // check which button was clicked
        if ($request->action == 'save_only') {
            return redirect()
                ->route('students.edit', $student->id)
                ->with('success', 'Student saved successfully.');
        }

        // Save & next step
        return redirect()
            ->route('students.assign.form', $student->id)
            ->with('success', 'Student saved. Proceed to Step 2.');
    }

    /**
     * Step 2 form: choose session_program and collect fees (shows current student).
     */
    public function showAssignForm($id)
    {
        $student = $this->students->find($id);
        $sessionPrograms = SessionProgram::with(['session', 'program'])->get();

        // get existing debit (total fees)
        $existingTotal = AllLedger::where('student_id', $student->id)
            ->where('type', 'debit')
            ->first();

        // get existing credit (advance fees)
        $existingAdvance = AllLedger::where('student_id', $student->id)
            ->where('type', 'credit')
            ->first();

        return view('students.assign_step2', compact('student', 'sessionPrograms', 'existingTotal', 'existingAdvance'));
    }

    /**
     * Assign session program and record fees (create ledger entry).
     * If fees_amount > 0 and student has no rollnum -> generate rollnum and save.
     */
    public function assignSessionProgram(Request $request, $id)
    {
        $student = $this->students->find($id);

        $validated = $request->validate([
            'session_program_id' => 'required|exists:session_program,id',
            'total_fees' => 'required|numeric|min:0',
            'advance_fees' => 'nullable|numeric|min:0',
        ]);

        $newSpId = $validated['session_program_id'];
        $total = floatval($validated['total_fees']);
        $advance = floatval($validated['advance_fees']);

        DB::transaction(function () use ($student, $newSpId, $total, $advance) {
            $oldSpId = $student->session_program_id;

            // Update student session_program
            $student->update(['session_program_id' => $newSpId]);

            // --- Total Fees (DEBIT) ---
            $totalRow = AllLedger::where('student_id', $student->id)
                ->where('ledger_category', 'total_fee')
                ->first();

            if ($totalRow) {
                $totalRow->update([
                    'amount' => $total,
                    'session_program_id' => $newSpId,
                    'type' => 'debit',
                    'ledger_category' => 'total_fee',
                ]);
            } else {
                AllLedger::create([
                    'student_id' => $student->id,
                    'amount' => $total,
                    'type' => 'debit',
                    'description' => 'Total Program Fees',
                    'session_program_id' => $newSpId,
                    'ledger_category' => 'total_fee',
                ]);
            }

            // --- Advance Fees (CREDIT) ---
            $advanceRow = AllLedger::where('student_id', $student->id)
                ->where('ledger_category', 'advance')
                ->first();

            if ($advanceRow) {
                $advanceRow->update([
                    'amount' => $advance,
                    'session_program_id' => $newSpId,
                    'type' => 'credit',
                    'ledger_category' => 'advance',
                ]);
            } elseif ($advance > 0) {
                AllLedger::create([
                    'student_id' => $student->id,
                    'amount' => $advance,
                    'type' => 'credit',
                    'description' => 'Advance Fee Payment',
                    'session_program_id' => $newSpId,
                    'ledger_category' => 'advance',
                ]);
            }

            // --- Generate Roll Number if not assigned ---
            if ($advance > 0 && empty($student->rollnum)) {
                $yearMonth = now()->format('ym');
                $lastStudent = Student::where('rollnum', 'LIKE', $yearMonth.'%')
                    ->orderBy('rollnum', 'DESC')
                    ->lockForUpdate()
                    ->first();

                $lastNumber = $lastStudent ? intval(substr($lastStudent->rollnum, 4)) : 0;
                $newRoll = $yearMonth.str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

                $student->update(['rollnum' => $newRoll]);
            }
        });

        return redirect()->route('students.index')->with('success', 'Session & fees updated successfully.');
    }

    /**
     * Edit displays step1 form for editing personal details OR you can separate
     * depending whether you want to edit session separately.
     */
    public function edit($id)
    {
        $student = $this->students->find($id);

        $categories = \App\Models\StuCategory::all();

        return view('students.create_step1', compact('student', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'cnic' => 'nullable|string|max:30',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'stu_category_id' => 'nullable|exists:stu_category,id',
        ]);

        $this->students->update($id, $validated);

        return redirect()
            ->route('students.assign.form', $id)
            ->with('success', 'Student updated. Continue to Step 2 (Session & Fees)');
    }

    public function destroy($id)
    {
        $student = $this->students->find($id);

        // delete all ledger entries
        $student->ledgers()->delete();

        // delete student
        $this->students->delete($id);

        return redirect()->back()->with('success', 'Student & all related ledger entries deleted!');
    }

    // Existing helper methods retained for AJAX usage
    public function getPrograms($sessionId)
    {
        $programs = DB::table('session_program')
            ->where('session_id', $sessionId)
            ->join('programs', 'programs.id', '=', 'session_program.program_id')
            ->select('programs.id', 'programs.name', 'programs.shortname', 'session_program.fees', 'session_program.seats')
            ->distinct()
            ->get();

        return response()->json($programs);
    }

    public function getSessionProgramInfo($id)
    {
        $sp = SessionProgram::findOrFail($id);
        $registered = Student::where('session_program_id', $id)->count();

        return response()->json([
            'fees' => $sp->fees,
            'seats' => $sp->seats,
            'available_seats' => $sp->seats - $registered,
        ]);
    }

    public function ledger(Student $student)
    {
        $student->load('sessionProgram.session', 'sessionProgram.program', 'ledgers');

        return view('students.student_ledger', compact('student'));
    }

    public function allAllLedger()
    {
        $data = Student::with(['sessionProgram.session', 'sessionProgram.program', 'ledgers'])->paginate(10);

        return view('students.all_student_ledger', compact('data'));
    }
}