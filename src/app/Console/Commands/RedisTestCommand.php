<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class RedisTestCommand extends Command
{
    protected $signature = 'redis:test';
    protected $description = 'Test Redis connection and basic set/get functionality';

    public function handle(): int
    {
        try {
            Cache::put('redis_test_key', 'ok', 300);
            $value = Cache::get('redis_test_key');

            if ($value === 'ok') {
                $this->info('✅ Redis is working. Value: ' . $value);
            } else {
                $this->error('❌ Redis key not found or mismatched');
            }
        } catch (\Throwable $e) {
            $this->error('❌ Redis error: ' . $e->getMessage());
        }

        return self::SUCCESS;
    }
}
