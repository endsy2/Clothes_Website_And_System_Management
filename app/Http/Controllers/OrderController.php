<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItems;
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
        try {
            $orders = Order::with(['customer'])->paginate(10);
            return response()->json($orders);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
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

        try {
            $order = Order::with(['customer', 'orderItems', 'orderItems.productVariant', 'orderItems.productVariant.product'])->findOrFail($id);
            return response()->json($order);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
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
            $order = Order::findOrFail($id);
            $order->delete();
            return response()->json(['message' => 'Order deleted successfully']);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function destroyMany(Request $request)
    {
        try {
            $ids = $request->input('ids');
            Order::destroy($ids);
            return response()->json(['message' => 'Orders deleted successfully']);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
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
