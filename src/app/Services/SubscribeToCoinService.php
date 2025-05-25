<?php

namespace App\Services;

use App\Models\User;
use App\Models\Coin;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SubscribeToCoinService
{
    public function subscribe(User $user, Coin $coin): void
    {
        if ($user->subscriptions()->where('coin_id', $coin->id)->exists()) {
            Log::warning('Duplicate subscription attempt', [
                'user_id' => $user->id,
                'email' => $user->email,
                'coin_id' => $coin->id,
                'coin' => $coin->symbol,
                'time' => now()->toDateTimeString(),
            ]);

            throw new HttpException(409, 'Already subscribed to this coin.');
        }

        $user->subscriptions()->attach($coin->id);

        Log::info('Coin subscription created', [
            'user_id' => $user->id,
            'email' => $user->email,
            'coin_id' => $coin->id,
            'coin' => $coin->symbol,
            'time' => now()->toDateTimeString(),
        ]);
    }
}
