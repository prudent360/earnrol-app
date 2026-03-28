@extends('layouts.app')

@section('title', $cohort->title)
@section('page_title', $cohort->title)
@section('page_subtitle', 'Cohort Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Dashboard
    </a>
</div>

{{-- Cover Image --}}
@if($cohort->cover_image)
<div class="rounded-2xl overflow-hidden mb-6 max-h-72">
    <img src="{{ Storage::url($cohort->cover_image) }}" alt="{{ $cohort->title }}" class="w-full h-72 object-cover">
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Main Content --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Title & Status --}}
        <div class="card">
            <div class="flex items-center gap-2 mb-3">
                <span class="badge {{ $cohort->status === 'active' ? 'bg-green-100 text-green-700' : ($cohort->status === 'upcoming' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500') }}">
                    {{ ucfirst($cohort->status) }}
                </span>
                @if($isEnrolled)
                <span class="badge bg-emerald-100 text-emerald-700">Enrolled</span>
                @endif
                @if($cohort->isFull() && !$isEnrolled)
                <span class="badge bg-red-100 text-red-600">Full</span>
                @endif
            </div>

            <h1 class="text-2xl font-bold text-[#1a1a2e] mb-3">{{ $cohort->title }}</h1>

            @if($cohort->description)
            <p class="text-gray-600 leading-relaxed">{{ $cohort->description }}</p>
            @endif

            {{-- Key Details Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 pt-6 border-t border-gray-100">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Start Date</p>
                    <p class="text-sm font-semibold text-[#1a1a2e]">{{ $cohort->start_date->format('M d, Y') }}</p>
                </div>
                @if($cohort->end_date)
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">End Date</p>
                    <p class="text-sm font-semibold text-[#1a1a2e]">{{ $cohort->end_date->format('M d, Y') }}</p>
                </div>
                @endif
                @if($cohort->duration)
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Duration</p>
                    <p class="text-sm font-semibold text-[#1a1a2e]">{{ $cohort->duration }}</p>
                </div>
                @endif
                @if($cohort->schedule)
                <div class="col-span-2 sm:col-span-1">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Schedule</p>
                    <p class="text-sm font-semibold text-[#1a1a2e]">{{ $cohort->schedule }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- What You'll Learn --}}
        @if(count($cohort->what_you_will_learn_list) > 0)
        <div class="card">
            <h2 class="text-lg font-bold text-[#1a1a2e] mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                What You'll Learn
            </h2>
            <ul class="space-y-3">
                @foreach($cohort->what_you_will_learn_list as $item)
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-sm text-gray-700">{{ $item }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Prerequisites --}}
        @if(count($cohort->prerequisites_list) > 0)
        <div class="card">
            <h2 class="text-lg font-bold text-[#1a1a2e] mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Prerequisites
            </h2>
            <ul class="space-y-3">
                @foreach($cohort->prerequisites_list as $item)
                <li class="flex items-start gap-3">
                    <svg class="w-4 h-4 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-sm text-gray-700">{{ $item }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Facilitator / Creator --}}
        @php
            $facilitator = $cohort->creator_id ? \App\Models\User::find($cohort->creator_id) : null;
            // Fall back to manual facilitator fields for admin-created cohorts
            $facName = $facilitator ? $facilitator->name : $cohort->facilitator_name;
            $facBio = $facilitator ? $facilitator->bio : $cohort->facilitator_bio;
            $facAvatar = $facilitator?->avatar;
            $facImage = $facAvatar ? Storage::url($facAvatar) : ($cohort->facilitator_image ? Storage::url($cohort->facilitator_image) : null);
        @endphp
        @if($facName)
        <div class="card">
            <h2 class="text-lg font-bold text-[#1a1a2e] mb-4">Meet Your Facilitator</h2>
            <div class="flex items-start gap-4">
                @if($facImage)
                <img src="{{ $facImage }}" alt="{{ $facName }}" class="w-20 h-20 rounded-full object-cover flex-shrink-0 border-2 border-gray-100">
                @else
                <div class="w-20 h-20 rounded-full bg-[#1a2535] flex items-center justify-center flex-shrink-0">
                    <span class="text-2xl font-bold text-white">{{ strtoupper(substr($facName, 0, 1)) }}</span>
                </div>
                @endif
                <div>
                    <h3 class="text-base font-bold text-[#1a1a2e]">{{ $facName }}</h3>
                    @if($facBio)
                    <p class="text-sm text-gray-600 mt-1 leading-relaxed">{{ $facBio }}</p>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">

        {{-- Enrolment Card --}}
        <div class="card sticky top-6">
            <div class="text-center mb-5">
                @if($paymentEnabled && $cohort->price > 0)
                <p class="text-3xl font-bold text-[#1a1a2e]">
                    {{ \App\Models\Setting::get('currency_symbol', '£') }}{{ number_format($cohort->price, 2) }}
                </p>
                <p class="text-xs text-gray-400 mt-1">One-time payment</p>
                @else
                <p class="text-3xl font-bold text-green-600">Free</p>
                <p class="text-xs text-gray-400 mt-1">No payment required</p>
                @endif
            </div>

            @if($isEnrolled)
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-center mb-4">
                    <svg class="w-8 h-8 text-emerald-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm font-bold text-emerald-700">You're Enrolled!</p>
                </div>

                @if($cohort->status === 'active' && $cohort->google_meet_link)
                <a href="{{ $cohort->google_meet_link }}" target="_blank" rel="noopener noreferrer" class="btn-primary w-full justify-center py-3 text-base mb-3 inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Join Live Class
                </a>
                @endif

                <a href="{{ route('cohorts.materials', $cohort) }}" class="w-full py-2.5 rounded-xl text-sm font-bold bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    View Materials
                </a>
            @elseif($cohort->isFull())
                <button disabled class="w-full py-3 rounded-xl bg-gray-200 text-gray-500 text-sm font-bold cursor-not-allowed">Cohort Full</button>
            @elseif($paymentEnabled && $cohort->price > 0)
                <div x-data="{
                    code: '',
                    validCode: '',
                    loading: false,
                    message: '',
                    valid: false,
                    discount: 0,
                    finalAmount: {{ $cohort->price }},
                    originalPrice: {{ $cohort->price }},
                    async applyCoupon() {
                        if (!this.code.trim()) return;
                        this.loading = true;
                        this.message = '';
                        try {
                            const res = await fetch('{{ route('coupons.validate') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    code: this.code,
                                    amount: this.originalPrice,
                                    type: 'cohort',
                                    item_id: {{ $cohort->id }}
                                })
                            });
                            const data = await res.json();
                            this.valid = data.valid;
                            this.message = data.message;
                            if (data.valid) {
                                this.discount = data.discount;
                                this.finalAmount = data.final_amount;
                                this.validCode = this.code;
                            } else {
                                this.discount = 0;
                                this.finalAmount = this.originalPrice;
                                this.validCode = '';
                            }
                        } catch (e) {
                            this.message = 'Unable to validate coupon.';
                            this.valid = false;
                        }
                        this.loading = false;
                    },
                    removeCoupon() {
                        this.code = '';
                        this.validCode = '';
                        this.valid = false;
                        this.message = '';
                        this.discount = 0;
                        this.finalAmount = this.originalPrice;
                    }
                }">
                    <div class="flex gap-2 mb-3">
                        <input type="text" x-model="code" placeholder="Coupon code" class="form-input text-sm flex-1" :disabled="valid" @keydown.enter.prevent="applyCoupon()">
                        <button type="button" x-show="!valid" @click="applyCoupon()" :disabled="loading || !code.trim()" class="px-3 py-2 text-xs font-bold rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors disabled:opacity-50">
                            <span x-show="!loading">Apply</span>
                            <span x-show="loading">...</span>
                        </button>
                        <button type="button" x-show="valid" @click="removeCoupon()" class="px-3 py-2 text-xs font-bold rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                            Remove
                        </button>
                    </div>
                    <p x-show="message" x-text="message" class="text-xs mb-2" :class="valid ? 'text-green-600' : 'text-red-500'"></p>
                    <div x-show="valid" x-transition class="mb-3 bg-green-50 rounded-lg p-2.5 text-xs space-y-1">
                        <div class="flex justify-between text-gray-500">
                            <span>Original price</span>
                            <span>{{ \App\Models\Setting::get('currency_symbol', '£') }}<span x-text="originalPrice.toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between text-green-600 font-semibold">
                            <span>Discount</span>
                            <span>-{{ \App\Models\Setting::get('currency_symbol', '£') }}<span x-text="discount.toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between text-[#1a1a2e] font-bold border-t border-green-200 pt-1">
                            <span>Total</span>
                            <span>{{ \App\Models\Setting::get('currency_symbol', '£') }}<span x-text="finalAmount.toFixed(2)"></span></span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @if($stripeEnabled)
                        <form method="POST" action="{{ route('payments.stripe.checkout', $cohort) }}">
                            @csrf
                            <input type="hidden" name="coupon_code" :value="validCode">
                            <button type="submit" class="btn-primary w-full justify-center py-3 text-base inline-flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                {{ $paypalEnabled ? 'Pay with Card' : 'Enrol Now' }}
                            </button>
                        </form>
                        @endif
                        @if($paypalEnabled)
                        <form method="POST" action="{{ route('payments.paypal.checkout', $cohort) }}">
                            @csrf
                            <input type="hidden" name="coupon_code" :value="validCode">
                            <button type="submit" class="w-full py-3 rounded-xl text-base font-bold bg-[#003087] text-white hover:bg-[#002060] transition-colors inline-flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106z"/></svg>
                                Pay with PayPal
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            @else
                <form method="POST" action="{{ route('cohorts.enrol-free', $cohort) }}">
                    @csrf
                    <button type="submit" class="btn-primary w-full justify-center py-3 text-base">Enrol Now — Free</button>
                </form>
            @endif

            {{-- Quick Info --}}
            <div class="mt-6 pt-5 border-t border-gray-100 space-y-3">
                @if($cohort->spotsLeft() !== null)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Spots remaining</span>
                    <span class="font-bold {{ $cohort->spotsLeft() <= 5 ? 'text-orange-600' : 'text-[#1a1a2e]' }}">{{ $cohort->spotsLeft() }} / {{ $cohort->max_students }}</span>
                </div>
                @endif
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Students enrolled</span>
                    <span class="font-bold text-[#1a1a2e]">{{ $cohort->enrollments()->count() }}</span>
                </div>
                @if($cohort->duration)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Duration</span>
                    <span class="font-bold text-[#1a1a2e]">{{ $cohort->duration }}</span>
                </div>
                @endif
                @if($cohort->schedule)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Schedule</span>
                    <span class="font-bold text-[#1a1a2e]">{{ $cohort->schedule }}</span>
                </div>
                @endif
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Format</span>
                    <span class="font-bold text-[#1a1a2e]">Live Online (Google Meet)</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
