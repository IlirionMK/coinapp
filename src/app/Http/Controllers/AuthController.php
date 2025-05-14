<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Services\UserRegistrationService;
use App\Services\LoginService;
use App\Services\LogoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request, UserRegistrationService $service)
    {
        $user = $service->register($request->validated());

        event(new Registered($user));

        Auth::login($user);

        return response()->json($user);
    }

    public function login(Request $request, LoginService $loginService)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!$loginService->attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

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
