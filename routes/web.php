<?php

use App\Http\Controllers\AdminController;
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
use App\Http\Controllers\UserController;
use App\View\Components\admin;
// use Database\Seeders\ProductSeeder;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

//Customer Route

Route::prefix("/")->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [LoginController::class, 'login'])->name('customerLogin');
        Route::get('register', [RegisterController::class, 'register'])->name('register');
        Route::post('login', [LoginController::class, 'store'])->name('login');
        Route::post('register', [RegisterController::class, 'store'])->name('store');
    });
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/', [UserController::class, 'PageHome'])->name('home');
    Route::get('detail', [UserController::class, 'PageProductDetail'])->name('productDetail');

    Route::get('productSort', [UserController::class, 'PageProductSort'])->name('productSort');
    Route::get('search-products', [ProductController::class, 'searchProduct']);
    Route::get('add-to-cart', [ProductController::class, 'addToCart'])->name('add-to-cart');
    Route::get('add-to-favorite', [ProductController::class, 'addToFavorite'])->name('add-to-favorite');
    Route::get('search-productsId-productVariantId/{productId}/{productVariantId?}', [ProductController::class, 'productByProductIdPrductVariantID']);
    Route::get('checkout', [UserController::class, 'PageCheckOut'])->middleware('auth:customer');

    Route::post('checkout', [CustomerController::class, 'checkout'])->middleware('auth:customer');
    Route::get('add-to-favorite', [UserController::class, 'PageAddToFavorite'])->name('add-to-favorite');

    //display brand
    Route::get('/brand', [BrandController::class, 'displayBrand'])->name('user.displaybrand');
    // Route::get('productsSort', function (Request $request) {
    //     $type = $request->query('type');

    //     if (in_array($type, ['brand', 'category', 'productType', 'discount'])) {
    //         $products = (new ProductController())->index($request)->getData(true);
    //         return view('user.productSort', ['products' => $products]);
    //     } else {
    //         return redirect()->route('home');
    //     }
    // })->name('productSort');
    // Route::get('productSort', [ProductController::class, 'productSort'])->name('user.productSort');

    // Route::get('/productSort', [ProductController::class, 'index'])->name('productSort');
});

//Admin Route

Route::prefix('/admin')->middleware('auth:admin')->group(function () {
    // dashboard
    Route::get('/dashboard', [AdminController::class, 'PageHome'])->name('admin.dashboard');
    //product
    Route::get('/product', [AdminController::class, 'PageProduct'])->name('admin.product');

    //insert product
    Route::get('/insertProduct', [ProductController::class, 'insertProductView'])->name('insertProductView');

    //get user page
    Route::get('/user', [AdminController::class, 'PageUser'])->name('admin.user');

    //get user detail page
    Route::get('/user/{id}', [AdminController::class, 'PageUserDetail'])->name('admin.userDetail');
    //get user order page
    Route::get('/order', [AdminController::class, 'PageOrder'])->name('admin.order');
    Route::get("/order/{id}", [AdminController::class, 'PageOrderDetail'])->name('admin.order-detail');
    //get discount page
    Route::get('/discount', [AdminController::class, 'PageDiscount'])->name('admin.discount');
    //delete discount route
    Route::delete("/discount/{id}", [DiscountController::class, 'destroy'])->name('deleteOneDiscount');
    // Route::get('/report', function (Request $request) {
    //     $product = new ProductController()->index($request)->getData(true);
    //     return view('admin.report');
    // })->name('admin.report');
    Route::post('/logout', [LoginController::class, 'adminLogout'])->name('adminLogout');
    // add product route
    Route::post('/add-product', [ProductController::class, 'store'])->name('add-product');
    //delete product route
    Route::delete('/delete-product-one', [ProductController::class, 'deleteOne'])->name('delete-product-one');
    Route::delete('/delete-products-many', [ProductController::class, 'deleteMany'])->name('delete-product-many');
    //get product detail page
    Route::get('/product/{id}', function (Request $request) {


        $product     = (new ProductController())->productByProductIdPrductVariantID($request['id'])->getData(true);

        $brands = (new BrandController())->showBrand()->getData(true);

        $categorys = (new CategoryController())->show()->getData(true);

        $productTypes = (new ProductTypeController())->show()->getData(true);


        $discounts = (new DiscountController())->index()->getData(true);

        // dd($discounts);
        return view('admin.product-detail', ['product' => $product, 'brands' => $brands, 'categorys' => $categorys, 'productTypes' => $productTypes, 'discounts' => $discounts]);
    })->name('admin.product-detail');
    // update product route
    Route::put("/product/{id}", [ProductController::class, 'update'])->name('admin.productupdate');
    // update product variant route

    Route::get('/insertProductVariant', [ProductVariantsController::class, 'show'])->name('admin.add-product-variant-show');
    Route::post('/add-product-variant', [ProductVariantsController::class, 'store'])->name('admin.add-product-variant');
    Route::delete('/deleteProductVariant', [ProductVariantsController::class, 'destroy'])->name('admin.delete-product-variant');
    Route::put('/updateProductVariant/{id}', [ProductVariantsController::class, 'update'])->name('admin.update-product-variant');

    Route::delete("/order/{id}", [OrderController::class, 'destroy'])->name('admin.order.delete');

    // insert brand
    Route::delete("/many-order", [OrderController::class, 'destroyMany'])->name('admin.order.deleteMany');
    // insert brand route and display
    Route::get('/insertBrand', [BrandController::class, 'displayInsertBrand'])->name('admin.insertBrandDisplay');
    Route::post('/insertBrand', [BrandController::class, 'store'])->name('admin.insertBrand');
    // insert category route and display
    Route::get('/insertCategory', [CategoryController::class, 'displayCategory'])->name('admin.insertCategoryDisplay');
    Route::post('/insertCategory', [CategoryController::class, 'store'])->name('admin.insertCategory');
    // insert product type route and display
    Route::get('/insertProductType', [ProductTypeController::class, 'displayProductType'])->name('admin.insertProductTypeDisplay');
    Route::post('/insertProductType', [ProductTypeController::class, 'store'])->name('admin.insertProductType');
    // insert discount route and display
    Route::get('/insertDiscount', [DiscountController::class, 'displayDiscount'])->name('admin.insertDiscountDisplay');
    Route::post('/insertDiscount', [DiscountController::class, 'store'])->name('admin.insertDiscount');
    // delete discount route
    Route::delete("/deleteDiscount/{id}", [DiscountController::class, 'destroy'])->name('admin.deleteDiscount');
    Route::delete('/deleteManyDiscount', [DiscountController::class, 'deleteMany'])->name('admin.deleteManyDiscount');
    // display edit discount
    Route::get('/editDiscount/{id}', [DiscountController::class, 'displayE ditDiscount'])->name('admin.editDiscountDisplay');
    Route::put('/editDiscount/{id}', [DiscountController::class, 'edit'])->name('admin.editDiscount');
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
