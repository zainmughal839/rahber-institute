<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\TaskCatRepositoryInterface;
use Illuminate\Http\Request;

class TaskCatController extends Controller
{
    protected $repo;

    public function __construct(TaskCatRepositoryInterface $repo)
    {
        $this->repo = $repo;

        $this->middleware('permission:task-cat.index')->only(['index']);
        $this->middleware('permission:task-cat.create')->only(['create','store']);
        $this->middleware('permission:task-cat.update')->only(['edit','update']);
        $this->middleware('permission:task-cat.delete')->only(['destroy']);
    }

    

    public function index()
    {
        $records = $this->repo->all();
        return view('task_cat.index', compact('records'));
    }

    public function create()
    {
        return view('task_cat.create');
    }

    public function store(Request $req)
    {
        $req->validate([
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'd_married_points' => 'required|integer|min:0'
        ]);

        $this->repo->store(
            $req->only('name', 'desc', 'd_married_points')
        );


        return redirect()->route('task-cat.create')->with('success', 'Category Created Successfully');
    }

    public function edit($id)
    {
        $record = $this->repo->find($id);
        return view('task_cat.create', compact('record'));
    }

    public function update(Request $req, $id)
    {
        $req->validate([
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'd_married_points' => 'required|integer|min:0'
        ]);

        $this->repo->update(
            $id,
            $req->only('name', 'desc', 'd_married_points')
        );


        return redirect()->route('task-cat.index')->with('success', 'Category Updated Successfully');
    }

    public function destroy($id)
    {
        $this->repo->delete($id);
        return redirect()->route('task-cat.index')->with('success', 'Deleted Successfully');
    }
}
