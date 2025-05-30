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
        // Получаем пользователя по email
        $user = User::where('email', $credentials['email'])->first();

        // Проверяем, существует ли пользователь и забанен ли он
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
