<?php

namespace App\Http\Controllers;

use App\Models\Expence;
use App\Models\ExpenseHead;
use App\Models\FundCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ExpenseHeadController extends Controller
{
    public function index()
    {
        // Fetch all expense heads with their corresponding expense category names
        return view('ExpenseHead.index');
    }


    public function store(Request $request)
    {
        $expenseHead = ExpenseHead::where('name', $request->name)->first();
        if ($expenseHead == null) {
            ExpenseHead::create($request->all());
            return redirect()->route('expenseshead.index')->with('success', 'Funds category added sucessfully');
        } else {
            return redirect()->route('expenseshead.index')->with('error', 'Expense head already exist');
        }
    }


    public function create()
    {
        $expenses = Expence::all();
        return view('ExpenseHead.expenseHeadCreate', compact('expenses'));
    }


    public function destroy($id)
    {
        $expense = ExpenseHead::find($id);
        $expense->delete();
        return redirect()->route('expenseshead.index')->with('success', 'Expenses Deleted Sucessfull.');
    }

    public function getExpenseHead(Request $request)
    {
      
        if ($request->ajax()) {
            $data = ExpenseHead::with('category')->orderBy('created_at', 'desc')->get();
            return DataTables::of($data)
                ->addColumn('category_name', function ($row) {
                    return $row->category ? $row->category->name : '';
                })
                ->addColumn('action', function ($row) {

                    $btn = '<form action="' . route('expense_heads.destroy', $row->id) . '" method="POST" style="display:inline-block;">
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
}
