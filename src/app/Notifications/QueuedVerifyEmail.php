<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;

class QueuedVerifyEmail extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        Log::info('[EMAIL] verify sent', [
            'user_id' => $notifiable->id,
            'email' => $notifiable->email,
            'time' => now()->toDateTimeString(),
        ]);

        return parent::via($notifiable);
    }
}
