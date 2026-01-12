<?php

namespace App\Http\Controllers;

use App\Models\AllLedger;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ProfitLossController extends Controller
{
   

    public function __construct()
    {
        $this->middleware('permission:profit-loss.index')->only(['index']);
    }

    public function index(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate   = $request->to_date;
        $showAll  = $request->get('show_all', false); // check if show_all is set

        $baseQuery = AllLedger::forProfitLoss($fromDate, $toDate)
            ->orderByDesc('voucher_date')
            ->orderByDesc('id');

        $income = (clone $baseQuery)->where('type', 'credit')->sum('amount');

        $expenses = (clone $baseQuery)->where('type', 'debit')->sum('amount');

        $profitLoss = $income - $expenses;

        if ($showAll) {
            $entries = $baseQuery->get();
        } else {
            $entries = $baseQuery->paginate(15)->withQueryString();
        }

        return view('profit_loss.index', compact(
            'income',
            'expenses',
            'profitLoss',
            'entries',
            'fromDate',
            'toDate',
            'showAll'
        ));
    }

    public function print(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $query = AllLedger::forProfitLoss($fromDate, $toDate);

        $income = $query->clone()->where('type', 'credit')->sum('amount');
        $expenses = $query->clone()->where('type', 'debit')->sum('amount');
        $profitLoss = $income - $expenses;

        $entries = $query->orderByDesc('voucher_date')->orderByDesc('id')->get();

        return view('profit_loss.print', compact('income', 'expenses', 'profitLoss', 'entries', 'fromDate', 'toDate'));
    }

    public function pdf(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $query = AllLedger::forProfitLoss($fromDate, $toDate);

        $income = $query->clone()->where('type', 'credit')->sum('amount');
        $expenses = $query->clone()->where('type', 'debit')->sum('amount');
        $profitLoss = $income - $expenses;

        $entries = $query->orderByDesc('voucher_date')->orderByDesc('id')->get();

        $pdf = Pdf::loadView('profit_loss.pdf', compact('income', 'expenses', 'profitLoss', 'entries', 'fromDate', 'toDate'));

        $fileName = 'Profit_Loss_Report_' . ($fromDate ?? 'start') . '_to_' . ($toDate ?? 'end') . '.pdf';

        return $pdf->download($fileName);
    }
}
