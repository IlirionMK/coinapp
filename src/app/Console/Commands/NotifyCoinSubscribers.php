<?php

namespace App\Console\Commands;

use App\Models\Coin;
use App\Notifications\CoinPriceAlertNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class NotifyCoinSubscribers extends Command
{
    protected $signature = 'notify:coin-subscribers';

    protected $description = 'Send price change alerts to coin subscribers based on their preferences';

    public function handle(): int
    {
        $coins = Coin::with('subscribers')->get();

        foreach ($coins as $coin) {
            $previousPrice = Cache::get("coin_price:{$coin->id}");

            if (!$previousPrice) {
                Cache::put("coin_price:{$coin->id}", $coin->price, now()->addMinutes(15));
                continue;
            }

            $percentChange = abs(($coin->price - $previousPrice) / $previousPrice * 100);

            foreach ($coin->subscribers as $user) {
                $settings = $user->pivot;

                if (!$settings || $settings->notification_frequency === 'none') {
                    continue;
                }

                if (
                    $settings->change_threshold !== null &&
                    $percentChange < $settings->change_threshold
                ) {
                    continue;
                }

                $user->notify(new CoinPriceAlertNotification($coin, $previousPrice, $coin->price));
            }

            Cache::put("coin_price:{$coin->id}", $coin->price, now()->addMinutes(15));
        }

        $this->info('Notifications processed.');
        return self::SUCCESS;
    }
}
