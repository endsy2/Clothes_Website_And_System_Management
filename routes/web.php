<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix("/")->group(function () {
    Route::get('/', function () {
        $brands = (new BrandController())->show()->getData(true);
        $products = (new ProductController())->show()->getData(true);
        $discounts = (new DiscountController())->index()->getData(true);
        // $product = (new ProductController())->show()->getData(true);
        // dd($brands);
        // dd($products);
        return view('welcome', ['brands' => $brands, 'products' => $products['data'], 'discounts' => $discounts]);
        // return dd($products['data']);
    });
    Route::get('detail', function (Request $request) {
        $products = (new ProductController())->show()->getData(true);
        $details = (new ProductController())->productById($request)->getData(true);

        // return view('productDetail', ['details' => $details, 'products' => $products]);
        return dd($products, $details);
    });
});


Route::get('/home', [ProductController::class, 'index']);
Route::get('/service', function () {
    return view('service');
});
Route::get('/about', function () {
    return view('about');
});
Route::get('/product/{id}', function ($id) {
    return view('product', ['id' => $id]);
});
Route::get('/products', [ProductController::class, 'index']);
