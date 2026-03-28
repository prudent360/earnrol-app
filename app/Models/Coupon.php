<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'description', 'discount_type', 'discount_value',
        'min_purchase', 'max_discount', 'usage_limit', 'used_count',
        'applies_to', 'applicable_id', 'starts_at', 'expires_at', 'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_purchase'   => 'decimal:2',
        'max_discount'   => 'decimal:2',
        'usage_limit'    => 'integer',
        'used_count'     => 'integer',
        'is_active'      => 'boolean',
        'starts_at'      => 'datetime',
        'expires_at'     => 'datetime',
    ];

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isValid(): bool
    {
        if (! $this->is_active) return false;
        if ($this->starts_at && now()->lt($this->starts_at)) return false;
        if ($this->expires_at && now()->gt($this->expires_at)) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;

        return true;
    }

    public function isApplicableTo(string $type, int $id): bool
    {
        if ($this->applies_to === 'all') return true;

        return $this->applies_to === $type && $this->applicable_id == $id;
    }

    public function calculateDiscount(float $amount): float
    {
        if ($this->min_purchase && $amount < (float) $this->min_purchase) {
            return 0;
        }

        if ($this->discount_type === 'percentage') {
            $discount = $amount * ((float) $this->discount_value / 100);
            if ($this->max_discount) {
                $discount = min($discount, (float) $this->max_discount);
            }
        } else {
            $discount = (float) $this->discount_value;
        }

        return min($discount, $amount);
    }
}
