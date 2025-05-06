<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Order;
use App\Models\OrderItems;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GraphController extends Controller
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

    public function DashBoardGraphArea()
    {
        try {
            $currentYear = Carbon::now()->year;
            $years = range($currentYear - 4, $currentYear); // [2021, 2022, 2023, 2024, 2025]

            // Query actual data
            $results = DB::table('orders')
                ->selectRaw("EXTRACT(YEAR FROM created_at)::INT as year, SUM(amount) as total")
                ->whereIn(DB::raw("EXTRACT(YEAR FROM created_at)::INT"), $years)
                ->groupBy('year')
                ->orderBy('year')
                ->get()
                ->keyBy('year');

            // Merge with missing years
            $data = collect($years)->map(function ($year) use ($results) {
                return [
                    'year' => $year,
                    'total' => $results[$year]->total ?? 0
                ];
            });

            return response()->json(['data' => $data->values()]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    public function OrderGraphAreaCustomer()
    {
        try {
            $currentYear = Carbon::now()->year;
            $years = range($currentYear - 4, $currentYear);


            $results = DB::table('customers')
                ->selectRaw("EXTRACT(YEAR FROM created_at)::INT as year, count(id) as total")
                ->whereIn(DB::raw("EXTRACT(YEAR FROM created_at)::INT"), $years)
                ->groupBy('year')
                ->orderBy('year')
                ->get()
                ->keyBy('year');

            // Merge with missing years
            $data = collect($years)->map(function ($year) use ($results) {
                return [
                    'year' => $year,
                    'total' => $results[$year]->total ?? 0
                ];
            });

            return response()->json(['data' => $data->values()]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th]);
        }
    }
    public function OrderGraphAreaSales()
    {
        try {
            $currentYear = Carbon::now()->year;
            $years = range($currentYear - 4, $currentYear);


            $results = DB::table('orders')
                ->selectRaw("EXTRACT(YEAR FROM created_at)::INT as year, count(id) as total")
                ->whereIn(DB::raw("EXTRACT(YEAR FROM created_at)::INT"), $years)
                ->groupBy('year')
                ->orderBy('year')
                ->get()
                ->keyBy('year');

            // Merge with missing years
            $data = collect($years)->map(function ($year) use ($results) {
                return [
                    'year' => $year,
                    'total' => $results[$year]->total ?? 0
                ];
            });

            return response()->json(['data' => $data->values()]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th]);
        }
    }
    // public function DashBoardGraphBar()
    // {
    //     try {
    //         $brands = OrderItems::select(
    //             'product_variants.product.brand_id',
    //             'brands.name as brand_name',
    //             DB::raw('SUM(order_items.quantity) as total_sold')
    //         )
    //             ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
    //             ->join('products', 'product_variants.product_id', '=', 'products.id')
    //             ->join('brands', 'products.brand_id', '=', 'brands.id')
    //             ->groupBy('products.brand_id', 'brands.name')
    //             ->orderByDesc('total_sold')
    //             ->take(10)
    //             ->get();

    //         return response()->json($brands);
    //     } catch (\Throwable $th) {
    //         return response()->json(['error' => $th->getMessage()]);
    //     }
    // }

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
        //
    }
}
