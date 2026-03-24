<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CohortSubmission extends Model
{
    protected $fillable = [
        'cohort_material_id', 'user_id', 'file_path',
        'file_name', 'notes', 'grade', 'feedback',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(CohortMaterial::class, 'cohort_material_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
