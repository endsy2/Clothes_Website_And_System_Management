<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    $brands = (new BrandController())->show();
    $products = (new ProductController())->show();
    // dd($brands);
    // dd($products);
    return view('welcome', ['brands' => $brands, 'products' => $products]);
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
