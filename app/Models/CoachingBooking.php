<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoachingBooking extends Model
{
    protected $fillable = [
        'user_id', 'coaching_service_id', 'coaching_slot_id',
        'payment_id', 'meeting_link', 'status', 'notes',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(CoachingService::class, 'coaching_service_id');
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(CoachingSlot::class, 'coaching_slot_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
