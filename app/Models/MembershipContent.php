<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembershipContent extends Model
{
    protected $fillable = [
        'membership_plan_id', 'title', 'description', 'content_type',
        'file_path', 'file_name', 'external_url', 'body',
        'sort_order', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    public function membershipPlan(): BelongsTo
    {
        return $this->belongsTo(MembershipPlan::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
