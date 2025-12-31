<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\McqCategory;
use App\Models\McqBank;

class McqBankController extends Controller
{
     public function __construct() {
         $this->middleware('permission:mcq.banks.index')->only(['index']);
        $this->middleware('permission:mcq.banks.create')->only(['create', 'store']);
        $this->middleware('permission:mcq.banks.update')->only(['edit', 'update']);
        $this->middleware('permission:mcq.banks.delete')->only('destroy');
    }


    // public function index()
    // {
    //     $banks = McqBank::with('category')
    //         ->where('teacher_id', auth()->id())
    //         ->latest()
    //         ->get();

    //     return view('mcq.banks.index', compact('banks'));
    // }


    
public function index()
{
    // ================= TEACHER =================
    if (auth()->user()->hasRole('teacher')) {

        $banks = McqBank::with('category')
            ->where('teacher_id', auth()->id()) 
            ->latest()
            ->get();
    }
    // ================= ADMIN =================
    else {

        $banks = McqBank::with('category')
            ->latest()
            ->get(); // ✅ all records
    }

    return view('mcq.banks.index', compact('banks'));
}




    public function create()
    {
        $categories = McqCategory::where('status',1)->get();
        return view('mcq.banks.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required',
            'mcq_category_id'=>'required|exists:mcq_categories,id',
            'description'=>'nullable',
            'status'=>'required|boolean'
        ]);

        $data['teacher_id'] = auth()->id();

        McqBank::create($data);

        return redirect()
            ->route('mcq.banks.index')
            ->with('success','MCQ Bank created');
    }

    public function edit(McqBank $bank)
    {
        $categories = McqCategory::where('status',1)->get();
        return view('mcq.banks.create', compact('bank','categories'));
    }

    public function update(Request $request, McqBank $bank)
    {
        $data = $request->validate([
            'name'=>'required',
            'mcq_category_id'=>'required|exists:mcq_categories,id',
            'description'=>'nullable',
            'status'=>'required|boolean'
        ]);

        $bank->update($data);

        return redirect()
            ->route('mcq.banks.index')
            ->with('success','MCQ Bank updated');
    }

    public function destroy(McqBank $bank)
    {
        $bank->delete();

        return redirect()
            ->route('mcq.banks.index')
            ->with('success','MCQ Bank deleted');
    }
}
