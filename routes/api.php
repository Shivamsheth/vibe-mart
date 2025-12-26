<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Auth Routes 
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/verify',[AuthController::class,'verifyOtp'])->name('verify');
    Route::post('/resend-otp',[AuthController::class,'resendOtp'])->name('resend');
    Route::post('/login',[AuthController::class,'login'])->name('login');
});
