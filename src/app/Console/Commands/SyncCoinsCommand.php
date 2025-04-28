<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Coin;
use Throwable;

class SyncCoinsCommand extends Command
{
    protected $signature   = 'coins:sync {--pages=4 : How many pages of 250 coins to fetch}';
    protected $description = 'Fetch and update cryptocurrency data from CoinGecko';

    private const BASE_URL = 'https://api.coingecko.com/api/v3/coins/markets';
    private const PER_PAGE = 20; // максимальное значение CoinGecko

    public function handle(): int
    {
        if (!Schema::hasTable('coins')) {
            $this->error('Table "coins" does not exist. Run: php artisan migrate');
            return self::FAILURE;
        }

        $pages = max(1, (int) $this->option('pages'));
        $this->info("Syncing up to {$pages} × " . self::PER_PAGE . ' coins…');

        // Проверяем, существует ли папка icons, иначе создаём
        $iconsPath = public_path('icons');
        if (!is_dir($iconsPath)) {
            mkdir($iconsPath, 0755, true);
        }

        try {
            for ($page = 1; $page <= $pages; $page++) {
                $response = Http::retry(3, 200)->get(self::BASE_URL, [
                    'vs_currency' => 'usd',
                    'order'       => 'market_cap_desc',
                    'per_page'    => self::PER_PAGE,
                    'page'        => $page,
                    'sparkline'   => false,
                ]);

                // Rate-limit protection
                if ($response->status() === 429) {
                    $wait = (int) $response->header('Retry-After', 5);
                    $this->warn("Rate-limited: waiting {$wait}s…");
                    sleep($wait);
                    $page--;   // повторяем ту же страницу
                    continue;
                }

                if (!$response->successful()) {
                    $this->error("CoinGecko request failed: HTTP {$response->status()}");
                    return self::FAILURE;
                }

                $coins = $response->json();
                if (empty($coins)) {
                    $this->line('No more coins — stopping early.');
                    break;
                }

                foreach ($coins as $c) {
                    $symbol = Str::lower($c['symbol']);
                    $localPath = $iconsPath . '/' . $symbol . '.png';
                    $publicPath = '/icons/' . $symbol . '.png';

                    // Проверяем наличие локальной иконки
                    if (!file_exists($localPath)) {
                        $this->line("Downloading icon for {$c['name']}...");

                        try {
                            $iconResponse = Http::retry(3, 200)->get($c['image']);

                            if ($iconResponse->successful()) {
                                file_put_contents($localPath, $iconResponse->body());
                            } else {
                                $this->warn("Failed to download icon for {$c['name']} (HTTP {$iconResponse->status()})");
                            }
                        } catch (Throwable $e) {
                            $this->warn("Error downloading icon for {$c['name']}: " . $e->getMessage());
                        }
                    }

                    // Обновляем или создаём монету
                    $coin = Coin::updateOrCreate(
                        ['coingecko_id' => $c['id']],
                        [
                            'name'                        => $c['name'],
                            'symbol'                      => Str::upper($c['symbol']),
                            'image'                       => $publicPath,
                            'price'                       => $c['current_price'],
                            'market_cap'                  => $c['market_cap'],
                            'market_cap_rank'             => $c['market_cap_rank'],
                            'price_change_percentage_24h' => $c['price_change_percentage_24h'],
                        ]
                    );

                    if ($coin->wasRecentlyCreated) {
                        $this->line("➕  Added new coin: {$coin->name}");
                    }
                }

                // CoinGecko рекомендует не превышать ~50 запросов/мин.
                sleep(1);
            }
        } catch (Throwable $e) {
            $this->error('Unexpected error: ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->info('Coins synced successfully ✔');
        return self::SUCCESS;
    }
}
