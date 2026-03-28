<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CoachingSlot extends Model
{
    protected $fillable = [
        'coaching_service_id', 'start_time', 'end_time', 'is_booked',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'is_booked' => 'boolean',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(CoachingService::class, 'coaching_service_id');
    }

    public function booking(): HasOne
    {
        return $this->hasOne(CoachingBooking::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_booked', false)->where('start_time', '>', now());
    }
}
