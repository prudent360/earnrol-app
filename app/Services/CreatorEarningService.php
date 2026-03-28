<?php

namespace App\Services;

use App\Models\Cohort;
use App\Models\CreatorEarning;
use App\Models\DigitalProduct;
use App\Models\MembershipPlan;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\CreatorEarningReceived;
use Illuminate\Support\Facades\DB;

class CreatorEarningService
{
    public static function creditCreatorIfEligible(Payment $payment): void
    {
        if (!Setting::get('creator_enabled')) {
            return;
        }

        // Determine the creator based on the payable type
        $payable = $payment->payable;
        if (!$payable) {
            return;
        }

        $creator = null;

        if ($payable instanceof DigitalProduct) {
            $creator = User::find($payable->user_id);
        } elseif ($payable instanceof Cohort) {
            $creator = $payable->creator_id ? User::find($payable->creator_id) : null;
        } elseif ($payable instanceof MembershipPlan) {
            $creator = User::find($payable->user_id);
        }

        // Skip if no creator (admin-created item) or creator is admin
        if (!$creator || !$creator->isCreator() || $creator->isAdmin()) {
            return;
        }

        // Prevent duplicate commission for the same payment
        if (CreatorEarning::where('payment_id', $payment->id)->exists()) {
            return;
        }

        $commissionRate = (float) Setting::get('creator_commission', 80);
        $commissionAmount = round($payment->amount * ($commissionRate / 100), 2);

        if ($commissionAmount <= 0) {
            return;
        }

        DB::transaction(function () use ($creator, $payment, $commissionAmount, $commissionRate) {
            CreatorEarning::create([
                'creator_id' => $creator->id,
                'payment_id' => $payment->id,
                'amount' => $commissionAmount,
                'commission_rate' => $commissionRate,
                'status' => 'pending',
            ]);

            $creator->increment('wallet_balance', $commissionAmount);

            $itemTitle = $payment->payable->title ?? 'Unknown Item';
            $creator->notify(new CreatorEarningReceived($commissionAmount, $itemTitle));
        });
    }
}
