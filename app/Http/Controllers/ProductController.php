<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Discount;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\ProductVariant;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\returnArgument;



class ProductController extends Controller
{
    // Method to handle displaying products with optional filters
    public function index(Request $request,)
    {
        try {
            // Get query parameters
            $type = $request->query('type');
            // $category = $request->query('category');
            // $brand = $request->query('brand');
            // $type = $request->query('type');

            // Create a base query to fetch products with relationships
            $query = Product::with(['category', 'brand', 'productVariant.productImages']);

            // If 'name' query parameter is provided, filter products by name

            if ($type == "name") {
                $products = $query->where('name', 'like', '%' . $name . '%')->get(); // Use get() for multiple products
                return response()->json($products);
            }

            // If 'category' query parameter is provided, filter by category
            if ($type == "category") {
                $categoryModel = Category::where('category_name', $category)->first(); // Look up category by name
                if ($categoryModel) {
                    $products = $query->where('category_id', $categoryModel->id)->get(); // Use get() for multiple products
                    return response()->json($products);
                } else {
                    return response()->json(['message' => 'Category not found'], 404);
                }
            }

            // If 'brand' query parameter is provided, filter by brand
            if ($type == "brand") {
                $brandModel = Brand::where('brand_name', $brand)->first(); // Look up brand by name
                if ($brandModel) {
                    $products = $query->where('brand_id', $brandModel->id)->get(); // Use get() for multiple products
                    return response()->json($products);
                } else {
                    return response()->json(['message' => 'Brand not found'], 404);
                }
            }
            if ($type == "productType") {
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
                'product.productType',
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
            'productType',
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
            $validateData = $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'category_name' => 'required|string',
                'brand_name' => 'required|string',
                'product_type_name' => 'required|string'
            ]);

            $category = Category::where('category_name', $validateData['category_name'])->first();
            $brand = Brand::where('brand_name', $validateData['brand_name'])->first();
            $product_type = ProductType::where('product_type_name', $validateData['product_type_name'])->first();

            if (!$category) {
                return redirect()->back()->withErrors(['category_name' => 'Category not found'])->withInput();
            }
            if (!$brand) {
                return redirect()->back()->withErrors(['brand_name' => 'Brand not found'])->withInput();
            }
            if (!$product_type) {
                return redirect()->back()->withErrors(['product_type_name' => 'Product Type not found'])->withInput();
            }

            $productData = [
                'name' => $validateData['name'],
                'description' => $validateData['description'],
                'product_type_id' => $product_type->id,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
            ];

            $product = Product::findOrFail($id);
            $product->update($productData);

            return redirect()->back()->with('success', 'Product updated successfully!');
        } catch (Exception $exception) {
            return redirect()->back()->with('error', 'An error occurred: ' . $exception->getMessage());
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
            // Validate inputs
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string'],
                'category_id' => ['required', 'numeric'],
                'brand_id' => ['required', 'numeric'],
                'product_type_id' => ['required', 'numeric'],
                'size' => 'required|array|min:1',
                'size.*' => 'in:S,M,L,XL,2XL',
                'price' => ['required', 'numeric'],
                'color' => ['required', 'array', 'min:1'],
                'color.*' => ['required', 'string'],
                'stock' => ['required', 'numeric'],
                'discount_id' => ['nullable', 'numeric'],
                'images' => ['required', 'array', 'min:1'],
                'images.*' => ['file', 'image', 'max:10240'], // max 10MB per image
            ]);
            // dd($validatedData['product_type_id']);
            // Get files from request
            $files = $request->file('images');

            // Store images and get URLs
            $imagePaths = [];
            foreach ($files as $file) {
                if ($file->isValid()) {
                    // Save in 'public/images' folder
                    $imagePaths[] = $file->store('images', 'public');
                    // $imagePaths[] = Storage::url($path);
                } else {
                    return back()->withErrors(['images' => 'One or more files failed to upload'])->withInput();
                }
            }


            // Create Product
            $product = Product::create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'price' => $validatedData['price'],
                'category_id' => $validatedData['category_id'],
                'brand_id' => $validatedData['brand_id'],
                'product_type_id' => $validatedData['product_type_id'], // ✅ This is what was missing
            ]);



            // Create Product Variants and associate images
            foreach ($validatedData['size'] as $size) {
                foreach ($validatedData['color'] as $color) {
                    $productVariant = $product->productVariant()->create([
                        'price' => $validatedData['price'],
                        'stock' => $validatedData['stock'],
                        'discount_id' => $validatedData['discount_id'],
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

            return redirect()->back()->with('success', 'Product created successfully!');
        } catch (Exception $e) {
            // Log the error for debugging if you want
            \Log::error('Error creating product: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong!')->withInput();
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
    public function deleteOne(Request $request)
    {
        $id = $request->input('id');

        // Retrieve the product and its relationships (product variants, product images, and order items)
        $product = Product::with([
            'productVariant.productImages',
            'productVariant.orderItems',
            'productVariant.orderItems.order'
        ])->find($id);

        // Check if the product exists
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        try {
            // Begin the transaction
            DB::beginTransaction();

            // Log the product being deleted
            Log::info("Attempting to delete product with ID: $id", ['product' => $product]);

            // Loop through each product variant and delete associated data
            foreach ($product->productVariant as $variant) {
                // Delete associated order items
                foreach ($variant->orderItems as $orderItem) {
                    Log::info("Deleting order item ID: {$orderItem->id}");
                    $orderItem->delete();
                }

                // Delete associated product images
                foreach ($variant->productImages as $image) {
                    Log::info("Deleting product image ID: {$image->id}");
                    $image->delete();
                }

                // If variant has associated orders, delete them as well
                foreach ($variant->orderItems as $orderItem) {
                    if ($orderItem->order) {
                        Log::info("Deleting order ID: {$orderItem->order->id}");
                        $orderItem->order->delete();
                    }
                }

                // Delete the variant itself
                Log::info("Deleting product variant ID: {$variant->id}");
                $variant->delete();
            }

            // Finally, delete the product
            Log::info("Deleting product ID: $id");
            $product->delete();

            // Commit the transaction
            DB::commit();

            // Return a success response
            return response()->json([
                'message' => 'Product deleted successfully'
            ], 200);
        } catch (Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            // Log the error for debugging
            Log::error("Error deleting product with ID: $id", ['error' => $e->getMessage()]);

            // Return a failure response
            return response()->json([
                'message' => 'Failed to delete product',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function deleteMany(Request $request)
    {
        $ids = $request->input('ids');

        // Attempt to find the products with the given IDs
        $products = Product::with(['productVariant.productImages', 'productVariant.orderItems', 'productVariant.orderItems.order'])
            ->whereIn('id', $ids)
            ->get();

        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products found'], 404);
        }

        try {
            // Begin transaction to ensure data integrity
            DB::beginTransaction();

            foreach ($products as $product) {
                // Delete related records for each product
                foreach ($product->productVariant as $variant) {
                    // Delete order items and their associated orders
                    foreach ($variant->orderItems as $orderItem) {
                        // Log order deletion
                        if ($orderItem->order) {
                            Log::info("Deleting order ID: {$orderItem->order->id}");
                            $orderItem->order->delete(); // Delete order first
                        }

                        // Log::info("Deleting order item ID: {$orderItem->id}");
                        $orderItem->delete(); // Delete order item
                    }

                    // Delete associated product images
                    foreach ($variant->productImages as $image) {
                        Log::info("Deleting product image ID: {$image->id}");
                        $image->delete(); // Delete image
                    }

                    // Delete the variant itself
                    Log::info("Deleting product variant ID: {$variant->id}");
                    $variant->delete();
                }

                // Delete the product itself
                Log::info("Deleting product ID: {$product->id}");
                $product->delete();
            }

            // Commit the transaction
            DB::commit();

            return response()->json([
                'data' => $products,
                'message' => 'Products deleted successfully'
            ], 200);
        } catch (Exception $e) {
            // Rollback if something goes wrong
            DB::rollback();

            // Return detailed error message
            Log::error("Error deleting products: " . $e->getMessage());
            return response()->json(['message' => 'Failed to delete products', 'error' => $e->getMessage()], 500);
        }
    }
    public function insertProductView(Request $request)
    {

        $discounts = new DiscountController()->discountName()->getData(true);
        $brands = new BrandController()->showBrand()->getData(true);
        $categories = new CategoryController()->show()->getData(true);
        $productTypes = new ProductTypeController()->show()->getData(true);

        return view('admin.insertProduct', ['discounts' => $discounts, 'brands' => $brands, 'categories' => $categories, 'productTypes' => $productTypes]);
    }
}
