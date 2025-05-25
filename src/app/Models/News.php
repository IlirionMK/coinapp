<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'uuid',
        'title',
        'url',
        'source',
        'summary',
        'published_at',
        'currencies',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'currencies' => 'array',
    ];
}
