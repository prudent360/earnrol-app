<?php

namespace App\Notifications;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LessonCompleted extends Notification
{
    use Queueable;

    public function __construct(
        protected Lesson $lesson,
        protected Course $course
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'      => 'lesson_complete',
            'icon'      => '✅',
            'title'     => 'Lesson Completed',
            'message'   => 'You completed "' . $this->lesson->title . '" in "' . $this->course->title . '"',
            'course_id' => $this->course->id,
            'lesson_id' => $this->lesson->id,
            'url'       => '/learning/' . $this->course->id . '/lessons/' . $this->lesson->slug,
        ];
    }
}
