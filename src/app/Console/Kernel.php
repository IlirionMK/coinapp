<?php

namespace App\Console;

//use App\Console\Commands\IconsSyncCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SyncCoinsCommand;
use App\Console\Commands\CacheCoinImages;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        SyncCoinsCommand::class,
        CacheCoinImages::class,
        //IconsSyncCommand::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Updating prices every 10 min
        $schedule->command('coins:sync')->everyTenMinutes();
        //$schedule->command('coins:cache-images')->dailyAt('02:00');

    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
