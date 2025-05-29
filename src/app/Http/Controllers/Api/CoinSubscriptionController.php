<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscribeToCoinRequest;
use App\Http\Requests\UpdateCoinSubscriptionRequest;
use App\Models\Coin;
use App\Models\User;
use App\Services\SubscribeToCoinService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CoinSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            $request->user()
                ->coinSubscriptions()
                ->get()
                ->map(function ($coin) {
                    return [
                        'coin_id' => $coin->id,
                        'coin' => [
                            'id' => $coin->id,
                            'name' => $coin->name,
                            'symbol' => $coin->symbol,
                            'price' => $coin->price,
                        ],
                        'notification_frequency' => $coin->subscription?->notification_frequency,
                        'change_threshold' => $coin->subscription?->change_threshold,
                    ];
                })
        );
    }

    public function store(
        SubscribeToCoinRequest $request,
        SubscribeToCoinService $service
    ) {
        /** @var User $user */
        $user = $request->user();

        $data = $request->validated();
        $coin = Coin::findOrFail($data['coin_id']);

        $frequency = $data['notification_frequency'] ?? 'daily';
        $threshold = $data['change_threshold'] ?? 1.0;

        $service->subscribe($user, $coin, $frequency, $threshold);

        Log::info('Coin subscribed', [
            'user_id' => $user->id,
            'email' => $user->email,
            'coin_id' => $coin->id,
            'notification_frequency' => $frequency,
            'change_threshold' => $threshold,
            'time' => now()->toDateTimeString(),
        ]);

        return response()->json(['message' => __('Subscribed successfully')]);
    }

    public function update(UpdateCoinSubscriptionRequest $request, $coinId)
    {
        /** @var User $user */
        $user = $request->user();

        $exists = $user->coinSubscriptions()->where('coin_id', $coinId)->exists();

        if (! $exists) {
            return response()->json(['message' => __('Subscription not found')], 404);
        }

        $user->coinSubscriptions()->updateExistingPivot($coinId, $request->validated());

        Log::info('Coin subscription updated', [
            'user_id' => $user->id,
            'coin_id' => $coinId,
            'updates' => $request->validated(),
            'time' => now()->toDateTimeString(),
        ]);

        return response()->json(['message' => __('Subscription updated')]);
    }

    public function destroy($coinId)
    {
        /** @var User $user */
        $user = auth()->user();

        if (! $user) {
            return response()->json(['message' => __('Unauthorized')], 401);
        }

        $attached = $user->coinSubscriptions()->where('coin_id', $coinId)->exists();

        if ($attached) {
            $user->coinSubscriptions()->detach($coinId);

            Log::info('Coin unsubscribed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'coin_id' => $coinId,
                'time' => now()->toDateTimeString(),
            ]);

            return response()->json(['message' => __('Unsubscribed')]);
        }

        Log::warning('Coin unsubscribe attempt for non-existent subscription', [
            'user_id' => $user->id,
            'email' => $user->email,
            'coin_id' => $coinId,
            'time' => now()->toDateTimeString(),
        ]);

        return response()->json(['message' => __('Subscription not found')], 404);
    }
}
