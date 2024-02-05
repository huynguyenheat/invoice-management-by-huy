<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
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

    public function getLastInvoice(){
        $invoiceLast = Invoice::orderBy('id','DESC')->first();
        return response()->json($invoiceLast);
    }

    public function addNewInvoice(Request $request){
        $invoice['customer_id'] = $request->customer_id;
        $invoice['date'] = $request->date;
        $invoice['due_date'] = $request->due_date;
        $invoice['number'] = $request->number;
        $invoice['reference'] = $request->reference;
        $invoice['terms'] = $request->terms;
        $invoice['sub_total'] = $request->sub_total;
        $invoice['discount'] = $request->discount;
        $invoice['total'] = $request->total;
        Invoice::create($invoice);

        $invoice_item = json_decode($request->invoice_item);
        foreach($invoice_item as $item){
            $invoiceItem['invoice_id'] = $item->invoice_id;
            $invoiceItem['product_id'] = $item->product_id;
            $invoiceItem['quantity'] = $item->quantity;
            InvoiceItem::create($invoiceItem);
        }
        
    }
}
