<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\ReferralEarning;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\ReferralCommissionEarned;
use Illuminate\Support\Facades\DB;

class ReferralService
{
    public static function creditCommissionIfEligible(Payment $payment): void
    {
        if (!Setting::get('referral_enabled')) {
            return;
        }

        $user = User::find($payment->user_id);

        if (!$user || !$user->referred_by) {
            return;
        }

        // Only credit on first completed payment
        $hasPriorPayment = Payment::where('user_id', $user->id)
            ->where('status', 'completed')
            ->where('id', '!=', $payment->id)
            ->exists();

        if ($hasPriorPayment) {
            return;
        }

        $referrer = User::find($user->referred_by);
        if (!$referrer) {
            return;
        }

        $commissionRate = (float) Setting::get('referral_commission', 10);
        $commissionAmount = round($payment->amount * ($commissionRate / 100), 2);

        if ($commissionAmount <= 0) {
            return;
        }

        DB::transaction(function () use ($referrer, $user, $payment, $commissionAmount, $commissionRate) {
            ReferralEarning::create([
                'user_id' => $referrer->id,
                'referred_user_id' => $user->id,
                'payment_id' => $payment->id,
                'amount' => $commissionAmount,
                'commission_rate' => $commissionRate,
            ]);

            $referrer->increment('wallet_balance', $commissionAmount);

            $referrer->notify(new ReferralCommissionEarned($user, $commissionAmount));
        });
    }
}
