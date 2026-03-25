<?php

namespace App\Notifications;

use App\Models\Setting;
use App\Models\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WithdrawalRequested extends Notification
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
            'title' => 'New Withdrawal Request',
            'message' => $this->withdrawal->user->name . ' has requested a withdrawal of ' . $symbol . number_format($this->withdrawal->amount, 2),
            'icon' => 'currency-pound',
            'color' => 'amber',
            'url' => route('admin.withdrawals.index', ['status' => 'pending']),
        ];
    }
}
