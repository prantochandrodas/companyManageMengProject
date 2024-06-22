<?php

namespace App\Http\Controllers;

use App\Imports\IncomeImport;
use App\Models\FundCategory;
use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\IncomeHead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class IncomeController extends Controller
{
    public function index()
    {
        // ************** check permission *************
        if (auth()->check()) {
            if (auth()->user()->can('income')) {
                return view('Income.index');
            } else {
                return redirect()->back()->with('error', 'You do not have permission to
             Income.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
    }

    public function getIncomeHeads($id)
    {
        $incomeHeads = IncomeHead::where('income_category_id', $id)->get();
        if ($incomeHeads->isEmpty()) {
            return response()->json(['message' => 'No income head found']);
        } else {
            return response()->json($incomeHeads);
        }
    }

    public function create()
    {
        $incomeCategories = IncomeCategory::all();
        $fundCategories = FundCategory::all();

        // ************** check permission *************
        if (auth()->check()) {
            if (auth()->user()->can('add_posts')) {
                return view('Income.create', compact('incomeCategories', 'fundCategories'));
            } else {
                return redirect()->back()->with('error', 'You do not have permission to
                add Income.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'income_category_id' => 'required',
            'income_head_id' => 'required',
            'fund_category_id' => 'required',
            'amount' => 'required|numeric|regex:/^\d{1,13}(\.\d{1,2})?$/',
            'description' => 'required',
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string',
            'email' => 'required|email',
            'phone_number' => 'required|regex:/^01[3-9]\d{8}$/|numeric',
        ]);
        $fundCategory = FundCategory::find($request->input('fund_category_id'));
        $fundCategory->update([
            'incomeAmount' => $fundCategory->incomeAmount + $request->input('amount'),
            'total' => $fundCategory->total + $request->input('amount'),
        ]);
        $income = new Income();
        $income->income_category_id = $request->input('income_category_id');
        $income->income_head_id = $request->input('income_head_id');
        $income->fund_category_id = $request->input('fund_category_id');
        $income->amount = $request->input('amount');
        $income->description = $request->input('description');
        $income->name = $request->input('name');
        $income->company_name = $request->input('company_name');
        $income->email = $request->input('email');
        $income->phone_number = $request->input('phone_number');
        $income->save();

        return redirect()->route('income.index')->with('success', 'added successfully');
    }

    public function getincome(Request $request)
    {
        if ($request->ajax()) {
            $data = Income::with('incomeCategory', 'incomeHead', 'fundCategory')->orderBy('created_at', 'desc')->get();

            return datatables()->of($data)
                ->addColumn('income_category', function ($row) {
                    return  $row->incomeCategory ? $row->incomeCategory->name : '';
                })
                ->addColumn('income_head', function ($row) {
                    return  $row->incomeHead ? $row->incomeHead->name : '';
                })
                ->addColumn('fund_category', function ($row) {
                    return $row->fundCategory ? $row->fundCategory->name : '';
                })
                ->addColumn('action', function ($row) {
                    $pdfUrl = route('income.pdf', $row->id);
                    $viewBtn = '<button data-id="' . $row->id . '" class="view btn btn-primary btn-sm">View</button>';
                    $pdfBtn = '<a href="' . $pdfUrl . '" class="btn btn-primary btn-sm mx-2" target="_blank">PDF</a>';
                    $printBtn = '<button data-id="' . $row->id . '" class="print btn btn-secondary btn-sm">Print</button>';
                    return $viewBtn . ' ' . $pdfBtn . ' ' . $printBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function view($id)
    {
        $income = Income::with('incomeCategory', 'incomeHead', 'fundCategory')->find($id);
        // $description = strip_tags($income->description);
        if ($income) {
            $response = [
                'income_category_id' => $income->incomeCategory->name,
                'income_head_id' => $income->incomeHead->name,
                'fund_category_id' => $income->fundCategory->name,
                'name' => $income->name,
                'company_name' => $income->company_name,
                'email' => $income->email,
                'phone_number' => $income->phone_number,
                'description' => $income->description,
                'amount' => $income->amount,
                'created_at' => $income->created_at
            ];

            // ************** check permission *************

            if (auth()->check()) {
                if (auth()->user()->can('income-view')) {
                    return response()->json($response);
                } else {
                    return redirect()->back()->with('error', 'You do not have permission to
                    Income view.');
                }
            } else {
                return redirect()->route('login')->with('error', 'You need to login first.');
            }
        } else {
            return response()->json(['error' => 'Expense not found'], 404);
        }
    }

    public function print($id)
    {
        $income = Income::with('incomeCategory', 'incomeHead', 'fundCategory')->find($id);

        if ($income) {
            // ************** check permission *************

            if (auth()->check()) {
                if (auth()->user()->can('income-print')) {
                    return view('Income.print', compact('income'));
                } else {
                    return redirect()->back()->with('error', 'You do not have permission to
                    Income print.');
                }
            } else {
                return redirect()->route('login')->with('error', 'You need to login first.');
            }
        } else {
            return view('Income.index', compact('error', 'income print error'));
        }
    }


    public function pdf($id)
    {
        $income = Income::with('incomeCategory', 'incomeHead', 'fundCategory')->find($id);

        if ($income) {
            $response = [
                'income_category_id' => $income->incomeCategory->name,
                'income_head_id' => $income->incomeHead->name,
                'fund_category_id' => $income->fundCategory->name,
                'company_name' => $income->company_name,
                'email' => $income->email,
                'phone_number' => $income->phone_number,
                'description' => $income->description,
                'amount' => $income->amount,
                'created_at' => $income->created_at->format('Y-m-d')
            ];

            // ************** check permission *************
            if (auth()->check()) {
                if (auth()->user()->can('income-print')) {
                    $pdf =  \PDF::loadView('Income.pdf', $response);
                    return $pdf->download('Income_' . $id . '.pdf');
                } else {
                    return redirect()->back()->with('error', 'You do not have permission to
                    Income pdf.');
                }
            } else {
                return redirect()->route('login')->with('error', 'You need to login first.');
            }


        } else {
            return response()->json(['error' => 'Income not found'], 404);
        }
    }

    public function report()
    {
        // dd('hi');
        //  ||||||||||||||||||| check permission  \\\\\\\\\\\\\\\\\
        if (auth()->check()) {        
            if (auth()->user()->can('income-report')) {
                // dd('hi');
                $incomeCategories = IncomeCategory::all();
                $funds = FundCategory::all();
                $query = Income::query();
                $query->whereDate('created_at', '>=', Carbon::today()->toDateString());
                $incomes = $query->orderBy('created_at', 'desc')->get();
        
                return view('Income.report', compact('incomeCategories', 'funds', 'incomes'));
            } else {
                return redirect()->back()->with('error', 'You do not have permission to Income Report.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
    }

    public function filter(Request $request)
    {
        $query = Income::query();
        // dd($request->income_category_id);

        if ($request->income_category_id) {
            $query->whereHas('IncomeHead', function ($q) use ($request) {
                $q->where('income_category_id', $request->income_category_id);
            });
        }

        if ($request->income_head_id) {
            $query->where('income_head_id', $request->income_head_id);
        }
        if ($request->fund_category_id) {
            $query->where('fund_category_id', $request->fund_category_id);
        }

        if ($request->formDate) {
            $query->whereDate('created_at', '>=', $request->formDate);
        }
        if ($request->toData) {
            $query->whereDate('created_at', '<=', $request->toDate);
        }


        // dd($request->formDate);
        $incomes = $query->get();
        $incomeCategories = IncomeCategory::all();
        $funds = FundCategory::all();

        //  ************************** check permission **************************

        if (auth()->check()) {
            if (auth()->user()->can('income-report-filter')) {
                return view('Income.report', compact('incomes', 'incomeCategories', 'funds'))->with($request->all);
            } else {
                return redirect()->back()->with('error', 'You do not have permission to
                filter Income Report.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
    }

    public function import()
    {

         //  ************************** check permission **************************

         if (auth()->check()) {
            if (auth()->user()->can('income-import-excel')) {
                return view('Income.excel');
            } else {
                return redirect()->back()->with('error', 'You do not have permission to
                Income excel.');
            }
        } else {
            return redirect()->route('login')->with('error', 'You need to login first.');
        }
        
    }

    public function StoreExcel(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new IncomeImport, $request->file('file'));

            // Retrieve the imported data
            $importedData = IncomeImport::$importedData;
            $newTotal = 0;

            foreach ($importedData as $data) {
                $fundCategoryId = $data['fund_category_id'];
                $amount = $data['amount'];

                $fundCategory = FundCategory::find($fundCategoryId);

                if (!$fundCategory) {
                    return redirect()->route('income.index')->with('error', 'Fund category not found for ID: ' . $fundCategoryId);
                } else {
                    $fundCategory->total += $amount;
                    $fundCategory->incomeAmount += $amount;

                    $fundCategory->save();
                }
            }
            return redirect()->route('income.index')->with('success', 'Data imported successfully!');
        } catch (\Exception $e) {
            \Log::error('Error during import:', ['exception' => $e]);
            return redirect()->back()->with('error', 'Error importing data: ' . $e->getMessage());
        }
    }
}
