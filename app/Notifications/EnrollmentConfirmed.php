<?php

namespace App\Notifications;

use App\Models\Cohort;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EnrollmentConfirmed extends Notification
{
    use Queueable;

    public function __construct(protected Cohort $cohort) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Enrolled Successfully',
            'message' => 'You are now enrolled in ' . $this->cohort->title . '.',
            'icon' => 'check-circle',
            'color' => 'green',
            'url' => route('dashboard'),
        ];
    }
}
