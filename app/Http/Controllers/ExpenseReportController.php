<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Expence;
use App\Models\ExpenseHead;
use App\Models\FundCategory;
use App\Models\OfficeExpense;
use Illuminate\Http\Request;

class ExpenseReportController extends Controller
{
    public function report()
    {
        $query = OfficeExpense::query();
        $query->whereDate('created_at', '>=', Carbon::today()->toDateString());
        $expenses = $query->get();
        $fundCategories = FundCategory::all();
        $expenseCategories = Expence::all();
        $expenseHeads = ExpenseHead::all();
        if (auth()->check()) {
            if (auth()->user()->can('expense-report')) {
                return view('ExpenseReport.index', compact('fundCategories', 'expenses', 'expenseCategories', 'expenseHeads'));
            } else {
                return redirect('/')->with('error', 'You do not have permission to view fund addjustment.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
       
    }



    public function getExpenseHeads(Request $request)
    {
        $expenseHeads = ExpenseHead::where('expense_category_id', $request->expense_category_id)->get();
        return response()->json($expenseHeads);
    }

    // ExpenseController.php

    public function filter(Request $request)
    {
        // dd($request);
        $query = OfficeExpense::query();

        if ($request->expense_category) {
            $query->whereHas('expenseHeadCategory', function ($q) use ($request) {
                $q->where('expense_category_id', $request->expense_category);
            });
        }

        if ($request->expense_head_category) {
            $query->where('expense_head_category', $request->expense_head_category);
        }

        if ($request->fund_category) {
            $query->where('fund_category', $request->fund_category);
        }

        if ($request->fromdate) {
            $query->whereDate('created_at', '>=', $request->fromdate);
        }

        if ($request->todate) {
            $query->whereDate('created_at', '<=', $request->todate);
        }

        $expenses = $query->get();
        $fundCategories = FundCategory::all();
        $expenseCategories = Expence::all();
        return view('ExpenseReport.index', compact('expenses', 'expenseCategories', 'fundCategories'))
            ->with('filters', $request->all());
    }
}
