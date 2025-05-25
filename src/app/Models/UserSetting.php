<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $fillable = [
        'user_id',
        'notification_frequency',
        'change_threshold',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
