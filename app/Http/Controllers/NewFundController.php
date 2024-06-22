<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use App\Models\FundCategory;
use App\Models\NewFund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class NewFundController extends Controller
{
    public function createPage()
    {
        
        if (auth()->check()) {
            if (auth()->user()->can('add-fundAdjustment')) {
                $funds = FundCategory::all();
                return view('fund.createFund', compact('funds'));
            } else {
                return redirect('/')->with('error', 'You do not have permission to view this page.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
    }

    public function index()
    {
        if (auth()->check()) {
            if (auth()->user()->can('fund-category')) {
                $funds = NewFund::with('category')->get();
                return view('fund.index', compact('funds'));
            } else {
                return redirect('/')->with('error', 'You do not have permission to view this page.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
    }

    public function getFunds(Request $request)
    {

        if ($request->ajax()) {
            $data = NewFund::with('category')->get();
            return DataTables::of($data)
                ->addColumn('category_name', function ($row) {
                    return $row->category ? $row->category->name : '';
                })
                ->addColumn('action', function ($row) {

                    $btn = '<form action="' . route('funds.destroy', $row->id) . '" method="POST" style="display:inline-block;">
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

    public function create(Request $request)
    {

        try {
            DB::beginTransaction();
            NewFund::create($request->all());

            $fundCategory = FundCategory::find($request->category_id);
            $newAmount = $fundCategory->addedFundAmount     + $request->amount;
            $newTotalAmount = $fundCategory->total + $request->amount;
            
            $fundCategory->update([
                'addedFundAmount' => $newAmount,
                'total' => $newTotalAmount
            ]);
            DB::commit();
            return redirect()->route('fund.index')->with('success', 'Expense head added sucessfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
