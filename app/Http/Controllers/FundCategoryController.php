<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use App\Models\FundCategory;
use App\Models\NewFund;
use App\Models\OfficeExpense;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FundCategoryController extends Controller
{
    public function index(){
        return view('FundCategory.index');
    }
    

    public function getFundsCategory(Request $request)
    {
        if ($request->ajax()) {
            $fundCategories = FundCategory::query();
    
            return DataTables::of($fundCategories)
                ->addColumn('expensedAmount', function ($fundCategory) {
                    $expensedAmount = OfficeExpense::where('fund_category', $fundCategory->id)->sum('amount');
                    return $expensedAmount;
                })
                ->addColumn('addedAmount', function ($fundCategory) {
                    $addedAmount = NewFund::where('category_id', $fundCategory->id)->sum('amount');
                    return $addedAmount;
                })
                ->addColumn('remainingAmount', function ($fundCategory) {
                    $expensedAmount = OfficeExpense::where('fund_category', $fundCategory->id)->sum('amount');
                    $addedAmount = NewFund::where('category_id', $fundCategory->id)->sum('amount');
                    $remainingAmount = $fundCategory->openingAmount + $addedAmount - $expensedAmount;
                  
                    return $remainingAmount;
                })
                ->make(true);
        }
        // if ($request->ajax()) {
        //     $users = FundCategory::query();
        //     return DataTables::of($users)->make(true);
        // }
        // return abort(404);
    }


    public function createPage()
    {
        return view('FundCategory.createpage');
    }

    public function store(Request $request){
        
    $fundCategory = FundCategory::where('name', $request->name)->first();
        if($fundCategory==null){
            $validateData=$request->validate([
                'name'=>'required|string|max:255',
                'openingAmount'=>'required|numeric'
            ]);

            //create new record in the fund_categories table
            $fundCategory=new FundCategory();
            $fundCategory->name=$validateData['name'];
            $fundCategory->openingAmount=$validateData['openingAmount'];
            $fundCategory->total=$validateData['openingAmount'];
            $fundCategory->save();


            $fundadded=new NewFund();
            $fundadded->Description='added opening amount';
            $fundadded->category_id=$fundCategory->id;
            $fundadded->amount=$validateData['openingAmount'];
            $fundadded->save();
            // FundCategory::create($request->all());
            return redirect()->route('fundsCategory.index')->with('success','Funds category added sucessfully');
        }else{
            return redirect()->route('fundsCategory.index')->with('error','Already exist');
        }
    }
}
