<?php

namespace App\Services;

use App\Models\User;
use App\Models\Coin;
use Illuminate\Support\Facades\Log;

class SubscribeToCoinService
{
    public function subscribe(User $user, Coin $coin, string $frequency = null, ?float $threshold = null): void
    {
        $frequency = $frequency ?? 'daily';
        $threshold = $threshold ?? 1.0;

        $user->coinSubscriptions()->syncWithoutDetaching([
            $coin->id => [
                'notification_frequency' => $frequency,
                'change_threshold' => $threshold,
            ]
        ]);

        Log::info('Coin subscription created or updated', [
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
