<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Coin;

class SyncCoinsCommand extends Command
{
    protected $signature = 'coins:sync';
    protected $description = 'Sync cryptocurrency data from CoinGecko API';

    public function handle(): void
    {
        $this->info('Fetching data from CoinGecko...');

        $response = Http::get('https://api.coingecko.com/api/v3/coins/markets', [
            'vs_currency' => 'usd',
            'order' => 'market_cap_desc',
            'per_page' => 10,
            'page' => 1,
            'sparkline' => false,
        ]);

        if ($response->failed()) {
            $this->error('Failed to fetch data from CoinGecko.');
            return;
        }

        foreach ($response->json() as $item) {
            Coin::updateOrCreate(
                ['coingecko_id' => $item['id']],
                [
                    'symbol' => $item['symbol'],
                    'name' => $item['name'],
                    'image' => $item['image'],
                    'price' => $item['current_price'],
                    'market_cap' => $item['market_cap'],
                    'market_cap_rank' => $item['market_cap_rank'],
                    'price_change_percentage_24h' => $item['price_change_percentage_24h'],
                ]
            );
        }

        $this->info('Data successfully synced.');
    }
}
