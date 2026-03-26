<?php

namespace App\Notifications;

use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CreatorEarningReceived extends Notification
{
    use Queueable;

    public function __construct(
        protected float $amount,
        protected string $itemTitle,
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $symbol = Setting::get('currency_symbol', '£');

        return [
            'title' => 'Creator Earning Received',
            'message' => "You earned {$symbol}" . number_format($this->amount, 2) . " from a sale of \"{$this->itemTitle}\"!",
            'icon' => 'currency-pound',
            'color' => 'green',
            'url' => route('creator.earnings.index'),
        ];
    }
}
