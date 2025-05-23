<?php

namespace App\Services;

use App\Models\User;
use App\Models\Coin;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SubscribeToCoinService
{
    public function subscribe(User $user, Coin $coin): void
    {
        if ($user->subscriptions()->where('coin_id', $coin->id)->exists()) {
            throw new HttpException(409, 'Already subscribed to this coin.');
        }

        $user->subscriptions()->attach($coin->id);
    }
}
