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
use Illuminate\Validation\Rule;

class CoinSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            $request->user()
                ->coinSubscriptions()
                ->withPivot('notification_frequency', 'change_threshold')
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
                        'notification_frequency' => $coin->subscription->notification_frequency,
                        'change_threshold' => $coin->subscription->change_threshold,
                    ];
                })
        );
    }

    public function store(
        SubscribeToCoinRequest $request,
        SubscribeToCoinService $service
    ) {
        $coin = Coin::findOrFail($request->validated()['coin_id']);

        $service->subscribe(
            $request->user(),
            $coin,
            $request->validated()['notification_frequency'],
            $request->validated()['change_threshold'] ?? null
        );

        return response()->noContent();
    }

    public function update(UpdateCoinSubscriptionRequest $request, $coinId)
    {
        /** @var User $user */
        $user = $request->user();

        $subscription = $user->coinSubscriptions()->where('coin_id', $coinId)->first();

        if (!$subscription) {
            return response()->json(['message' => 'Subscription not found'], 404);
        }

        $subscription->update($request->validated());

        return response()->json(['message' => 'Subscription updated']);
    }

    public function destroy($coinId)
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $subscription = $user->coinSubscriptions()->where('coin_id', $coinId)->first();

        if ($subscription) {
            $subscription->delete();

            Log::info('Coin unsubscribed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'coin_id' => $coinId,
                'time' => now()->toDateTimeString(),
            ]);

            return response()->json(['message' => 'Unsubscribed']);
        }

        Log::warning('Coin unsubscribe attempt for non-existent subscription', [
            'user_id' => $user->id,
            'email' => $user->email,
            'coin_id' => $coinId,
            'time' => now()->toDateTimeString(),
        ]);

        return response()->json(['message' => 'Subscription not found'], 404);
    }
}
