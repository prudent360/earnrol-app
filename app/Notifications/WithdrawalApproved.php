<?php

namespace App\Notifications;

use App\Models\Setting;
use App\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WithdrawalApproved extends Notification
{
    use Queueable;

    public function __construct(protected Withdrawal $withdrawal) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $symbol = Setting::get('currency_symbol', '£');

        return [
            'title' => 'Withdrawal Approved',
            'message' => 'Your withdrawal of ' . $symbol . number_format($this->withdrawal->amount, 2) . ' has been approved.',
            'icon' => 'check-circle',
            'color' => 'green',
            'url' => route('referrals.index'),
        ];
    }
}
