<?php

namespace App\Http\Controllers;

use App\Models\Expence;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{

    public function index()
    {
        if (auth()->check()) {
            if (auth()->user()->can('expense-category')) {
                $expenses = Expence::all();
                return view('expenses.index', compact('expenses'));
            } else {
                return redirect('/')->with('error', 'You do not have permission to view expense category.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
    }

    public function getExpense()
    {
        $data = Expence::all();
        return datatables()->of($data)
            ->addColumn('action', function ($row) {

                $btn = '<form action="' . route('expenses.destroy', $row->id) . '" method="POST" style="display:inline-block;">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="delete btn btn-danger btn-sm">Delete</button>
                         </form>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $fundCategory = Expence::where('name', $request->name)->first();
        if ($fundCategory == null) {
            Expence::create($request->only('name'));
            return redirect()->route('expenses.index')->with('success', 'Expenses added sucessfully.');
        } else {
            return redirect()->route('expenses.index')->with('error', 'Expenses category already exist.');
        }
    }


    public function create()
    {
        if (auth()->check()) {
            if (auth()->user()->can('add-expenseCategory')) {
                return view('createexpcategory');
            } else {
                return redirect('/')->with('error', 'You do not have permission to add expense category.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
    }

    public function destroy($id)
    {
        if (auth()->check()) {
            if (auth()->user()->hasrole('delete_posts')) {
                $expense = Expence::find($id);
                $expense->delete();
                return redirect()->route('expenses.index')->with('success', 'Expenses Deleted Sucessfull.');
            } else {
                return redirect('/')->with('error', 'You do not have permission to delete expense category.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
    }
}
