<?php

namespace App\Http\Controllers;

use App\Models\MembershipPlan;
use App\Models\MembershipSubscription;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = Setting::get('stripe_webhook_secret', config('services.stripe.webhook_secret'));

        if ($webhookSecret) {
            try {
                $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
            } catch (\Exception $e) {
                Log::error('Stripe Webhook Signature Error', ['message' => $e->getMessage()]);
                return response('Invalid signature', 400);
            }
        } else {
            $event = json_decode($payload);
        }

        $type = is_object($event) && property_exists($event, 'type') ? $event->type : ($event['type'] ?? null);
        $data = is_object($event) && property_exists($event, 'data') ? $event->data->object : ($event['data']['object'] ?? null);

        match ($type) {
            'invoice.payment_succeeded' => $this->handleInvoicePaymentSucceeded($data),
            'invoice.payment_failed'    => $this->handleInvoicePaymentFailed($data),
            'customer.subscription.deleted' => $this->handleSubscriptionDeleted($data),
            default => null,
        };

        return response('OK', 200);
    }

    protected function handleInvoicePaymentSucceeded($invoice)
    {
        $subscriptionId = $invoice->subscription ?? ($invoice['subscription'] ?? null);
        if (!$subscriptionId) return;

        $subscription = MembershipSubscription::where('gateway_subscription_id', $subscriptionId)->first();
        if (!$subscription) return;

        // Skip initial payment (already handled by callback)
        $billingReason = $invoice->billing_reason ?? ($invoice['billing_reason'] ?? null);
        if ($billingReason === 'subscription_create') return;

        $amount = ($invoice->amount_paid ?? ($invoice['amount_paid'] ?? 0)) / 100;

        $payment = Payment::create([
            'user_id' => $subscription->user_id,
            'payable_type' => MembershipPlan::class,
            'payable_id' => $subscription->membership_plan_id,
            'amount' => $amount,
            'reference' => $invoice->id ?? ($invoice['id'] ?? 'renewal-' . uniqid()),
            'gateway' => 'stripe',
            'status' => 'completed',
            'currency' => Setting::get('currency', 'GBP'),
            'subscription_id' => $subscription->id,
            'is_renewal' => true,
        ]);

        $billingIntervalMonths = [
            'monthly' => 1,
            'quarterly' => 3,
            'yearly' => 12,
        ];

        $membership = $subscription->membershipPlan;
        $months = $billingIntervalMonths[$membership->billing_interval] ?? 1;

        $subscription->update([
            'status' => 'active',
            'current_period_start' => now(),
            'current_period_end' => now()->addMonths($months),
        ]);

        \App\Services\CreatorEarningService::creditCreatorIfEligible($payment);

        Log::info('Membership renewal processed', [
            'subscription_id' => $subscription->id,
            'payment_id' => $payment->id,
        ]);
    }

    protected function handleInvoicePaymentFailed($invoice)
    {
        $subscriptionId = $invoice->subscription ?? ($invoice['subscription'] ?? null);
        if (!$subscriptionId) return;

        $subscription = MembershipSubscription::where('gateway_subscription_id', $subscriptionId)->first();
        if (!$subscription) return;

        $subscription->update(['status' => 'past_due']);

        Log::warning('Membership payment failed', ['subscription_id' => $subscription->id]);
    }

    protected function handleSubscriptionDeleted($stripeSubscription)
    {
        $subscriptionId = $stripeSubscription->id ?? ($stripeSubscription['id'] ?? null);
        if (!$subscriptionId) return;

        $subscription = MembershipSubscription::where('gateway_subscription_id', $subscriptionId)->first();
        if (!$subscription) return;

        $subscription->update([
            'status' => 'expired',
            'ends_at' => now(),
        ]);

        Log::info('Membership subscription expired', ['subscription_id' => $subscription->id]);
    }
}
