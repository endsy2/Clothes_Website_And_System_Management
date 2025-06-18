<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Exception;
use Illuminate\Container\Attributes\Log as AttributesLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use function Illuminate\Log\log;

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

            Log::info('Validation passed', $validatedData);

            DB::beginTransaction();

            // Ensure 'public/images' exists
            if (!file_exists(public_path('images'))) {
                mkdir(public_path('images'), 0755, true);
            }

            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                if (!$image->isValid()) {
                    throw new Exception("Invalid image upload: " . $image->getClientOriginalName());
                }

                // Move uploaded image to public/images with unique name
                $filename = uniqid() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images'), $filename);
                $relativePath = 'images/' . $filename;

                $imagePaths[] = $relativePath;
                Log::info("Image stored: {$relativePath}");
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
            Log::info("Updating product variant with ID: {$id}");
            Log::info("Request data:", $request->all());
            Log::info('Request file: ' . json_encode($request->file('images')));

            $variant = ProductVariant::find($id);
            if (!$variant) {
                Log::error("Variant not found with ID: {$id}");
                return redirect()->back()->with('error', "Product variant with ID {$id} not found.");
            }

            $request->validate([
                'price' => 'nullable|numeric|min:0',
                'stock' => 'nullable|integer|min:0',
                'color' => 'nullable|string',
                'size' => 'nullable|string',
                'discount_id' => 'nullable|exists:discounts,id',
                'images' => 'nullable|array',
                'images.*' => 'file|image|max:10240', // 10MB max size
            ]);

            $variant->fill($request->only(['price', 'stock', 'color', 'size', 'discount_id']));
            $variant->save();
            Log::info("Updated variant data:", $variant->toArray());

            if ($request->hasFile('images')) {
                // Delete old images
                foreach ($variant->productImages as $oldImage) {
                    $oldImagePath = public_path($oldImage->images);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                    $oldImage->delete();
                }

                // Save new images to public/images
                foreach ($request->file('images') as $image) {
                    $filename = uniqid() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('images'), $filename);
                    $relativePath = 'images/' . $filename;

                    Log::info("Image saved to: {$relativePath}");
                    $variant->productImages()->create(['images' => $relativePath]);
                }
            }

            Log::info("Product variant with ID {$id} updated successfully.");
            return redirect()->back()->with('success', 'Product variant updated successfully!');
        } catch (Exception $e) {
            Log::error("Error updating product variant: " . $e->getMessage());
            return redirect()->back()->with('error
', 'An error occurred while updating the product variant: ' . $e->getMessage());
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
