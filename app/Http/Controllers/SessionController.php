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
        'sessions_name' => 'required|string|max:255',
        'start_date' => 'required|string|max:10',
        'end_date' => 'required|string|max:10',
        'description' => 'nullable|string',
    ]);

    $this->sessionRepo->create($request->all());

    return redirect()->back()->with('success', 'Session created successfully.');
}




public function update(Request $request, $id)
{
    $request->validate([
        'sessions_name' => 'required|string|max:255',
        'start_date' => 'required|string|max:10',
        'end_date' => 'required|string|max:10',
        'description' => 'nullable|string',
    ]);

    $this->sessionRepo->update($id, $request->all());

    return redirect()->back()->with('success', 'Updated session successfully.');
}





    public function edit($id)
    {
        $session = $this->sessionRepo->find($id);

        return view('sessions.create', compact('session'));
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