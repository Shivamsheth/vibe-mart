<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;

// Auth views
Route::view('/login', 'auth.login')->name('login.view');
Route::view('/register', 'auth.register')->name('register.view');
Route::view('/verify-otp', 'auth.verify-otp')->name('verify.view');
Route::view('/resend-otp', 'auth.resend-otp')->name('resend.view');

// Customer home (protected)

    Route::get('/', [CustomerDashboardController::class, 'index'])->name('home');


// Admin dashboard (protected)
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');
        
