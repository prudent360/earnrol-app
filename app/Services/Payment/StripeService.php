<?php

namespace App\Services\Payment;

use App\Models\Cohort;
use App\Models\Setting;
use App\Models\User;
use Stripe\Stripe;
use Stripe\Checkout\Session;
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
}
