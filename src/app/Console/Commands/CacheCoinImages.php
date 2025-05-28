<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Models\Coin;


class CacheCoinImages extends Command
{
    protected $signature = 'coins:cache-images';
    protected $description = 'Download and cache coin images locally through proxy';

    public function handle()
    {
        $coins = Coin::select('id', 'name', 'image')->get();

        $this->info('Caching coin images...');

        foreach ($coins as $coin) {
            if (!$coin->image) {
                $this->warn("Skipping coin {$coin->name}: No image URL.");
                continue;
            }

            $key = 'img_proxy_' . md5($coin->image);

            if (Cache::has($key)) {
                $this->info("Already cached: {$coin->name}");
                continue;
            }

            try {
                $response = Http::timeout(5)->get($coin->image);

                if ($response->successful()) {
                    Cache::put($key, $response->body(), now()->addDay());
                    $this->info("Cached: {$coin->name}");
                } else {
                    $this->warn("Failed to fetch image for {$coin->name}");
                }
            } catch (\Exception $e) {
                $this->error("Error caching {$coin->name}: {$e->getMessage()}");
            }
        }

        $this->info('All coin images cached!');
    }
}
