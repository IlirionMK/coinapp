<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscribeToCoinRequest;
use App\Models\Coin;
use App\Services\SubscribeToCoinService;
use Illuminate\Http\Request;

class CoinSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        return response()->json($request->user()->subscriptions);
    }

    public function store(
        SubscribeToCoinRequest $request,
        SubscribeToCoinService $service
    ) {
        $coin = Coin::findOrFail($request->validated()['coin_id']);
        $service->subscribe($request->user(), $coin);

        return response()->noContent();
    }

    public function destroy($coinId)
    {
        $user = auth()->user();

        $subscription = $user->subscriptions()->where('coin_id', $coinId)->first();

        if ($subscription) {
            $subscription->delete();
            return response()->json(['message' => 'Unsubscribed']);
        }

        return response()->json(['message' => 'Subscription not found'], 404);
    }
}
