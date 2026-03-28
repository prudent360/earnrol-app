<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AffiliateCommissionEarned extends Notification
{
    use Queueable;

    public function __construct(
        protected float $amount,
        protected string $itemTitle
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Affiliate Commission Earned',
            'message' => 'You earned ' . number_format($this->amount, 2) . ' from an affiliate sale of ' . $this->itemTitle . '.',
            'icon' => 'cash',
            'color' => 'green',
            'url' => route('affiliate.earnings'),
        ];
    }
}
