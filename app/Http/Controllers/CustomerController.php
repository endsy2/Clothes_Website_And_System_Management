<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Order;
use App\Models\OrderItems;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $customer = Customers::paginate(15);
            return response()->json($customer);
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => "something went wrong",
                    "error" => $th
                ]
            );
        }
    }
    public function count()
    {
        try {
            $countCustomer = count(Customers::all());
            return response()->json($countCustomer);
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
        try {
            return view('user.user-register');
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function login(Request $request)
    {
        try {
            return view('user.user-login');
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function checkout(Request $request)
    {
        $paymentMethod = $request->input("payment_method");

        $status = $paymentMethod === "Credit Card" ? "Paid" : "Not Paid";

        $order = Order::create([
            'customer_id' => Auth::id(), // Use the Auth facade correctly
            'pay_method' => $paymentMethod,
            'state' => $status,
            'amount' => $request->input('total_price'),
        ]);

        $items = $request->input('items', []); // fallback to empty array to avoid null

        foreach ($items as $item) {
            OrderItems::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_variant_id' => $item['product_variant_id'],
                'quantity' => $item['quantity'],
                'amount' => (float) $item['quantity'] * (float) $item['price'],
            ]);
        }

        return redirect('/')->with('success', 'true');
    }
    /**
     * Display the specified resource.
     */


    public function show(string $id)
    {
        try {
            $customer = Customers::with('orders')->findOrFail($id);

            return response()->json($customer);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Customer not found.'], 404);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
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
        //
    }
}
