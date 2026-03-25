<?php

namespace App\Notifications;

use App\Mail\TemplateMail;
use App\Models\Setting;
use App\Models\User;
use App\Services\Mail\TemplateService;
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
        $channels = ['database'];
        if (TemplateService::isEnabled('referral_earned')) {
            $channels[] = 'mail';
        }
        return $channels;
    }

    public function toMail($notifiable)
    {
        $symbol = Setting::get('currency_symbol', '£');

        return (new TemplateMail('referral_earned', [
            'name'           => $notifiable->name,
            'referred_name'  => $this->referredUser->name,
            'amount'         => $symbol . number_format((float)$this->amount, 2),
            'wallet_balance' => $symbol . number_format((float)$notifiable->wallet_balance, 2),
            'referrals_url'  => route('referrals.index'),
        ]))->to($notifiable->email);
    }

    public function toArray($notifiable): array
    {
        $symbol = Setting::get('currency_symbol', '£');

        return [
            'title' => 'Referral Commission Earned',
            'message' => $this->referredUser->name . ' made a payment. You earned ' . $symbol . number_format((float)$this->amount, 2) . '!',
            'icon' => 'currency-pound',
            'color' => 'green',
            'url' => route('referrals.index'),
        ];
    }
}
