<?php

namespace App\Notifications;

use App\Models\MentorshipSession;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMentorshipRequest extends Notification
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
        return 'mentor'; // Using mentor template for now as it's similar
    }

    protected function getTemplateData($notifiable): array
    {
        return [
            'name' => $notifiable->name,
            'mentor_name' => 'You', // Since it's sent to the mentor
            'session_datetime' => $this->session->scheduled_at->format('M d, Y g:i A'),
            'meeting_url' => route('mentorship.sessions.index'),
        ];
    }

    public function toArray($notifiable): array
    {
        return [
            'type'    => 'mentorship_request',
            'icon'    => '🤝',
            'title'   => 'New Booking Request',
            'message' => "{$this->session->user->name} booked a session with you on " . $this->session->scheduled_at->format('M d, g:i A'),
            'url'     => route('mentorship.sessions.index'),
        ];
    }
}
