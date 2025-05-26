<?php

namespace App\Notifications;

use App\Models\Coin;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CoinPriceAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Coin $coin;
    protected float $oldPrice;
    protected float $newPrice;

    public function __construct(Coin $coin, float $oldPrice, float $newPrice)
    {
        $this->coin = $coin;
        $this->oldPrice = $oldPrice;
        $this->newPrice = $newPrice;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $diff = round((($this->newPrice - $this->oldPrice) / $this->oldPrice) * 100, 2);
        $symbol = strtoupper($this->coin->symbol);

        return (new MailMessage)
            ->subject("📈 Price Alert: {$this->coin->name}")
            ->greeting("Hello {$notifiable->name},")
            ->line("The price of {$this->coin->name} ({$symbol}) has changed.")
            ->line("Old price: \${$this->oldPrice}")
            ->line("New price: \${$this->newPrice}")
            ->line("Change: {$diff}%")
            ->action('View Coin News', url("/news?currency={$symbol}"))
            ->line('You are receiving this email because of your alert settings.');
    }
}
