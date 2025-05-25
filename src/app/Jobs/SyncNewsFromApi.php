<?php

namespace App\Jobs;

use App\Models\News;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncNewsFromApi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        Log::info('[SyncNewsFromApi] Starting job');

        $token = config('services.cryptopanic.token');

        if (empty($token)) {
            Log::error('[SyncNewsFromApi] Missing CryptoPanic API token in config');
            return;
        }

        $url = 'https://cryptopanic.com/api/v1/posts/?auth=' . $token . '&public=true';

        $response = Http::get($url);

        if ($response->failed()) {
            Log::error('[SyncNewsFromApi] Failed to fetch news from CryptoPanic', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return;
        }

        Log::debug('[SyncNewsFromApi] Full response body', [
            'raw' => $response->body(),
            'parsed' => $response->json(),
        ]);

        $posts = $response->json('results');

        if (!is_array($posts)) {
            Log::warning('[SyncNewsFromApi] Unexpected response format', ['response' => $response->json()]);
            return;
        }

        foreach ($posts as $item) {
            if (!isset($item['title'], $item['published_at'], $item['url'])) {
                continue;
            }

            News::updateOrCreate(
                ['uuid' => $item['id']],
                [
                    'title' => $item['title'],
                    'url' => $item['url'],
                    'source' => $item['source']['title'] ?? null,
                    'summary' => $item['content'] ?? null,
                    'published_at' => $item['published_at'],
                    'currencies' => $item['currencies'] ?? [],
                ]
            );
        }

        Log::info('[SyncNewsFromApi] Synced ' . count($posts) . ' news items');
    }
}
