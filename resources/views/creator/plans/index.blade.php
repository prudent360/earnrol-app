@extends('layouts.app')

@section('title', 'Creator Plans')
@section('page_title', 'Choose Your Plan')
@section('page_subtitle', 'Subscribe to start creating and selling on the platform')

@section('content')
<div class="max-w-5xl mx-auto">
    @if($currentSubscription && $currentSubscription->isActive())
    <div class="card mb-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-[#1a1a2e]">Current Plan: {{ $currentSubscription->creatorPlan->title }}</p>
                <p class="text-xs text-gray-400">
                    @if($currentSubscription->isCancelled())
                        Access until {{ $currentSubscription->ends_at->format('M d, Y') }}
                    @else
                        Renews {{ $currentSubscription->current_period_end?->format('M d, Y') }}
                    @endif
                </p>
            </div>
            @if(!$currentSubscription->isCancelled())
            <form method="POST" action="{{ route('creator.plans.cancel') }}">
                @csrf
                <button type="submit" class="text-xs text-red-500 hover:underline" onclick="return confirm('Cancel your subscription? You will keep access until the end of the current billing period.')">Cancel Subscription</button>
            </form>
            @endif
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($plans as $plan)
        <div class="card relative {{ $plan->is_featured ? 'ring-2 ring-[#e05a3a]' : '' }}">
            @if($plan->is_featured)
            <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                <span class="bg-[#e05a3a] text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full">Most Popular</span>
            </div>
            @endif

            <div class="text-center pt-4 pb-6">
                <h3 class="text-lg font-bold text-[#1a1a2e]">{{ $plan->title }}</h3>
                @if($plan->description)
                <p class="text-sm text-gray-400 mt-1">{{ $plan->description }}</p>
                @endif
                <div class="mt-4">
                    <span class="text-3xl font-bold text-[#1a1a2e]">{{ $currencySymbol }}{{ number_format($plan->price, 2) }}</span>
                    <span class="text-sm text-gray-400">{{ $plan->billing_label }}</span>
                </div>
            </div>

            @if(count($plan->features_list) > 0)
            <ul class="space-y-3 mb-6">
                @foreach($plan->features_list as $feature)
                <li class="flex items-start gap-2 text-sm text-gray-600">
                    <svg class="w-4 h-4 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ $feature }}
                </li>
                @endforeach
            </ul>
            @endif

            <div class="text-sm text-gray-400 space-y-1 mb-6">
                <p>{{ $plan->max_products ? 'Up to ' . $plan->max_products . ' products' : 'Unlimited products' }}</p>
                <p>{{ $plan->max_cohorts ? 'Up to ' . $plan->max_cohorts . ' cohorts' : 'Unlimited cohorts' }}</p>
            </div>

            @if($currentSubscription && $currentSubscription->isActive() && $currentSubscription->creator_plan_id === $plan->id)
            <button disabled class="w-full py-3 rounded-xl text-sm font-bold bg-gray-100 text-gray-400 cursor-not-allowed">Current Plan</button>
            @else
            <form method="POST" action="{{ route('creator.plans.stripe.checkout', $plan) }}">
                @csrf
                <button type="submit" class="w-full py-3 rounded-xl text-sm font-bold {{ $plan->is_featured ? 'bg-[#e05a3a] text-white hover:bg-[#c94d30]' : 'bg-[#1a1a2e] text-white hover:bg-[#2a2a4e]' }} transition-colors">
                    Subscribe
                </button>
            </form>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection
