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
            'itemPrograms' => [],
            'sessions' => $this->sessions->all(),
            'programs' => $this->programs->all(),
        ]);
    }


    
public function store(Request $request)
{
    $request->validate([
        'session_id' => 'required|exists:sessions_p,id',
        'programs' => 'required|array',
        'programs.*.program_id' => 'required|exists:programs,id',
        'programs.*.seats' => 'nullable|integer|min:1',
        'programs.*.fees' => 'nullable|numeric|min:0',
    ]);

    // Create parent session_program
    $sessionProgram = $this->repo->create([
        'session_id' => $request->session_id,
    ]);

    // Attach programs with seats & fees
    foreach ($request->programs as $p) {
        $sessionProgram->programs()->attach($p['program_id'], [
            'seats' => $p['seats'],
            'fees'  => $p['fees'],
        ]);
    }

    return redirect()->route('session_program.index')
        ->with('success', 'Programs assigned successfully.');
}





public function edit($id)
{
    $item = $this->repo->find($id);

    $itemPrograms = $item->programs->map(function ($p) {
        return [
            'program_id' => $p->id,
            'seats'      => $p->pivot->seats,
            'fees'       => $p->pivot->fees,
        ];
    })->toArray();

    return view('session_program.create', [
        'item'         => $item,
        'itemPrograms' => $itemPrograms,
        'sessions'     => $this->sessions->all(),
        'programs'     => $this->programs->all(),
    ]);
}



    

    public function update(Request $request, $id)
{
    $request->validate([
        'session_id' => 'required|exists:sessions_p,id',
        'programs' => 'required|array',
        'programs.*.program_id' => 'required|exists:programs,id',
        'programs.*.seats' => 'nullable|integer|min:1',
        'programs.*.fees' => 'nullable|numeric|min:0',
    ]);

    // Update session
    $this->repo->update($id, [
        'session_id' => $request->session_id,
    ]);

    $sessionProgram = $this->repo->find($id);

    // Remove old
    $sessionProgram->programs()->detach();

    // Re-attach
    foreach ($request->programs as $p) {
        $sessionProgram->programs()->attach($p['program_id'], [
            'seats' => $p['seats'],
            'fees'  => $p['fees'],
        ]);
    }

    return redirect()->route('session_program.index')
        ->with('success', 'Updated successfully.');
}




    public function destroy($id)
    {
        $item = $this->repo->find($id);

        if ($item) {
            $item->programs()->detach();
            $item->delete();
        }

        return redirect()->route('session_program.index')
            ->with('success', 'Deleted successfully.');
    }
}
