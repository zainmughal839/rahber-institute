<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\StuCategoryRepositoryInterface;
use Illuminate\Http\Request;

class StuCategoryController extends Controller
{
    protected $category;

    public function __construct(StuCategoryRepositoryInterface $category)
    {
        $this->category = $category;

        $this->middleware('permission:stu_category.index')->only(['index']);
        $this->middleware('permission:stu_category.create')->only(['create', 'store']);
        $this->middleware('permission:stu_category.update')->only(['edit', 'update']);
        $this->middleware('permission:stu_category.delete')->only(['destroy']);
    }

    public function index()
    {
        $categories = $this->category->all();

        return view('stu_category.index', compact('categories'));
    }

    public function create()
    {
        return view('stu_category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $this->category->store($request->only('name', 'desc'));

        return redirect()->route('stu-category.create')
            ->with('success', 'Category Added Successfully!');
    }

    public function edit($id)
    {
        $category = $this->category->find($id);

        return view('stu_category.create', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $this->category->update($id, $request->only('name', 'desc'));

        return redirect()->route('stu-category.index')
            ->with('success', 'Category Updated Successfully!');
    }

    public function destroy($id)
    {
        $this->category->delete($id);

        return redirect()->route('stu-category.index')
            ->with('success', 'Category Deleted Successfully!');
    }
}