<?php

use App\Http\Controllers\FundCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/fundCategory/store', [FundCategoryController::class, 'store'])->name('fundsCategory.store');
// Route::get('/fundsCategory', [FundCategoryController::class, 'index'])->name('fundsCategory.index');