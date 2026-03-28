<?php

namespace App\Notifications;

use App\Models\MembershipPlan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MembershipSubscriptionConfirmed extends Notification
{
    use Queueable;

    public function __construct(protected MembershipPlan $plan) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Subscription Confirmed',
            'message' => 'You are now subscribed to ' . $this->plan->title . '. Access your exclusive content now!',
            'icon' => 'star',
            'color' => 'green',
            'url' => route('memberships.content', $this->plan),
        ];
    }
}
