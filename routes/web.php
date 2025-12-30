<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public auth pages
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register.view');

// API login that also creates session
Route::post('/api/auth/login', [AuthController::class, 'login'])
    ->middleware('web')
    ->name('api.login');

// Protected area (requires session auth)
Route::middleware(['web', 'auth'])->group(function () {

    // Dashboards
    Route::get('/', [CustomerDashboardController::class, 'index'])->name('home');
    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/seller', [SellerDashboardController::class, 'index'])->name('seller.dashboard');

    // Seller product pages + FULL CRUD (all session-based)
    Route::prefix('seller')->name('seller.')->group(function () {
        // Views
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        
        // 🔥 ADD THIS ONE LINE ONLY:
        Route::post('/products/upload-images-first', [ProductController::class, 'uploadImagesFirst'])->name('products.upload-images-first');
        
        // Actions (JSON responses - all work with session auth)
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::post('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/products/{product}/toggle', [ProductController::class, 'toggleStatus'])->name('products.toggle');
        Route::delete('/products/images/{image}', [ProductController::class, 'deleteImage'])->name('products.image.delete');
    });
});
