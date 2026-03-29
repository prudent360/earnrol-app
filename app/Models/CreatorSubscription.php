<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreatorSubscription extends Model
{
    protected $fillable = [
        'user_id', 'creator_plan_id', 'payment_id', 'status',
        'gateway', 'gateway_subscription_id',
        'current_period_start', 'current_period_end',
        'cancelled_at', 'ends_at', 'trial_ends_at',
    ];

    protected function casts(): array
    {
        return [
            'current_period_start' => 'datetime',
            'current_period_end' => 'datetime',
            'cancelled_at' => 'datetime',
            'ends_at' => 'datetime',
            'trial_ends_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creatorPlan(): BelongsTo
    {
        return $this->belongsTo(CreatorPlan::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'subscription_id');
    }

    public function isActive(): bool
    {
        if ($this->onTrial()) {
            return true;
        }

        return $this->status === 'active' && (
            !$this->current_period_end || $this->current_period_end->isFuture()
        );
    }

    public function onTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function isCancelled(): bool
    {
        return $this->cancelled_at !== null;
    }

    public function onGracePeriod(): bool
    {
        return $this->isCancelled() && $this->ends_at && $this->ends_at->isFuture();
    }
}
