<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function login()
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
    public function store(Request $request)
    {
        try {
            $attributes = request()->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);
            Log::info('Login attempt', ['email' => $attributes['email']]);
            if (Auth::guard('customer')->attempt($attributes)) {
                $request->session()->regenerate();
                return redirect('/')->with('success', 'You have been logged in');
            }
            return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
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
            $attributes = request()->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (Auth::guard('admin')->attempt($attributes)) {
                $request->session()->regenerate();
                return redirect('/admin/dashboard')->with('success', 'You have been logged in');
            }
            return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function logout(Request $request)
    {
        try {
            Auth::guard('customer')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $request->session()->flash('success', 'You have been logged out');
            return redirect('/')->with('success', 'You have been logged out');
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function adminLogout(Request $request)
    {
        try {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $request->session()->flash('success', 'You have been logged out');
            return redirect('/admin/login')->with('success', 'You have been logged out');
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
