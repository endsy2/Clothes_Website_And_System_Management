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
            // Validate request data
            $validatedData = $request->validate([
                'name' => 'required|string',
                'description' => 'nullable|string',
                'category_name' => 'required|exists:categories,category_name',
                'brand_name' => 'required|exists:brands,brand_name',
                'discount_name' => 'nullable|exists:discounts,discount_name',
                'price' => 'nullable|numeric',
                'stock' => 'nullable|integer|min:0',
                'color' => 'nullable|string',
                'size' => 'nullable|string',
                'product_id' => 'nullable|exists:products,id'
            ]);

            // Find related rows using name instead of ID
            $categoryRow = Category::where('category_name', $request->input('category_name'))->firstOrFail();
            $brandRow = Brand::where('brand_name', $request->input('brand_name'))->firstOrFail();
            $discountRow = Discount::where('discount_name', $request->input('discount_name'))->first();

            // Debug to check if discount exists
            if (!$discountRow) {
                return response()->json(['error' => 'Discount not found!'], 400);
            }

            // Prepare product data
            $productData = [
                'name' => $request->input('name'),
                'description' => $request->input('description', ''),
                'category_id' => $categoryRow->id,
                'brand_id' => $brandRow->id
            ];

            // Update product
            $product = Product::findOrFail($id);
            $product->update($productData);

            // Find product variants
            $productVariantQuery = ProductVariant::where('product_id', $id);

            if ($request->filled('color') && $request->filled('size')) {
                $productVariantQuery->where('color', $request->input('color'))
                    ->where('size', $request->input('size'));
            } elseif ($request->filled('color')) {
                $productVariantQuery->where('color', $request->input('color'));
            } elseif ($request->filled('size')) {
                $productVariantQuery->where('size', $request->input('size'));
            }

            $productVariants = $productVariantQuery->get();

            // Debug: Check if product variants exist
            if ($productVariants->isEmpty()) {
                return response()->json(['error' => 'No matching product variants found'], 400);
            }

            // Update each variant
            foreach ($productVariants as $productVariant) {
                $productVariant->update([
                    'discount_id' => optional($discountRow)->id, // ✅ Ensures no null errors
                    'price' => $request->input('price', 0),
                    'stock' => $request->input('stock', 0),
                ]);
            }

            return response()->json([
                'message' => 'Product updated successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
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
