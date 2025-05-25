<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginService
{
    public function attempt(array $credentials, $request): bool
    {
        $success = Auth::attempt($credentials);

        if ($success) {
            Log::info(' Successful login', [
                'email' => $credentials['email'],
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'time' => now()->toDateTimeString(),
            ]);
        } else {
            Log::warning(' Failed login attempt', [
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
