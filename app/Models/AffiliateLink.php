<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class AffiliateLink extends Model
{
    protected $fillable = [
        'user_id', 'affiliable_type', 'affiliable_id', 'code', 'clicks',
    ];

    protected function casts(): array
    {
        return [
            'clicks' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function affiliable(): MorphTo
    {
        return $this->morphTo();
    }

    public function sales(): HasMany
    {
        return $this->hasMany(AffiliateSale::class);
    }

    public function getUrlAttribute(): string
    {
        return url('/ref/' . $this->code);
    }

    public static function generateCode(): string
    {
        do {
            $code = Str::random(8);
        } while (static::where('code', $code)->exists());

        return $code;
    }
}
