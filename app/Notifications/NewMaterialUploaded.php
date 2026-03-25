<?php

namespace App\Notifications;

use App\Mail\TemplateMail;
use App\Models\Cohort;
use App\Models\CohortMaterial;
use App\Services\Mail\TemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewMaterialUploaded extends Notification
{
    use Queueable;

    public function __construct(protected CohortMaterial $material, protected Cohort $cohort) {}

    public function via($notifiable): array
    {
        $channels = ['database'];
        if (TemplateService::isEnabled('new_material')) {
            $channels[] = 'mail';
        }
        return $channels;
    }

    public function toMail($notifiable)
    {
        return (new TemplateMail('new_material', [
            'name'           => $notifiable->name,
            'material_title' => $this->material->title,
            'cohort_name'    => $this->cohort->title,
            'materials_url'  => route('cohorts.materials', $this->cohort),
        ]))->to($notifiable->email);
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
