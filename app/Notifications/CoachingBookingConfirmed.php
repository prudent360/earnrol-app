<?php

namespace App\Notifications;

use App\Models\CoachingService;
use App\Models\CoachingSlot;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CoachingBookingConfirmed extends Notification
{
    use Queueable;

    public function __construct(
        protected CoachingService $service,
        protected ?CoachingSlot $slot
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $time = $this->slot ? $this->slot->start_time->format('M d, Y g:i A') : 'TBD';

        return [
            'title' => 'Coaching Session Booked',
            'message' => 'Your session for ' . $this->service->title . ' on ' . $time . ' is confirmed. The creator will share the meeting link.',
            'icon' => 'calendar',
            'color' => 'green',
            'url' => route('coaching.my-bookings'),
        ];
    }
}
