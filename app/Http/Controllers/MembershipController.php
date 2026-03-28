<?php

namespace App\Http\Controllers;

use App\Models\MembershipPlan;
use App\Models\MembershipSubscription;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Subscription as StripeSubscription;

class MembershipController extends Controller
{
    public function index()
    {
        $memberships = MembershipPlan::published()
            ->withCount('activeSubscriptions')
            ->latest()
            ->paginate(12);

        $currencySymbol = Setting::get('currency_symbol', '£');

        return view('memberships.index', compact('memberships', 'currencySymbol'));
    }

    public function show(MembershipPlan $membership)
    {
        $membership->load('creator');
        $currencySymbol = Setting::get('currency_symbol', '£');
        $subscribed = Auth::check() && Auth::user()->hasActiveSubscription($membership);

        $subscription = null;
        if ($subscribed) {
            $subscription = Auth::user()->membershipSubscriptions()
                ->where('membership_plan_id', $membership->id)
                ->where('status', 'active')
                ->first();
        }

        $reviews = $membership->approvedReviews()->with('user')->latest()->get();
        $averageRating = $membership->averageRating();
        $reviewCount = $reviews->count();

        $userReview = null;
        if (Auth::check()) {
            $userReview = $membership->reviews()->where('user_id', Auth::id())->first();
        }

        return view('memberships.show', compact(
            'membership', 'currencySymbol', 'subscribed', 'subscription',
            'reviews', 'averageRating', 'reviewCount', 'userReview'
        ));
    }

    public function myMemberships()
    {
        $subscriptions = Auth::user()->membershipSubscriptions()
            ->with('membershipPlan')
            ->latest()
            ->get();

        $currencySymbol = Setting::get('currency_symbol', '£');

        return view('memberships.my-memberships', compact('subscriptions', 'currencySymbol'));
    }

    public function content(MembershipPlan $membership)
    {
        $user = Auth::user();

        if (!$user->hasActiveSubscription($membership)) {
            return redirect()->route('memberships.show', $membership)
                ->with('error', 'You need an active subscription to access this content.');
        }

        $contents = $membership->contents()->published()->orderBy('sort_order')->get();

        return view('memberships.content', compact('membership', 'contents'));
    }

    public function cancel(MembershipPlan $membership)
    {
        $user = Auth::user();
        $subscription = $user->membershipSubscriptions()
            ->where('membership_plan_id', $membership->id)
            ->where('status', 'active')
            ->first();

        if (!$subscription) {
            return back()->with('error', 'No active subscription found.');
        }

        // Cancel on Stripe if applicable
        if ($subscription->gateway === 'stripe' && $subscription->gateway_subscription_id) {
            try {
                Stripe::setApiKey(Setting::get('stripe_secret_key', config('services.stripe.secret')));
                $stripeSub = StripeSubscription::retrieve($subscription->gateway_subscription_id);
                $stripeSub->cancel_at_period_end = true;
                $stripeSub->save();
            } catch (\Exception $e) {
                Log::error('Stripe Subscription Cancel Error', ['message' => $e->getMessage()]);
            }
        }

        $subscription->update([
            'cancelled_at' => now(),
            'ends_at' => $subscription->current_period_end,
        ]);

        return back()->with('success', 'Your subscription has been cancelled. You will retain access until the end of your billing period.');
    }
}
