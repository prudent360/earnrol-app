<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\CohortEnrollment;
use App\Models\Payment;
use App\Models\Setting;
use App\Services\Payment\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected StripeService $stripe;

    public function __construct(StripeService $stripe)
    {
        $this->stripe = $stripe;
    }

    public function initialize(Cohort $cohort)
    {
        $user = Auth::user();

        // Already enrolled?
        if ($user->cohortEnrollments()->where('cohort_id', $cohort->id)->exists()) {
            return redirect()->route('dashboard')->with('error', 'You are already enrolled in this cohort.');
        }

        // Cohort full?
        if ($cohort->isFull()) {
            return redirect()->route('dashboard')->with('error', 'This cohort is full.');
        }

        // Payment not enabled — free enrol
        if (!Setting::get('stripe_enabled')) {
            CohortEnrollment::create([
                'user_id' => $user->id,
                'cohort_id' => $cohort->id,
                'enrolled_at' => now(),
            ]);
            return redirect()->route('dashboard')->with('success', 'You are now enrolled in ' . $cohort->title . '!');
        }

        // Create Stripe Checkout Session
        $session = $this->stripe->createCheckoutSession($cohort, $user);

        if ($session) {
            Payment::create([
                'user_id' => $user->id,
                'payable_type' => Cohort::class,
                'payable_id' => $cohort->id,
                'amount' => $cohort->price,
                'reference' => $session->id,
                'gateway' => 'stripe',
                'status' => 'pending',
                'currency' => Setting::get('currency', 'GBP'),
            ]);
            return redirect($session->url);
        }

        return back()->with('error', 'Unable to initialize payment. Please try again.');
    }

    public function callback(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('dashboard')->with('error', 'Missing session ID.');
        }

        $session = $this->stripe->verifySession($sessionId);

        if ($session) {
            $payment = Payment::where('reference', $sessionId)->first();
            return $this->finalizePayment($payment, $session->toArray());
        }

        return redirect()->route('dashboard')->with('error', 'Payment verification failed.');
    }

    protected function finalizePayment($payment, $gatewayResponse)
    {
        if ($payment && $payment->status !== 'completed') {
            $payment->update([
                'status' => 'completed',
                'gateway_response' => $gatewayResponse,
            ]);

            CohortEnrollment::firstOrCreate([
                'user_id' => $payment->user_id,
                'cohort_id' => $payment->payable_id,
            ], [
                'payment_id' => $payment->id,
                'enrolled_at' => now(),
            ]);

            $cohort = Cohort::find($payment->payable_id);
            return redirect()->route('dashboard')
                ->with('success', 'Payment successful! You are now enrolled in ' . ($cohort->title ?? 'the cohort') . '.');
        }

        return redirect()->route('dashboard')->with('error', 'Payment already processed or not found.');
    }
}
