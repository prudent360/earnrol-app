<?php

namespace App\Notifications;

use App\Models\Certificate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CertificateIssued extends Notification
{
    use Queueable;

    public function __construct(protected Certificate $certificate) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Certificate Issued',
            'message' => 'Your certificate for ' . $this->certificate->cohort->title . ' is ready to download.',
            'icon' => 'academic-cap',
            'color' => 'blue',
            'url' => route('certificates.index'),
        ];
    }
}
