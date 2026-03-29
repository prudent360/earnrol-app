<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Cohort extends Model
{
    protected $fillable = [
        'title', 'description', 'price', 'currency',
        'google_meet_link', 'start_date', 'end_date',
        'status', 'max_students',
        'facilitator_name', 'facilitator_bio', 'facilitator_image',
        'schedule', 'what_you_will_learn', 'prerequisites', 'cover_image',
        'certificate_enabled',
        'creator_id', 'approval_status', 'rejection_reason',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'certificate_enabled' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

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

    public function materials(): HasMany
    {
        return $this->hasMany(CohortMaterial::class);
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(CohortDiscussion::class);
    }

    public function getDurationAttribute(): ?string
    {
        if (!$this->start_date || !$this->end_date) {
            return null;
        }
        $days = $this->start_date->diffInDays($this->end_date);
        if ($days < 7) {
            $days = max(1, (int) ceil($days));
            return $days . ' ' . ($days === 1 ? 'day' : 'days');
        }
        $weeks = (int) ceil($days / 7);
        return $weeks . ' ' . ($weeks === 1 ? 'week' : 'weeks');
    }

    public function getWhatYouWillLearnListAttribute(): array
    {
        if (!$this->what_you_will_learn) return [];
        return array_filter(array_map('trim', explode("\n", $this->what_you_will_learn)));
    }

    public function getPrerequisitesListAttribute(): array
    {
        if (!$this->prerequisites) return [];
        return array_filter(array_map('trim', explode("\n", $this->prerequisites)));
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

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function affiliateProduct(): MorphOne
    {
        return $this->morphOne(AffiliateProduct::class, 'affiliable');
    }

    public function salesPage(): MorphOne
    {
        return $this->morphOne(SalesPage::class, 'pageable');
    }

    public function approvedReviews(): MorphMany
    {
        return $this->reviews()->where('is_approved', true);
    }

    public function averageRating(): ?float
    {
        return $this->approvedReviews()->avg('rating');
    }
}
