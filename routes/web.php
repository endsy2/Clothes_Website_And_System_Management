<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
