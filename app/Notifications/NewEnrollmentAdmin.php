<?php

namespace App\Notifications;

use App\Models\Cohort;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewEnrollmentAdmin extends Notification
{
    use Queueable;

    public function __construct(protected User $student, protected Cohort $cohort, protected string $method = 'paid') {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'New Enrollment',
            'message' => $this->student->name . ' enrolled in ' . $this->cohort->title . ' (' . $this->method . ').',
            'icon' => 'user-add',
            'color' => 'blue',
            'url' => route('admin.cohorts.edit', $this->cohort),
        ];
    }
}
