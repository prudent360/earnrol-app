<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CreatorPlan extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'price', 'billing_interval',
        'features', 'max_products', 'max_cohorts', 'is_featured',
        'sort_order', 'stripe_product_id', 'stripe_price_id', 'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'features' => 'json',
            'max_products' => 'integer',
            'max_cohorts' => 'integer',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(CreatorSubscription::class);
    }

    public function activeSubscriptions(): HasMany
    {
        return $this->hasMany(CreatorSubscription::class)->where('status', 'active');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getFeaturesListAttribute(): array
    {
        return is_array($this->features) ? $this->features : [];
    }

    public function getBillingLabelAttribute(): string
    {
        return match ($this->billing_interval) {
            'yearly' => '/year',
            default => '/month',
        };
    }

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }
}
