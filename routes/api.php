<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountTransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureHasTrnxPin;
use App\Http\Middleware\EnsureIsAdmin;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [AuthController::class, 'forgetPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/resend-password-reset-otp', [AuthController::class, 'forgetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::put('/resend-verification', [AuthController::class, 'resendVerificationCode']);
    Route::post('/verify-email', [AuthController::class, 'verifyEmail'])->name('verification.verify');
    Route::get('/me', [UserController::class, 'currentUser'])->name('user.me');
    Route::get('/logout', [AuthController::class, 'logout'])->name('user.logout');
    Route::post('/set-transaction-pin', [UserController::class, 'setTransaction'])->name('user.set-transaction-pin');

    Route::middleware('verified')->group(function () {
        Route::get('/accounts', [AccountController::class, 'index']);
        Route::post('/accounts/{account}/cash-deposit', [AccountController::class, 'cashDeposit'])
                ->middleware('ensure.is.admin')
                ->name('accounts.cash-deposit');

        Route::post('/accounts', [AccountController::class, 'store']);
        Route::post('/accounts/{account}/intra-transfer', [AccountController::class, 'intraBankTransfer'])->middleware(EnsureHasTrnxPin::class);
        Route::get('/accounts/{account}', [AccountController::class, 'show']);
        Route::delete('/accounts/{account}', [AccountController::class, 'destroy']);

        Route::resource(
            'accounts.transactions',
            AccountTransactionController::class)
        ->only(['index', 'show']);
        Route::post(
            '/accounts/{account}/transactions/{transaction}/report',
            [AccountTransactionController::class, 'report']
        );
    });
});
