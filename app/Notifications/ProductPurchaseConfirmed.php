<?php

namespace App\Notifications;

use App\Models\DigitalProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProductPurchaseConfirmed extends Notification
{
    use Queueable;

    public function __construct(protected DigitalProduct $product) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Purchase Confirmed',
            'message' => 'You now have access to ' . $this->product->title . '. Download it anytime from My Downloads.',
            'icon' => 'download',
            'color' => 'green',
            'url' => route('products.downloads'),
        ];
    }
}
