<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cohort extends Model
{
    protected $fillable = [
        'title', 'description', 'price', 'currency',
        'google_meet_link', 'start_date', 'end_date',
        'status', 'max_students',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function enrollments(): HasMany
    {
        return $this->hasMany(CohortEnrollment::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'cohort_enrollments')
                    ->withPivot('payment_id', 'enrolled_at')
                    ->withTimestamps();
    }

    public function isFull(): bool
    {
        if (is_null($this->max_students)) {
            return false;
        }
        return $this->enrollments()->count() >= $this->max_students;
    }

    public function spotsLeft(): ?int
    {
        if (is_null($this->max_students)) {
            return null;
        }
        return max(0, $this->max_students - $this->enrollments()->count());
    }
}
