<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SyncNewsFromApi;

class SyncNewsCommand extends Command
{
    protected $signature = 'news:sync';
    protected $description = 'Fetch and store the latest cryptocurrency news from API';

    public function handle()
    {
        $this->info('Dispatching SyncNewsFromApi job...');
        SyncNewsFromApi::dispatch();
        $this->info('Done.');
    }
}
