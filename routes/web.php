<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

Route::prefix("/")->group(function () {
    Route::get('/', function () {
        $brands = (new BrandController())->show()->getData(true);
        $products = (new ProductController())->show()->getData(true);
        $discounts = (new DiscountController())->index()->getData(true);
        // $product = (new ProductController())->show()->getData(true);
        // dd($brands);
        // dd($products);
        return view('user.welcome', ['brands' => $brands, 'products' => $products['data'], 'discounts' => $discounts]);
    });
    Route::get('detail', function (Request $request) {
        $relatedProducts = (new ProductController())->show()->getData(true);
        $product = (new ProductController())->productByProductIdPrductVariantID($request['id'])->getData(true);
        return view('user.productDetail', ['product' => $product, 'relatedProducts' => $relatedProducts['data']]);
    });
    Route::get('login', [CustomerController::class, 'login'])->name('login');
    Route::get('register', [CustomerController::class, 'store'])->name('register');
    Route::get('productSort', function (Request $request) {
        $title = $request->query('type') ?? 'Default';
        $products = (new ProductController())->index($request)->getData(true);
        return view('user.productSort', ['products' => $products['data'], 'title' => $title]);
    });
    Route::get('search-products', [ProductController::class, 'searchProduct']);
    Route::get('add-to-cart', [ProductController::class, 'addToCart'])->name('add-to-cart');
    Route::get('add-to-favorite', [ProductController::class, 'addToFavorite'])->name('add-to-favorite');
    Route::get('search-productsId-productVariantId/{productId}/{productVariantId?}', [ProductController::class, 'productByProductIdPrductVariantID']);
    Route::post('checkout', function (Request $request) {
        $cart = $request->input('cart');
        // $productCart=(new ProductController())->productByProductIdPrductVariantID($request['id'],$request['productVariantId'])->getData(true);
        // return view('user.checkout')
        return dd($cart);
    });
    // Route::get('/productSort', [ProductController::class, 'index'])->name('productSort');
});
Route::prefix('/admin')->group(function () {
    Route::get('/dashboard', function (Request $request) {

        $countCustomer = (new CustomerController())->count()->getData(true);
        $countOrder = (new OrderController())->count()->getData(true);
        $totalRevenue = (new OrderController())->totalRevenues()->getData(true);
        $trendProduct = (new ProductController())->trendProduct()->getData(true);
        return view('admin.dashboard', [
            'countCustomer' => $countCustomer,
            'countOrder' => $countOrder,
            'totalRevenue' => $totalRevenue,
            'trendProduct' => $trendProduct
        ]);
    });

    Route::get('/product', function (Request $request) {
        $product = new ProductController()->index($request)->getData(true);
        return view('admin.product');
    });
    Route::get('/user', function (Request $request) {
        $product = new ProductController()->index($request)->getData(true);
        return view('admin.user');
    });
    Route::get('/order', function (Request $request) {
        $product = new ProductController()->index($request)->getData(true);
        return view('admin.order');
    });
    Route::get('/discount', function (Request $request) {
        $product = new ProductController()->index($request)->getData(true);
        return view('admin.discount');
    });
    Route::get('/report', function (Request $request) {
        $product = new ProductController()->index($request)->getData(true);
        return view('admin.report');
    });
});
