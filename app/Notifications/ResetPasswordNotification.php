<?php

namespace App\Notifications;

use App\Mail\TemplateMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(protected string $token) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new TemplateMail('reset', [
            'name'      => $notifiable->name,
            'reset_url' => $resetUrl,
        ]))->to($notifiable->email);
    }
}
