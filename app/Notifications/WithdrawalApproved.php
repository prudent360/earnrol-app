<?php

namespace App\Notifications;

use App\Mail\TemplateMail;
use App\Models\Setting;
use App\Models\Withdrawal;
use App\Services\Mail\TemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WithdrawalApproved extends Notification
{
    use Queueable;

    public function __construct(protected Withdrawal $withdrawal) {}

    public function via($notifiable): array
    {
        $channels = ['database'];
        if (TemplateService::isEnabled('withdrawal_approved')) {
            $channels[] = 'mail';
        }
        return $channels;
    }

    public function toMail($notifiable)
    {
        $symbol = Setting::get('currency_symbol', '£');

        return (new TemplateMail('withdrawal_approved', [
            'name'   => $notifiable->name,
            'amount' => $symbol . number_format((float)$this->withdrawal->amount, 2),
        ]))->to($notifiable->email);
    }

    public function toArray($notifiable): array
    {
        $symbol = Setting::get('currency_symbol', '£');

        return [
            'title' => 'Withdrawal Approved',
            'message' => 'Your withdrawal of ' . $symbol . number_format((float)$this->withdrawal->amount, 2) . ' has been approved.',
            'icon' => 'check-circle',
            'color' => 'green',
            'url' => route('referrals.index'),
        ];
    }
}
