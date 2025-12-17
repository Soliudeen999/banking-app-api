<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

Route::post('/login', [AuthController::class, 'login']);
// Route::post('/login', 'App\Http\Controllers\AuthController@login');

// Route::post('login', function(LoginRequest $loginRequest){
//     $data = $loginRequest->validated();

//         $user = User::where('email', $data['email'])->first();

//         if(!$user)
//             throw ValidationException::withMessages(['email' => 'Invalid Credentials']);

//         if(!Hash::check($data['password'], $user->password))
//             throw ValidationException::withMessages(['email' => 'Incorrect Login Credentials']);

//         Auth::login($user);

//         $token = $user->createToken('access_token')->plainTextToken;

//         return response()->json([
//            'message' => 'Login successful',
//            'data' => [
//                 'user' => $user,
//                 'token' => $token
//             ],
//         ]);
// });

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
        Route::post('/accounts/{account}/intra-transfer', [AccountController::class, 'intraBankTransfer']);
        Route::get('/accounts/{account}', [AccountController::class, 'show']);
        Route::delete('/accounts/{account}', [AccountController::class, 'destroy']);

    });
});
