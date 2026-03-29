<?php

namespace App\Http\Controllers;

use App\Models\CreatorPlan;
use App\Models\CreatorSubscription;
use App\Models\Payment;
use App\Models\Setting;
use App\Services\Payment\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreatorPlanPaymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $plans = CreatorPlan::active()->orderBy('sort_order')->orderBy('price')->get();
        $currentSubscription = $user->creatorSubscription;
        $currencySymbol = Setting::get('currency_symbol', '£');

        return view('creator.plans.index', compact('plans', 'currentSubscription', 'currencySymbol'));
    }

    public function stripeCheckout(CreatorPlan $plan)
    {
        $user = Auth::user();

        if (!$user->isCreator()) {
            return back()->with('error', 'You must be an approved creator to subscribe.');
        }

        $stripe = app(StripeService::class);
        $session = $stripe->createCreatorPlanCheckout($plan, $user);

        if (!$session) {
            return back()->with('error', 'Unable to create checkout session. Please try again.');
        }

        // Create pending payment
        Payment::create([
            'user_id' => $user->id,
            'payable_type' => CreatorPlan::class,
            'payable_id' => $plan->id,
            'amount' => $plan->price,
            'reference' => $session->id,
            'gateway' => 'stripe',
            'status' => 'pending',
            'currency' => Setting::get('currency', 'GBP'),
        ]);

        return redirect($session->url);
    }

    public function stripeCallback(Request $request)
    {
        $sessionId = $request->get('session_id');
        if (!$sessionId) {
            return redirect()->route('creator.plans.index')->with('error', 'Invalid session.');
        }

        $stripe = app(StripeService::class);
        $session = $stripe->verifySession($sessionId);

        if (!$session) {
            return redirect()->route('creator.plans.index')->with('error', 'Payment verification failed.');
        }

        $payment = Payment::where('reference', $sessionId)->first();
        if (!$payment || $payment->status === 'completed') {
            return redirect()->route('creator.dashboard')->with('info', 'Payment already processed.');
        }

        $payment->update([
            'status' => 'completed',
            'gateway_response' => json_encode($session->toArray()),
        ]);

        $plan = CreatorPlan::find($payment->payable_id);
        $billingMonths = $plan->billing_interval === 'yearly' ? 12 : 1;
        $trialDays = (int) Setting::get('creator_trial_days', 0);

        $subscription = CreatorSubscription::create([
            'user_id' => $payment->user_id,
            'creator_plan_id' => $plan->id,
            'payment_id' => $payment->id,
            'status' => 'active',
            'gateway' => 'stripe',
            'gateway_subscription_id' => $session->subscription ?? null,
            'current_period_start' => now(),
            'current_period_end' => now()->addMonths($billingMonths),
            'trial_ends_at' => $trialDays > 0 ? now()->addDays($trialDays) : null,
        ]);

        // Ensure user is in creator mode
        $user = Auth::user();
        $user->update(['active_mode' => 'creator']);

        return redirect()->route('creator.dashboard')->with('success', 'Subscribed to ' . $plan->title . ' plan!');
    }

    public function cancel()
    {
        $user = Auth::user();
        $subscription = $user->creatorSubscription;

        if (!$subscription || !$subscription->isActive()) {
            return back()->with('error', 'No active subscription to cancel.');
        }

        $subscription->update([
            'cancelled_at' => now(),
            'ends_at' => $subscription->current_period_end,
        ]);

        return back()->with('success', 'Subscription cancelled. Access continues until ' . $subscription->current_period_end->format('M d, Y') . '.');
    }
}
