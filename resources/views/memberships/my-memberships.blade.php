@extends('layouts.app')

@section('title', 'My Memberships')
@section('page_title', 'My Memberships')
@section('page_subtitle', 'Manage your active subscriptions')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($subscriptions as $sub)
    <div class="card !p-0 overflow-hidden">
        @if($sub->membershipPlan->cover_image)
        <img src="{{ Storage::url($sub->membershipPlan->cover_image) }}" alt="{{ $sub->membershipPlan->title }}" class="w-full h-32 object-cover">
        @else
        <div class="w-full h-32 bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center">
            <svg class="w-10 h-10 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
        </div>
        @endif

        <div class="p-5">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-bold text-[#1a1a2e]">{{ $sub->membershipPlan->title }}</h3>
                @if($sub->isActive())
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700">Active</span>
                @elseif($sub->status === 'past_due')
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-700">Past Due</span>
                @else
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700">{{ ucfirst($sub->status) }}</span>
                @endif
            </div>

            <p class="text-lg font-extrabold text-[#1a1a2e]">
                {{ $currencySymbol }}{{ number_format($sub->membershipPlan->price, 2) }}
                <span class="text-xs text-gray-400 font-normal">/{{ $sub->membershipPlan->billing_interval }}</span>
            </p>

            @if($sub->cancelled_at)
            <p class="text-xs text-amber-600 mt-1">Cancels {{ $sub->ends_at?->format('M d, Y') }}</p>
            @elseif($sub->current_period_end)
            <p class="text-xs text-gray-400 mt-1">Renews {{ $sub->current_period_end->format('M d, Y') }}</p>
            @endif

            <div class="mt-4 space-y-2">
                @if($sub->isActive())
                <a href="{{ route('memberships.content', $sub->membershipPlan) }}" class="btn-primary w-full justify-center text-sm py-2">
                    Access Content
                </a>
                @endif
                <a href="{{ route('memberships.show', $sub->membershipPlan) }}" class="block w-full text-center text-sm text-gray-500 hover:text-[#e05a3a] font-medium">
                    View Details
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-16 text-gray-400">
        <p>You don't have any subscriptions yet.</p>
        <a href="{{ route('memberships.index') }}" class="text-[#e05a3a] hover:underline mt-2 inline-block">Browse Memberships</a>
    </div>
    @endforelse
</div>
@endsection
