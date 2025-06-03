<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class UserRegistrationService
{
    public function register(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
        ]);

        Log::info('User created via registration service', [
            'user_id' => $user->id,
            'email' => $user->email,
            'time' => now()->toDateTimeString(),
        ]);

        Cache::tags(['admin_users'])->flush();

        return $user;
    }
}
