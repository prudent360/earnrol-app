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
        'pdf'          => ['label' => 'PDF',   'icon' => 'from-red-500 to-rose-600',      'bg' => 'bg-red-50',      'text' => 'text-red-500',     'svg' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z M9 13h6 M9 17h4'],
        'document'     => ['label' => 'DOC',   'icon' => 'from-blue-500 to-indigo-600',   'bg' => 'bg-blue-50',     'text' => 'text-blue-500',    'svg' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        'spreadsheet'  => ['label' => 'XLS',   'icon' => 'from-emerald-500 to-green-600', 'bg' => 'bg-emerald-50',  'text' => 'text-emerald-500', 'svg' => 'M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z'],
        'presentation' => ['label' => 'PPT',   'icon' => 'from-orange-500 to-red-500',    'bg' => 'bg-orange-50',   'text' => 'text-orange-500',  'svg' => 'M8 13v-1m4 1v-3m4 3V8M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z'],
        'video'        => ['label' => 'VIDEO', 'icon' => 'from-purple-500 to-violet-600', 'bg' => 'bg-purple-50',   'text' => 'text-purple-500',  'svg' => 'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        'audio'        => ['label' => 'AUDIO', 'icon' => 'from-pink-500 to-rose-500',     'bg' => 'bg-pink-50',     'text' => 'text-pink-500',    'svg' => 'M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z'],
        'image'        => ['label' => 'IMAGE', 'icon' => 'from-cyan-500 to-blue-500',     'bg' => 'bg-cyan-50',     'text' => 'text-cyan-500',    'svg' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
        'archive'      => ['label' => 'ZIP',   'icon' => 'from-amber-500 to-yellow-600',  'bg' => 'bg-amber-50',    'text' => 'text-amber-500',   'svg' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
        'code'         => ['label' => 'CODE',  'icon' => 'from-slate-600 to-gray-800',    'bg' => 'bg-slate-50',    'text' => 'text-slate-500',   'svg' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4'],
        'ebook'        => ['label' => 'eBOOK', 'icon' => 'from-teal-500 to-emerald-600',  'bg' => 'bg-teal-50',     'text' => 'text-teal-500',    'svg' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
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
