<?php

namespace App\Notifications;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReferralCommissionEarned extends Notification
{
    use Queueable;

    public function __construct(
        protected User $referredUser,
        protected float $amount,
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $symbol = Setting::get('currency_symbol', '£');

        return [
            'title' => 'Referral Commission Earned',
            'message' => $this->referredUser->name . ' made their first payment. You earned ' . $symbol . number_format($this->amount, 2) . '!',
            'icon' => 'currency-pound',
            'color' => 'green',
            'url' => route('referrals.index'),
        ];
    }
}
