<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CohortDiscussion extends Model
{
    protected $fillable = [
        'cohort_id',
        'user_id',
        'parent_id',
        'body',
    ];

    public function cohort(): BelongsTo
    {
        return $this->belongsTo(Cohort::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CohortDiscussion::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(CohortDiscussion::class, 'parent_id')->oldest();
    }

    public function isTopLevel(): bool
    {
        return is_null($this->parent_id);
    }
}
