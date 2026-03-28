<?php

namespace App\Http\Controllers;

use App\Models\MembershipPlan;
use App\Models\MembershipSubscription;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\MembershipSubscriptionConfirmed;
use App\Services\CouponService;
use App\Services\Payment\StripeService;
use App\Services\AffiliateCommissionService;
use App\Services\ReferralService;
use App\Models\CouponUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class MembershipPaymentController extends Controller
{
    public function stripeCheckout(Request $request, MembershipPlan $membership)
    {
        $user = Auth::user();

        $check = $this->preCheck($membership, $user);
        if ($check) return $check;

        if (!Setting::get('stripe_enabled')) {
            return back()->with('error', 'Stripe payments are not enabled.');
        }

        if (!$membership->stripe_price_id) {
            return back()->with('error', 'This membership is not configured for Stripe payments.');
        }

        try {
            $stripe = new StripeService();
            $session = $stripe->createSubscriptionCheckout($membership, $user);

            if (!$session) {
                return back()->with('error', 'Unable to initialize payment. Please try again.');
            }

            Payment::create([
                'user_id' => $user->id,
                'payable_type' => MembershipPlan::class,
                'payable_id' => $membership->id,
                'amount' => $membership->price,
                'reference' => $session->id,
                'gateway' => 'stripe',
                'status' => 'pending',
                'currency' => Setting::get('currency', 'GBP'),
                'affiliate_link_id' => AffiliateCommissionService::resolveAffiliateLinkId(MembershipPlan::class, $membership->id),
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            Log::error('Membership Stripe Error', ['message' => $e->getMessage()]);
            return back()->with('error', 'Unable to initialize payment. Please try again.');
        }
    }

    public function stripeCallback(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('memberships.index')->with('error', 'Missing session ID.');
        }

        try {
            Stripe::setApiKey(Setting::get('stripe_secret_key', config('services.stripe.secret')));
            $session = Session::retrieve([
                'id' => $sessionId,
                'expand' => ['subscription'],
            ]);

            if ($session && $session->payment_status === 'paid') {
                $payment = Payment::where('reference', $sessionId)->first();
                return $this->finalizeSubscription($payment, $session);
            }
        } catch (\Exception $e) {
            Log::error('Membership Stripe Callback Error', ['message' => $e->getMessage()]);
        }

        return redirect()->route('memberships.index')->with('error', 'Payment verification failed.');
    }

    public function bankTransferForm(Request $request, MembershipPlan $membership)
    {
        $user = Auth::user();

        $check = $this->preCheck($membership, $user);
        if ($check) return $check;

        if (!Setting::get('bank_transfer_enabled')) {
            return back()->with('error', 'Bank transfer is not enabled.');
        }

        $pendingPayment = Payment::where('user_id', $user->id)
            ->where('payable_type', MembershipPlan::class)
            ->where('payable_id', $membership->id)
            ->where('gateway', 'bank_transfer')
            ->where('status', 'pending')
            ->first();

        $bankDetails = [
            'bank_name'       => Setting::get('bank_name', ''),
            'account_name'    => Setting::get('bank_account_name', ''),
            'sort_code'       => Setting::get('bank_sort_code', ''),
            'account_number'  => Setting::get('bank_account_number', ''),
            'iban'            => Setting::get('bank_iban', ''),
            'reference_note'  => Setting::get('bank_reference_note', ''),
            'currency_symbol' => Setting::get('currency_symbol', '£'),
        ];

        return view('memberships.bank-transfer', compact('membership', 'bankDetails', 'pendingPayment'));
    }

    public function bankTransferSubmit(Request $request, MembershipPlan $membership)
    {
        $user = Auth::user();

        $check = $this->preCheck($membership, $user);
        if ($check) return $check;

        if (!Setting::get('bank_transfer_enabled')) {
            return back()->with('error', 'Bank transfer is not enabled.');
        }

        $request->validate([
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $receiptPath = $request->file('receipt')->store('receipts', 'public');

        $payment = Payment::create([
            'user_id'      => $user->id,
            'payable_type' => MembershipPlan::class,
            'payable_id'   => $membership->id,
            'amount'       => $membership->price,
            'reference'    => 'BT-' . strtoupper(uniqid()),
            'gateway'      => 'bank_transfer',
            'status'       => 'pending',
            'currency'     => Setting::get('currency', 'GBP'),
            'receipt_path' => $receiptPath,
            'affiliate_link_id' => AffiliateCommissionService::resolveAffiliateLinkId(MembershipPlan::class, $membership->id),
        ]);

        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();
        Notification::send($admins, new \App\Notifications\NewBankTransferAdmin($payment));

        return redirect()->route('memberships.show', $membership)
            ->with('success', 'Receipt uploaded! Your payment is being reviewed. You will get access once approved.');
    }

    protected function preCheck(MembershipPlan $membership, $user)
    {
        if ($user->hasActiveSubscription($membership)) {
            return redirect()->route('memberships.show', $membership)
                ->with('error', 'You already have an active subscription to this plan.');
        }

        if ($membership->status !== 'published' || $membership->approval_status !== 'approved') {
            return redirect()->route('memberships.index')
                ->with('error', 'This membership plan is not available.');
        }

        if ($membership->isFull()) {
            return redirect()->route('memberships.show', $membership)
                ->with('error', 'This membership plan has reached its maximum subscribers.');
        }

        return null;
    }

    protected function finalizeSubscription($payment, $session)
    {
        if ($payment && $payment->status !== 'completed') {
            $stripeSubscription = $session->subscription;

            $payment->update([
                'status' => 'completed',
                'gateway_response' => $session->toArray(),
            ]);

            $billingIntervalMonths = [
                'monthly' => 1,
                'quarterly' => 3,
                'yearly' => 12,
            ];

            $membership = MembershipPlan::find($payment->payable_id);
            $months = $billingIntervalMonths[$membership->billing_interval] ?? 1;

            $subscription = MembershipSubscription::create([
                'user_id' => $payment->user_id,
                'membership_plan_id' => $payment->payable_id,
                'payment_id' => $payment->id,
                'status' => 'active',
                'gateway' => 'stripe',
                'gateway_subscription_id' => is_string($stripeSubscription) ? $stripeSubscription : ($stripeSubscription->id ?? null),
                'current_period_start' => now(),
                'current_period_end' => now()->addMonths($months),
            ]);

            $payment->update(['subscription_id' => $subscription->id]);

            $user = User::find($payment->user_id);
            $user->notify(new MembershipSubscriptionConfirmed($membership));

            ReferralService::creditCommissionIfEligible($payment);
            AffiliateCommissionService::creditIfEligible($payment);
            \App\Services\CreatorEarningService::creditCreatorIfEligible($payment);

            return redirect()->route('memberships.show', $membership)
                ->with('success', 'Welcome! You are now subscribed to ' . $membership->title . '.');
        }

        return redirect()->route('memberships.index')->with('error', 'Payment already processed or not found.');
    }
}
