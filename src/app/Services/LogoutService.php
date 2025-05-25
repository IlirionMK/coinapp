<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogoutService
{
    public function logout(): void
    {
        $user = Auth::user();

        if ($user) {
            Log::info(' User logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
                'time' => now()->toDateTimeString(),
            ]);
        }

        Auth::guard('web')->logout();
    }
}
