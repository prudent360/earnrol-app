<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CohortMaterial extends Model
{
    protected $fillable = [
        'cohort_id', 'uploaded_by', 'title', 'description',
        'file_path', 'file_name', 'type', 'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function cohort(): BelongsTo
    {
        return $this->belongsTo(Cohort::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(CohortSubmission::class);
    }

    public function isAssignment(): bool
    {
        return $this->type === 'assignment';
    }

    public function submissionBy(int $userId): ?CohortSubmission
    {
        return $this->submissions()->where('user_id', $userId)->first();
    }
}
