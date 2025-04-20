<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Report\Xml\Totals;

class OrderController extends Controller
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
    public function count()
    {
        try {
            //code...
            $countOrder = count(Order::all());
            return response()->json($countOrder);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function totalRevenues()
    {
        try {
            $totalRevenues = Order::sum('amount'); // Fixed this line
            return response()->json($totalRevenues);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
