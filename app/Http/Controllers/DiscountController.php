<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get paginated product variants with related product, image, and discount
        $products_variant_row = ProductVariant::whereNotNull('discount_id')->paginate(10);

        // Collect product IDs from the paginated product variants
        $product_ids = $products_variant_row->pluck('product_id')->unique()->toArray();

        // Fetch products using the product_ids
        $products = Product::with('brand', 'category', 'productVariant.productImages', 'productVariant.discount')
            ->whereIn('id', $product_ids)
            ->get(); // Use `get()` instead of `findOrFail()` to handle multiple products

        // Return the products as a JSON response
        return response()->json($products);
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
