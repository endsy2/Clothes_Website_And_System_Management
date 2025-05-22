<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;


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
        try {
            Log::info('Incoming product variant request', $request->except('images'));

            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'discount_id' => 'nullable|exists:discounts,id',
                'price' => 'required|numeric',
                'stock' => 'required|integer|min:0',
                'color' => 'required|array|min:1',
                'color.*' => 'required|string',
                'size' => 'required|array|min:1',
                'size.*' => 'required|string',
                'images' => ['required', 'array', 'min:1'],
                'images.*' => ['file', 'image', 'max:10240'],
            ]);
            // dd($validatedData);

            Log::info('Validation passed', $validatedData);

            DB::beginTransaction();

            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                if (!$image->isValid()) {
                    throw new Exception("Invalid image upload: " . $image->getClientOriginalName());
                }
                $storedPath = $image->store('images', 'public');
                $imagePaths[] = $storedPath;
                Log::info("Image stored: {$storedPath}");
            }

            foreach ($validatedData['color'] as $color) {
                foreach ($validatedData['size'] as $size) {
                    Log::info("Creating variant: color={$color}, size={$size}");

                    $productVariant = ProductVariant::create([
                        'product_id' => $validatedData['product_id'],
                        'discount_id' => $validatedData['discount_id'] ?? null,
                        'price' => $validatedData['price'],
                        'stock' => $validatedData['stock'],
                        'color' => $color,
                        'size' => $size,
                    ]);

                    if (!$productVariant) {
                        throw new Exception("Failed to create variant for {$color}/{$size}");
                    }

                    // Fix here: use 'images' key as per your migration
                    foreach ($imagePaths as $path) {
                        $productVariant->productImages()->create(['images' => $path]);
                        Log::info("Image linked to variant ID {$productVariant->id}: {$path}");
                    }

                    Log::info("Variant created: ID={$productVariant->id}");
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'All product variants created successfully!');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Product variant creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }



    /**
     * Display the specified resource.
     */
    public function show()
    {
        $products = Product::get()->toArray();
        $discounts = Discount::all()->toArray();
        return view('admin.insertProductVariant', compact('products', 'discounts'));
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
        try {
            $productImage = ProductImage::where('product_variant_id', $id)->get();
            if ($productImage) {
                foreach ($productImage as $image) {
                    $image->delete();
                }
            }
            $productVariant = ProductVariant::findOrFail($id);
            $productVariant->delete();

            return redirect()->back()->with('success', 'Product variant deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
