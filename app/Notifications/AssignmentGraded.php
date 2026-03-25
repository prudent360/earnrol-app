<?php

namespace App\Notifications;

use App\Models\CohortSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AssignmentGraded extends Notification
{
    use Queueable;

    public function __construct(protected CohortSubmission $submission) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $material = $this->submission->material;
        $cohort = $material->cohort;

        return [
            'title' => 'Assignment Graded',
            'message' => 'Your submission for "' . $material->title . '" received a grade of ' . $this->submission->grade . '.',
            'icon' => 'academic-cap',
            'color' => 'purple',
            'url' => route('cohorts.materials', $cohort),
        ];
    }
}
