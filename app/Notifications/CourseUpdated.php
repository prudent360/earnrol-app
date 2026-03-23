<?php

namespace App\Notifications;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CourseUpdated extends Notification
{
    use Queueable;

    public function __construct(
        protected Course $course
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'      => 'course_update',
            'icon'      => '📝',
            'title'     => 'Course Updated',
            'message'   => 'The course "' . $this->course->title . '" has been updated.',
            'course_id' => $this->course->id,
            'url'       => '/learning/' . $this->course->id,
        ];
    }
}
