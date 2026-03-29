<?php

namespace App\Http\Controllers;

use App\Models\CreatorPlan;
use App\Models\CreatorSubscription;
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

        $billingReason = $invoice->billing_reason ?? ($invoice['billing_reason'] ?? null);
        if ($billingReason === 'subscription_create') return;

        $amount = ($invoice->amount_paid ?? ($invoice['amount_paid'] ?? 0)) / 100;

        // Check MembershipSubscription first
        $membershipSub = MembershipSubscription::where('gateway_subscription_id', $subscriptionId)->first();
        if ($membershipSub) {
            $this->handleMembershipRenewal($membershipSub, $invoice, $amount);
            return;
        }

        // Check CreatorSubscription
        $creatorSub = CreatorSubscription::where('gateway_subscription_id', $subscriptionId)->first();
        if ($creatorSub) {
            $this->handleCreatorRenewal($creatorSub, $invoice, $amount);
            return;
        }
    }

    private function handleMembershipRenewal(MembershipSubscription $subscription, $invoice, float $amount)
    {
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

        $months = match ($subscription->membershipPlan->billing_interval ?? 'monthly') {
            'quarterly' => 3,
            'yearly' => 12,
            default => 1,
        };

        $subscription->update([
            'status' => 'active',
            'current_period_start' => now(),
            'current_period_end' => now()->addMonths($months),
        ]);

        \App\Services\CreatorEarningService::creditCreatorIfEligible($payment);
        Log::info('Membership renewal processed', ['subscription_id' => $subscription->id]);
    }

    private function handleCreatorRenewal(CreatorSubscription $subscription, $invoice, float $amount)
    {
        Payment::create([
            'user_id' => $subscription->user_id,
            'payable_type' => CreatorPlan::class,
            'payable_id' => $subscription->creator_plan_id,
            'amount' => $amount,
            'reference' => $invoice->id ?? ($invoice['id'] ?? 'renewal-' . uniqid()),
            'gateway' => 'stripe',
            'status' => 'completed',
            'currency' => Setting::get('currency', 'GBP'),
            'subscription_id' => $subscription->id,
            'is_renewal' => true,
        ]);

        $months = $subscription->creatorPlan->billing_interval === 'yearly' ? 12 : 1;

        $subscription->update([
            'status' => 'active',
            'current_period_start' => now(),
            'current_period_end' => now()->addMonths($months),
        ]);

        Log::info('Creator subscription renewal processed', ['subscription_id' => $subscription->id]);
    }

    protected function handleInvoicePaymentFailed($invoice)
    {
        $subscriptionId = $invoice->subscription ?? ($invoice['subscription'] ?? null);
        if (!$subscriptionId) return;

        $membershipSub = MembershipSubscription::where('gateway_subscription_id', $subscriptionId)->first();
        if ($membershipSub) {
            $membershipSub->update(['status' => 'past_due']);
            Log::warning('Membership payment failed', ['subscription_id' => $membershipSub->id]);
            return;
        }

        $creatorSub = CreatorSubscription::where('gateway_subscription_id', $subscriptionId)->first();
        if ($creatorSub) {
            $creatorSub->update(['status' => 'past_due']);
            Log::warning('Creator subscription payment failed', ['subscription_id' => $creatorSub->id]);
        }
    }

    protected function handleSubscriptionDeleted($stripeSubscription)
    {
        $subscriptionId = $stripeSubscription->id ?? ($stripeSubscription['id'] ?? null);
        if (!$subscriptionId) return;

        $membershipSub = MembershipSubscription::where('gateway_subscription_id', $subscriptionId)->first();
        if ($membershipSub) {
            $membershipSub->update(['status' => 'expired', 'ends_at' => now()]);
            Log::info('Membership subscription expired', ['subscription_id' => $membershipSub->id]);
            return;
        }

        $creatorSub = CreatorSubscription::where('gateway_subscription_id', $subscriptionId)->first();
        if ($creatorSub) {
            $creatorSub->update(['status' => 'expired', 'ends_at' => now()]);
            Log::info('Creator subscription expired', ['subscription_id' => $creatorSub->id]);
        }
    }
}
