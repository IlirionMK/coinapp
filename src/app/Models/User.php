<?php

namespace App\Models;

use App\Models\Coin;
use App\Models\UserSetting;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\QueuedVerifyEmail;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_banned',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_banned' => 'boolean',
        ];
    }


    /**
     * Override default email verification notification to queue-based one.
     */
    public function sendEmailVerificationNotification(): void
    {
        if ($this->hasVerifiedEmail()) {
            return;
        }

        $this->notify(new QueuedVerifyEmail());
    }

    public function settings()
    {
        return $this->hasOne(UserSetting::class);
    }

    public function coinSubscriptions()
    {
        return $this->belongsToMany(Coin::class, 'coin_subscriptions')
            ->using(\App\Models\CoinSubscription::class)
            ->withPivot(['notification_frequency', 'change_threshold'])
            ->as('subscription');
    }
}
