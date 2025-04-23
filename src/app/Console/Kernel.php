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
        // Здесь можно будет настроить запуск по расписанию
        // $schedule->command('coins:sync')->everyFiveMinutes();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
