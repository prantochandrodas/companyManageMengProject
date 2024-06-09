<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\OfficeExpense;
use Carbon\Carbon;
use Illuminate\Http\Request;

use function Laravel\Prompts\alert;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

        // dd($fromDate);
        // dd($toDate);
        if ($fromDate !== null || $toDate !== null) {
            // Fetch ledger entries
            $ledgerEntries = $this->fetchLedgerEntries($fromDate, $toDate);
            return view('ledger.index', compact('ledgerEntries', 'fromDate', 'toDate'));
        } else {
            $ledgerEntries = null;
            return view('Ledger.index', compact('ledgerEntries'));
        }
    }

    public function view(Request $request)
    {
        $date = $request->query('date');
        $type = $request->query('type');
        $debit = $request->query('debit');
        $credit = $request->query('credit');
        $balance = $request->query('balance');
        $description = htmlspecialchars_decode($request->query('description'), ENT_QUOTES);
        // Strip HTML tags from the description
        $description = strip_tags($description);

        $response = [
            'date' => $date,
            'type' => $type,
            'description' => $description,
            'debit' => $debit,
            'credit' => $credit,
            'balance' => $balance,
        ];

        return response()->json($response);
    }

    public function pdf(Request $request)
    {
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        // dd($request->all());
        if ($fromDate !== null || $toDate !== null) {
            // Fetch ledger entries
            $ledgerEntries = $this->fetchLedgerEntries($fromDate, $toDate);
            // Strip HTML tags from descriptions
            foreach ($ledgerEntries as $entry) {
                $entry->description = strip_tags($entry->description);
            }
            // dd($ledgerEntries);
            $pdf =  \PDF::loadView('Ledger.pdf', compact('ledgerEntries'));
            return $pdf->download('Ledger_' . '.pdf');
        } else {
            $ledgerEntries = [];
            $pdf =  \PDF::loadView('Ledger.pdf', $ledgerEntries);
            return $pdf->download('Ledger_' . '.pdf');
        }
    }

    public function print(Request $request)
    {
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

        if ($fromDate !== null || $toDate !== null) {
            // Fetch ledger entries
            $ledgerEntries = $this->fetchLedgerEntries($fromDate, $toDate);
            return view('Ledger.print', compact('ledgerEntries'));
        } else {
            $ledgerEntries = null;
            return view('Ledger.print', compact('ledgerEntries'));
        }
    }


    public function export(Request $request)
    {
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('todate');
        if ($fromDate !== null || $toDate !== null) {
            // Fetch ledger entries
            $ledgerEntries = $this->fetchLedgerEntries($fromDate, $toDate);
            // Strip HTML tags from descriptions
            foreach ($ledgerEntries as $entry) {
                $entry->description = strip_tags($entry->description);
            }
            // Generate Excel using Laravel Excel package
            return \Excel::download(new \App\Exports\LedgerExport($ledgerEntries), 'ledger.xlsx');
        } else {
            $ledgerEntries = null;
            return \Excel::download(new \App\Exports\LedgerExport($ledgerEntries), 'ledger.xlsx');
        }
    }

    private function fetchLedgerEntries($fromDate, $toDate)
    {
        
        $incomes = Income::select('id', 'created_at as date', 'amount', 'description')
            ->when($fromDate, function ($query) use ($fromDate) {
                return $query->whereDate('created_at', '>=', $fromDate);
            })
            ->when($toDate, function ($query) use ($toDate) {
                return $query->whereDate('created_at', '<=', $toDate);
            })
            ->get()
            ->map(function ($income) {
                $income->type = 'Income';
                $income->debit = $income->amount;
                $income->credit = 0;
                return $income;
            });



        $expenses = OfficeExpense::select('id', 'created_at as date', 'amount', 'description')
            ->when($fromDate, function ($query) use ($fromDate) {
                return $query->whereDate('created_at', '>=', $fromDate);
            })
            ->when($toDate, function ($query) use ($toDate) {
                return $query->whereDate('created_at', '<=', $toDate);
            })
            ->get()
            ->map(function ($expense) {
                $expense->type = 'Expense';
                $expense->debit = 0;
                $expense->credit = $expense->amount;
                return $expense;
            });



        $ledgerEntries = $incomes->merge($expenses)->sortBy('date');

        $balance = 0;
        if ($fromDate) {
            $initialIncome = Income::whereDate('created_at', '<', $fromDate)->sum('amount');
            $initialExpense = OfficeExpense::whereDate('created_at', '<', $fromDate)->sum('amount');
            $balance = $initialIncome - $initialExpense;
        }

        foreach ($ledgerEntries as $entry) {
            $balance += $entry->debit - $entry->credit;
            $entry->balance = $balance;
        }

        $openingBalance = $ledgerEntries[0]->balance;
        $entry->openingBalance = $openingBalance;

        return $ledgerEntries;
    }
}
