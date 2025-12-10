<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function(){
    Route::put('/resend-verification', [AuthController::class, 'resendVerificationCode']);
    Route::post('/verify-email', [AuthController::class, 'verifyEmail'])->name('verification.verify');
    Route::get('/me', [UserController::class, 'currentUser'])->name('user.me');
    Route::get('/logout', [AuthController::class, 'logout'])->name('user.logout');

    Route::middleware('verified')->group(function() {
        Route::get('/accounts', [AccountController::class, 'index']);

    });
});
