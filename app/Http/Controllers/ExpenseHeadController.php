<?php

namespace App\Http\Controllers;

use App\Models\ExpenseHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseHeadController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:expense-head.index')->only(['index']);
        $this->middleware('permission:expense-head.create')->only(['create','store']);
        $this->middleware('permission:expense-head.update')->only(['edit','update']);
        $this->middleware('permission:expense-head.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->has('all') || $request->path() === 'expense_heads/all') {
            $expenseHeads = ExpenseHead::orderBy('code')->get();
            return view('expense_heads.index', compact('expenseHeads'))->with('showAll', true);
        }

        $expenseHeads = ExpenseHead::orderBy('code')->paginate(10);
        return view('expense_heads.index', compact('expenseHeads'));
    }

    public function create()
    {
        return view('expense_heads.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:expense_heads,name',
            'code' => 'required|string|max:50|unique:expense_heads,code',
            'description' => 'nullable|string',
        ]);

        ExpenseHead::create($request->all());

        return redirect()->route('expense_heads.index')
            ->with('success', 'Expense Head created successfully.');
    }

    public function show(ExpenseHead $expenseHead)
    {
        return view('expense_heads.show', compact('expenseHead'));
    }

    public function edit(ExpenseHead $expenseHead)
    {
        return view('expense_heads.edit', compact('expenseHead'));
    }

    public function update(Request $request, ExpenseHead $expenseHead)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:expense_heads,name,' . $expenseHead->id,
            'code' => 'required|string|max:50|unique:expense_heads,code,' . $expenseHead->id,
            'description' => 'nullable|string',
        ]);

        $expenseHead->update($request->all());

        return redirect()->route('expense_heads.index')
            ->with('success', 'Expense Head updated successfully.');
    }

    public function destroy(ExpenseHead $expenseHead)
    {
        $expenseHead->delete();

        return redirect()->route('expense_heads.index')
            ->with('success', 'Expense Head deleted successfully.');
    }
}