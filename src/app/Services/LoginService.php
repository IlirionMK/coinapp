<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginService
{
    public function attempt(array $credentials): bool
    {
        $success = Auth::attempt($credentials);

        if (! $success) {
            Log::warning('Неудачная попытка входа', [
                'email' => $credentials['email'] ?? null,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now()->toDateTimeString(),
            ]);
        }

        return $success;
    }

    public function currentUser()
    {
        return Auth::user();
    }
}
