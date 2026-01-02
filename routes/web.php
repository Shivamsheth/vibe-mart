<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Payments\CheckoutController;
use App\Http\Controllers\Customer\WishlistController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| 
| 🔥 COMPLETE ROUTES FOR VIBEMART
| - Public Home + Products
| - Full Cart System ✅
| - Auth + Dashboards
| - Seller CRUD
| - Admin Panel
|
*/

/*
🔥 PUBLIC ROUTES - NO AUTH REQUIRED
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('product.show');

// 🔥 AUTH PAGES
Route::view('/login', 'auth.login')->name('login.view');
Route::view('/register', 'auth.register')->name('register.view');
Route::view('/verify-otp', 'auth.verify-otp')->name('verify-otp');
Route::view('/resend-otp', 'auth.resend-otp')->name('resend.view');

// 🔥 PUBLIC API LOGIN
Route::post('/api/auth/login', [AuthController::class, 'login'])->middleware('web')->name('api.login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('web')->name('logout');

/*
🔥 CART ROUTES - FULL SYSTEM ✅
*/
Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// Cart AJAX Operations (works for guests too)
Route::middleware('web')->group(function () {
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{productId}', [CartController::class, 'remove'])->name('cart.remove');
   Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

});

/*
🔥 PROTECTED ROUTES - AUTH REQUIRED
*/
Route::middleware(['web', 'auth'])->group(function () {

    // 🔥 DASHBOARDS
    Route::get('/customer', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
    Route::get('/customer/cart', [CartController::class, 'show'])->name('customer.cart'); // Navbar link
    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/seller', [SellerDashboardController::class, 'index'])->name('seller.dashboard');

    // 🔥 CUSTOMER DASHBOARD
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/profile', [CustomerDashboardController::class, 'profile'])->name('profile');
        Route::get('/orders', [CustomerDashboardController::class, 'orders'])->name('orders');
        Route::get('/wishlist', [CustomerDashboardController::class, 'wishlist'])->name('wishlist');
        Route::get('/support', [CustomerDashboardController::class, 'support'])->name('support');
    });

    Route::prefix('checkout')->name('checkout.')->group(function () {

            // AJAX: lock checkout
            Route::post('/order-summary', [CheckoutController::class, 'orderSummary'])
                ->name('order-summary');

            // PAGE: payment UI
            Route::get('/payment', [CheckoutController::class, 'paymentPage'])
                ->name('payment');

            // AJAX: create order
            Route::post('/place-order', [CheckoutController::class, 'orderCreation'])
                ->name('place-order');
        });

        Route::prefix('wishlist')->name('wishlist.')->group(function (){
            Route::post('/add',[WishlistController::class,'add'])->name('add');
            //Route::get('/list',[WishlistController::class,'index'])->name('index');
        });

    // 🔥 SELLER PRODUCTS - FULL CRUD
    Route::prefix('seller')->name('seller.')->group(function () {
        Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        
        // Upload & Actions
        Route::post('/products/upload-images-first', [ProductController::class, 'uploadImagesFirst'])->name('products.upload-images-first');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::post('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/products/{product}/toggle', [ProductController::class, 'toggleStatus'])->name('products.toggle');
        Route::delete('/products/images/{image}', [ProductController::class, 'deleteImage'])->name('products.image.delete');
    });

    // 🔥 ADMIN PANEL
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
        Route::get('/sellers/{id}/products', [AdminProductController::class, 'sellerProducts'])->name('sellers.products');
        Route::post('/sellers/{id}/toggle', [AdminProductController::class, 'toggleSeller'])->name('sellers.toggle');
    });
});
