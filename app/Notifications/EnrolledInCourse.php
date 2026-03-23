<?php

namespace App\Notifications;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EnrolledInCourse extends Notification
{
    use Queueable, Traits\BaseMailNotification;

    public function __construct(
        protected Course $course
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function getTemplateKey(): string
    {
        return 'enroll';
    }

    protected function getTemplateData(object $notifiable): array
    {
        return [
            'name' => $notifiable->name,
            'course_name' => $this->course->title,
            'course_url' => url('/learning/' . $this->course->id),
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'      => 'enrollment',
            'icon'      => '🎓',
            'title'     => 'Enrolled Successfully',
            'message'   => 'You have been enrolled in "' . $this->course->title . '"',
            'course_id' => $this->course->id,
            'url'       => '/learning/' . $this->course->id,
        ];
    }
}
