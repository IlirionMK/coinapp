<?php

namespace App\Models;

use App\Enums\NotificationFrequency;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CoinSubscription extends Pivot
{
    public $incrementing = false;
    public $timestamps = false;

    protected $table = 'coin_subscriptions';
    protected $primaryKey = null;

    protected $fillable = [
        'user_id',
        'coin_id',
        'notification_frequency',
        'change_threshold',
    ];

    protected $casts = [
        'notification_frequency' => NotificationFrequency::class,
    ];

    public function coin()
    {
        return $this->belongsTo(Coin::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
