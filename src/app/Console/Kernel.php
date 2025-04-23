<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SyncCoinsCommand;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        SyncCoinsCommand::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Updating prices every 10 min
         $schedule->command('coins:sync')->everyTenMinutes();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
