<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Model
{
    protected $fillable = [
        'user_id', 'payable_id', 'payable_type',
        'amount', 'currency', 'status',
        'reference', 'gateway', 'gateway_response',
        'receipt_path', 'admin_note',
        'coupon_id', 'original_amount', 'discount_amount',
    ];

    protected $casts = [
        'gateway_response' => 'json',
        'amount'           => 'decimal:2',
        'original_amount'  => 'decimal:2',
        'discount_amount'  => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }
}
