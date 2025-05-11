<?php

namespace App\Services;

use App\Models\User;
use App\Models\Coin;

class SubscribeToCoinService
{
    public function subscribe(User $user, Coin $coin): void
    {
        $user->subscriptions()->syncWithoutDetaching([$coin->id]);
    }
}
