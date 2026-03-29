<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateClick extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'affiliate_link_id', 'ip_address', 'user_agent', 'referer',
        'user_id', 'is_unique', 'is_suspicious', 'suspicious_reason', 'country',
    ];

    protected function casts(): array
    {
        return [
            'is_unique' => 'boolean',
            'is_suspicious' => 'boolean',
        ];
    }

    public function affiliateLink(): BelongsTo
    {
        return $this->belongsTo(AffiliateLink::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
