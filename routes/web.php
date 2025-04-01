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
        $product = (new ProductController())->show()->getData(true);
        // dd($brands);
        // dd($products);
        return view('welcome', ['brands' => $brands, 'products' => $products, 'discounts' => $discounts]);
        // return dd($discounts);
    });
    Route::get('detail', function (Request $request) {
        $productController = app(ProductController::class);

        // Call index() and show() correctly
        $detailResponse = $productController->index($request);
        $productResponse = $productController->show();

        // Convert JSON response to array
        $details = json_decode($detailResponse->getContent(), true);
        $products = json_decode($productResponse->getContent(), true);

        return view('productDetail', ['details' => $details, 'products' => $products]);
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
