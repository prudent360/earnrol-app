@extends('layouts.app')

@section('title', $membership->title)
@section('page_title', $membership->title)
@section('page_subtitle', 'Membership Plan')

@section('content')
<div class="mb-6">
    <a href="{{ route('memberships.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Memberships
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Plan Details --}}
    <div class="lg:col-span-2 space-y-5">
        @if($membership->cover_image)
        <div class="rounded-2xl overflow-hidden">
            <img src="{{ Storage::url($membership->cover_image) }}" alt="{{ $membership->title }}" class="w-full h-64 object-cover">
        </div>
        @endif

        <div class="card">
            <h2 class="text-xl font-bold text-[#1a1a2e] mb-2">{{ $membership->title }}</h2>
            @if($membership->creator)
            <p class="text-sm text-gray-500 mb-3">by <span class="font-medium text-[#1a1a2e]">{{ $membership->creator->name }}</span></p>
            @endif
            @if($membership->description)
            <div class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $membership->description }}</div>
            @endif
        </div>

        @if(count($membership->features_list) > 0)
        <div class="card">
            <h3 class="text-sm font-bold text-[#1a1a2e] mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                What You Get
            </h3>
            <ul class="space-y-2.5">
                @foreach($membership->features_list as $feature)
                <li class="flex items-center gap-2.5 text-sm text-gray-600">
                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ $feature }}
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Welcome Message (only for subscribers) --}}
        @if($subscribed && $membership->welcome_message)
        <div class="bg-green-50 border border-green-200 rounded-2xl p-5">
            <h3 class="text-sm font-bold text-green-700 mb-2 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Welcome!
            </h3>
            <p class="text-sm text-green-600 whitespace-pre-line">{{ $membership->welcome_message }}</p>
        </div>
        @endif
    </div>

    {{-- Sidebar: Pricing & Actions --}}
    <div class="space-y-5">
        <div class="card sticky top-6">
            <div class="text-center mb-5">
                <p class="text-3xl font-extrabold text-[#1a1a2e]">{{ $currencySymbol }}{{ number_format($membership->price, 2) }}</p>
                <p class="text-sm text-gray-400">per {{ $membership->billing_interval }}</p>
            </div>

            @if($subscribed)
                <div class="bg-green-50 rounded-xl p-4 text-center mb-4">
                    <p class="text-sm font-bold text-green-700">You're Subscribed!</p>
                    @if($subscription && $subscription->cancelled_at)
                    <p class="text-xs text-amber-600 mt-1">Access until {{ $subscription->ends_at?->format('M d, Y') }}</p>
                    @elseif($subscription)
                    <p class="text-xs text-gray-500 mt-1">Renews {{ $subscription->current_period_end?->format('M d, Y') }}</p>
                    @endif
                </div>

                <a href="{{ route('memberships.content', $membership) }}" class="btn-primary w-full justify-center mb-3">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Access Content
                </a>

                @if($subscription && !$subscription->cancelled_at)
                <form action="{{ route('memberships.cancel', $membership) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-center text-sm text-red-500 hover:text-red-700 font-medium" onclick="return confirm('Are you sure you want to cancel? You will retain access until the end of your billing period.')">
                        Cancel Subscription
                    </button>
                </form>
                @endif
            @else
                @if($membership->isFull())
                <div class="bg-amber-50 rounded-xl p-4 text-center">
                    <p class="text-sm text-amber-700 font-medium">This membership is currently full.</p>
                </div>
                @else
                    @if(\App\Models\Setting::get('stripe_enabled') && $membership->stripe_price_id)
                    <form action="{{ route('memberships.stripe.checkout', $membership) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primary w-full justify-center mb-3">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Subscribe with Card
                        </button>
                    </form>
                    @endif

                    @if(\App\Models\Setting::get('bank_transfer_enabled'))
                    <a href="{{ route('memberships.bank-transfer', $membership) }}" class="block w-full text-center px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:border-gray-300 hover:bg-gray-50 transition-colors">
                        Pay via Bank Transfer
                    </a>
                    @endif
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
