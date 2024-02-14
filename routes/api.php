<?php

use App\Http\Controllers\AuthController;
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
use App\Http\Controllers\InvoiceItemController;
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register/',[AuthController::class,'register']);
Route::post('/login/',[AuthController::class,'login']);

Route::get('/invoice/',[InvoiceController::class,'getListInvoice']);
Route::get('/invoice/search/',[InvoiceController::class,'searchInvoice']);
Route::get('/customer/list/',[CustomerController::class,'getListCustomer']);
Route::get('/invoice/lastinvoice/',[InvoiceController::class,'getLastInvoice']);
Route::get('/product/list/',[ProductController::class,'getListProduct']);

Route::group(['middleware'=>['auth:sanctum']], function(){
    Route::post('/logout/',[AuthController::class,'logout']);
    Route::post('/invoice/addnew/post/',[InvoiceController::class,'addNewInvoice']);
});

Route::get('/invoice/detail/{id}',[InvoiceController::class,'getInvoiceId']);
Route::get('/invoice/getexist/{id}',[InvoiceController::class,'getExistInvoice']);
Route::get('/invoiceitem/delete/{id}',[InvoiceItemController::class,'deleteInvoiceItem']);
Route::post('/invoice/edit/{id}',[InvoiceController::class,'updateInvoice']);
Route::get('/invoice/delete/{id}',[InvoiceController::class,'deleteInvoice']);
