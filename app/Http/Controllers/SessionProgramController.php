<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\ProgramRepositoryInterface;
use App\Repositories\Interfaces\SessionProgramRepositoryInterface;
use App\Repositories\Interfaces\SessionRepositoryInterface;
use Illuminate\Http\Request;

class SessionProgramController extends Controller
{
    protected $repo;
    protected $sessions;
    protected $programs;

    public function __construct(
        SessionProgramRepositoryInterface $repo,
        SessionRepositoryInterface $sessions,
        ProgramRepositoryInterface $programs
    ) {
        $this->repo = $repo;
        $this->sessions = $sessions;
        $this->programs = $programs;
    }

    public function index(Request $request)
    {
        if ($request->query('all') == 'true') {
            $sessionPrograms = $this->repo->getAll();
            $showAll = true;

            return view('session_program.index', compact('sessionPrograms', 'showAll'));
        }

        $sessionPrograms = $this->repo->paginate(10);

        return view('session_program.index', compact('sessionPrograms'));
    }

    public function create()
    {
        return view('session_program.create', [
            'item' => null,
            'sessions' => $this->sessions->all(),
            'programs' => $this->programs->all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:sessions_p,id',
            'program_id' => 'required|exists:programs,id',
            'seats' => 'nullable|integer',
            'fees' => 'nullable|numeric',
        ]);

        $this->repo->create($request->all());

        return redirect()->route('session_program.index')
            ->with('success', 'Record added successfully.');
    }

    public function edit($id)
    {
        $item = $this->repo->find($id);

        return view('session_program.create', [
            'item' => $item,
            'sessions' => $this->sessions->all(),
            'programs' => $this->programs->all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'session_id' => 'required|exists:sessions_p,id',
            'program_id' => 'required|exists:programs,id',
            'seats' => 'nullable|integer',
            'fees' => 'nullable|numeric',
        ]);

        $this->repo->update($id, $request->all());

        return redirect()->route('session_program.index')
            ->with('success', 'Updated successfully.');
    }

    public function destroy($id)
    {
        $this->repo->delete($id);

        return redirect()->route('session_program.index')
            ->with('success', 'Deleted successfully.');
    }
}
