<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\SubjectRepositoryInterface;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    protected $subjects;

    public function __construct(SubjectRepositoryInterface $subjects)
    {
        $this->subjects = $subjects;

        // Permissions
        $this->middleware('permission:subject.index')->only('index');
        $this->middleware('permission:subject.create')->only(['create', 'store']);
        $this->middleware('permission:subject.update')->only(['edit', 'update']);
        $this->middleware('permission:subject.delete')->only('destroy');
    }

    public function index()
    {
        $subjects = $this->subjects->all();

        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('subjects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_name' => 'required|string|max:255',
        ]);

        $this->subjects->store($request->only('book_name', 'book_short_name'));

        return redirect()->route('subjects.index')
            ->with('success', 'Subject Added Successfully!');
    }

    public function edit($id)
    {
        $subject = $this->subjects->find($id);

        return view('subjects.create', compact('subject'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'book_name' => 'required|string|max:255',
        ]);

        $this->subjects->update($id, $request->only('book_name', 'book_short_name'));

        return redirect()->route('subjects.index')
            ->with('success', 'Subject Updated Successfully!');
    }

    public function destroy($id)
    {
        $this->subjects->delete($id);

        return redirect()->route('subjects.index')
            ->with('success', 'Subject Deleted Successfully!');
    }
}