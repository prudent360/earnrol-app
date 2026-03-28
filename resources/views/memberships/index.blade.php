@extends('layouts.app')

@section('title', 'Memberships')
@section('page_title', 'Memberships')
@section('page_subtitle', 'Subscribe to exclusive content from top creators')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($memberships as $plan)
    <a href="{{ route('memberships.show', $plan) }}" class="card hover:shadow-lg transition-shadow group !p-0 overflow-hidden">
        @if($plan->cover_image)
        <img src="{{ Storage::url($plan->cover_image) }}" alt="{{ $plan->title }}" class="w-full h-40 object-cover">
        @else
        <div class="w-full h-40 bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center">
            <svg class="w-12 h-12 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
        </div>
        @endif
        <div class="p-5">
            <h3 class="text-lg font-bold text-[#1a1a2e] group-hover:text-[#e05a3a] transition-colors">{{ $plan->title }}</h3>
            @if($plan->creator)
            <p class="text-xs text-gray-400 mt-1">by {{ $plan->creator->name }}</p>
            @endif
            @if($plan->description)
            <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ Str::limit($plan->description, 100) }}</p>
            @endif

            @if(count($plan->features_list) > 0)
            <ul class="mt-3 space-y-1">
                @foreach(array_slice($plan->features_list, 0, 3) as $feature)
                <li class="flex items-center gap-1.5 text-xs text-gray-500">
                    <svg class="w-3.5 h-3.5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ $feature }}
                </li>
                @endforeach
                @if(count($plan->features_list) > 3)
                <li class="text-xs text-gray-400">+{{ count($plan->features_list) - 3 }} more</li>
                @endif
            </ul>
            @endif

            <div class="mt-4 flex items-center justify-between">
                <div>
                    <span class="text-xl font-extrabold text-[#1a1a2e]">{{ $currencySymbol }}{{ number_format($plan->price, 2) }}</span>
                    <span class="text-xs text-gray-400">/{{ $plan->billing_interval }}</span>
                </div>
                @if($plan->active_subscriptions_count > 0)
                <span class="text-[10px] text-gray-400">{{ $plan->active_subscriptions_count }} {{ Str::plural('subscriber', $plan->active_subscriptions_count) }}</span>
                @endif
            </div>
        </div>
    </a>
    @empty
    <div class="col-span-full text-center py-16 text-gray-400">
        No membership plans available yet.
    </div>
    @endforelse
</div>

@if($memberships->hasPages())
<div class="mt-8">
    {{ $memberships->links() }}
</div>
@endif
@endsection
