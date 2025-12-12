<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Models\Otp;
use App\Models\User;
use App\Notifications\SendResetPasswordTokenNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $loginRequest)
    {
        $data = $loginRequest->validated();

        $user = User::where('email', $data['email'])->first();

        if(!$user)
            throw ValidationException::withMessages(['email' => 'Invalid Credentials']);

        if(!Hash::check($data['password'], $user->password))
            throw ValidationException::withMessages(['email' => 'Incorrect Login Credentials']);

        Auth::login($user);

        $token = $user->createToken('access_token')->plainTextToken;

        return response()->json([
           'message' => 'Login successful',
           'data' => [
                'user' => $user,
                'token' => $token
            ],
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

        $user = DB::transaction(function() use($data){
            $user = User::create($data);
            return $user;
        });

        Auth::login($user);
        event(new Registered(user: $user));

        return response()->json([
           'message' => 'Registration Completed',
           'data' => [
                'user' => $user,
                'token' => $user->createToken('access_token')->plainTextToken
            ]
        ]);
    }

    public function resendVerificationCode()
    {
        $user = auth()->user();
        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Verification code has been sent again. check your email'
        ]);
    }

    public function verifyEmail(VerifyEmailRequest $request): JsonResponse
    {
        /** @var App\Models\User $user */
        $user = $request->user();

        $otp = $user->otps()
                ->where('type', 'verification')
                ->where('code', $request->validated('otp'))
                ->latest()
                ->first();

        if(!$otp){
            throw ValidationException::withMessages(['otp' => 'Invalid Otp']);
        }

        if($otp->isExpired()){
            throw ValidationException::withMessages(['otp' => 'Otp has expired']);
        }

        $user->markEmailAsVerified();

        return response()->json([
           'message' => 'Verification Successfull',
           'data' => [
                'user' => auth()->user(),
            ]
        ]);
    }

    public function forgetPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email']
        ]);

        $user = User::query()->whereEmailIs($data['email'])->first();

        if($user){
            $token = random_int(1000000, 9999999);
            $user->otps()->create([
                'code' => $token,
                'expires_at' => now()->addMinutes(10),
            ]);
            $user->notify((new SendResetPasswordTokenNotification($token))->delay(now()));
        }

        return response()->json(['message' => 'Email sent successfully'], 200, ['APP_USER_AVAI' => !!$user]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
           'email' => ['required', 'email', 'exists:users,email'],
           'otp' => ['required', 'string', 'min:7'],
           'password' => [Password::default(), 'confirmed', 'required']
        ]);

        $otp = Otp::query()
                ->where('code', $data['otp'])
                ->whereHas('user', fn($q) => $q->where('email', $data['email']))
                ->first();

        if(!$otp)
            throw ValidationException::withMessages(['otp' => 'Invalid otp']);

        if($otp->isExpired()){
            throw ValidationException::withMessages(['otp' => 'Otp has expired. try and get new']);
        }

        $password = Hash::make($data['password']);

        User::whereEmail($data['email'])->update(['password' => $password]);

        return response()->json(['message' => 'Password Reset completed. Please proceed to login.']);
    }

    public function logout()
    {
        $user = auth()->user();

        $user->tokens()->delete();

        return response()->json(['message' => 'Logout Successfull.']);
    }
}
