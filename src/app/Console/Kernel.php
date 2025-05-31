<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Console\Commands\SyncCoinsCommand;
use App\Console\Commands\CacheCoinImages;
use App\Console\Commands\SyncNewsCommand;
use App\Console\Commands\NotifyCoinSubscribers;
use App\Jobs\SyncNewsFromApi;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        SyncCoinsCommand::class,
        CacheCoinImages::class,
        SyncNewsCommand::class,
        NotifyCoinSubscribers::class,
    ];


    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('coins:sync')->everyTenMinutes();
        $schedule->command('notify:coin-subscribers')->everyFiveMinutes()->withoutOverlapping();

        //$schedule->job(new SyncNewsFromApi)->everyTenMinutes();
        $schedule->command('log:test')->everyTenMinutes();
    }


    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
