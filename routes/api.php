<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PhpParser\Node\Stmt\TryCatch;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/show', [ProductController::class, 'show']);
    Route::get('/{id}', [ProductController::class, 'productById']);
    Route::post('/store', [ProductController::class, 'store']);
    Route::delete('/delete/{id}', [ProductController::class, 'destroy']);
    Route::put('/update/{id}', [ProductController::class, 'update']);
});
Route::prefix('brand')->group(function () {
    Route::get('/', [BrandController::class, 'index']);
    Route::get('/paginate', [BrandController::class, 'show']);
    Route::post('/store', [BrandController::class, 'store']);
    Route::delete('/delete/{id}', [BrandController::class, 'destroy']);
    Route::put('/update/{id}', [BrandController::class, 'update']);
});
Route::prefix('discount')->group(function () {
    Route::get('/', [DiscountController::class, 'index']);
});
Route::get('/brand', [BrandController::class, 'show']);
