<?php

namespace App\Http\Controllers;

use App\Models\api;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


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
        return view('admin.insert-category');
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        try {
            $validateData = $request->validate([
                'category_name' => ['required', 'string'],
                'image' => ['required', 'image', 'mimes:jpg,jpeg,png,svg,gif', 'max:2048'],
            ]);

            // Upload to DigitalOcean Spaces correctly
            $file = $request->file('image');
            Log::info('Uploading file: ' . $file->getClientOriginalName());

            $path = $file->store('uploads/categories', 'spaces'); // ✅ uses tmp path and proper API

            if (!$path) {
                throw new \Exception('Upload failed');
            }

            // Save category
            $category = Category::create([
                'category_name' => $validateData['category_name'],
                'images' => $path,
            ]);

            return redirect()->back()->with('success', 'Category created successfully');
        } catch (\Throwable $th) {
            Log::error('Error creating category: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Failed to create category: ' . $th->getMessage());
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
        return $categories;
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
