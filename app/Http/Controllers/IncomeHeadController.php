<?php

namespace App\Http\Controllers;

use App\Models\IncomeCategory;
use App\Models\IncomeHead;
use Illuminate\Http\Request;

class IncomeHeadController extends Controller
{
    public function index(){

        if (auth()->check()) {
            if (auth()->user()->can('income-head')) {
                return view('IncomeHead.index');
            } else {
                return redirect('/')->with('error', 'You do not have permission to income head page.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }

        
    }

    public function getIncomeHead(Request $request){
        if($request->ajax()){
            $data=IncomeHead::with('incomeCategory')->get();
            return datatables()->of($data)
            ->addColumn('income_category',function($row){
                return $row->incomeCategory ? $row->incomeCategory->name :'';
            })
            ->addColumn('action', function ($row) {
                $deleteUrl = route('incomeHead.distroy', $row->id);

                $csrfToken = csrf_field();
                $methodField = method_field("DELETE");

                $deleteBtn = '
            <form action="' . $deleteUrl . '" method="POST" style="display:inline-block;">
                ' . $csrfToken . '
                ' . $methodField . '
                <button type="submit" class="delete btn btn-danger btn-sm">Delete</button>
            </form>';
                return $deleteBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
    public function create(){
        
        $incomeCategories=IncomeCategory::all();
        if (auth()->check()) {
            if (auth()->user()->can('add_posts')) {
                return view('IncomeHead.create',compact('incomeCategories'));
            } else {
                return redirect()->back()->with('error', 'You do not have permission to add income head page.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
       
    }

    public function store(Request $request){
        
        $findIncomeHead=IncomeHead::where('name',$request->name)->first();
        if($findIncomeHead){
            return redirect()->route('incomeHead.index')->with('error','already exist');
        }else{
            IncomeHead::create($request->all());
            return redirect()->route('incomeHead.index')->with('success','sucessfully added');
        }
    }


    public function distroy($id){
        $findIncomeHead=IncomeHead::find($id);

        if (auth()->check()) {
            if (auth()->user()->can('delete_posts')) {
                $findIncomeHead->delete();
                return redirect()->route('incomeHead.index')->with('success','Income head Deleted');
            } else {
                return redirect()->back()->with('error', 'You do not have permission to income head delete page.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }

       
    }
}
