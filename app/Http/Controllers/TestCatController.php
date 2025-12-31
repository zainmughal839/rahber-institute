<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\TestCatRepositoryInterface;
use Illuminate\Http\Request;

class TestCatController extends Controller
{
    protected $repo;

    public function __construct(TestCatRepositoryInterface $repo)
    {
        $this->repo = $repo;

        $this->middleware('permission:test-cat.index')->only(['index']);
        $this->middleware('permission:test-cat.create')->only(['create','store']);
        $this->middleware('permission:test-cat.update')->only(['edit','update']);
        $this->middleware('permission:test-cat.delete')->only(['destroy']);
    }

    public function index()
    {
        $records = $this->repo->all();
        return view('test_cat.index', compact('records'));
    }

    public function create()
    {
        return view('test_cat.create');
    }

    public function store(Request $req)
    {
        $req->validate([
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string'
        ]);

        $this->repo->store($req->only('name','desc'));

        return redirect()->route('test-cat.create')
            ->with('success', 'Test Category Created Successfully');
    }

    public function edit($id)
    {
        $record = $this->repo->find($id);
        return view('test_cat.create', compact('record'));
    }

    public function update(Request $req, $id)
    {
        $req->validate([
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string'
        ]);

        $this->repo->update($id, $req->only('name','desc'));

        return redirect()->route('test-cat.index')
            ->with('success', 'Test Category Updated Successfully');
    }

    public function destroy($id)
    {
        $this->repo->delete($id);

        return redirect()->route('test-cat.index')
            ->with('success', 'Deleted Successfully');
    }
}
