<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Product\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
 * AUTH API
 */
Route::prefix('auth')->group(function () {
    Route::post('/register',   [AuthController::class, 'register'])->name('api.register');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
    Route::post('/login',      [AuthController::class, 'login'])->name('api.login');
    Route::post('/logout',     [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

/*
 * SELLER PRODUCT CRUD API (TOKEN-BASED ONLY)
 */
Route::prefix('seller')
    ->middleware(['web', 'seller' , 'auth'])  // Removed 'web'
    ->name('seller.')
    ->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);

        Route::post('/products/{product}/toggle', [ProductController::class, 'toggleStatus']);
        Route::delete('/products/images/{image}', [ProductController::class, 'deleteImage']);
    });


    Route::post('/test-image-upload', [ImageController::class, 'upload']);

