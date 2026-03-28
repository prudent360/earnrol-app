<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\MembershipPlan;
use App\Models\Setting;
use App\Services\Payment\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MembershipController extends Controller
{
    public function index()
    {
        $memberships = Auth::user()->membershipPlans()
            ->withCount('activeSubscriptions')
            ->latest()
            ->paginate(10);

        return view('creator.memberships.index', compact('memberships'));
    }

    public function create()
    {
        return view('creator.memberships.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0.50',
            'billing_interval' => 'required|in:monthly,quarterly,yearly',
            'features'         => 'nullable|string',
            'max_subscribers'  => 'nullable|integer|min:1',
            'welcome_message'  => 'nullable|string',
            'cover_image'      => 'nullable|image|max:4096',
        ]);

        $data = $request->only(['title', 'description', 'price', 'billing_interval', 'features', 'max_subscribers', 'welcome_message']);
        $data['user_id'] = Auth::id();
        $data['slug'] = MembershipPlan::generateSlug($data['title']);
        $data['status'] = 'published';
        $data['approval_status'] = 'pending';

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('memberships/covers', 'public');
        }

        // Create Stripe product and price if Stripe is enabled
        if (Setting::get('stripe_enabled')) {
            $stripe = new StripeService();

            $plan = new MembershipPlan($data);
            $productId = $stripe->createSubscriptionProduct($plan);

            if ($productId) {
                $data['stripe_product_id'] = $productId;
                $priceId = $stripe->createSubscriptionPrice($productId, (float) $data['price'], $data['billing_interval']);
                $data['stripe_price_id'] = $priceId;
            }
        }

        $membership = MembershipPlan::create($data);

        if (\App\Models\Setting::get('affiliate_enabled')) {
            $membership->affiliateProduct()->updateOrCreate([], [
                'affiliate_enabled' => $request->boolean('affiliate_enabled'),
                'commission_percentage' => $request->input('affiliate_commission', 0),
            ]);
        }

        return redirect()->route('creator.memberships.index')
            ->with('success', 'Membership plan submitted for review! It will be visible once approved by an admin.');
    }

    public function edit(MembershipPlan $membership)
    {
        $this->authorizeOwner($membership);

        return view('creator.memberships.edit', compact('membership'));
    }

    public function update(Request $request, MembershipPlan $membership)
    {
        $this->authorizeOwner($membership);

        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0.50',
            'billing_interval' => 'required|in:monthly,quarterly,yearly',
            'features'         => 'nullable|string',
            'max_subscribers'  => 'nullable|integer|min:1',
            'welcome_message'  => 'nullable|string',
            'cover_image'      => 'nullable|image|max:4096',
        ]);

        $data = $request->only(['title', 'description', 'price', 'billing_interval', 'features', 'max_subscribers', 'welcome_message']);

        // Re-review if previously approved
        if ($membership->approval_status === 'approved') {
            $data['approval_status'] = 'pending';
            $data['rejection_reason'] = null;
        }

        if ($request->hasFile('cover_image')) {
            if ($membership->cover_image) {
                Storage::disk('public')->delete($membership->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('memberships/covers', 'public');
        }

        // Update Stripe price if price or interval changed
        $priceChanged = (float) $data['price'] !== (float) $membership->price || $data['billing_interval'] !== $membership->billing_interval;
        if ($priceChanged && Setting::get('stripe_enabled')) {
            $stripe = new StripeService();
            $productId = $membership->stripe_product_id;

            if (!$productId) {
                $productId = $stripe->createSubscriptionProduct($membership);
                $data['stripe_product_id'] = $productId;
            }

            if ($productId) {
                $priceId = $stripe->createSubscriptionPrice($productId, (float) $data['price'], $data['billing_interval']);
                $data['stripe_price_id'] = $priceId;
            }
        }

        $membership->update($data);

        if (\App\Models\Setting::get('affiliate_enabled')) {
            $membership->affiliateProduct()->updateOrCreate([], [
                'affiliate_enabled' => $request->boolean('affiliate_enabled'),
                'commission_percentage' => $request->input('affiliate_commission', 0),
            ]);
        }

        return redirect()->route('creator.memberships.index')
            ->with('success', 'Membership plan updated.' . ($membership->approval_status === 'pending' ? ' It will be re-reviewed by an admin.' : ''));
    }

    public function destroy(MembershipPlan $membership)
    {
        $this->authorizeOwner($membership);

        if ($membership->activeSubscriptions()->exists()) {
            return back()->with('error', 'Cannot delete a membership plan with active subscribers.');
        }

        if ($membership->cover_image) {
            Storage::disk('public')->delete($membership->cover_image);
        }

        $membership->delete();

        return redirect()->route('creator.memberships.index')
            ->with('success', 'Membership plan deleted.');
    }

    public function subscribers(MembershipPlan $membership)
    {
        $this->authorizeOwner($membership);

        $subscribers = $membership->subscriptions()
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('creator.memberships.subscribers', compact('membership', 'subscribers'));
    }

    protected function authorizeOwner(MembershipPlan $membership): void
    {
        if ($membership->user_id !== Auth::id()) {
            abort(403, 'You do not own this membership plan.');
        }
    }
}
