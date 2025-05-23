<?php

namespace App\Http\Controllers;

use App\Models\api;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function displayCategory()
    {
        return view('admin.insertCategory');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validateData = $request->validate([
                'category_name' => ['required', 'string']
            ]);

            $category = Category::create([
                'category_name' => $validateData['category_name'],
                'images' => $request->file('images')->store('imagpes', 'public')
            ]);

            return redirect()->back()->with('success', 'Category created successfully');
        } catch (\Throwable $th) {
            Log::error('Error creating category: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Category name already exists');
            // return response()->json(['error' => $th->getMessage()], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show()
    {
        $categories = Category::get();
        return response()->json($categories);
    }
    public function paginateCategory()
    {
        $categories = Category::paginate(6);
        return response()->json($categories);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, api $api)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(api $api)
    {
        //
    }
}
