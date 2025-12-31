<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\McqCategoryRepositoryInterface;
use Illuminate\Http\Request;

class McqCategoryController extends Controller
{
    public function __construct(protected McqCategoryRepositoryInterface $repo) {
         $this->middleware('permission:mcq-category.index')->only(['index']);
        $this->middleware('permission:mcq-category.create')->only(['create', 'store']);
        $this->middleware('permission:mcq-category.update')->only(['edit', 'update']);
        $this->middleware('permission:mcq-category.delete')->only('destroy');
    }

 

    public function index()
    {
        $categories = $this->repo->all(); 
        return view('mcq.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('mcq.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean'
        ]);

        $this->repo->create($data); 
        return redirect()
            ->route('mcq.categories.index')
            ->with('success','Category created');
    }

    public function edit($id)
    {
        $category = $this->repo->find($id); // ✅
        return view('mcq.categories.create', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean'
        ]);

        $this->repo->update($id, $data); // ✅
        return redirect()
            ->route('mcq.categories.index')
            ->with('success','Updated');
    }

    public function destroy($id)
    {
        $this->repo->delete($id); // ✅
        return back()->with('success','Deleted');
    }
}
