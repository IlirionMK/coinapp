<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginService
{
    public function attempt(array $credentials, $request): bool
    {
        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->is_banned) {
            Log::warning('Blocked user login attempt', [
                'email' => $credentials['email'],
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'time' => now()->toDateTimeString(),
            ]);

            throw ValidationException::withMessages([
                'email' => [__('auth.banned')],
            ]);
        }

        $success = Auth::attempt($credentials);

        if ($success) {
            $request->session()->regenerate();

            Log::info('Successful login', [
                'email' => $credentials['email'],
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'time' => now()->toDateTimeString(),
            ]);
        } else {
            Log::warning('Failed login attempt', [
                'email' => $credentials['email'],
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'time' => now()->toDateTimeString(),
            ]);
        }

        return $success;
    }

    public function currentUser()
    {
        return Auth::user();
    }
}
