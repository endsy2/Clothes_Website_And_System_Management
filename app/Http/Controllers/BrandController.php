<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use Exception;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function __construct()
    // {
    //     $this->middleware('auth:admin')->except(['show', 'showBrand']);
    // }
    public function displayBrand()
    {
        $brands = Brand::paginate(10);
        // dd($brands);
        return view('user.brand-display', ['brands' => $brands]);
    }

    public function displayInsertBrand()
    {

        return view('admin.insert-brand');
    }
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

            // Handle file upload — manually move to public/images
            $file = $request->file('image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename); // Move to /public/images
            $imagePath = '/images/' . $filename; // Relative path for frontend

            // Create brand record
            $brand = Brand::create([
                'brand_name' => $validatedData['brand_name'],
                'image' => $imagePath
            ]);

            return redirect()->back()->with('success', 'Brand created successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Brand name already exists');
            // Or log the exact error: Log::error($e->getMessage());
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
    public function showBrand()
    {
        $brands = Brand::get();
        return response()->json($brands);
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
