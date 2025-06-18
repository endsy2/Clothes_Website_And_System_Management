<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Product;
use App\Models\ProductVariant;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

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
    public function displayEditDiscount($id)
    {
        $discount = Discount::find($id);
        return view('admin.edit-discount', ['discount' => $discount->toArray()]);
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
            $discounts = Discount::get();
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

        Log::info("Creating discount with data: ", $validatedData);
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
    public function edit($id)
    {
        try {
            Discount::where('id', $id)->update([
                'discount_name' => request('discount_name'),
                'discount' => request('discount'),
                'start_date' => request('start_date'),
                'end_date' => request('end_date'),
            ]);
            log::info("Discount with ID " . $id . " updated successfully");
            // Flash message to session
            Session::flash('success', 'Discount updated successfully');

            // Redirect back or wherever you want
            return redirect('/admin/discount')->with('success', 'Discount updated successfully');
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Discount not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }



    public function destroy($id)
    {
        try {
            Log::info("Attempting to delete discount with ID: " . $id);
            if (!$id) {
                Log::error("No ID provided for deletion");
                return response()->json(['message' => 'No ID provided'], 400);
            }
            $discount = Discount::findOrFail($id);
            Log::info("Found discount: " . $discount);
            $discount->delete();
            Log::info("Discount with ID " . $id . " deleted successfully");
            return response()->json(['message' => 'Discount deleted successfully']);
        } catch (\Exception $e) {
            Log::error("Delete failed: " . $e->getMessage());

            return response()->json([
                'message' => 'Failed to delete discount',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function deleteMany(Request $request)
    {
        try {
            log::info("Delete many discounts request received");

            $ids = $request->input('ids');
            if (empty($ids)) {
                return response()->json(['message' => 'No IDs provided'], 400);
            }

            $result = Discount::whereIn('id', $ids)->delete();
            if ($result) {
                return response()->json(['message' => 'Discount deleted successfully']);
            } else {
                return response()->json(['message' => 'No discounts found for the provided IDs'], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Delete Failed',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
