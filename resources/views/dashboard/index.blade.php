@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Welcome back to your learning hub')

@section('content')

{{-- Welcome banner --}}
<div class="bg-[#1a2535] rounded-2xl p-6 mb-6 relative overflow-hidden">
    <div class="absolute right-0 top-0 bottom-0 w-1/3 opacity-10">
        <div class="w-64 h-64 rounded-full absolute -right-10 -top-10" style="background-color: {{ \App\Models\Setting::get('brand_color', '#e05a3a') }};"></div>
    </div>
    <div class="relative">
        <p class="text-gray-400 text-sm mb-1">Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }},</p>
        <h2 class="text-2xl font-bold text-white mb-1">{{ auth()->user()->name ?? 'Learner' }}</h2>

        @if($activeCohort)
        <p class="text-gray-300 text-sm">Your class <span class="font-bold text-white">{{ $activeCohort->cohort->title }}</span> is live!</p>
        <div class="mt-4">
            <a href="{{ $activeCohort->cohort->google_meet_link }}" target="_blank" rel="noopener noreferrer" class="btn-primary text-sm py-2.5 inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                Join Live Class
            </a>
        </div>
        @else
        <p class="text-gray-300 text-sm">Explore available cohorts below and enrol to start your learning journey.</p>
        @endif
    </div>
</div>

{{-- Active Cohort Card --}}
@if($activeCohort && $activeCohort->cohort->google_meet_link)
<div class="mb-6">
    <div class="bg-white rounded-2xl border-2 border-green-200 p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                    <span class="text-xs font-bold text-green-700 uppercase tracking-wider">Live Now</span>
                </div>
                <h3 class="text-xl font-bold text-[#1a1a2e]">{{ $activeCohort->cohort->title }}</h3>
                @if($activeCohort->cohort->description)
                <p class="text-sm text-gray-500 mt-1">{{ $activeCohort->cohort->description }}</p>
                @endif
                <p class="text-xs text-gray-400 mt-2">Started {{ $activeCohort->cohort->start_date->format('M d, Y') }}</p>
            </div>
            <a href="{{ $activeCohort->cohort->google_meet_link }}" target="_blank" rel="noopener noreferrer"
               class="btn-primary py-3 px-8 text-base flex-shrink-0 inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                Join Google Meet
            </a>
        </div>
    </div>
</div>
@endif

{{-- My Enrolled Cohorts --}}
@if($enrolledCohorts->count() > 0)
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-[#1a1a2e]">My Cohorts</h3>
        <a href="{{ route('cohorts.index') }}" class="text-sm font-medium text-[#e05a3a] hover:underline">View all</a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($enrolledCohorts as $enrollment)
        <div class="card">
            <div class="flex items-center gap-2 mb-3">
                <span class="badge {{ $enrollment->cohort->status === 'active' ? 'bg-green-100 text-green-700' : ($enrollment->cohort->status === 'upcoming' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500') }}">
                    {{ ucfirst($enrollment->cohort->status) }}
                </span>
            </div>
            <h4 class="font-bold text-[#1a1a2e] mb-1">{{ $enrollment->cohort->title }}</h4>
            <p class="text-xs text-gray-400 mb-3">Starts {{ $enrollment->cohort->start_date->format('M d, Y') }}</p>
            <div class="flex items-center gap-4">
                <a href="{{ route('cohorts.materials', $enrollment->cohort) }}" class="text-sm font-bold text-blue-600 hover:underline flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Materials
                </a>
                @if($enrollment->cohort->status === 'active' && $enrollment->cohort->google_meet_link)
                <a href="{{ $enrollment->cohort->google_meet_link }}" target="_blank" rel="noopener noreferrer" class="text-sm font-bold text-[#e05a3a] hover:underline flex items-center gap-1">
                    Join Class
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Available Cohorts --}}
<div>
    <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">Available Cohorts</h3>
    @if($availableCohorts->isEmpty())
    <div class="bg-white rounded-2xl p-8 border border-dashed border-gray-300 text-center">
        <p class="text-gray-500 text-sm">No new cohorts available right now. Check back soon!</p>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($availableCohorts as $cohort)
        <div class="card hover:shadow-md transition-shadow">
            <div class="flex items-center gap-2 mb-3">
                <span class="badge {{ $cohort->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                    {{ ucfirst($cohort->status) }}
                </span>
                @if($cohort->isFull())
                <span class="badge bg-red-100 text-red-600">Full</span>
                @elseif($cohort->spotsLeft() !== null)
                <span class="badge bg-orange-100 text-orange-600">{{ $cohort->spotsLeft() }} spots left</span>
                @endif
            </div>
            <h4 class="font-bold text-[#1a1a2e] mb-1">{{ $cohort->title }}</h4>
            @if($cohort->description)
            <p class="text-xs text-gray-500 mb-3">{{ Str::limit($cohort->description, 80) }}</p>
            @endif
            <div class="flex items-center justify-between text-xs text-gray-400 mb-4">
                <span>Starts {{ $cohort->start_date->format('M d, Y') }}</span>
                <span class="font-bold text-[#1a1a2e] text-base">
                    {{ \App\Models\Setting::get('currency_symbol', '£') }}{{ number_format($cohort->price, 2) }}
                </span>
            </div>

            @if($cohort->isFull())
                <button disabled class="w-full py-2.5 rounded-xl bg-gray-200 text-gray-500 text-sm font-bold cursor-not-allowed">Cohort Full</button>
            @elseif($paymentEnabled && $cohort->price > 0)
                <div class="flex gap-2">
                    @if($stripeEnabled)
                    <form method="POST" action="{{ route('payments.stripe.checkout', $cohort) }}" class="flex-1">
                        @csrf
                        <button type="submit" class="btn-primary w-full justify-center py-2.5 text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            {{ $paypalEnabled ? 'Card' : 'Enrol Now' }}
                        </button>
                    </form>
                    @endif
                    @if($paypalEnabled)
                    <form method="POST" action="{{ route('payments.paypal.checkout', $cohort) }}" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full py-2.5 rounded-xl text-sm font-bold bg-[#003087] text-white hover:bg-[#002060] transition-colors inline-flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106z"/></svg>
                            PayPal
                        </button>
                    </form>
                    @endif
                </div>
            @else
                <form method="POST" action="{{ route('cohorts.enrol-free', $cohort) }}">
                    @csrf
                    <button type="submit" class="btn-primary w-full justify-center py-2.5 text-sm">Enrol Now — Free</button>
                </form>
            @endif
        </div>
        @endforeach
    </div>
    @endif
</div>

@endsection
