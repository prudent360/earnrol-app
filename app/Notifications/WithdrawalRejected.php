<?php

namespace App\Notifications;

use App\Models\Setting;
use App\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WithdrawalRejected extends Notification
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
        $note = $this->withdrawal->admin_note ? ' Reason: ' . $this->withdrawal->admin_note : '';

        return [
            'title' => 'Withdrawal Rejected',
            'message' => 'Your withdrawal of ' . $symbol . number_format($this->withdrawal->amount, 2) . ' was rejected.' . $note,
            'icon' => 'x-circle',
            'color' => 'red',
            'url' => route('referrals.index'),
        ];
    }
}
