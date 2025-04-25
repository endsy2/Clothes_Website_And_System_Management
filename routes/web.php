<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Route;

//Customer Route

Route::prefix("/")->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [LoginController::class, 'login'])->name('login');
        Route::get('register', [RegisterController::class, 'register'])->name('register');
        Route::post('login', [LoginController::class, 'store'])->name('login');
        Route::post('register', [RegisterController::class, 'store'])->name('store');
    });
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/', function () {
        $brands = (new BrandController())->show()->getData(true);
        $products = (new ProductController())->show()->getData(true);
        $discounts = (new DiscountController())->index()->getData(true);
        return view('user.welcome', ['brands' => $brands, 'products' => $products['data'], 'discounts' => $discounts]);
    })->name('home');
    Route::get('detail', function (Request $request) {
        $relatedProducts = (new ProductController())->show()->getData(true);
        $product = (new ProductController())->productByProductIdPrductVariantID($request['id'])->getData(true);
        return view('user.productDetail', ['product' => $product, 'relatedProducts' => $relatedProducts['data']]);
    });

    Route::get('productSort', function (Request $request) {
        $title = $request->query('type') ?? 'Default';
        $products = (new ProductController())->index($request)->getData(true);
        return dd($products);
    });
    Route::get('search-products', [ProductController::class, 'searchProduct']);
    Route::get('add-to-cart', [ProductController::class, 'addToCart'])->name('add-to-cart');
    Route::get('add-to-favorite', [ProductController::class, 'addToFavorite'])->name('add-to-favorite');
    Route::get('search-productsId-productVariantId/{productId}/{productVariantId?}', [ProductController::class, 'productByProductIdPrductVariantID']);
    Route::get('checkout', function (Request $request) {
        // $cart = $request->input('cart');
        // $productCart=(new ProductController())->productByProductIdPrductVariantID($request['id'],$request['productVariantId'])->getData(true);
        return view('user.checkout');
    })->middleware('auth');
    Route::post('checkout', [CustomerController::class, 'checkout'])->middleware('auth');
    Route::get('add-to-favorite', function (Request $request) {
        return view('user.add-to-favorite');
    });
    // Route::get('/productSort', [ProductController::class, 'index'])->name('productSort');
});

//Admin Route

Route::prefix('/admin')->middleware('auth:admin')->group(function () {
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
    })->name('admin.dashboard');
    Route::get('/product', function (Request $request) {
        $product = new ProductController()->index($request)->getData(true);
        return view('admin.product');
    })->name('admin.product');
    Route::get('/user', function (Request $request) {
        $product = new ProductController()->index($request)->getData(true);
        return view('admin.user');
    })->name('admin.user');
    Route::get('/order', function (Request $request) {
        $product = new ProductController()->index($request)->getData(true);
        return view('admin.order');
    });
    Route::get('/discount', function (Request $request) {
        $product = new ProductController()->index($request)->getData(true);
        return view('admin.discount');
    })->name('admin.discount');
    Route::get('/report', function (Request $request) {
        $product = new ProductController()->index($request)->getData(true);
        return view('admin.report');
    })->name('admin.report');
    Route::post('/logout', [LoginController::class, 'adminLogout'])->name('adminLogout');
});

//admin:guest Route

Route::prefix('/admin')->middleware('guest:admin')->group(function () {
    Route::get("/login", function (Request $request) {
        return view('admin.login');
    });
    Route::get('/sigup', function (Request $request) {
        return view('admin.sigup');
    });
    Route::post('/login', [LoginController::class, 'adminstore'])->name('adminLogin');
    Route::post('/sigup', [RegisterController::class, 'adminStore'])->name('adminStore');
});
