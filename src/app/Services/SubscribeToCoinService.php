<?php

namespace App\Services;

use App\Models\User;
use App\Models\Coin;
use App\Models\CoinSubscription;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SubscribeToCoinService
{
    public function subscribe(User $user, Coin $coin, string $frequency = null, ?float $threshold = null): void
    {
        $exists = CoinSubscription::query()
            ->where('user_id', $user->id)
            ->where('coin_id', $coin->id)
            ->exists();

        if ($exists) {
            Log::warning('Duplicate subscription attempt', [
                'user_id' => $user->id,
                'email' => $user->email,
                'coin_id' => $coin->id,
                'coin' => $coin->symbol,
                'time' => now()->toDateTimeString(),
            ]);

            throw new HttpException(409, 'Already subscribed to this coin.');
        }

        $frequency = $frequency ?? 'daily';
        $threshold = $threshold ?? 1.0;

        $user->coinSubscriptions()->attach($coin->id, [
            'notification_frequency' => $frequency,
            'change_threshold' => $threshold,
        ]);

        Log::info('Coin subscription created', [
            'user_id' => $user->id,
            'email' => $user->email,
            'coin_id' => $coin->id,
            'coin' => $coin->symbol,
            'notification_frequency' => $frequency,
            'change_threshold' => $threshold,
            'time' => now()->toDateTimeString(),
        ]);
    }
}
