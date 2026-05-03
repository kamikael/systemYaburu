<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('auth:sanctum')->group(function () {

    // AUTH
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);

    // USERS
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::post('/users/{id}/change-password', [UserController::class, 'changePassword']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::get('/users', [UserController::class, 'index']);

    // SHOPS
    Route::post('/shops', [ShopController::class, 'store']);
    Route::get('/users/{id}/shops', [ShopController::class, 'userShops']);
    Route::put('/shops/{id}', [ShopController::class, 'update'])->middleware('shop.owner');
    Route::delete('/shops/{id}', [ShopController::class, 'destroy'])->middleware('shop.owner');
    Route::get('/shops/{id}', [ShopController::class, 'show']);

    // 🔥 JORIS PART
    Route::prefix('/shops/{shop_id}')
        ->middleware('shop.owner')
        ->group(function () {

        // PRODUCTS
        Route::post('/products', [ProductController::class, 'store']);
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/{id}', [ProductController::class, 'show']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

        // CATEGORIES
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        // ORDERS
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
        Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);
        Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
    });
});