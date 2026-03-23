<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Course extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'category', 'level',
        'price', 'is_free', 'duration_hours', 'lesson_count',
        'rating', 'student_count', 'badge', 'is_featured',
        'status', 'thumbnail', 'icon_color', 'instructor_id',
    ];

    protected $casts = [
        'is_free'     => 'boolean',
        'is_featured' => 'boolean',
        'price'       => 'decimal:2',
        'rating'      => 'decimal:1',
    ];

    // Auto-generate slug from title
    protected static function booted(): void
    {
        static::creating(function (Course $course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('order');
    }

    public function lessons(): HasManyThrough
    {
        return $this->hasManyThrough(Lesson::class, Chapter::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function enrolledUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments')
                    ->withPivot('progress', 'completed_at')
                    ->withTimestamps();
    }

    // All available categories
    public static function categories(): array
    {
        return [
            'cloud-computing'  => 'Cloud Computing',
            'devops-cicd'      => 'DevOps & CI/CD',
            'cybersecurity'    => 'Cybersecurity',
            'data-engineering' => 'Data Engineering',
            'linux'            => 'Linux',
            'networking'       => 'Networking',
        ];
    }

    public function getCategoryLabelAttribute(): string
    {
        return static::categories()[$this->category] ?? ucfirst($this->category);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
