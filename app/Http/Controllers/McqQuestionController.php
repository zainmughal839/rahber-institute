<?php

namespace App\Http\Controllers;

use App\Models\McqBank;
use App\Models\McqQuestion;

use Illuminate\Http\Request;

class McqQuestionController extends Controller
{
    public function index(McqBank $bank)
    {
        $questions = $bank->questions()->latest()->get();
        return view('mcq.questions.index', compact('bank','questions'));
    }

    public function create(McqBank $bank)
    {
        return view('mcq.questions.create', [
            'bank' => $bank,
            'editQuestion' => null
        ]);
    }

    public function store(Request $request, McqBank $bank)
    {
        $this->validateRequest($request);

        foreach ($request->questions as $q) {
            $bank->questions()->create($q);
        }

        return redirect()
            ->route('mcq.banks.questions.index',$bank->id)
            ->with('success','MCQs added successfully');
    }

    public function edit(McqBank $bank, McqQuestion $question)
    {
        return view('mcq.questions.create', [
            'bank' => $bank,
            'editQuestion' => $question
        ]);
    }

    public function update(Request $request, McqBank $bank, McqQuestion $question)
    {
        $this->validateRequest($request);

        $question->update($request->questions[0]);

        return redirect()
            ->route('mcq.banks.questions.index',$bank->id)
            ->with('success','MCQ updated successfully');
    }

    public function destroy(McqBank $bank, McqQuestion $question)
    {
        $question->delete();

        return response()->json(['status' => true]);
    }

    private function validateRequest(Request $request)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*.question' => 'required',
            'questions.*.option_a' => 'required',
            'questions.*.option_b' => 'required',
            'questions.*.option_c' => 'required',
            'questions.*.option_d' => 'required',
            'questions.*.correct_option' => 'required|in:a,b,c,d',
        ]);
    }
}
