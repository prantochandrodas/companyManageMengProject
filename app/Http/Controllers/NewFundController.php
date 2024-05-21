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
        $funds = FundCategory::all();
        return view('fund.createFund', compact('funds'));
    }

    public function index()
    {
        $funds = NewFund::with('category')->get();
        return view('fund.index', compact('funds'));
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
            $newAmount = $fundCategory->addedFundAmount	 + $request->amount;
            $newTotalAmount = $fundCategory->total + $request->amount;

            $fundCategory->update([
                'addedFundAmount' => $newAmount,
                'total'=>$newTotalAmount
            ]);
            DB::commit();
            return redirect()->route('fund.index')->with('success', 'Expense head added sucessfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }



    public function destroy($id)
    {
        $fund = NewFund::find($id);
        $fund->delete();
        return redirect()->route('fund.index')->with('success', 'Expense head added sucessfully.');
    }
}
