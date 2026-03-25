<?php

namespace App\Notifications;

use App\Mail\TemplateMail;
use App\Models\Payment;
use App\Services\Mail\TemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentRejected extends Notification
{
    use Queueable;

    public function __construct(protected Payment $payment) {}

    public function via($notifiable): array
    {
        $channels = ['database'];
        if (TemplateService::isEnabled('payment_rejected')) {
            $channels[] = 'mail';
        }
        return $channels;
    }

    public function toMail($notifiable)
    {
        $cohortTitle = $this->payment->payable->title ?? 'a cohort';

        return (new TemplateMail('payment_rejected', [
            'name'        => $notifiable->name,
            'cohort_name' => $cohortTitle,
            'reason'      => $this->payment->admin_note ?? 'No reason provided.',
        ]))->to($notifiable->email);
    }

    public function toArray($notifiable): array
    {
        $cohortTitle = $this->payment->payable->title ?? 'a cohort';

        return [
            'title' => 'Payment Rejected',
            'message' => 'Your bank transfer for ' . $cohortTitle . ' was not approved. Please contact support or try again.',
            'icon' => 'x-circle',
            'color' => 'red',
            'url' => route('dashboard'),
        ];
    }
}
