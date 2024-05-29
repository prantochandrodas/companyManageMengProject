<?php

namespace App\Http\Controllers;

use App\Models\IncomeCategory;
use Illuminate\Http\Request;

class IncomeCategoryController extends Controller
{
    public function index()
    {
        return view('IncomeCategory.index');
    }

    public function create()
    {
        return view('IncomeCategory.create');
    }

    public function store(Request $request)
    {
        $findIncomeCategory = IncomeCategory::where('name', $request->name)->first();
        if ($findIncomeCategory !== null) {
            return redirect()->route('incomeCategory.index')->with('error', 'Added exist');
        } else {
            IncomeCategory::create($request->all());
            return redirect()->route('incomeCategory.index')->with('success', 'Added Income sucessfull');
        }
    }

    public function getIncomeCategory(Request $request)
    {
        if ($request->ajax()) {
            $data = IncomeCategory::all();
            return datatables()->of($data)
                ->addColumn('action', function ($row) {
                    $deleteUrl = route('incomeCategory.distroy', $row->id);

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

    public function distroy($id){
        $income=IncomeCategory::find($id);
        $income->delete();
        return redirect()->route('incomeCategory.index')->with('success','Deleted successful');
    }
}
