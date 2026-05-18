<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Agentia;


/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/stores/{id}/orders', [OrderController::class, 'store']);
Route::get('productTypes', [ProductTypeController::class, 'index']);
Route::post('/stores/{id}/orders', [OrderController::class, 'store']);
Route::post(
    '/admin/login',
    [AuthController::class, 'adminLogin']
);

/*
|--------------------------------------------------------------------------
| Routes protégées
|--------------------------------------------------------------------------
*/

Route::post('/refresh', [AuthController::class, 'refresh']);
Route::middleware('auth:sanctum')->group(function () {

    //  Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    //  Users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::post('/users/{id}/change-password', [UserController::class, 'changePassword']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    //  stores
    Route::post('/stores', [StoreController::class, 'store']);
    Route::get('/stores/{id}', [StoreController::class, 'show']);
    Route::put('/stores/{id}', [StoreController::class, 'update']);
    Route::delete('/stores/{id}', [StoreController::class, 'destroy']);
    Route::get('/users/{id}/stores', [StoreController::class, 'userstores']);

    // 👤 orders
    Route::get('/stores/{id}/orders', [OrderController::class, 'index']);
    Route::get('/stores/{store_id}/orders/{id}', [OrderController::class, 'show']);
    Route::post('/stores/{store_id}/orders/{id}', [OrderController::class, 'updateStatus']);
    Route::delete('/stores/{store_id}/orders/{id}', [OrderController::class, 'destroy']);

     Route::prefix('stores/{store_id}')
    ->group(function () {

        Route::get('/products', [ProductController::class, 'index']);

        Route::post('/products', [ProductController::class, 'store']);

        Route::get('/products/{id}', [ProductController::class, 'show']);

        Route::put('/products/{id}', [ProductController::class, 'update']);

        Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    });
    
    // agent ia
    Route::get('tools/users', [Agentia::class, 'check_user']);
    Route::prefix('tools/stores/{store_id}')->group(function () {
        Route::get('stats', [Agentia::class, 'get_stat']);
        Route::get('orders', [Agentia::class, 'get_orders']);
        Route::get('products', [Agentia::class, 'get_products']);
    });

});