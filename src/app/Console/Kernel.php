<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Console\Commands\SyncCoinsCommand;
use App\Console\Commands\CacheCoinImages;
use App\Console\Commands\SyncNewsCommand;
use App\Jobs\SyncNewsFromApi;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        SyncCoinsCommand::class,
        CacheCoinImages::class,
        SyncNewsCommand::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Updating prices every 10 min
        $schedule->command('coins:sync')->everyTenMinutes();

        // Sync news every 15 min
        $schedule->job(new SyncNewsFromApi)->everyFifteenMinutes();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
