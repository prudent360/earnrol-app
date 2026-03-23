<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MentorshipSession extends Model
{
    protected $fillable = [
        'mentor_id', 'user_id', 'scheduled_at', 
        'duration_minutes', 'status', 'topic', 
        'notes', 'meeting_link'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
