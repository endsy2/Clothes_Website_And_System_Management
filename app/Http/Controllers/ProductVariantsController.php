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
        return view('admin.insert-product-variant', compact('products', 'discounts'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // ✅ Validate request first!
            $validatedData = $request->validate([
                'price' => 'nullable|numeric',
                'stock' => 'nullable|integer|min:0',
                'color' => 'nullable|string',
                'size' => 'nullable|string',
                'image' => 'nullable|array',
                'image.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048', // validate each file
            ]);

            // ✅ Find the variant
            $variant = ProductVariant::findOrFail($id);

            // ✅ Update basic fields
            $variant->price = $validatedData['price'] ?? $variant->price;
            $variant->stock = $validatedData['stock'] ?? $variant->stock;
            $variant->color = $validatedData['color'] ?? $variant->color;
            $variant->size = $validatedData['size'] ?? $variant->size;
            $variant->save(); // 🟢 Don't forget to save the changes

            // ✅ Handle multiple images
            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $image) {
                    if (!$image->isValid()) {
                        throw new \Exception("Invalid image upload: " . $image->getClientOriginalName());
                    }

                    $storedPath = $image->store('productImage', 'public'); // storage/app/public/productImage
                    $variant->productImages()->create(['images' => $storedPath]);
                }
            }

            return redirect()->back()->with('success', 'Product variant(s) updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $id = $request->input('id');
            if (!$id) {
                return response()->json(['message' => 'Product variant ID is required.'], 400);
            }

            $productImage = ProductImage::where('product_variant_id', $id)->get();
            foreach ($productImage as $image) {
                $image->delete();
            }

            $productVariant = ProductVariant::findOrFail($id);
            $productVariant->delete();

            return response()->json(['message' => 'Product variant deleted successfully!']);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}
