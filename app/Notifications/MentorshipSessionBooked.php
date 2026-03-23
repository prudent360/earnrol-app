<?php

namespace App\Notifications;

use App\Models\MentorshipSession;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MentorshipSessionBooked extends Notification
{
    use Queueable, Traits\BaseMailNotification;

    protected $session;

    public function __construct(MentorshipSession $session)
    {
        $this->session = $session;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    protected function getTemplateKey(): string
    {
        return 'mentor';
    }

    protected function getTemplateData($notifiable): array
    {
        return [
            'name' => $notifiable->name,
            'mentor_name' => $this->session->mentor->user->name,
            'session_datetime' => $this->session->scheduled_at->format('M d, Y g:i A'),
            'meeting_url' => $this->session->meeting_url ?? 'TBA',
        ];
    }

    public function toArray($notifiable): array
    {
        return [
            'type'    => 'mentorship_booked',
            'icon'    => '📅',
            'title'   => 'Session Confirmed',
            'message' => "Your session with {$this->session->mentor->user->name} is confirmed for " . $this->session->scheduled_at->format('M d, g:i A'),
            'url'     => route('mentorship.sessions.index'),
        ];
    }
}
