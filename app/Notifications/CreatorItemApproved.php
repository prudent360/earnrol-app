<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CreatorItemApproved extends Notification
{
    use Queueable;

    public function __construct(
        protected string $itemType,
        protected string $itemTitle,
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
            'title' => ucfirst($this->itemType) . ' Approved',
            'message' => "Your {$this->itemType} \"{$this->itemTitle}\" has been approved and is now live!",
            'icon' => 'check-circle',
            'color' => 'green',
            'url' => $route,
        ];
    }
}
