<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class LogoutService
{
    public function logout(): void
    {
        Auth::guard('web')->logout();
    }
}
