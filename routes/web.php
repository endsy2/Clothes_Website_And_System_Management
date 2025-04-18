<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\DiscountController;
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
    Route::get('/detail', function (Request $request) {
        $relatedProducts = (new ProductController())->show()->getData(true);
        $product = (new ProductController())->productById($request['id'])->getData(true);
        return view('user.productDetail', ['product' => $product, 'relatedProducts' => $relatedProducts['data']]);
    });
    Route::get('/login', [UserController::class, 'login'])->name('login');
    Route::get('/register', [UserController::class, 'register'])->name('register');
    Route::get('/productSort', function (Request $request) {
        $title = $request->query('type') ?? 'Default';
        $products = (new ProductController())->index($request)->getData(true);
        return view('user.productSort', ['products' => $products['data'], 'title' => $title]);
    });
    // Route::get('/productSort', [ProductController::class, 'index'])->name('productSort');
});
Route::prefix('/admin')->group(function () {
    Route::get('/dashboard', function (Request $request) {
        $chart_options = [
            'chart_title' => 'Products by Month',
            'report_type' => 'group_by_date',
            'model' => Product::class,
            'group_by_field' => 'created_at',
            'group_by_period' => 'month',
            'chart_type' => 'bar',
            'chart_color' => '70, 130, 180', // optional
        ];

        $chart1 = new LaravelChart($chart_options);

        return view('admin.dashboard', compact('chart1'));
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
