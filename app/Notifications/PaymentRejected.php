<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentRejected extends Notification
{
    use Queueable;

    public function __construct(protected Payment $payment) {}

    public function via($notifiable): array
    {
        return ['database'];
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
