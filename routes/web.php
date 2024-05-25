<?php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseHeadController;
use App\Http\Controllers\ExpenseReportController;
use App\Http\Controllers\FundCategoryController;
use App\Http\Controllers\NewFundController;
use App\Http\Controllers\OfficeExpenseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/getExpense', [ExpenseController::class, 'getExpense'])->name('expensesCategory.get');
Route::get('/expense', [ExpenseController::class, 'index'])->name('expenses.index');
Route::get('/expense/create', [ExpenseController::class, 'create'])->name('expense.create');
Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
Route::delete('/expenses/{id}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

// expense head 
Route::get('/getExpenseHead', [ExpenseHeadController::class, 'getExpenseHead'])->name('expenseHead.get');
Route::get('/create/expense-head', [ExpenseHeadController::class, 'create'])->name('expensehead.create');
Route::post('/expense_head/create', [ExpenseHeadController::class, 'store'])->name('expensehead.store');
Route::get('/expenses_head', [ExpenseHeadController::class, 'index'])->name('expenseshead.index');
Route::delete('/expense_heads/{id}', [ExpenseHeadController::class, 'destroy'])->name('expense_heads.destroy');

// Funds category 
Route::get('/getFundCategory', [FundCategoryController::class, 'getFundsCategory'])->name('fundsCategory.get');
Route::get('/create-fund/category/page',[FundCategoryController::class,'createPage'])->name('fundCategory.create');
Route::get('/',[FundCategoryController::class,'index'])->name('fundsCategory.index');
Route::post('/create/funds/category',[FundCategoryController::class,'store'])->name('fundsCategory.store');


// added fund list
Route::get('/getFunds', [NewFundController::class, 'getFunds'])->name('fundslist.get');
Route::get('/create_fund', [NewFundController::class, 'createPage'])->name('fundCreate.page');
Route::post('/create_fund/create', [NewFundController::class, 'create'])->name('create_fund.create');
Route::get('/allfund', [NewFundController::class, 'index'])->name('fund.index');
Route::delete('/funds/{id}', [NewFundController::class, 'destroy'])->name('funds.destroy');


// OFFICE EXPENSE 
Route::get('/getOfficeExpense', [OfficeExpenseController::class, 'getFunds'])->name('officeExpense.get');
Route::get('/officeExpense/create',[OfficeExpenseController::class,'create'])->name('officeExpense.create');
Route::post('/office_expenses', [OfficeExpenseController::class, 'store'])->name('officeExpense.store');
Route::delete('/officeExpense/{id}', [OfficeExpenseController::class, 'destroy'])->name('officeExpense.destroy');
Route::get('/officeExpense', [OfficeExpenseController::class, 'index'])->name('officeExpense.index');


// Route::get('/report',[OfficeExpenseController::class,'reportPageView'])->name('report.filter');
// Route::get('/expense/report/filter',[OfficeExpenseController::class,'filter'])->name('report.filter');
Route::get('/report/page', [ExpenseReportController::class, 'report'])->name('report.page');
Route::get('/report', [ExpenseReportController::class, 'filter'])->name('report.filter');
Route::get('/report/getExpenseHeads', [ExpenseReportController::class, 'getExpenseHeads'])->name('/report.getExpenseHeads');

Route::get('/officeExpense/{expense_category_id}', [OfficeExpenseController::class, 'getExpenseHeads']);
