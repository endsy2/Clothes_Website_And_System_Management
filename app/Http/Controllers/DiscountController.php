<?php

namespace App\Http\Controllers;

use App\Models\Discount;

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
        $products_variant_row = ProductVariant::whereNotNull('discount_id')
            ->with(['product', 'image', 'discount']) // Eager load relationships
            ->paginate(10);

        return response()->json($products_variant_row);
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
