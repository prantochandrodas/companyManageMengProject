<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use App\Models\FundCategory;
use App\Models\Income;
use App\Models\NewFund;
use App\Models\OfficeExpense;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use PHPUnit\TextUI\Configuration\Php;
use Yajra\DataTables\Facades\DataTables;

class FundCategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:Fund-Add', ['only' => ['create']]);
    }

    public function index()
    {
        if (auth()->check()) {
            if (auth()->user()->can('fund-category')) {
                return view('FundCategory.index');
            } else {
                return redirect()->route('login')->with('error', 'You do not have permission to view this page.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
    }


    public function getFundsCategory(Request $request)
    {
        if ($request->ajax()) {
            $users = FundCategory::all();
            return DataTables::of($users)->make(true);
        }
        return abort(404);
    }


    public function createPage()
    {
        if (auth()->check()) {
            if (auth()->user()->can('add-fund')) {
                return view('FundCategory.createpage');
            } else {
                return redirect('/')->with('error', 'You do not have permission to add fund.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
    }

    public function store(Request $request)
    {
        $fundCategory = FundCategory::where('name', $request->name)->first();
        if ($fundCategory == null) {
            $validateData = $request->validate([
                'name' => 'required|string|max:255',
                'openingAmount' => 'required|numeric'
            ]);

            //create new record in the fund_categories table
            $fundCategory = new FundCategory();
            $fundCategory->name = $validateData['name'];
            $fundCategory->openingAmount = $validateData['openingAmount'];
            $fundCategory->total = $validateData['openingAmount'];
            $fundCategory->save();


            $fundadded = new NewFund();
            $fundadded->Description = 'added opening amount';
            $fundadded->category_id = $fundCategory->id;
            $fundadded->amount = $validateData['openingAmount'];
            $fundadded->save();
            // FundCategory::create($request->all());
            // return redirect()->route('fundsCategory.index')->with('success', 'Funds category added sucessfully');
            return response()->json([
                'message'=>'Fundcategory added successfully',
                'fundcategory'=>$fundCategory,
            ],200);
        } else {
            return response()->json([
                'message' => 'Funds category already exists',
            ], 409);
        }
    }
}
