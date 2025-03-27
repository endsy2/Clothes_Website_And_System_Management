<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Method to handle displaying products with optional filters
    public function index(Request $request,)
    {
        // Get query parameters
        $name = $request->query('name');
        $category = $request->query('category');
        $brand = $request->query('brand');


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

        // If no query parameter is provided, paginate the results
        $products = $query->paginate(10); // Paginate the products
        return response()->json($products);
    }


    // Method to get a specific product by ID
    public function productById($id)
    {
        // Find product by ID, throw an exception if not found
        $product = Product::with(['category', 'brand', 'productVariant.productImages'])
            ->findOrFail($id);
        // Return the product as a JSON response
        return response()->json($product);
    }
    public function update(Request $request, $id) {}
    public function destroy($id) {}
    public function store(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category_name' => ['required', 'string'],
            'brand_name' => ['required', 'string'],
            'product_images' => ['required', 'array'],
            'product_images.*' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'size' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'color' => ['required', 'string'],
            'stock' => ['required', 'integer'],
        ]);

        // Find Category and Brand by Name
        $category = Category::where('category_name', $request->input('category_name'))->first();
        $brand = Brand::where('brand_name', $request->input('brand_name'))->first();

        // Check if Category and Brand exist
        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        if (!$brand) {
            return response()->json(['error' => 'Brand not found'], 404);
        }

        // Save the product to the database
        $product = Product::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'category_id' => $category->id, // Store category_id, not category_name
            'brand_id' => $brand->id, // Store brand_id, not brand_name
        ]);

        // Create the product variant
        $product_variant = $product->productVariant()->create([
            'price' => $validatedData['price'],
            'stock' => $validatedData['stock'],
            'size' => $validatedData['size'], // Save size as a JSON string
            'color' => $validatedData['color'] // Save color as a JSON string
        ]);

        // Handle the image uploads
        foreach ($validatedData['product_images'] as $image) {
            $imagePath = $image->store('product_images', 'public'); // Save image to 'product_images' folder
            $product_variant->productImages()->create([
                'image_url' => $imagePath,
            ]);
        }

        // Return a success response with product details
        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201); // 201 = Created
    }
}
