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
    public function displayProductType()
    {
        return view('admin.insertProductType');
    }
    public function store(Request $request)
    {
        try {
            $validateDate = $request->validate([
                'product_type_name' => ['required', 'string', 'required']
            ]);

            $productType = ProductType::create([
                'product_type_name' => $validateDate['product_type_name']
            ]);
            return redirect()->back()->with('success', 'Product Type created successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Product Type name already exists');
        }
    }
}
