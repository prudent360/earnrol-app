<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class DigitalProduct extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'price',
        'cover_image',
        'file_path',
        'file_name',
        'file_size',
        'document_type',
        'status',
        'download_count',
    ];

    public const DOCUMENT_TYPES = [
        'pdf'           => ['label' => 'PDF',           'icon' => 'from-red-500 to-rose-600',      'bg' => 'bg-red-50',      'text' => 'text-red-500'],
        'document'      => ['label' => 'DOC',           'icon' => 'from-blue-500 to-indigo-600',   'bg' => 'bg-blue-50',     'text' => 'text-blue-500'],
        'spreadsheet'   => ['label' => 'XLS',           'icon' => 'from-emerald-500 to-green-600', 'bg' => 'bg-emerald-50',  'text' => 'text-emerald-500'],
        'presentation'  => ['label' => 'PPT',           'icon' => 'from-orange-500 to-red-500',    'bg' => 'bg-orange-50',   'text' => 'text-orange-500'],
        'video'         => ['label' => 'VIDEO',         'icon' => 'from-purple-500 to-violet-600', 'bg' => 'bg-purple-50',   'text' => 'text-purple-500'],
        'audio'         => ['label' => 'AUDIO',         'icon' => 'from-pink-500 to-rose-500',     'bg' => 'bg-pink-50',     'text' => 'text-pink-500'],
        'image'         => ['label' => 'IMAGE',         'icon' => 'from-cyan-500 to-blue-500',     'bg' => 'bg-cyan-50',     'text' => 'text-cyan-500'],
        'archive'       => ['label' => 'ZIP',           'icon' => 'from-amber-500 to-yellow-600',  'bg' => 'bg-amber-50',    'text' => 'text-amber-500'],
        'code'          => ['label' => 'CODE',          'icon' => 'from-slate-600 to-gray-800',    'bg' => 'bg-slate-50',    'text' => 'text-slate-500'],
        'ebook'         => ['label' => 'eBOOK',         'icon' => 'from-teal-500 to-emerald-600',  'bg' => 'bg-teal-50',     'text' => 'text-teal-500'],
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'file_size' => 'integer',
            'download_count' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(ProductPurchase::class);
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 0) . ' KB';
        }

        return $bytes . ' bytes';
    }

    public function isFree(): bool
    {
        return $this->price <= 0;
    }

    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'like', $slug . '%')->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
