<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;


/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/shops/{id}/orders', [OrderController::class, 'store']);

/*
|--------------------------------------------------------------------------
| Routes protégées
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // 🔐 Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    // 👤 Users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::post('/users/{id}/change-password', [UserController::class, 'changePassword']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // 🏪 Shops
    Route::post('/shops', [ShopController::class, 'store']);
    Route::get('/shops/{id}', [ShopController::class, 'show']);
    Route::put('/shops/{id}', [ShopController::class, 'update']);
    Route::delete('/shops/{id}', [ShopController::class, 'destroy']);
    Route::get('/users/{id}/shops', [ShopController::class, 'userShops']);

    // 📦 Products
    Route::apiResource('shops.products', ProductController::class);

    // 🗂️ Categories
    Route::apiResource('shops.category', CategoryController::class);

    // 👤 orders
    Route::get('/shops/{id}/orders', [OrderController::class, 'index']);
    Route::get('/shops/{shop_id}/orders/{id}', [OrderController::class, 'show']);
    Route::post('/shops/{shop_id}/orders/{id}', [OrderController::class, 'updateStatus']);
    Route::delete('/shops/{shop_id}/orders/{id}', [OrderController::class, 'destroy']);


});