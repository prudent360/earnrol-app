<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentApproved extends Notification
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
            'title' => 'Payment Approved',
            'message' => 'Your bank transfer for ' . $cohortTitle . ' has been approved. You are now enrolled!',
            'icon' => 'credit-card',
            'color' => 'green',
            'url' => route('dashboard'),
        ];
    }
}
