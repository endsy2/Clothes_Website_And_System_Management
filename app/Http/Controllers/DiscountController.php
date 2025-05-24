<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Product;
use App\Models\ProductVariant;
use Exception;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;

use function Pest\Laravel\get;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function displayDiscount()
    {
        return view('admin.insert-discount');
    }
    public function index()
    {
        try {
            // Step 1: Get only product variant IDs that have a discount
            $products_variant_row = ProductVariant::whereNotNull('discount_id')->paginate(20);
            $product_ids = $products_variant_row->pluck('product_id')->unique()->toArray();

            // Step 2: Load products with only the product variants that have discounts
            $products = Product::with([
                'brand',
                'category',
                'productVariant' => function ($query) {
                    $query->whereNotNull('discount_id')
                        ->with(['productImages', 'discount']);
                }
            ])
                ->whereIn('id', $product_ids)
                ->get();

            return response()->json($products);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching products.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function discountName()
    {
        try {
            $discounts = Discount::paginate(10);
            return response()->json($discounts);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching discounts.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'discount_name' => 'required|string',
            'discount' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date', // Optional: ensures end date is not before start date
        ]);


        try {
            $discount = Discount::create([
                'discount_name' => $validatedData['discount_name'],
                'discount' => $validatedData['discount'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
            ]);

            return redirect()->back()->with('success', 'Discount Created Successfully');
        } catch (Exception $exception) {
            return redirect()->back()->with('error', 'Discount Creation Failed');
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $result = Discount::where('id', $id)->delete();
            if ($result) {
                return response()->json(['message' => 'Delete Success']);
            } else {
                return response()->json(['message' => 'Discount not found'], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Delete Failed',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
