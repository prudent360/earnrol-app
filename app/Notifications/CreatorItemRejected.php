<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CreatorItemRejected extends Notification
{
    use Queueable;

    public function __construct(
        protected string $itemType,
        protected string $itemTitle,
        protected string $reason,
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $route = $this->itemType === 'product'
            ? route('creator.products.index')
            : route('creator.cohorts.index');

        return [
            'title' => ucfirst($this->itemType) . ' Rejected',
            'message' => "Your {$this->itemType} \"{$this->itemTitle}\" was rejected. Reason: {$this->reason}",
            'icon' => 'x-circle',
            'color' => 'red',
            'url' => $route,
        ];
    }
}
