<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{
    protected $table = 'job_listings';

    protected $fillable = [
        'title', 'company', 'location', 'type', 'salary_range', 
        'description', 'requirements', 'status', 'user_id'
    ];

    public function poster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
