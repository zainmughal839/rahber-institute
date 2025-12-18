<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\ProgramRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Subject;
use Illuminate\Validation\Rule;

class ProgramController extends Controller
{
    protected $programRepo;

    public function __construct(ProgramRepositoryInterface $programRepo)
    {
        $this->programRepo = $programRepo;
    }

    public function index()
    {
        $programs = $this->programRepo->paginate(10);
        return view('programs.index', compact('programs'));
    }

    public function all()
    {
        $programs = $this->programRepo->all();
        $showAll = true;
        return view('programs.index', compact('programs', 'showAll'));
    }

    public function create()
    {
        return view('programs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:programs,name',
            'shortname' => 'nullable|string|max:50',
            'program_code' => 'nullable|string|max:50|unique:programs,program_code',
            'description' => 'nullable|string',
        ]);

        $this->programRepo->create($request->only([
            'name',
            'shortname',
            'program_code',
            'description',
        ]));

        return back()->with('success', 'Program created successfully.');
    }

    public function edit($id)
    {
        $program = $this->programRepo->find($id);
        return view('programs.create', compact('program'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique('programs')->ignore($id)],
            'shortname' => 'nullable|string|max:50',
            'program_code' => ['nullable', 'string', Rule::unique('programs')->ignore($id)],
            'description' => 'nullable|string',
        ]);

        $this->programRepo->update($id, $request->only([
            'name',
            'shortname',
            'program_code',
            'description',
        ]));

        return back()->with('success', 'Program updated successfully.');
    }

    public function destroy($id)
    {
        $this->programRepo->delete($id);

        return redirect()->route('programs.index')
            ->with('success', 'Program deleted successfully.');
    }
}
