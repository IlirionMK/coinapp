<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Coin;

class IconsSyncCommand extends Command
{
    protected $signature = 'icons:sync';
    protected $description = 'Download cryptocurrency icons and save them locally';

    public function handle(): int
    {
        $this->info('Starting to sync icons...');

        $savePath = public_path('icons');

        if (!is_dir($savePath)) {
            mkdir($savePath, 0755, true);
            $this->info('Created directory: /public/icons/');
        }

        $coins = Coin::select('coingecko_id', 'image')->get();

        foreach ($coins as $coin) {
            if (empty($coin->coingecko_id) || empty($coin->image)) {
                $this->warn("Skipping coin with missing data: " . json_encode($coin));
                continue;
            }

            $filename = strtolower($coin->coingecko_id) . '.png';
            $filePath = $savePath . '/' . $filename;

            if (file_exists($filePath)) {
                $this->info("Icon already exists: $filename, skipping...");
                continue;
            }

            try {
                $this->info("Downloading icon for {$coin->coingecko_id}...");

                $response = Http::timeout(15)->get($coin->image);

                if ($response->successful()) {
                    file_put_contents($filePath, $response->body());
                    $this->info("Saved: $filename");
                } else {
                    $this->error("Failed to download icon for {$coin->coingecko_id}: HTTP " . $response->status());
                }
            } catch (\Exception $e) {
                $this->error("Error downloading {$coin->coingecko_id}: {$e->getMessage()}");
            }
        }

        $this->info('Icons synchronization complete.');
        return 0;
    }
}
