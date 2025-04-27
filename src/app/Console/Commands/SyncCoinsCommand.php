<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Coin;

class SyncCoinsCommand extends Command
{
    protected $signature = 'coins:sync';
    protected $description = 'Fetch and update cryptocurrency data from CoinGecko';

    public function handle(): int
    {
        $this->info('Fetching coins from CoinGecko...');

        $response = Http::get('https://api.coingecko.com/api/v3/coins/markets', [
            'vs_currency' => 'usd',
            'order' => 'market_cap_desc',
            'per_page' => 100,
            'page' => 1,
            'sparkline' => false,
        ]);

        if (!$response->successful()) {
            $this->error('Failed to fetch data');
            return Command::FAILURE;
        }

        foreach ($response->json() as $data) {
            Coin::updateOrCreate(
                ['coingecko_id' => $data['id']],
                [
                    'name' => $data['name'],
                    'symbol' => $data['symbol'],
                    'image' => $data['image'],
                    'price' => $data['current_price'],
                    'market_cap' => $data['market_cap'],
                    'market_cap_rank' => $data['market_cap_rank'],
                    'price_change_percentage_24h' => $data['price_change_percentage_24h'],
                ]
            );
        }

        $this->info('Coins synced successfully.');
        return Command::SUCCESS;
    }
}
