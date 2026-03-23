<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Lesson extends Model
{
    protected $fillable = [
        'chapter_id', 'title', 'slug', 'content', 
        'video_url', 'duration_minutes', 'is_preview', 'order'
    ];

    protected $casts = [
        'is_preview' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Lesson $lesson) {
            if (empty($lesson->slug)) {
                $lesson->slug = Str::slug($lesson->title);
            }
        });
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lesson_user', 'lesson_id', 'user_id')
                    ->withPivot('is_completed', 'last_watched_at')
                    ->withTimestamps();
    }
}
