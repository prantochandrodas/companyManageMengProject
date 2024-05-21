<?php

namespace App\Http\Controllers;

use App\Models\Expence;
use App\Models\ExpenseHead;
use App\Models\FundCategory;
use App\Models\OfficeExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OfficeExpenseController extends Controller
{
    public function index()
    {
        return view('OfficeExpense.index');
    }

    public function report(Request $request)
    {
        $fundCategories = FundCategory::all();
        $expenseCategories = Expence::all();
        $expenseHeads=ExpenseHead::all();
        $expenses = null;
        return view('ExpenseReport.index', compact('fundCategories', 'expenses','expenseCategories','expenseHeads'));
    }

    public function getExpenseHeads($expense_category_id)
    {
        $expenseHeads = ExpenseHead::where('expense_category_id', $expense_category_id)->get();
        if ($expenseHeads->isEmpty()) {
            return response()->json(['message' => 'No expense heads found'], 404);
        }
        return response()->json($expenseHeads);
    }

    public function filter(Request $request)
    {
        //  dd($request);
        $request->validate([
            'expense_category' => 'required|exists:expences,id',
            'expenseHeads' => 'required|exists:expense_heads,id',
            'fund_category' => 'required|exists:fund_categories,id',
            'fromdate' => 'required|date',
            'todate' => 'required|date'
        ]);

         // Build the query
         $query = OfficeExpense::query();
        
        if($request->has('fromdate') && $request->has('todate')){
            $query->whereBetween('created_at', [$request->fromdate, $request->todate]);
        }

        
        // Apply expense head category filter
        if ($request->filled('expense_heads')) {
            $query->where('expense_heads', $request->expenseHeads);
        }


         // Apply fund category filter
         if ($request->filled('fund_category')) {
            $query->where('fund_category', $request->fund_category);
        }

        // Execute the query and get the results
        $expenses = $query->get();

        // $expenses = OfficeExpense::where('fund_category', $fundCategoryId)
        //     ->whereDate('created_at', $date)
        //     ->get();
        $fundCategories = FundCategory::all();
        $expenseCategories = Expence::all();
        $expenseHeads = ExpenseHead::all();
        return view('ExpenseReport.index', compact('fundCategories', 'expenses', 'expenseCategories', 'expenseHeads'));
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
            $data = OfficeExpense::with('expenseCategory', 'expenseHeadCategory', 'fundCategory')->get();
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

                    $btn = '<form action="' . route('officeExpense.destroy', $row->id) . '" method="POST" style="display:inline-block;">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="delete btn btn-danger btn-sm">Delete</button>
                         </form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function destroy($id)
    {
        $fund = OfficeExpense::find($id);
        $fund->delete();
        return redirect()->route('officeExpense.index')->with('success', 'Expense head added sucessfully.');
    }

    public function store(Request $request)
    {
        // dd($request);
        try {
            DB::beginTransaction();
            $fundCategory = FundCategory::find($request->fund_category);
            if ($fundCategory->total >= $request->amount) {
                OfficeExpense::create($request->all());
                $newTotalAmount = $fundCategory->total - $request->amount;
                $newAddedAmount = $fundCategory->expensedAmount + $request->amount;
                $fundCategory->update([
                    'total' => $newTotalAmount,
                    'expensedAmount'=>$newAddedAmount
                ]);
                DB::commit();
                return redirect()->route('officeExpense.index')->with('success', 'Expense added sucessfully');
            } else {
                DB::commit();
                return redirect()->route('officeExpense.index')->with('error', 'Not have enough');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
