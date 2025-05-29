<?php

namespace App\Services;

use App\Models\User;
use App\Models\Coin;
use Illuminate\Support\Facades\Log;
use App\Enums\NotificationFrequency;

class SubscribeToCoinService
{
    public function subscribe(User $user, Coin $coin, NotificationFrequency|string|null $frequency = null, ?float $threshold = null): void
    {
        $frequency = $frequency instanceof NotificationFrequency
            ? $frequency
            : NotificationFrequency::tryFrom($frequency) ?? NotificationFrequency::Daily;

        $threshold = $threshold ?? 1.0;

        $user->coinSubscriptions()->attach($coin->id, [
            'notification_frequency' => $frequency->value,
            'change_threshold' => $threshold,
        ]);

        Log::info('Coin subscription created or updated', [
            'user_id' => $user->id,
            'email' => $user->email,
            'coin_id' => $coin->id,
            'coin' => $coin->symbol,
            'notification_frequency' => $frequency->value,
            'change_threshold' => $threshold,
            'time' => now()->toDateTimeString(),
        ]);
    }
}
