<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceItem;

class InvoiceItemController extends Controller
{
    public function deleteInvoiceItem($id){
        InvoiceItem::destroy($id);
    }
}
