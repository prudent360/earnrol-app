<?php

namespace App\Services;

use App\Models\Cohort;
use App\Models\Coupon;
use App\Models\DigitalProduct;
use App\Models\MembershipPlan;

class CouponService
{
    /**
     * Validate a coupon code and calculate the discount for a given amount.
     *
     * @return array{valid: bool, coupon: ?Coupon, discount: float, final_amount: float, message: string}
     */
    public function validate(string $code, float $amount, string $type = 'all', int $itemId = 0): array
    {
        $coupon = Coupon::where('code', strtoupper(trim($code)))->first();

        if (! $coupon) {
            return $this->fail('Coupon code not found.');
        }

        if (! $coupon->isValid()) {
            if ($coupon->expires_at && now()->gt($coupon->expires_at)) {
                return $this->fail('This coupon has expired.');
            }
            if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
                return $this->fail('This coupon has reached its usage limit.');
            }
            return $this->fail('This coupon is not currently active.');
        }

        if (! $coupon->isApplicableTo($type, $itemId)) {
            return $this->fail('This coupon does not apply to this item.');
        }

        // Verify creator coupon ownership
        if ($coupon->creator_id && $itemId) {
            if (! $this->verifyCreatorOwnership($coupon, $type, $itemId)) {
                return $this->fail('This coupon does not apply to this item.');
            }
        }

        $discount = $coupon->calculateDiscount($amount);

        if ($discount <= 0) {
            if ($coupon->min_purchase && $amount < (float) $coupon->min_purchase) {
                return $this->fail('Minimum purchase of ' . number_format((float) $coupon->min_purchase, 2) . ' required.');
            }
            return $this->fail('This coupon cannot be applied.');
        }

        $finalAmount = round($amount - $discount, 2);

        $message = $coupon->discount_type === 'percentage'
            ? "{$coupon->discount_value}% off applied!"
            : "Discount of " . number_format($discount, 2) . " applied!";

        return [
            'valid'        => true,
            'coupon'       => $coupon,
            'discount'     => $discount,
            'final_amount' => $finalAmount,
            'message'      => $message,
        ];
    }

    private function verifyCreatorOwnership(Coupon $coupon, string $type, int $itemId): bool
    {
        return match ($type) {
            'cohort' => Cohort::where('id', $itemId)->where('creator_id', $coupon->creator_id)->exists(),
            'product' => DigitalProduct::where('id', $itemId)->where('user_id', $coupon->creator_id)->exists(),
            'membership' => MembershipPlan::where('id', $itemId)->where('user_id', $coupon->creator_id)->exists(),
            default => false,
        };
    }

    private function fail(string $message): array
    {
        return [
            'valid'        => false,
            'coupon'       => null,
            'discount'     => 0,
            'final_amount' => 0,
            'message'      => $message,
        ];
    }
}
