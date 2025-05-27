<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Services\UserRegistrationService;
use App\Services\LoginService;
use App\Services\LogoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request, UserRegistrationService $service)
    {
        $user = $service->register($request->validated());

        $user->sendEmailVerificationNotification();

        Log::info('User registered', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'time' => now()->toDateTimeString(),
        ]);

        return response()->json(['message' => 'Registered']);
    }

    public function login(Request $request, LoginService $loginService)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! $loginService->attempt($credentials, $request)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $request->session()->regenerate();

        return response()->json($loginService->currentUser());
    }

    public function logout(Request $request, LogoutService $logoutService)
    {
        $logoutService->logout();

        return response()->json(['message' => 'Logged out']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
