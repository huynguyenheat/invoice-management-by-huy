<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function getListInvoice(){
        $invoiceList = Invoice::orderBy('id','DESC')->with('customer')->get();
        return response()->json($invoiceList);
    }

    public function searchInvoice(Request $request){
        $keyword = $request->input('keyword');
        $searchResult = Invoice::with('customer')->where('id', 'LIKE', "%$keyword%")->get();
        return response()->json($searchResult);
    }
}
