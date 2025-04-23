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
        'market_cap',
        'market_cap_rank',
        'price_change_percentage_24h',
    ];
}
