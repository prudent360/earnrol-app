<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class SalesPage extends Model
{
    public const TEMPLATES = [
        'starter' => 'Starter',
        'professional' => 'Professional',
        'bold' => 'Bold',
        'minimal' => 'Minimal',
    ];

    protected $fillable = [
        'user_id', 'pageable_type', 'pageable_id',
        'template', 'content', 'is_published', 'custom_slug',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'json',
            'is_published' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'custom_slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $count = 1;

        while (static::where('custom_slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }
}
