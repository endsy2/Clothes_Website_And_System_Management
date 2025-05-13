<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    public function show()
    {
        $productTypes = ProductType::get();
        return response()->json($productTypes);
    }
}
