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

        // check permission ***********
        
        if (auth()->check()) {
            if (auth()->user()->can('ledger')) {
                $fromDate = $request->input('fromDate');
                $toDate = $request->input('toDate');
                if ($fromDate !== null || $toDate !== null) {
                    // Fetch ledger entries
                    $ledgerData = $this->fetchLedgerEntries($fromDate, $toDate);
                    $ledgerEntries = $ledgerData['ledgerEntries'];
                    $setOpeningAmount = $ledgerData['setOpeningAmount'];
                    return view('ledger.index', compact('ledgerEntries', 'setOpeningAmount', 'fromDate', 'toDate'));
                } else {
                    $ledgerEntries = null;
                    return view('Ledger.index', compact('ledgerEntries'));
                }
            } else {
                return redirect('/')->with('error', 'You do not have permission to view Ledger.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
    }

    // public function view(Request $request)
    // {


    //     $date = $request->query('date');
    //     $type = $request->query('type');
    //     $debit = $request->query('debit');
    //     $credit = $request->query('credit');
    //     $balance = $request->query('balance');
    //     $description = htmlspecialchars_decode($request->query('description'), ENT_QUOTES);
    //     // Strip HTML tags from the description
    //     $description = strip_tags($description);

    //     $response = [
    //         'date' => $date,
    //         'type' => $type,
    //         'description' => $description,
    //         'debit' => $debit,
    //         'credit' => $credit,
    //         'balance' => $balance,
    //     ];

    //     if (auth()->check()) {
    //         if (auth()->user()->can('ledger-view')) {
    //             return response()->json($response);
    //         } else {
    //             return redirect()->back()->with('error', 'You do not have permission to view.');
    //         }
    //     } else {
    //         return redirect()->route('login')->with('error', 'You need to login first.');
    //     }
    // }

    public function pdf(Request $request)
    {
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        // dd($request->all());
        if ($fromDate !== null || $toDate !== null) {
            // Fetch ledger entries
            $ledgerData = $this->fetchLedgerEntries($fromDate, $toDate);
            $ledgerEntries = $ledgerData['ledgerEntries'];
            $setOpeningAmount = $ledgerData['setOpeningAmount'];
            // Strip HTML tags from descriptions
            foreach ($ledgerEntries as $entry) {
                $entry->description = strip_tags($entry->description);
            }
            // dd($ledgerEntries);

            if (auth()->check()) {
                if (auth()->user()->can('ledger-pdf')) {
                    $pdf =  \PDF::loadView('Ledger.pdf', compact('ledgerEntries', 'setOpeningAmount'));
                    return $pdf->download('Ledger_' . '.pdf');
                } else {
                    return redirect()->back()->with('error', 'You do not have permission to pdf download.');
                }
            } else {
                return redirect()->route('login')->with('error', 'You need to login first.');
            }
        } else {
            $ledgerEntries = [];
            $setOpeningAmount = [];
            $pdf =  \PDF::loadView('Ledger.pdf', $ledgerEntries, $setOpeningAmount);
            return $pdf->download('Ledger_' . '.pdf');
        }
    }

    public function print(Request $request)
    {
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

        if ($fromDate !== null || $toDate !== null) {
            // Fetch ledger entries
            $ledgerData = $this->fetchLedgerEntries($fromDate, $toDate);
            $ledgerEntries = $ledgerData['ledgerEntries'];
            $setOpeningAmount = $ledgerData['setOpeningAmount'];
            if (auth()->check()) {
                if (auth()->user()->can('ledger-print')) {
                    return view('ledger.print', compact('ledgerEntries', 'setOpeningAmount', 'fromDate', 'toDate'));
                } else {
                    return redirect()->back()->with('error', 'You do not have permission to print download.');
                }
            } else {
                return redirect()->route('login')->with('error', 'You need to login first.');
            }
            
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
            $ledgerData = $this->fetchLedgerEntries($fromDate, $toDate);
            $ledgerEntries = $ledgerData['ledgerEntries'];
            $setOpeningAmount = $ledgerData['setOpeningAmount'];
            // Strip HTML tags from descriptions
            foreach ($ledgerEntries as $entry) {
                $entry->description = strip_tags($entry->description);
            }

            if (auth()->check()) {
                if (auth()->user()->can('ledger-print')) {
                    return \Excel::download(new \App\Exports\LedgerExport($ledgerEntries), 'ledger.xlsx');
                } else {
                    return redirect()->back()->with('error', 'You do not have permission to excel download.');
                }
            } else {
                return redirect()->route('login')->with('error', 'You need to login first.');
            }
            // Generate Excel using Laravel Excel package
            
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
        $setOpeningAmount = 0;
        if ($fromDate) {
            $initialIncome = Income::whereDate('created_at', '<', $fromDate)->sum('amount');
            $initialExpense = OfficeExpense::whereDate('created_at', '<', $fromDate)->sum('amount');
            $setOpeningAmount = $initialIncome - $initialExpense;
            $balance = $initialIncome - $initialExpense;
        }

        foreach ($ledgerEntries as $entry) {

            $balance += $entry->debit - $entry->credit;
            $entry->balance = $balance;
        }

        return [
            'ledgerEntries' => $ledgerEntries,
            'setOpeningAmount' => $setOpeningAmount,
        ];
    }
}
