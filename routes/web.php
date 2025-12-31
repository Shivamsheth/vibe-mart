<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController; // 🔥 NEW: Public Home
use App\Http\Controllers\Admin\ProductController as AdminProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🔥 PUBLIC HOME - NO AUTH REQUIRED (Everyone can access)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/login', 'auth.login')->name('login.view');
Route::view('/register', 'auth.register')->name('register.view');
Route::view('/verify-otp','auth.verify-otp')->name('verify-otp');
Route::view('/resend-otp','auth.resend-otp')->name('resend.view');

// 🔥 PUBLIC PRODUCT DETAIL - NO AUTH REQUIRED
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('product.show');

// 🔥 API login that also creates session
Route::post('/api/auth/login', [AuthController::class, 'login'])
    ->middleware('web')
    ->name('api.login');
    // 🔥 ADD LOGOUT ROUTE - PUBLIC (before auth middleware)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('web')
    ->name('logout');


// 🔥 PROTECTED AREA - AUTH REQUIRED
Route::middleware(['web', 'auth'])->group(function () {

    // 🔥 DASHBOARDS (Fixed naming)
    Route::get('/customer', [CustomerDashboardController::class, 'index'])->name('customer.dashboard'); // Fixed name
    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/seller', [SellerDashboardController::class, 'index'])->name('seller.dashboard');

    // 🔥 SELLER PRODUCT PAGES + FULL CRUD
    Route::prefix('seller')->name('seller.')->group(function () {
        // Views
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        
        // Image upload (your fix)
        Route::post('/products/upload-images-first', [ProductController::class, 'uploadImagesFirst'])->name('products.upload-images-first');
        
        // Actions
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
// 🔥 CHANGE PUT → POST
Route::post('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/products/{product}/toggle', [ProductController::class, 'toggleStatus'])->name('products.toggle');
        Route::delete('/products/images/{image}', [ProductController::class, 'deleteImage'])->name('products.image.delete');
    });

    Route::prefix('customer')->group(function (){
        Route::get('/profile',[CustomerDashboardController::class,'profile'])->name('customer.profile');
        Route::get('/orders',[CustomerDashboardController::class,'orders'])->name('customer.orders');
        Route::get('/cart',[CustomerDashboardController::class,'cart'])->name('customer.cart');
        Route::get('/support-system',[CustomerDashboardController::class,'support'])->name('customer.support');
        Route::get('/wishlist',[CustomerDashboardController::class,'wishlist'])->name('customer.wishlist');

    }); 

    Route::prefix('admin')->group(function(){
        Route::get('/customers-list',[AdminProductController::class,'seller'])->name('admin.customers-list');
    });
});
