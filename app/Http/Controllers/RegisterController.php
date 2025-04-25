<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Customers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function register()
    {
        try {
            return view('user.userRegister');
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request)
    {
        try {
            // Validate customer input
            $attributes = $request->validate([
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|unique:customers,email',  // Check against customers table
                'address' => 'required|string|max:255',
                'password' => ['required', Password::min(6)->letters()->numbers()->mixedCase(), 'confirmed'],
            ]);

            // Hash password before saving (optional, Laravel does it automatically)
            $attributes['password'] = bcrypt($request->password);

            // Create customer
            $customer = Customers::create($attributes);

            // Log in the customer
            FacadesAuth::guard('customer')->login($customer);  // Use the correct guard for customers

            return redirect('/')->with('success', 'Your account has been created');
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function adminStore(Request $request)
    {
        try {
            $attributes = $request->validate([
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|unique:admins,email',  // Ensure you validate against the 'admins' table
                'password' => ['required', Password::min(6)->letters()->numbers()->mixedCase(), 'confirmed'],
            ]);

            // Create the admin
            $admin = Admin::create($attributes);

            // Log the admin in using the admin guard
            FacadesAuth::guard('admin')->login($admin);

            return redirect('/admin/dashboard')->with('success', 'Your account has been created');
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
