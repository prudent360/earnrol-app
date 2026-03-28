<?php

namespace App\Services\Payment;

use App\Models\Cohort;
use App\Models\MembershipPlan;
use App\Models\Setting;
use App\Models\User;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Product;
use Stripe\Price;
use Illuminate\Support\Facades\Log;

class StripeService
{
    protected string $secretKey;

    public function __construct()
    {
        $this->secretKey = Setting::get('stripe_secret_key', config('services.stripe.secret'));
        Stripe::setApiKey($this->secretKey);
    }

    public function createCheckoutSession(Cohort $cohort, User $user, ?float $amount = null): ?Session
    {
        try {
            $currency = Setting::get('currency', 'GBP');
            $chargeAmount = $amount ?? $cohort->price;

            return Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => strtolower($currency),
                        'product_data' => [
                            'name' => $cohort->title,
                            'description' => $cohort->description ?? 'Cohort enrolment',
                        ],
                        'unit_amount' => intval($chargeAmount * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payments.callback') . '?session_id={CHECKOUT_SESSION_ID}&gateway=stripe',
                'cancel_url' => route('dashboard'),
                'customer_email' => $user->email,
                'metadata' => [
                    'cohort_id' => $cohort->id,
                    'user_id' => $user->id,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe Checkout Session Error', ['message' => $e->getMessage()]);
            return null;
        }
    }

    public function verifySession(string $sessionId): ?Session
    {
        try {
            $session = Session::retrieve($sessionId);
            if ($session->payment_status === 'paid') {
                return $session;
            }
            return null;
        } catch (\Exception $e) {
            Log::error('Stripe Session Verification Error', ['message' => $e->getMessage()]);
            return null;
        }
    }

    public function createSubscriptionProduct(MembershipPlan $plan): ?string
    {
        try {
            $product = Product::create([
                'name' => $plan->title,
                'description' => $plan->description ?? 'Membership plan',
            ]);

            return $product->id;
        } catch (\Exception $e) {
            Log::error('Stripe Product Creation Error', ['message' => $e->getMessage()]);
            return null;
        }
    }

    public function createSubscriptionPrice(string $productId, float $amount, string $interval): ?string
    {
        try {
            $currency = Setting::get('currency', 'GBP');

            $intervalMap = [
                'monthly'   => ['interval' => 'month', 'count' => 1],
                'quarterly' => ['interval' => 'month', 'count' => 3],
                'yearly'    => ['interval' => 'year',  'count' => 1],
            ];

            $recurring = $intervalMap[$interval] ?? $intervalMap['monthly'];

            $price = Price::create([
                'product' => $productId,
                'unit_amount' => intval($amount * 100),
                'currency' => strtolower($currency),
                'recurring' => [
                    'interval' => $recurring['interval'],
                    'interval_count' => $recurring['count'],
                ],
            ]);

            return $price->id;
        } catch (\Exception $e) {
            Log::error('Stripe Price Creation Error', ['message' => $e->getMessage()]);
            return null;
        }
    }

    public function createSubscriptionCheckout(MembershipPlan $plan, User $user, ?float $amount = null): ?Session
    {
        try {
            $priceId = $plan->stripe_price_id;

            if (!$priceId) {
                Log::error('Stripe Subscription Checkout: No price ID for plan', ['plan_id' => $plan->id]);
                return null;
            }

            return Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $priceId,
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => route('memberships.stripe.callback') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('memberships.show', $plan),
                'customer_email' => $user->email,
                'metadata' => [
                    'membership_plan_id' => $plan->id,
                    'user_id' => $user->id,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe Subscription Checkout Error', ['message' => $e->getMessage()]);
            return null;
        }
    }
}
