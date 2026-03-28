<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class MembershipPlan extends Model
{
    protected $fillable = [
        'user_id', 'title', 'slug', 'description', 'cover_image',
        'price', 'billing_interval', 'features', 'max_subscribers',
        'welcome_message', 'status', 'approval_status', 'rejection_reason',
        'stripe_product_id', 'stripe_price_id',
    ];

    public const BILLING_INTERVALS = [
        'monthly'   => 'Monthly',
        'quarterly' => 'Quarterly',
        'yearly'    => 'Yearly',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'max_subscribers' => 'integer',
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

    public function subscriptions(): HasMany
    {
        return $this->hasMany(MembershipSubscription::class);
    }

    public function activeSubscriptions(): HasMany
    {
        return $this->subscriptions()->where('status', 'active');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(MembershipContent::class)->orderBy('sort_order');
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

    public function isFull(): bool
    {
        if (!$this->max_subscribers) {
            return false;
        }

        return $this->activeSubscriptions()->count() >= $this->max_subscribers;
    }

    public function getFeaturesListAttribute(): array
    {
        if (!$this->features) {
            return [];
        }

        return array_filter(array_map('trim', explode("\n", $this->features)));
    }

    public function getBillingLabelAttribute(): string
    {
        return self::BILLING_INTERVALS[$this->billing_interval] ?? $this->billing_interval;
    }

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'like', $slug . '%')->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
