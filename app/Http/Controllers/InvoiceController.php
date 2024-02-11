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
        $newInvoice = Invoice::create($invoice);

        $invoice_items = json_decode($request->invoice_item);
        foreach($invoice_items as $item){
            $invoiceItem['invoice_id'] = $newInvoice->id;
            $invoiceItem['product_id'] = $item->id;
            $invoiceItem['quantity'] = $item->quantity;
            InvoiceItem::create($invoiceItem);
        }
        
    }

    public function getInvoiceId($id){
        $invoice = Invoice::with(['customer','invoice_items.product'])->find($id);
        return response()->json(['invoice'=>$invoice],200);
    }

    public function getExistInvoice($id){
        $invoice = Invoice::with(['customer','invoice_items.product'])->find($id);
        return response()->json(['invoice'=>$invoice],200);
    }

    public function updateInvoice(Request $request, $id){
        $invoice = Invoice::where('id',$id)->first();
        $invoice->customer_id = $request->customer_id;
        $invoice->date= $request->date;
        $invoice->due_date = $request->due_date;
        $invoice->number = $request->number;
        $invoice->reference = $request->reference;
        $invoice->terms = $request->terms;
        $invoice->sub_total = $request->sub_total;
        $invoice->discount = $request->discount;
        $invoice->total = $request->total;
        $invoice->update($request->all());

        
        $invoice_items = json_decode($request->input("invoice_item"));
        $invoice->invoice_items()->delete();
        foreach($invoice_items as $item){
            $invoiceItem['invoice_id'] = $invoice->id;
            $invoiceItem['product_id'] = $item->product_id;
            $invoiceItem['quantity'] = $item->quantity;
            InvoiceItem::create($invoiceItem);
        }
        
    }
}
