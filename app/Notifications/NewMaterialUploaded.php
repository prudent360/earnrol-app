<?php

namespace App\Notifications;

use App\Models\Cohort;
use App\Models\CohortMaterial;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewMaterialUploaded extends Notification
{
    use Queueable;

    public function __construct(protected CohortMaterial $material, protected Cohort $cohort) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $type = $this->material->type === 'assignment' ? 'assignment' : 'material';

        return [
            'title' => 'New ' . ucfirst($type) . ' Added',
            'message' => '"' . $this->material->title . '" was added to ' . $this->cohort->title . '.',
            'icon' => $type === 'assignment' ? 'clipboard' : 'document',
            'color' => $type === 'assignment' ? 'orange' : 'blue',
            'url' => route('cohorts.materials', $this->cohort),
        ];
    }
}
