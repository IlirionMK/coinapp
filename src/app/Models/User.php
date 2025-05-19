<?php

namespace App\Models;

use App\Models\Coin;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\QueuedVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

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
        ];
    }

    /**
     * Subscribed coins (many-to-many).
     */
    public function subscriptions()
    {
        return $this->belongsToMany(Coin::class);
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
}
