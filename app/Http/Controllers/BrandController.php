<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        try {
            // Validate request
            $validatedData = $request->validate([
                "brand_name" => ['required', 'string', 'max:120'],
                "image" => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048']
            ]);

            // Handle file upload
            $file = $request->file('image');
            $path = $file->store('brand_image', 'public');
            $imagePath = Storage::url($path);

            // Create brand record
            $brand = Brand::create([
                'brand_name' => $validatedData['brand_name'],
                'image' => $imagePath
            ]);

            return response()->json(['message' => 'Insert successfully', 'brand' => $brand], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show()
    {
        $brands = Brand::paginate(10); // This returns a LengthAwarePaginator instance
        return $brands;
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                "brand_name" => ['required', 'string', 'max:200'],
                "image" => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048']
            ]);
            $file = $request->file('image');
            $path = $file->store('brand_image', 'public');
            $imagePath = Storage::url($path);
            $data = [
                'brand_name' => $validatedData['brand_name'],
                'image' => $imagePath
            ];
            $brand = Brand::update($data);
            return response()->json(['message' => 'update sucessfully']);
            // return response()->json($request->all(), 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::find($id);
        $brand->delete();
        return response('Delete Brand sucessfully', 200);
    }
}
