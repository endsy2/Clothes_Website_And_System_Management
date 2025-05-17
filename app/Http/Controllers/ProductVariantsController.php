<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use App\Models\ProductVariant;
use Exception;
use Illuminate\Http\Request;

class ProductVariantsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        try {
            $validatedData = $request->validate([
                // 'discount_name' => 'nullable|exists:discounts,discount_name',
                'price' => 'nullable|numeric',
                'stock' => 'nullable|integer|min:0',
                'color' => 'nullable|string',
                'size' => 'nullable|string',
            ]);

            // Get the discount row if present
            $discountRow = $request->filled('discount_name')
                ? Discount::where('discount_name', $request->discount_name)->first()
                : null;

            // Build the query to find product variants
            $productVariantQuery = ProductVariant::where('product_id', $id);

            if ($request->filled('color')) {
                $productVariantQuery->where('color', $request->color);
            }

            if ($request->filled('size')) {
                $productVariantQuery->where('size', $request->size);
            }

            $productVariants = $productVariantQuery->get();

            if ($productVariants->isEmpty()) {
                return redirect()->back()->withErrors(['variant' => 'No matching product variants found'])->withInput();
            }

            foreach ($productVariants as $variant) {
                $variant->update([
                    'discount_id' => optional($discountRow)->id,
                    'price' => $request->filled('price') ? $request->price : $variant->price,
                    'stock' => $request->filled('stock') ? $request->stock : $variant->stock,
                ]);
            }

            return redirect()->back()->with('success', 'Product variant(s) updated successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
