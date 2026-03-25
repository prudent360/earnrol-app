<?php

namespace App\Notifications;

use App\Mail\TemplateMail;
use App\Models\Payment;
use App\Models\Setting;
use App\Services\Mail\TemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentApproved extends Notification
{
    use Queueable;

    public function __construct(protected Payment $payment) {}

    public function via($notifiable): array
    {
        $channels = ['database'];
        if (TemplateService::isEnabled('payment_approved')) {
            $channels[] = 'mail';
        }
        return $channels;
    }

    public function toMail($notifiable)
    {
        $cohortTitle = $this->payment->payable->title ?? 'a cohort';
        $symbol = Setting::get('currency_symbol', '£');

        return (new TemplateMail('payment_approved', [
            'name'          => $notifiable->name,
            'cohort_name'   => $cohortTitle,
            'amount'        => $symbol . number_format((float)$this->payment->amount, 2),
            'dashboard_url' => route('dashboard'),
        ]))->to($notifiable->email);
    }

    public function toArray($notifiable): array
    {
        $cohortTitle = $this->payment->payable->title ?? 'a cohort';

        return [
            'title' => 'Payment Approved',
            'message' => 'Your bank transfer for ' . $cohortTitle . ' has been approved. You are now enrolled!',
            'icon' => 'credit-card',
            'color' => 'green',
            'url' => route('dashboard'),
        ];
    }
}
