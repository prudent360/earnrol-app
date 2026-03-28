@extends('layouts.app')

@section('title', $coaching->title)
@section('page_title', $coaching->title)
@section('page_subtitle', '1-on-1 Coaching')

@section('content')
<div class="mb-6">
    <a href="{{ route('coaching.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Coaching
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{ selectedSlot: null }">
    {{-- Details --}}
    <div class="lg:col-span-2 space-y-5">
        @if($coaching->cover_image)
        <div class="rounded-2xl overflow-hidden">
            <img src="{{ Storage::url($coaching->cover_image) }}" alt="{{ $coaching->title }}" class="w-full h-64 object-cover">
        </div>
        @endif

        <div class="card">
            <h2 class="text-xl font-bold text-[#1a1a2e] mb-2">{{ $coaching->title }}</h2>
            @if($coaching->creator)
            <p class="text-sm text-gray-500 mb-3">by <span class="font-medium text-[#1a1a2e]">{{ $coaching->creator->name }}</span></p>
            @endif

            <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $coaching->duration_minutes }} minutes
                </span>
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    {{ $coaching->platform_label }}
                </span>
            </div>

            @if($coaching->description)
            <div class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $coaching->description }}</div>
            @endif
        </div>

        {{-- Available Slots --}}
        @if($availableSlots->count() > 0)
        <div class="card">
            <h3 class="text-sm font-bold text-[#1a1a2e] mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Available Time Slots
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($availableSlots as $slot)
                <button type="button" @click="selectedSlot = {{ $slot->id }}"
                    :class="selectedSlot === {{ $slot->id }} ? 'border-[#e05a3a] bg-[#e05a3a]/5 ring-1 ring-[#e05a3a]' : 'border-gray-200 hover:border-gray-300'"
                    class="border rounded-xl p-3 text-left transition-colors">
                    <p class="text-sm font-semibold text-[#1a1a2e]">{{ $slot->start_time->format('l, M d, Y') }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $slot->start_time->format('g:i A') }} — {{ $slot->end_time->format('g:i A') }}</p>
                </button>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Sidebar: Pricing & Book --}}
    <div class="space-y-5">
        <div class="card sticky top-6">
            <div class="text-center mb-5">
                <p class="text-3xl font-extrabold text-[#1a1a2e]">{{ $currencySymbol }}{{ number_format($coaching->price, 2) }}</p>
                <p class="text-sm text-gray-400">per session</p>
            </div>

            @if($availableSlots->count() > 0)
                <p class="text-xs text-gray-500 text-center mb-3" x-show="!selectedSlot">Select a time slot to book</p>

                <div x-show="selectedSlot" x-transition>
                    @if(\App\Models\Setting::get('stripe_enabled'))
                    <form action="{{ route('coaching.stripe.checkout', $coaching) }}" method="POST" class="mb-3">
                        @csrf
                        <input type="hidden" name="slot_id" :value="selectedSlot">
                        <button type="submit" class="btn-primary w-full justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Book with Card
                        </button>
                    </form>
                    @endif

                    @if(\App\Models\Setting::get('bank_transfer_enabled'))
                    <form action="{{ route('coaching.bank-transfer', $coaching) }}" method="GET">
                        <input type="hidden" name="slot_id" :value="selectedSlot">
                        <button type="submit" class="block w-full text-center px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:border-gray-300 hover:bg-gray-50 transition-colors">
                            Pay via Bank Transfer
                        </button>
                    </form>
                    @endif
                </div>
            @else
                <div class="bg-gray-50 rounded-xl p-4 text-center">
                    <p class="text-sm text-gray-500">No time slots available right now.</p>
                    <p class="text-xs text-gray-400 mt-1">Check back later for new openings.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
