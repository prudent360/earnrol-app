<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AffiliateProduct extends Model
{
    protected $fillable = [
        'affiliable_type', 'affiliable_id',
        'affiliate_enabled', 'commission_percentage',
    ];

    protected function casts(): array
    {
        return [
            'affiliate_enabled' => 'boolean',
            'commission_percentage' => 'decimal:2',
        ];
    }

    public function affiliable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeEnabled($query)
    {
        return $query->where('affiliate_enabled', true)->where('commission_percentage', '>', 0);
    }
}
