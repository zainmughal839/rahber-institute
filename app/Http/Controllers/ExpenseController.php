<?php

namespace App\Http\Controllers;

use App\Models\AllLedger;
use App\Models\ExpenseHead;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:expense.index')->only(['index']);
        $this->middleware('permission:expense.create')->only(['create','store']);
        $this->middleware('permission:expense.update')->only(['edit','update']);
        $this->middleware('permission:expense.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = AllLedger::where('ledger_category', 'expense');

        if ($request->filled('voucher_no')) {
            $query->where('voucher_no', 'like', '%' . $request->voucher_no . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('voucher_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('voucher_date', '<=', $request->to_date);
        }

        $query->orderByDesc('voucher_date')->orderByDesc('id');

        if ($request->boolean('show_all')) {
            $expenses = $query->get();
            $showAll = true;
        } else {
            $expenses = $query->paginate(15);
            $showAll = false;
        }

        return view('expenses.index', compact('expenses', 'showAll'));
    }

    public function show($voucherNo)
    {
        $entries = AllLedger::where('voucher_no', $voucherNo)->get();

        abort_if($entries->isEmpty(), 404);

        return view('expenses.slip', [
            'entries' => $entries,
            'voucherNo' => $voucherNo,
        ]);
    }

    public function print($voucherNo)
    {
        $entries = AllLedger::where('voucher_no', $voucherNo)->get();

        abort_if($entries->isEmpty(), 404);

        return view('expenses.slip-print', [
            'entries' => $entries,
            'voucherNo' => $voucherNo,
        ]);
    }

    public function pdf($voucherNo)
    {
        $entries = AllLedger::where('voucher_no', $voucherNo)->get();

        abort_if($entries->isEmpty(), 404);

        $pdf = Pdf::loadView('expenses.slip-pdf', [
            'entries' => $entries,
            'voucherNo' => $voucherNo,
        ])->setPaper('A4');

        return $pdf->download("Expense-Voucher-$voucherNo.pdf");
    }

    public function create()
    {
        $expenseHeads = ExpenseHead::orderBy('code')->get();

        // Next Voucher No
        $last = AllLedger::where('voucher_no', 'like', 'EXP-%')
            ->orderByDesc('id')
            ->first();

        $nextNumber = $last ? (intval(substr($last->voucher_no, 4)) + 1) : 1;
        $nextVoucherNo = 'EXP-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return view('expenses.create', compact('expenseHeads', 'nextVoucherNo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'voucher_date' => 'required|date',
            'entries' => 'required|array|min:1',
            'entries.*.expense_head_id' => 'required|exists:expense_heads,id',
            'entries.*.amount' => 'required|numeric',
            'entries.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Generate Voucher No
        $last = AllLedger::where('voucher_no', 'like', 'EXP-%')->orderByDesc('id')->first();
        $nextNumber = $last ? (intval(substr($last->voucher_no, 4)) + 1) : 1;
        $voucherNo = 'EXP-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        DB::beginTransaction();
        try {
            foreach ($request->entries as $entry) {
                $expenseHead = ExpenseHead::findOrFail($entry['expense_head_id']);

                $imagePath = null;
                if (isset($entry['image']) && $entry['image']) {
                    $imagePath = $entry['image']->store('vouchers', 'public');
                }

                AllLedger::create([
                    'amount' => abs($entry['amount']), // always positive amount
                    'type' => $entry['amount'] < 0 ? 'credit' : 'debit', // negative = refund (credit)
                    'ledger_category' => 'expense',
                    'title' => $expenseHead->name,
                    'description_fee' => $entry['description'] ?? null,
                    'description' => $entry['description'] ?? null,
                    'voucher_no' => $voucherNo,
                    'voucher_date' => $request->voucher_date,
                    'image_path' => $imagePath,
                    // agar expense_head_id column add karna chahte ho to yeh bhi add kar sakte ho
                    // 'expense_head_id' => $expenseHead->id,
                ]);
            }

            DB::commit();
            return redirect()->route('expenses.index')->with('success', 'Expense voucher created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Something went wrong: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $ledger = AllLedger::findOrFail($id);
        if ($ledger->image_path) {
            Storage::disk('public')->delete($ledger->image_path);
        }
        $ledger->delete();

        return back()->with('success', 'Expense entry deleted.');
    }
}