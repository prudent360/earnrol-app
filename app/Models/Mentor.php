<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mentor extends Model
{
    protected $fillable = [
        'user_id', 'role_title', 'company', 'bio', 
        'avatar_text', 'rating', 'sessions_count', 
        'expertise', 'price_label', 'icon_color', 'is_available'
    ];

    protected $casts = [
        'expertise'    => 'json',
        'is_available' => 'boolean',
        'rating'       => 'decimal:1',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(MentorshipSession::class);
    }
}
