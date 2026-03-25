<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewBankTransferAdmin extends Notification
{
    use Queueable;

    public function __construct(protected Payment $payment) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $user = $this->payment->user;
        $cohortTitle = $this->payment->payable->title ?? 'a cohort';

        return [
            'title' => 'Bank Transfer Pending',
            'message' => $user->name . ' submitted a bank transfer receipt for ' . $cohortTitle . '. Review needed.',
            'icon' => 'currency-pound',
            'color' => 'amber',
            'url' => route('admin.payments.index', ['status' => 'pending']),
        ];
    }
}
