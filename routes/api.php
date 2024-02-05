<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
use App\Http\Controllers\ProductController;
use \App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CustomerController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/invoice/',[InvoiceController::class,'getListInvoice']);
Route::get('/invoice/search/',[InvoiceController::class,'searchInvoice']);
Route::get('/customer/list/',[CustomerController::class,'getListCustomer']);
Route::get('/invoice/lastinvoice/',[InvoiceController::class,'getLastInvoice']);
Route::get('/product/list/',[ProductController::class,'getListProduct']);
Route::post('/invoice/addnew/post/',[InvoiceController::class,'addNewInvoice']);
