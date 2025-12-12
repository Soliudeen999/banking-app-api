<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [AuthController::class, 'forgetPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/resend-password-reset-otp', [AuthController::class, 'forgetPassword']);

Route::middleware('auth:sanctum')->group(function(){
    Route::put('/resend-verification', [AuthController::class, 'resendVerificationCode']);
    Route::post('/verify-email', [AuthController::class, 'verifyEmail'])->name('verification.verify');
    Route::get('/me', [UserController::class, 'currentUser'])->name('user.me');
    Route::get('/logout', [AuthController::class, 'logout'])->name('user.logout');

    Route::middleware('verified')->group(function() {
        Route::get('/accounts', [AccountController::class, 'index']);
        Route::post('/accounts', [AccountController::class, 'store']);
        Route::get('/accounts/{account}', [AccountController::class, 'show']);

    });
});
