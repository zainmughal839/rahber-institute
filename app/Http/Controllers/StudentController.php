<?php

namespace App\Http\Controllers;

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
    }

    public function index(Request $request)
    {
        // If ?all=1 → show all records
        if ($request->has('all')) {
            $data = $this->students->allWithoutPagination();
            $showAll = true;

            return view('students.index', compact('data', 'showAll'));
        }

        // Otherwise paginate 10 per page
        $data = $this->students->paginate(10);

        return view('students.index', compact('data'));
    }

    public function create()
    {
        $sessionPrograms = SessionProgram::with(['session', 'program'])->get();

        // compute next rollnum for display on form (YYMM + 3-digit counter)
        $yearMonth = now()->format('ym'); // e.g. '2511'
        $lastStudent = Student::where('rollnum', 'LIKE', $yearMonth.'%')
            ->orderBy('rollnum', 'DESC')
            ->first();

        $lastNumber = $lastStudent ? intval(substr($lastStudent->rollnum, 4)) : 0;
        $nextRoll = $yearMonth.str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return view('students.create', compact('sessionPrograms', 'nextRoll'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'father_name' => 'required',
            'cnic' => 'nullable',
            'phone' => 'nullable',
            'fees' => 'required|numeric',
            'rollnum' => 'required|string', // keep existing rollnum
            'session_program_id' => 'required|exists:session_program,id',
        ]);

        $this->students->create($validated);

        return redirect()->back()->with('success', 'Student added successfully.');
    }

    public function edit($id)
    {
        $student = $this->students->find($id);
        $sessionPrograms = SessionProgram::with(['session', 'program'])->get();

        return view('students.create', compact('student', 'sessionPrograms'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'cnic' => 'nullable|string|max:30',
            'phone' => 'nullable|string|max:30',
            'fees' => 'required|numeric',
            'session_program_id' => 'required|exists:session_program,id',
            'rollnum' => 'required|string', // keep existing rollnum
        ]);

        $this->students->update($id, $validated);

        return redirect()->back()->with('success', 'Student updated successfully!');
    }

    public function destroy($id)
    {
        $this->students->delete($id);

        return redirect()->back()->with('success', 'Student deleted!');
    }

    /**
     * Return programs for a given session (used by the AJAX dropdown)
     * URL: GET /get-programs/{session}.
     */
    public function getPrograms($sessionId)
    {
        // Join session_program -> programs and return unique programs for that session
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
}
