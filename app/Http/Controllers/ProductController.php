<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\ProductVariant;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Method to handle displaying products with optional filters
    public function index(Request $request,)
    {
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
        // return response()->json($name);
    }
    public function show()
    {
        $products = Product::with(['category', 'brand', 'productVariant.productImages'])->paginate(10);
        return response()->json($products);
    }


    // Method to get a specific product by ID
    public function productById($id)
    {
        // Find product by ID, throw an exception if not found
        $product = Product::with(['category', 'brand', 'productVariant.discount', 'productVariant.productImages',])
            ->findOrFail($id);
        // Return the product as a JSON response
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
            // Validate the input data
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string'],
                'category_name' => ['required', 'string'],
                'brand_name' => ['required', 'string'],
                'size' => ['required', 'string'],
                'price' => ['required', 'numeric'],
                'color' => ['required', 'string'],
                'stock' => ['required', 'integer'],
            ]);

            // Check if the 'images' file is provided
            if (!$request->hasFile('images')) {
                return response()->json([
                    'error' => 'No files uploaded',
                ], 400);
            }

            // Retrieve the uploaded files
            $files = $request->file('images');

            // Ensure files are an array
            if (!is_array($files)) {
                $files = [$files];  // Convert single file to array
            }

            // Initialize an array to store file paths
            $imagePaths = [];

            // Process the uploaded files
            foreach ($files as $file) {
                if ($file->isValid()) {
                    // Store file and get the path
                    $path = $file->store('product_images', 'public');
                    $imagePaths[] = Storage::url($path);  // Return full URL for stored file
                } else {
                    return response()->json([
                        'error' => 'File upload failed for one or more files.',
                        'file' => $file->getClientOriginalName(),
                    ], 400);
                }
            }

            // Find Category and Brand by Name
            $category = Category::where('category_name', $validatedData['category_name'])->first();
            $brand = Brand::where('brand_name', $validatedData['brand_name'])->first();

            // Check if Category and Brand exist
            if (!$category) {
                return response()->json(['error' => 'Category not found'], 404);
            }
            if (!$brand) {
                return response()->json(['error' => 'Brand not found'], 404);
            }

            // Save the product
            $product = Product::create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'category_id' => $category->id,
                'brand_id' => $brand->id,
            ]);

            // Create the product variant
            $productVariant = $product->productVariant()->create([
                'price' => $validatedData['price'],
                'stock' => $validatedData['stock'],
                'size' => $validatedData['size'],
                'color' => $validatedData['color'],
            ]);

            // Handle image uploads and store images in the database
            foreach ($imagePaths as $imagePath) {
                // Save image URL to database
                $productVariant->productImages()->create([
                    'images' => $imagePath,
                ]);
            }

            return response()->json([
                'message' => 'Product created successfully',
                'product' => $product,
                'images' => $imagePaths,
            ], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    public function trendProduct()
    {
        try {
            $products = OrderItems::select('product_variant_id', DB::raw('SUM(quantity) as total_sold'))
                ->groupBy('product_variant_id')
                ->orderByDesc('total_sold')
                ->with('productVariant.product', 'productVariant.productImages')
                ->take(70) // get only the top 10
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
}
