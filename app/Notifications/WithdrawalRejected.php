<?php

namespace App\Notifications;

use App\Mail\TemplateMail;
use App\Models\Setting;
use App\Models\Withdrawal;
use App\Services\Mail\TemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WithdrawalRejected extends Notification
{
    use Queueable;

    public function __construct(protected Withdrawal $withdrawal) {}

    public function via($notifiable): array
    {
        $channels = ['database'];
        if (TemplateService::isEnabled('withdrawal_rejected')) {
            $channels[] = 'mail';
        }
        return $channels;
    }

    public function toMail($notifiable)
    {
        $symbol = Setting::get('currency_symbol', '£');

        return (new TemplateMail('withdrawal_rejected', [
            'name'   => $notifiable->name,
            'amount' => $symbol . number_format((float)$this->withdrawal->amount, 2),
            'reason' => $this->withdrawal->admin_note ?? 'No reason provided.',
        ]))->to($notifiable->email);
    }

    public function toArray($notifiable): array
    {
        $symbol = Setting::get('currency_symbol', '£');
        $note = $this->withdrawal->admin_note ? ' Reason: ' . $this->withdrawal->admin_note : '';

        return [
            'title' => 'Withdrawal Rejected',
            'message' => 'Your withdrawal of ' . $symbol . number_format((float)$this->withdrawal->amount, 2) . ' was rejected.' . $note,
            'icon' => 'x-circle',
            'color' => 'red',
            'url' => route('referrals.index'),
        ];
    }
}
