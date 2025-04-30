<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Discount;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\ProductVariant;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\returnArgument;



class ProductController extends Controller
{
    // Method to handle displaying products with optional filters
    public function index(Request $request,)
    {
        try {
            // Get query parameters
            $name = $request->query('name');
            $category = $request->query('category');
            $brand = $request->query('brand');
            $type = $request->query('type');

            // Create a base query to fetch products with relationships
            $query = Product::with(['category', 'brand', 'productVariant.productImages']);

            // If 'name' query parameter is provided, filter products by name

            if ($name) {
                $products = $query->where('name', 'like', '%' . $name . '%')->get(); // Use get() for multiple products
                return response()->json($products);
            }

            // If 'category' query parameter is provided, filter by category
            if ($category) {
                $categoryModel = Category::where('category_name', $category)->first(); // Look up category by name
                if ($categoryModel) {
                    $products = $query->where('category_id', $categoryModel->id)->get(); // Use get() for multiple products
                    return response()->json($products);
                } else {
                    return response()->json(['message' => 'Category not found'], 404);
                }
            }

            // If 'brand' query parameter is provided, filter by brand
            if ($brand) {
                $brandModel = Brand::where('brand_name', $brand)->first(); // Look up brand by name
                if ($brandModel) {
                    $products = $query->where('brand_id', $brandModel->id)->get(); // Use get() for multiple products
                    return response()->json($products);
                } else {
                    return response()->json(['message' => 'Brand not found'], 404);
                }
            }
            if ($type) {
                $products = $query->where('productType', $type)->simplePaginate(); // Use get() for multiple products
                return response()->json($products['data']);
            }

            // If no query parameter is provided, paginate the results
            $products = $query->paginate(10); // Paginate the products
            return response()->json($products);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching products.',
                'message' => $e->getMessage(),
            ], 500);
        }
        // return response()->json($name);
    }
    public function show()
    {
        $products = Product::with(['category', 'brand', 'productVariant.productImages'])->paginate(10);
        return response()->json($products);
    }


    // Method to get a specific product by ID
    public function productByProductIdPrductVariantID($id, $productVariantId = null)
    {
        // Check if productId is missing
        if (!$id) {
            return response()->json(['error' => 'Product ID is required'], 400);
        }

        // If variant ID is provided, return variant with product
        if ($productVariantId) {
            $variant = ProductVariant::with([
                'productImages',
                'product.category',
                'product.brand',
                'product.productVariant.discount',
                'discount',
            ])
                ->where('product_id', $id)
                ->where('id', $productVariantId)
                ->first();

            if (!$variant) {
                return response()->json(['error' => 'Product variant not found'], 404);
            }

            return response()->json($variant);
        }

        // Otherwise, return full product data
        $product = Product::with([
            'category',
            'brand',
            'productVariant.discount',
            'productVariant.productImages',
        ])->find($id);


        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    public function update(Request $request, $id)
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

    public function destroy($id)
    {
        try {
            // Find the product
            $product = Product::findOrFail($id);

            // Get all product variants
            $productVariants = $product->productVariant;

            // Delete product images first
            foreach ($productVariants as $variant) {
                foreach ($variant->productImages as $image) {
                    $image->delete();
                }
            }

            // Delete product variants
            foreach ($productVariants as $variant) {
                $variant->delete();
            }

            // Delete the product itself
            $product->delete();

            return response()->json(['message' => 'Product deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function store(Request $request)
    {
        try {
            // Validate
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string'],
                'category_name' => ['required', 'string'],
                'brand_name' => ['required', 'string'],
                'size' => 'required|array|min:1',
                'size.*' => 'in:S,M,L,XL,2XL',
                'price' => ['required', 'numeric'],
                'color' => ['required', 'array'],
                'color.*' => ['required'],
                'stock' => ['required', 'numeric'],
                'discount' => ['nullable', 'string'],
            ]);

            // Check Images
            if (!$request->hasFile('images')) {
                return response()->json(['errors' => ['images' => 'No files uploaded']], 422);
            }

            $files = $request->file('images');
            if (!is_array($files)) {
                $files = [$files];
            }

            $imagePaths = [];

            foreach ($files as $file) {
                if ($file->isValid()) {
                    $path = $file->store('images', 'public');
                    $imagePaths[] = Storage::url($path);
                } else {
                    return response()->json(['errors' => ['images' => 'One or more files failed to upload']], 422);
                }
            }

            // Find Category and Brand
            $category = Category::where('category_name', $validatedData['category_name'])->first();
            $brand = Brand::where('brand_name', $validatedData['brand_name'])->first();

            if (!$category) {
                return response()->json(['errors' => ['category_name' => 'Category not found']], 422);
            }

            if (!$brand) {
                return response()->json(['errors' => ['brand_name' => 'Brand not found']], 422);
            }

            // Optional: Find Discount
            $discountId = null;
            if (!empty($validatedData['discount'])) {
                $discount = Discount::where('discount_name', $validatedData['discount'])->first();
                $discountId = $discount?->id; // PHP 8+ nullsafe operator
            }

            // Save Product
            $product = Product::create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'category_id' => $category->id,
                'brand_id' => $brand->id,
            ]);

            // Save Variants
            foreach ($validatedData['size'] as $size) {
                foreach ($validatedData['color'] as $color) {
                    $productVariant = $product->productVariant()->create([
                        'price' => $validatedData['price'],
                        'stock' => $validatedData['stock'],
                        'discount_id' => $discountId,
                        'size' => $size,
                        'color' => $color,
                    ]);

                    foreach ($imagePaths as $imagePath) {
                        $productVariant->productImages()->create([
                            'images' => $imagePath,
                        ]);
                    }
                }
            }

            return back()->with('success', 'Product created successfully!');
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong!')->withInput();
        }
    }



    public function trendProduct()
    {
        try {
            $products = OrderItems::select('product_variant_id', DB::raw('SUM(quantity) as total_sold'))
                ->groupBy('product_variant_id')
                ->orderByDesc('total_sold')
                ->with('productVariant.product', 'productVariant.productImages', 'productVariant.product.category', 'productVariant.product.brand')
                ->take(10) // get only the top 10
                ->get();

            return response()->json([
                'message' => 'Trending products retrieved successfully',
                'products' => $products,
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    public function searchProduct()
    {
        try {
            $keyword = request('keyword');

            $products = Product::with(['productVariant.productImages', 'category', 'brand'])
                ->where('name', 'like', "%{$keyword}%")
                ->limit(5)
                ->get();

            return response()->json($products);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while searching for products.'], 500);
        }
    }
    public function addToCart()
    {
        try {
            return view("user.add-to-cart");
        } catch (Exception $e) {
            return response()->json([
                'message' => "something went wrong",
                'error' => ($e->getMessage()),
            ]);
        }
    }
    public function addToFavorite()
    {
        try {
            return view("user.add-to-favorite");
        } catch (Exception $e) {
            return response()->json([
                'message' => "something went wrong",
                'error' => ($e->getMessage()),
            ]);
        }
    }
}
