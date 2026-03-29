<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CreatorPlan;
use App\Services\Payment\StripeService;
use Illuminate\Http\Request;

class CreatorPlanController extends Controller
{
    public function index()
    {
        $plans = CreatorPlan::withCount('subscriptions')
            ->orderBy('sort_order')
            ->orderBy('price')
            ->get();

        return view('admin.creator-plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.creator-plans.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0.01',
            'billing_interval' => 'required|in:monthly,yearly',
            'features' => 'nullable|string',
            'max_products' => 'nullable|integer|min:1',
            'max_cohorts' => 'nullable|integer|min:1',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $data['slug'] = CreatorPlan::generateSlug($data['title']);
        $data['features'] = $data['features']
            ? array_filter(array_map('trim', explode("\n", $data['features'])))
            : [];
        $data['is_featured'] = $request->boolean('is_featured');

        // Create Stripe product and price
        $stripe = app(StripeService::class);
        $productId = $stripe->createCreatorPlanProduct($data['title']);
        if ($productId) {
            $data['stripe_product_id'] = $productId;
            $priceId = $stripe->createCreatorPlanPrice($productId, (float) $data['price'], $data['billing_interval']);
            if ($priceId) {
                $data['stripe_price_id'] = $priceId;
            }
        }

        CreatorPlan::create($data);

        return redirect()->route('admin.creator-plans.index')->with('success', 'Creator plan created.');
    }

    public function edit(CreatorPlan $creatorPlan)
    {
        return view('admin.creator-plans.edit', ['plan' => $creatorPlan]);
    }

    public function update(Request $request, CreatorPlan $creatorPlan)
    {
        $data = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0.01',
            'billing_interval' => 'required|in:monthly,yearly',
            'features' => 'nullable|string',
            'max_products' => 'nullable|integer|min:1',
            'max_cohorts' => 'nullable|integer|min:1',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $data['features'] = $data['features']
            ? array_filter(array_map('trim', explode("\n", $data['features'])))
            : [];
        $data['is_featured'] = $request->boolean('is_featured');

        // If price or interval changed, create new Stripe price
        if ($creatorPlan->price != $data['price'] || $creatorPlan->billing_interval !== $data['billing_interval']) {
            $stripe = app(StripeService::class);
            $productId = $creatorPlan->stripe_product_id;

            if (!$productId) {
                $productId = $stripe->createCreatorPlanProduct($data['title']);
                $data['stripe_product_id'] = $productId;
            }

            if ($productId) {
                $priceId = $stripe->createCreatorPlanPrice($productId, (float) $data['price'], $data['billing_interval']);
                if ($priceId) {
                    $data['stripe_price_id'] = $priceId;
                }
            }
        }

        $creatorPlan->update($data);

        return redirect()->route('admin.creator-plans.index')->with('success', 'Plan updated.');
    }

    public function destroy(CreatorPlan $creatorPlan)
    {
        if ($creatorPlan->activeSubscriptions()->count() > 0) {
            return back()->with('error', 'Cannot delete a plan with active subscribers.');
        }

        $creatorPlan->delete();

        return redirect()->route('admin.creator-plans.index')->with('success', 'Plan deleted.');
    }

    public function subscribers(CreatorPlan $plan)
    {
        $subscribers = $plan->subscriptions()
            ->with('user:id,name,email')
            ->latest()
            ->paginate(25);

        return view('admin.creator-plans.subscribers', compact('plan', 'subscribers'));
    }
}
