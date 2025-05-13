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
            // Validate only product variant related fields
            $validatedData = $request->validate([
                'discount_name' => 'nullable|exists:discounts,discount_name',
                'price' => 'nullable|numeric',
                'stock' => 'nullable|integer|min:0',
                'color' => 'nullable|string',
                'size' => 'nullable|string',
            ]);

            // Get discount row if discount_name is provided
            $discountRow = null;
            if ($request->filled('discount_name')) {
                $discountRow = Discount::where('discount_name', $request->input('discount_name'))->first();
                if (!$discountRow) {
                    return redirect()->back()->withErrors(['discount_name' => 'Discount not found!'])->withInput();
                }
            }

            // Build query for product variant
            $productVariantQuery = ProductVariant::where('product_id', $id);

            if ($request->filled('color')) {
                $productVariantQuery->where('color', $request->input('color'));
            }

            if ($request->filled('size')) {
                $productVariantQuery->where('size', $request->input('size'));
            }

            $productVariants = $productVariantQuery->get();

            if ($productVariants->isEmpty()) {
                return redirect()->back()->withErrors(['variant' => 'No matching product variants found'])->withInput();
            }

            // Update all matching product variants
            foreach ($productVariants as $variant) {
                $variant->update([
                    'discount_id' => optional($discountRow)->id,
                    'price' => $request->input('price', $variant->price),
                    'stock' => $request->input('stock', $variant->stock),
                ]);
            }

            return redirect()->back()->with('success', 'Product variant(s) updated successfully!');
            // return $id;
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
