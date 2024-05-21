<?php

namespace App\Http\Controllers;
use App\Models\Expence;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{

    public function index(){
        $expenses=Expence::all();
        return view('expenses.index',compact('expenses'));
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
 
    public function store(Request $request){

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $fundCategory = Expence::where('name', $request->name)->first();
        if($fundCategory==null){
            Expence::create($request->only('name'));
            return redirect()->route('expenses.index')->with('success','Expenses added sucessfully.');
        }else{
            return redirect()->route('expenses.index')->with('error','Expenses category already exist.');
        }
       

    }


    public function create()
    {
        return view('createexpcategory');
    }

    public function destroy($id){
        $expense=Expence::find($id);
        $expense->delete();
        return redirect()->route('expenses.index')->with('success','Expenses Deleted Sucessfull.');
    }
}
