<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getListProduct(){
        $data = Product::orderBy('id', 'ASC')->get();
        return response()->json($data);
    }
}
