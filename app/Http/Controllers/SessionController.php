<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\SessionRepositoryInterface;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    protected $sessionRepo;

    public function __construct(SessionRepositoryInterface $sessionRepo)
    {
        $this->sessionRepo = $sessionRepo;
    }

    public function index()
    {
        $sessions = $this->sessionRepo->all();

        return view('sessions.index', compact('sessions'));
    }

    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        $this->sessionRepo->create($request->all());

        return redirect()->route('sessions.index')->with('success', 'Session created successfully.');
    }

    public function edit($id)
    {
        $session = $this->sessionRepo->find($id);

        return view('sessions.create', compact('session'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        $this->sessionRepo->update($id, $request->all());

        return redirect()->route('sessions.index')->with('success', 'Session updated successfully.');
    }

    public function destroy($id)
    {
        $this->sessionRepo->delete($id);

        return redirect()->route('sessions.index')->with('success', 'Session deleted successfully.');
    }

    public function all()
    {
        $sessions = $this->sessionRepo->allRecords(); // ALL records (no pagination)

        return view('sessions.index', compact('sessions'))
               ->with('showAll', true);
    }
}
