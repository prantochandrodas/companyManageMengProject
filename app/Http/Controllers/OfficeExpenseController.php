<?php

namespace App\Http\Controllers;

use App\Models\Expence;
use App\Models\ExpenseHead;
use App\Models\ExpenseMaster;
use App\Models\FundCategory;
use App\Models\OfficeExpense;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OfficeExpenseController extends Controller
{
    public function index()
    {
        return view('OfficeExpense.index');
    }


    public function getExpenseHeads($expense_category_id)
    {
        $expenseHeads = ExpenseHead::where('expense_category_id', $expense_category_id)->get();
        if ($expenseHeads->isEmpty()) {
            return response()->json(['message' => 'No expense heads found'], 404);
        }
        return response()->json($expenseHeads);
    }



    public function create()
    {
        $expenseCategories = Expence::all();
        $expenseHeads = ExpenseHead::all();
        $fundCategories = FundCategory::all();
        return view('OfficeExpense.create', compact('expenseCategories', 'expenseHeads', 'fundCategories'));
    }

    public function getFunds(Request $request)
    {
        // $data = NewFund::all();
        // return datatables()->of($data)
        //     ->addColumn('action', function ($row) {

        //         $btn = '<form action="' . route('funds.destroy', $row->id) . '" method="POST" style="display:inline-block;">
        //                     ' . csrf_field() . '
        //                     ' . method_field('DELETE') . '
        //                     <button type="submit" class="delete btn btn-danger btn-sm">Delete</button>
        //                  </form>';
        //         return $btn;
        //     })
        //     ->rawColumns(['action'])
        //     ->make(true);


        if ($request->ajax()) {
            $data = OfficeExpense::with('expenseCategory', 'expenseHeadCategory', 'fundCategory')->orderBy('created_at', 'desc')->get();
            return DataTables::of($data)
                ->addColumn('expense_category', function ($row) {
                    return $row->expenseCategory ? $row->expenseCategory->name : '';
                })
                ->addColumn('expenseHead_category', function ($row) {
                    return $row->expenseHeadCategory ? $row->expenseHeadCategory->name : '';
                })
                ->addColumn('fund_category', function ($row) {
                    return $row->fundCategory ? $row->fundCategory->name : '';
                })
                ->addColumn('action', function ($row) {
                    // $printUrl = route('officeExpense.print', $row->id);
                    $pdfUrl = route('officeExpense.pdf', $row->id);
                    $viewBtn = '<button data-id="' . $row->id . '" class="view btn btn-primary btn-sm">View</button>';
                    $pdfBtn = '<a href="' . $pdfUrl . '" class="btn btn-primary btn-sm mx-2" target="_blank">PDF</a>';
                    $printBtn = '<button data-id="' . $row->id . '" class="print btn btn-secondary btn-sm">Print</button>';
                    return $viewBtn . ' ' . $pdfBtn . ' ' . $printBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function view($id)
    {
        $officeExpense = OfficeExpense::with('expenseCategory', 'expenseHeadCategory', 'fundCategory')->find($id);
        if ($officeExpense) {
            $response = [
                'id' => $officeExpense->id,
                'expenseCategory' => $officeExpense->expenseCategory ? $officeExpense->expenseCategory->name : 'N/A',
                'expenseHeadCategory' => $officeExpense->expenseHeadCategory ? $officeExpense->expenseHeadCategory->name : 'N/A',
                'fundCategory' => $officeExpense->fundCategory ? $officeExpense->fundCategory->name : 'N/A',
                'description' => $officeExpense->description,
                'amount' => $officeExpense->amount,
                'created_at' => $officeExpense->created_at,
            ];
            // dd($response);
            return response()->json($response);
        } else {
            return response()->json(['error' => 'Expense not found'], 404);
        }
    }

    public function distroy($id)
    {
        $fund = OfficeExpense::find($id);
        $fund->delete();
        return redirect()->route('officeExpense.index')->with('success', 'Expense head added sucessfully.');
    }

    public function store(Request $request)
    {
        $newTotal = 0;
        $fundCategories = $request->input('fund_category');
        $amounts = $request->input('amount');

        foreach ($fundCategories as $key => $fundCategoryId) {
            $newTotal += $amounts[$key];

            $fundCategory = FundCategory::find($fundCategoryId);
            // dd($fundCategory->total);
            if ($fundCategory->total < $amounts[$key]) {
                return redirect()->route('officeExpense.index')->with('error', 'Not enough funds in ' . $fundCategory->name);
            }else{
              $newTotal=  $fundCategory->total-$amounts[$key];
              $newExpensedAmount=  $fundCategory->expensedAmount+$amounts[$key];
                $fundCategory->update([
                    'total' => $newTotal,
                    'expensedAmount' =>$newExpensedAmount
                ]);
            }
        }

        // Calculate total amount of all expenses
        $totalAmount = 0;
        foreach ($request->input('expense_category') as $key => $category) {
            $totalAmount += $request->input('amount')[$key];
        }

        // Insert the total amount into ExpenseMaster table
        $expenseMaster = ExpenseMaster::create([
            'code' => $request->_token,
            'amount' => $totalAmount,
        ]);

        
        foreach ($request->input('expense_category') as $key => $category) {
            // dd($expenseMaster->id);
            OfficeExpense::create([
                'expense_category' => $category,
                'expense_head_category' => $request->input('expense_head_category')[$key],
                'fund_category' => $request->input('fund_category')[$key],
                'amount' => $request->input('amount')[$key],
                'description' => $request->input('description')[$key],
                'expense_master_id' => $expenseMaster->id,
            ]);
        }
        return redirect()->route('officeExpense.index')->with('success', 'Expense added successfully');
    }

    public function print($id)
    {
        $officeExpense = OfficeExpense::findOrFail($id);
        return view('officeExpense.print', compact('officeExpense'));
    }

    public function pdf($id)
    {
       $officeExpense = OfficeExpense::with('expenseCategory', 'expenseHeadCategory', 'fundCategory')->find($id);
        if (!$officeExpense) {
            return redirect()->route('officeExpense.index')->with('error', 'Expense not found');
        }

        $data = [
            'id' => $officeExpense->id,
            'expenseCategory' => $officeExpense->expenseCategory ? $officeExpense->expenseCategory->name : 'N/A',
            'expenseHeadCategory' => $officeExpense->expenseHeadCategory ? $officeExpense->expenseHeadCategory->name : 'N/A',
            'fundCategory' => $officeExpense->fundCategory ? $officeExpense->fundCategory->name : 'N/A',
            'description' => $officeExpense->description,
            'amount' => $officeExpense->amount,
            'date'=> $officeExpense->created_at->format('Y-m-d')
        ];

        $pdf =  \PDF::loadView('officeExpense.pdf', $data);
        return $pdf->download('expense_' . $id . '.pdf');
    }
}
