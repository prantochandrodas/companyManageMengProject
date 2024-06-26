<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseHeadController;
use App\Http\Controllers\ExpenseReportController;
use App\Http\Controllers\FundCategoryController;
use App\Http\Controllers\IncomeCategoryController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\IncomeHeadController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\NewFundController;
use App\Http\Controllers\OfficeExpenseController;
use App\Http\Controllers\PermisionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Models\IncomeHead;
use App\Models\OfficeExpense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

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
Route::get('/fundCategory/create', [FundCategoryController::class, 'createPage'])->name('fundCategory.create');
Route::get('/', [FundCategoryController::class, 'index'])->name('fundsCategory.index');
// Route::post('/fundCategory/store', [FundCategoryController::class, 'store'])->name('fundsCategory.store');


// added fund list
Route::get('/getFunds', [NewFundController::class, 'getFunds'])->name('fundslist.get');
Route::get('/create_fund', [NewFundController::class, 'createPage'])->name('fundCreate.page');
Route::post('/create_fund/create', [NewFundController::class, 'create'])->name('create_fund.create');
Route::get('/allfund', [NewFundController::class, 'index'])->name('fund.index');
Route::delete('/funds/{id}', [NewFundController::class, 'destroy'])->name('funds.destroy');


// OFFICE EXPENSE 
Route::get('/getOfficeExpense', [OfficeExpenseController::class, 'getFunds'])->name('officeExpense.get');
Route::get('/officeExpense/create', [OfficeExpenseController::class, 'createPage'])->name('officeExpense.create');
Route::post('/office_expenses', [OfficeExpenseController::class, 'store'])->name('officeExpense.store');
Route::delete('/officeExpense/{id}', [OfficeExpenseController::class, 'distroy'])->name('officeExpense.distroy');
Route::get('/officeExpense', [OfficeExpenseController::class, 'indexPage'])->name('officeExpense.index');
Route::get('/officeExpenseView/{officeExpense}', [OfficeExpenseController::class, 'view'])->name('officeExpense.view');
Route::get('/office-expense/print/{id}', [OfficeExpenseController::class, 'print'])->name('officeExpense.print');
Route::get('/office-expense/pdf/{id}', [OfficeExpenseController::class, 'pdf'])->name('officeExpense.pdf');



Route::get('/report/page', [ExpenseReportController::class, 'report'])->name('report.page');
Route::get('/report/getReport', [ExpenseReportController::class, 'getReport'])->name('report.getReport');
Route::get('/report', [ExpenseReportController::class, 'filter'])->name('report.filter');
Route::get('/report/getExpenseHeads', [ExpenseReportController::class, 'getExpenseHeads'])->name('/report.getExpenseHeads');
Route::get('/officeExpense/{expense_category_id}', [OfficeExpenseController::class, 'getExpenseHeads']);



// income 
Route::get('/incomeCategory', [IncomeCategoryController::class, 'index'])->name('incomeCategory.index');
Route::get('/incomeCategory/getIncomeCategory', [IncomeCategoryController::class, 'getIncomeCategory'])->name('incomeCategory.getIncomeCategory');
Route::get('/incomeCategory/create', [IncomeCategoryController::class, 'create'])->name('incomeCategory.create');
Route::post('/incomeCategory/store', [IncomeCategoryController::class, 'store'])->name('incomeCategory.store');
Route::delete('/incomeCategory/distroy/{id}', [IncomeCategoryController::class, 'distroy'])->name('incomeCategory.distroy');


Route::get('/incomeHead', [IncomeHeadController::class, 'index'])->name('incomeHead.index');
Route::get('/incomeHead/getIncomeHead', [IncomeHeadController::class, 'getIncomeHead'])->name('incomeHead.getIncomeHead');
Route::get('/incomeHead/create', [IncomeHeadController::class, 'create'])->name('incomeHead.create');
Route::post('/incomeHead/store', [IncomeHeadController::class, 'store'])->name('incomeHead.store');
Route::delete('/incomeHead/distroy/{id}', [IncomeHeadController::class, 'distroy'])->name('incomeHead.distroy');


Route::get('/income', [IncomeController::class, 'index'])->name('income.index');
Route::get('/income/getincome', [IncomeController::class, 'getincome'])->name('income.getincome');
Route::get('/income/incomeHead/{id}', [IncomeController::class, 'getIncomeHeads'])->name('income.getIncomeHead');
Route::get('/income/create', [IncomeController::class, 'create'])->name('income.create');
Route::post('/income/store', [IncomeController::class, 'store'])->name('income.store');
Route::delete('/income/distroy/{id}', [IncomeController::class, 'distroy'])->name('income.distroy');
Route::get('/income/pdf/{id}', [IncomeController::class, 'pdf'])->name('income.pdf');
Route::get('/income/print/{id}', [IncomeController::class, 'print'])->name('income.print');
Route::get('/income/view/{id}', [IncomeController::class, 'view'])->name('income.view');
Route::get('/income/report', [IncomeController::class, 'report'])->name('income.report');
Route::get('/income/filter', [IncomeController::class, 'filter'])->name('income.filter');
Route::get('/report/getIncomeHeads', [IncomeController::class, 'getIncomeHeads'])->name('/report.getIncomeHeads');
Route::get('/income/import', [IncomeController::class, 'import'])->name('income.import');
Route::post('/income/StoreExcel', [IncomeController::class, 'StoreExcel'])->name('income.StoreExcel');


Route::get('/ledger/index', [LedgerController::class, 'index'])->name('ledger.index');
Route::get('/ledger/view', [LedgerController::class, 'view'])->name('ledger.view');
Route::get('/ledger/pdf', [LedgerController::class, 'pdf'])->name('ledger.pdf');
Route::get('/ledger/print', [LedgerController::class, 'print'])->name('ledger.print');

Route::get('/ledger/export', [LedgerController::class, 'export'])->name('ledger.export');


Route::get('/permissions', [PermisionController::class, 'index'])->name('permissions.index');
Route::get('permissions/create', [PermisionController::class, 'create'])->name('permissions.create');
Route::get('permissions/edit/{id}', [PermisionController::class, 'edit'])->name('permissions.edit');
Route::post('permissions/store', [PermisionController::class, 'store'])->name('permissions.store');
Route::put('permissions/update/{id}', [PermisionController::class, 'update'])->name('permissions.update');
Route::delete('permissions/distroy/{id}', [PermisionController::class, 'distroy'])->name('permissions.distroy');

// Roles 

Route::get('role', [RoleController::class, 'index'])->name('role.index');
Route::get('role/create', [RoleController::class, 'create'])->name('role.create');
Route::get('role/edit/{id}', [RoleController::class, 'edit'])->name('role.edit');
Route::post('role/store', [RoleController::class, 'store'])->name('role.store');
Route::put('role/update/{id}', [RoleController::class, 'update'])->name('role.update');
Route::delete('role/distroy/{id}', [RoleController::class, 'distroy'])->name('role.distroy');
Route::get('role/add-permission/{id}', [RoleController::class, 'addPermissionToRole'])->name('role.add-permission');
Route::put('role/give-permission/{id}', [RoleController::class, 'givePermissionToRole'])->name('role.give-permission');


// Route::group('middleware'=>['']);

Route::get('user', [UserController::class, 'indexPage'])->name('user.index');
Route::get('user/create', [UserController::class, 'create'])->name('user.create');
Route::post('user/store', [UserController::class, 'store'])->name('user.store');
Route::delete('user/distroy/{id}', [UserController::class, 'distroy'])->name('user.distroy');
Route::get('user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
Route::put('user/update/{id}', [UserController::class, 'update'])->name('user.update');

// Authentication Routes...
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
