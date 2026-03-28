<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class CoachingService extends Model
{
    protected $fillable = [
        'user_id', 'title', 'slug', 'description', 'price',
        'duration_minutes', 'cover_image', 'meeting_platform',
        'status', 'approval_status', 'rejection_reason',
    ];

    public const MEETING_PLATFORMS = [
        'google_meet' => 'Google Meet',
        'zoom'        => 'Zoom',
        'custom'      => 'Custom Link',
    ];

    public const DURATION_OPTIONS = [15, 30, 45, 60, 90, 120];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'duration_minutes' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function slots(): HasMany
    {
        return $this->hasMany(CoachingSlot::class);
    }

    public function availableSlots(): HasMany
    {
        return $this->slots()->where('is_booked', false)->where('start_time', '>', now());
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(CoachingBooking::class);
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function affiliateProduct(): MorphOne
    {
        return $this->morphOne(AffiliateProduct::class, 'affiliable');
    }

    public function approvedReviews(): MorphMany
    {
        return $this->reviews()->where('is_approved', true);
    }

    public function averageRating(): ?float
    {
        return $this->approvedReviews()->avg('rating');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->where('approval_status', 'approved');
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function isFree(): bool
    {
        return $this->price <= 0;
    }

    public function getPlatformLabelAttribute(): string
    {
        return self::MEETING_PLATFORMS[$this->meeting_platform] ?? $this->meeting_platform;
    }

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'like', $slug . '%')->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
