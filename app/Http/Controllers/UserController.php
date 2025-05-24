<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function PageHome()
    {
        $brands = (new BrandController())->show()->getData(true);
        $products = (new ProductController())->show()->getData(true);
        $discounts = (new DiscountController())->index()->getData(true);
        $categories = (new CategoryController())->paginateCategory()->getData(true);
        // dd($categories['data']);''

        return view('user.welcome', ['brands' => $brands, 'products' => $products['data'], 'discounts' => $discounts, 'categories' => $categories['data']]);
    }
    public function PageProductDetail(Request $request)
    {
        $relatedProducts = (new ProductController())->show()->getData(true);
        $product = (new ProductController())->productByProductIdPrductVariantID($request['id'])->getData(true);
        return view('user.product-detail', ['product' => $product, 'relatedProducts' => $relatedProducts['data']]);
    }
    public function PageProductSort(Request $request)
    {
        $title = $request->query('type') ?? 'Default';
        $products = (new ProductController())->index($request)->getData(true);
        return dd($products);
    }
    public function PageCheckOut(Request $request)
    {
        return view('user.checkout');
    }
    public function PageAddToFavorite(Request $request)
    {
        return view('user.add-to-favorite');
    }
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
}
