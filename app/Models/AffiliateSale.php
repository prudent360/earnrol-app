<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateSale extends Model
{
    protected $fillable = [
        'affiliate_link_id', 'affiliate_user_id', 'buyer_user_id',
        'payment_id', 'sale_amount', 'affiliate_commission',
        'admin_commission', 'creator_amount', 'commission_rate',
        'admin_fee_rate', 'status',
    ];

    protected function casts(): array
    {
        return [
            'sale_amount' => 'decimal:2',
            'affiliate_commission' => 'decimal:2',
            'admin_commission' => 'decimal:2',
            'creator_amount' => 'decimal:2',
            'commission_rate' => 'decimal:2',
            'admin_fee_rate' => 'decimal:2',
        ];
    }

    public function affiliateLink(): BelongsTo
    {
        return $this->belongsTo(AffiliateLink::class);
    }

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'affiliate_user_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_user_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
