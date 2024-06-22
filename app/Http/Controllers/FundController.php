<?php

namespace App\Http\Controllers;

use App\Models\Expence;
use App\Models\Fund;
use App\Models\FundCategory;
use Faker\Core\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class FundController extends Controller
{
    public function createPage()
    {
        if (auth()->check()) {
            if (auth()->user()->can('add-fund')) {
                $funds = FundCategory::all();
                return view('fund.createFund', compact('funds'));
            } else {
                return redirect('/')->with('error', 'You do not have permission to add fund.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
    }

    public function index()
    {
        if (auth()->check()) {
            if (auth()->user()->can('add-fund')) {
                $funds = Fund::with('category')->get();
                return view('fund.index', compact('funds'));
            } else {
                return redirect('/')->with('error', 'You do not have permission to view fund addjustment.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
    }

    public function getFunds(Request $request)
    {
        if ($request->ajax()) {
            $data = Fund::with('category')->orderBy('created_at', 'desc')->get();
            return DataTables::of($data)
                ->addColumn('category_name', function ($row) {
                    return $row->category ? $row->category->name : '';
                })
                ->addColumn('action', function ($row) {
                    $deleteUrl = route('expense_heads.destroy', $row->id);
                    return '<button type="button" class="btn btn-danger btn-sm deleteBtn" data-url="' . $deleteUrl . '">Delete</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create(Request $request)
    {

        try {
            DB::beginTransaction();
            Fund::create($request->all());

            $fundCategory = FundCategory::find($request->category_id);
            $newAmount = $fundCategory->amount + $request->amount;

            $fundCategory->update([
                'amount' => $newAmount,
            ]);
            DB::commit();
            return redirect()->route('fund.index')->with('success', 'Expense head added sucessfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }



    // public function destroy($id)
    // {
    //     if (auth()->check()) {
    //         if (auth()->user()->can('delete_posts')) {
    //             $fund = Fund::find($id);
    //             $fund->delete();
    //             return redirect()->route('fund.index')->with('success', 'Expense head added sucessfully.');
    //         } else {
    //             return redirect('/')->with('error', 'You do not have permission to view fund addjustment.');
    //         }
    //     } else {
    //         return redirect()->route('login')->with('error', 'You need to login first.');
    //     }
    // }
}
