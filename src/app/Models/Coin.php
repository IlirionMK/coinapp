<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    protected $fillable = [
        'coingecko_id',
        'symbol',
        'name',
        'image',
        'price',
        'price_change_percentage_24h',
        'market_cap',
        'volume_24h',
    ];

    protected $casts = [
        'price' => 'float',
        'price_change_percentage_24h' => 'float',
        'market_cap' => 'integer',
        'volume_24h' => 'float',
    ];

    protected $appends = ['icon_path'];

    public function getIconPathAttribute(): string
    {
        return "/icons/" . strtolower($this->symbol) . ".png";
    }
    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'coin_subscriptions')
            ->using(\App\Models\CoinSubscription::class)
            ->withPivot(['notification_frequency', 'change_threshold']);
    }

}
