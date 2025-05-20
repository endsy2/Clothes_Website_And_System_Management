<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\GraphController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
// use App\Http\Controllers\ProductVariantsController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ProductVariantsController;
use App\Http\Controllers\RegisterController;
// use Database\Seeders\ProductSeeder;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth as FacadesAuth;
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
        return view('user.checkout');
    })->middleware('auth:customer');

    Route::post('checkout', [CustomerController::class, 'checkout'])->middleware('auth:customer');
    Route::get('add-to-favorite', function (Request $request) {
        return view('user.add-to-favorite');
    });
    // Route::get('/productSort', [ProductController::class, 'index'])->name('productSort');
});

//Admin Route

Route::prefix('/admin')->middleware('auth:admin')->group(function () {
    // dashboard
    Route::get('/dashboard', function (Request $request) {

        $countCustomer = (new CustomerController())->count()->getData(true);
        $countOrder = (new OrderController())->count()->getData(true);
        $totalRevenue = (new OrderController())->totalRevenues()->getData(true);
        $trendProduct = (new ProductController())->trendProduct()->getData(true);
        $AreaChart = (new GraphController())->DashBoardGraphArea()->getData(true);
        // $BarChart = (new GraphController())->DashBoardGraphBar()->getData(true);

        // dd($BarChart);
        return view('admin.dashboard', [
            'countCustomer' => $countCustomer,
            'countOrder' => $countOrder,
            'totalRevenue' => $totalRevenue,
            'trendProduct' => $trendProduct,
            'areaChart' => $AreaChart['data']
        ]);
    })->name('admin.dashboard');
    Route::get('/product', function (Request $request) {
        $products = new ProductController()->index($request)->getData(true);
        $discounts = new DiscountController()->discountName()->getData(true);
        $brands = new BrandController()->showBrand()->getData(true);
        $categories = new CategoryController()->show()->getData(true);
        $productTypes = new ProductTypeController()->show()->getData(true);

        return view('admin.product', ['products' => $products, 'discounts' => $discounts, 'brands' => $brands, 'categories' => $categories, 'productTypes' => $productTypes]);
    })->name('admin.product');
    Route::get('/insertProduct', [ProductController::class, 'insertProductView'])->name('insertProductView');
    Route::get('/user', function (Request $request) {
        // $product = new ProductController()->index($request)->getData(true);
        $customers = new CustomerController()->index()->getData(true);

        // dd($customers);
        return view('admin.user', ['customers' => $customers]);
    })->name('admin.user');


    Route::get('/user/{id}', function ($id, Request $request) {
        $customer = new CustomerController()->show($id)->getData(true);
        // dd($customer);
        return view('admin.customerDetail', ['customers' => $customer]);
    });

    Route::get('/order', function (Request $request) {
        // $product = new ProductController()->index($request)->getData(true);
        $orders = new OrderController()->index()->getData(true);
        $areaChartCustomer = new GraphController()->OrderGraphAreaCustomer()->getData(true);
        $areaChartSales = new GraphController()->OrderGraphAreaSales()->getData(true);


        // dd($areaChartSales);
        return view('admin.order', ['orders' => $orders, 'areaChartCustomer' => $areaChartCustomer, 'areaChartSales' => $areaChartSales]);
    });
    Route::get('/discount', function (Request $request) {
        // $product = new ProductController()->index($request)->getData(true);
        $discounts = new DiscountController()->discountName()->getData(true);
        // dd($discounts['data']);
        return view('admin.discount', ['discounts' => $discounts]);
    })->name('admin.discount');
    Route::delete("/discount/{id}", [DiscountController::class, 'destroy'])->name('deleteOneDiscount');
    Route::get('/report', function (Request $request) {
        $product = new ProductController()->index($request)->getData(true);
        return view('admin.report');
    })->name('admin.report');
    Route::post('/logout', [LoginController::class, 'adminLogout'])->name('adminLogout');
    Route::post('/add-product', [ProductController::class, 'store'])->name('add-product');
    Route::delete('/delete-product-one', [ProductController::class, 'deleteOne'])->name('delete-product-one');
    Route::delete('/delete-products-many', [ProductController::class, 'deleteMany'])->name('delete-product-many');
    Route::get('/product/{id}', function (Request $request) {
        $product     = (new ProductController())->productByProductIdPrductVariantID($request['id'])->getData(true);
        $brands = (new BrandController())->showBrand()->getData(true);
        $categorys = (new CategoryController())->show()->getData(true);
        $productTypes = (new ProductTypeController()->show()->getData(true));
        $discounts = (new DiscountController()->index()->getData(true));
        // dd($discounts);
        return view('admin.productDetail', ['product' => $product, 'brands' => $brands, 'categorys' => $categorys, 'productTypes' => $productTypes, 'discounts' => $discounts]);
    })->name('admin.product-detail');
    Route::put("/product/{id}", [ProductController::class, 'update'])->name('admin.productupdate');
    Route::put("/productVariant/{id}", [ProductVariantsController::class, 'update'])->name('admin.productVariantUpdate');
    Route::get('/insertProductVariant', [ProductVariantsController::class, 'show'])->name('admin.add-product-variant-show');
    Route::post('/add-product-variant', [ProductVariantsController::class, 'store'])->name('admin.add-product-variant');
    Route::delete('/delete-product-variant', [ProductVariantsController::class, 'destroy'])->name('admin.delete-product-variant');
    Route::delete("/order/{id}", [OrderController::class, 'destroy'])->name('admin.order.delete');
    Route::get("/order/{id}", function (Request $request) {

        $order = (new OrderController())->show($request['id'])->getData(true);
        // dd($order);
        return view('admin.orderDetail', ['orders' => $order]);
    })->name('admin.order-detail');
    // insert brand
    Route::delete("/many-order", [OrderController::class, 'destroyMany'])->name('admin.order.deleteMany');
    Route::get('/insertBrand', [BrandController::class, 'displayBrand'])->name('admin.inserBrandDisplay');
    Route::post('/insertBrand', [BrandController::class, 'store'])->name('admin.insertBrand');
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
