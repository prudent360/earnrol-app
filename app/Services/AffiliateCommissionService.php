<?php

namespace App\Services;

use App\Models\AffiliateLink;
use App\Models\AffiliateProduct;
use App\Models\AffiliateSale;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\AffiliateCommissionEarned;
use Illuminate\Support\Facades\DB;

class AffiliateCommissionService
{
    public static function creditIfEligible(Payment $payment): bool
    {
        if (!Setting::get('affiliate_enabled')) {
            return false;
        }

        if (!$payment->affiliate_link_id) {
            return false;
        }

        // Prevent duplicate
        if (AffiliateSale::where('payment_id', $payment->id)->exists()) {
            return false;
        }

        $link = AffiliateLink::find($payment->affiliate_link_id);
        if (!$link) {
            return false;
        }

        // Prevent self-referral
        if ($link->user_id === $payment->user_id) {
            return false;
        }

        // Get affiliate product config
        $affiliateProduct = AffiliateProduct::where('affiliable_type', $payment->payable_type)
            ->where('affiliable_id', $payment->payable_id)
            ->where('affiliate_enabled', true)
            ->first();

        if (!$affiliateProduct || $affiliateProduct->commission_percentage <= 0) {
            return false;
        }

        $saleAmount = (float) $payment->amount;
        $commissionRate = (float) $affiliateProduct->commission_percentage;
        $adminFeeRate = (float) Setting::get('affiliate_admin_fee', 5);

        $affiliateCommission = round($saleAmount * ($commissionRate / 100), 2);
        $remaining = $saleAmount - $affiliateCommission;
        $adminCommission = round($remaining * ($adminFeeRate / 100), 2);
        $creatorAmount = round($remaining - $adminCommission, 2);

        if ($affiliateCommission <= 0) {
            return false;
        }

        DB::transaction(function () use ($link, $payment, $saleAmount, $affiliateCommission, $adminCommission, $creatorAmount, $commissionRate, $adminFeeRate) {
            AffiliateSale::create([
                'affiliate_link_id' => $link->id,
                'affiliate_user_id' => $link->user_id,
                'buyer_user_id' => $payment->user_id,
                'payment_id' => $payment->id,
                'sale_amount' => $saleAmount,
                'affiliate_commission' => $affiliateCommission,
                'admin_commission' => $adminCommission,
                'creator_amount' => $creatorAmount,
                'commission_rate' => $commissionRate,
                'admin_fee_rate' => $adminFeeRate,
                'status' => 'completed',
            ]);

            // Credit affiliate wallet
            $affiliate = User::find($link->user_id);
            $affiliate->increment('wallet_balance', $affiliateCommission);

            $itemTitle = $payment->payable->title ?? 'Item';
            $affiliate->notify(new AffiliateCommissionEarned($affiliateCommission, $itemTitle));
        });

        return true;
    }

    public static function resolveAffiliateLinkId($payableType, $payableId): ?int
    {
        $code = request()->cookie('affiliate_ref');
        if (!$code) {
            return null;
        }

        $link = AffiliateLink::where('code', $code)
            ->where('affiliable_type', $payableType)
            ->where('affiliable_id', $payableId)
            ->first();

        if (!$link) {
            return null;
        }

        // Verify the item has affiliate enabled
        $affiliateProduct = AffiliateProduct::where('affiliable_type', $payableType)
            ->where('affiliable_id', $payableId)
            ->where('affiliate_enabled', true)
            ->first();

        if (!$affiliateProduct) {
            return null;
        }

        return $link->id;
    }
}
