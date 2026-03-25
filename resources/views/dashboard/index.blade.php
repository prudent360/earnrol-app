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

{{-- Referral Banner --}}
@if(\App\Models\Setting::get('referral_enabled') && auth()->user()->referral_code)
<div class="rounded-2xl p-5 mb-6 relative overflow-hidden" style="background: linear-gradient(135deg, #1a2535 0%, #2a3f55 40%, #e05a3a 100%);">
    {{-- Decorative elements --}}
    <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
    <div class="absolute right-20 bottom-0 w-20 h-20 bg-[#e05a3a]/30 rounded-full blur-xl"></div>
    <div class="absolute left-1/2 -top-4 w-24 h-24 bg-white/5 rounded-full blur-2xl"></div>

    <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 bg-white/15 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0 border border-white/20">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
            </div>
            <div>
                <p class="text-white font-bold text-sm">Refer & Earn {{ \App\Models\Setting::get('referral_commission', 10) }}% Commission</p>
                <p class="text-white/60 text-xs mt-0.5">Share your link with friends and earn on their first payment</p>
            </div>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <button onclick="navigator.clipboard.writeText('{{ auth()->user()->referralLink() }}').then(()=>{ this.textContent='Copied!'; setTimeout(()=>{ this.textContent='Copy Link'; }, 2000); })" class="bg-white text-[#1a2535] hover:bg-gray-100 px-5 py-2.5 rounded-xl text-sm font-bold transition-colors shadow-lg">
                Copy Link
            </button>
            <a href="{{ route('referrals.index') }}" class="bg-white/15 hover:bg-white/25 backdrop-blur-sm text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-colors border border-white/20">
                View Referrals
            </a>
        </div>
    </div>
</div>
@endif

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
        @php $ec = $enrollment->cohort; @endphp
        <div class="card">
            <div class="flex items-center gap-2 mb-3">
                <span class="badge {{ $ec->status === 'active' ? 'bg-green-100 text-green-700' : ($ec->status === 'upcoming' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500') }}">
                    {{ ucfirst($ec->status) }}
                </span>
                <span class="badge bg-emerald-100 text-emerald-700">Enrolled</span>
            </div>
            <h4 class="font-bold text-[#1a1a2e] mb-1">{{ $ec->title }}</h4>
            <p class="text-xs text-gray-400 mb-3">Starts {{ $ec->start_date->format('M d, Y') }}</p>
            <div class="flex items-center flex-wrap gap-3">
                <button type="button" onclick="openCohortModal('enrolled-{{ $ec->id }}')" class="text-sm font-bold text-[#1a2535] hover:underline flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Details
                </button>
                <a href="{{ route('cohorts.materials', $ec) }}" class="text-sm font-bold text-blue-600 hover:underline flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Materials
                </a>
                @if($ec->status === 'active' && $ec->google_meet_link)
                <a href="{{ $ec->google_meet_link }}" target="_blank" rel="noopener noreferrer" class="text-sm font-bold text-[#e05a3a] hover:underline flex items-center gap-1">
                    Join Class
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                @endif
            </div>
        </div>

        {{-- Enrolled Cohort Detail Modal --}}
        <div id="cohort-modal-enrolled-{{ $ec->id }}" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeCohortModal('enrolled-{{ $ec->id }}')"></div>
            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                        <button onclick="closeCohortModal('enrolled-{{ $ec->id }}')" class="absolute top-4 right-4 z-10 w-8 h-8 rounded-full bg-white/90 shadow flex items-center justify-center hover:bg-gray-100 transition-colors">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>

                        @if($ec->cover_image)
                        <div class="rounded-t-2xl overflow-hidden">
                            <img src="{{ Storage::url($ec->cover_image) }}" alt="{{ $ec->title }}" class="w-full h-48 object-cover">
                        </div>
                        @endif

                        <div class="p-6 space-y-5">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="badge {{ $ec->status === 'active' ? 'bg-green-100 text-green-700' : ($ec->status === 'upcoming' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500') }}">
                                        {{ ucfirst($ec->status) }}
                                    </span>
                                    <span class="badge bg-emerald-100 text-emerald-700">Enrolled</span>
                                </div>
                                <h2 class="text-xl font-bold text-[#1a1a2e]">{{ $ec->title }}</h2>
                                @if($ec->description)
                                <p class="text-sm text-gray-600 mt-2 leading-relaxed">{{ $ec->description }}</p>
                                @endif
                            </div>

                            {{-- Key Details --}}
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Start Date</p>
                                    <p class="text-sm font-semibold text-[#1a1a2e] mt-0.5">{{ $ec->start_date->format('M d, Y') }}</p>
                                </div>
                                @if($ec->end_date)
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">End Date</p>
                                    <p class="text-sm font-semibold text-[#1a1a2e] mt-0.5">{{ $ec->end_date->format('M d, Y') }}</p>
                                </div>
                                @endif
                                @if($ec->duration)
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Duration</p>
                                    <p class="text-sm font-semibold text-[#1a1a2e] mt-0.5">{{ $ec->duration }}</p>
                                </div>
                                @endif
                                @if($ec->schedule)
                                <div class="bg-gray-50 rounded-xl p-3 col-span-2 sm:col-span-1">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Schedule</p>
                                    <p class="text-sm font-semibold text-[#1a1a2e] mt-0.5">{{ $ec->schedule }}</p>
                                </div>
                                @endif
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Format</p>
                                    <p class="text-sm font-semibold text-[#1a1a2e] mt-0.5">Live Online</p>
                                </div>
                            </div>

                            {{-- Facilitator --}}
                            @if($ec->facilitator_name)
                            <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-4">
                                @if($ec->facilitator_image)
                                <img src="{{ Storage::url($ec->facilitator_image) }}" alt="{{ $ec->facilitator_name }}" class="w-12 h-12 rounded-full object-cover flex-shrink-0">
                                @else
                                <div class="w-12 h-12 rounded-full bg-[#1a2535] flex items-center justify-center flex-shrink-0">
                                    <span class="text-lg font-bold text-white">{{ strtoupper(substr($ec->facilitator_name, 0, 1)) }}</span>
                                </div>
                                @endif
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Facilitator</p>
                                    <p class="text-sm font-bold text-[#1a1a2e]">{{ $ec->facilitator_name }}</p>
                                    @if($ec->facilitator_bio)
                                    <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $ec->facilitator_bio }}</p>
                                    @endif
                                </div>
                            </div>
                            @endif

                            {{-- What You'll Learn --}}
                            @if(count($ec->what_you_will_learn_list) > 0)
                            <div>
                                <h3 class="text-sm font-bold text-[#1a1a2e] mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    What You'll Learn
                                </h3>
                                <ul class="space-y-2">
                                    @foreach($ec->what_you_will_learn_list as $item)
                                    <li class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span class="text-sm text-gray-700">{{ $item }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            {{-- Prerequisites --}}
                            @if(count($ec->prerequisites_list) > 0)
                            <div>
                                <h3 class="text-sm font-bold text-[#1a1a2e] mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Prerequisites
                                </h3>
                                <ul class="space-y-2">
                                    @foreach($ec->prerequisites_list as $item)
                                    <li class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        <span class="text-sm text-gray-700">{{ $item }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            {{-- Actions for enrolled student --}}
                            <div class="pt-4 border-t border-gray-100 space-y-3">
                                @if($ec->status === 'active' && $ec->google_meet_link)
                                <a href="{{ $ec->google_meet_link }}" target="_blank" rel="noopener noreferrer" class="btn-primary w-full justify-center py-3 text-sm inline-flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    Join Live Class
                                </a>
                                @endif
                                <a href="{{ route('cohorts.materials', $ec) }}" class="w-full py-2.5 rounded-xl text-sm font-bold bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors inline-flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    View Materials
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
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
            @if($cohort->cover_image)
            <div class="-mx-5 -mt-5 mb-4 rounded-t-2xl overflow-hidden">
                <img src="{{ Storage::url($cohort->cover_image) }}" alt="{{ $cohort->title }}" class="w-full h-36 object-cover">
            </div>
            @endif
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

            <button type="button" onclick="openCohortModal({{ $cohort->id }})" class="w-full py-2.5 rounded-xl text-sm font-bold bg-[#1a2535] text-white hover:bg-[#2a3545] transition-colors mb-2">
                View Details
            </button>
        </div>

        {{-- Cohort Detail Modal --}}
        <div id="cohort-modal-{{ $cohort->id }}" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeCohortModal({{ $cohort->id }})"></div>

            {{-- Modal Content --}}
            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">

                        {{-- Close Button --}}
                        <button onclick="closeCohortModal({{ $cohort->id }})" class="absolute top-4 right-4 z-10 w-8 h-8 rounded-full bg-white/90 shadow flex items-center justify-center hover:bg-gray-100 transition-colors">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>

                        {{-- Cover Image --}}
                        @if($cohort->cover_image)
                        <div class="rounded-t-2xl overflow-hidden">
                            <img src="{{ Storage::url($cohort->cover_image) }}" alt="{{ $cohort->title }}" class="w-full h-48 object-cover">
                        </div>
                        @endif

                        <div class="p-6 space-y-5">
                            {{-- Header --}}
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="badge {{ $cohort->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ ucfirst($cohort->status) }}
                                    </span>
                                    @if($cohort->isFull())
                                    <span class="badge bg-red-100 text-red-600">Full</span>
                                    @elseif($cohort->spotsLeft() !== null)
                                    <span class="badge bg-orange-100 text-orange-600">{{ $cohort->spotsLeft() }} spots left</span>
                                    @endif
                                </div>
                                <h2 class="text-xl font-bold text-[#1a1a2e]">{{ $cohort->title }}</h2>
                                @if($cohort->description)
                                <p class="text-sm text-gray-600 mt-2 leading-relaxed">{{ $cohort->description }}</p>
                                @endif
                            </div>

                            {{-- Key Details --}}
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Start Date</p>
                                    <p class="text-sm font-semibold text-[#1a1a2e] mt-0.5">{{ $cohort->start_date->format('M d, Y') }}</p>
                                </div>
                                @if($cohort->end_date)
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">End Date</p>
                                    <p class="text-sm font-semibold text-[#1a1a2e] mt-0.5">{{ $cohort->end_date->format('M d, Y') }}</p>
                                </div>
                                @endif
                                @if($cohort->duration)
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Duration</p>
                                    <p class="text-sm font-semibold text-[#1a1a2e] mt-0.5">{{ $cohort->duration }}</p>
                                </div>
                                @endif
                                @if($cohort->schedule)
                                <div class="bg-gray-50 rounded-xl p-3 col-span-2 sm:col-span-1">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Schedule</p>
                                    <p class="text-sm font-semibold text-[#1a1a2e] mt-0.5">{{ $cohort->schedule }}</p>
                                </div>
                                @endif
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Format</p>
                                    <p class="text-sm font-semibold text-[#1a1a2e] mt-0.5">Live Online</p>
                                </div>
                                @if($cohort->spotsLeft() !== null)
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Spots Left</p>
                                    <p class="text-sm font-semibold {{ $cohort->spotsLeft() <= 5 ? 'text-orange-600' : 'text-[#1a1a2e]' }} mt-0.5">{{ $cohort->spotsLeft() }} / {{ $cohort->max_students }}</p>
                                </div>
                                @endif
                            </div>

                            {{-- Facilitator --}}
                            @if($cohort->facilitator_name)
                            <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-4">
                                @if($cohort->facilitator_image)
                                <img src="{{ Storage::url($cohort->facilitator_image) }}" alt="{{ $cohort->facilitator_name }}" class="w-12 h-12 rounded-full object-cover flex-shrink-0">
                                @else
                                <div class="w-12 h-12 rounded-full bg-[#1a2535] flex items-center justify-center flex-shrink-0">
                                    <span class="text-lg font-bold text-white">{{ strtoupper(substr($cohort->facilitator_name, 0, 1)) }}</span>
                                </div>
                                @endif
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Facilitator</p>
                                    <p class="text-sm font-bold text-[#1a1a2e]">{{ $cohort->facilitator_name }}</p>
                                    @if($cohort->facilitator_bio)
                                    <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $cohort->facilitator_bio }}</p>
                                    @endif
                                </div>
                            </div>
                            @endif

                            {{-- What You'll Learn --}}
                            @if(count($cohort->what_you_will_learn_list) > 0)
                            <div>
                                <h3 class="text-sm font-bold text-[#1a1a2e] mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    What You'll Learn
                                </h3>
                                <ul class="space-y-2">
                                    @foreach($cohort->what_you_will_learn_list as $item)
                                    <li class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span class="text-sm text-gray-700">{{ $item }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            {{-- Prerequisites --}}
                            @if(count($cohort->prerequisites_list) > 0)
                            <div>
                                <h3 class="text-sm font-bold text-[#1a1a2e] mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Prerequisites
                                </h3>
                                <ul class="space-y-2">
                                    @foreach($cohort->prerequisites_list as $item)
                                    <li class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        <span class="text-sm text-gray-700">{{ $item }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            {{-- Price & Enrol --}}
                            <div class="pt-4 border-t border-gray-100">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <p class="text-xs text-gray-400">Price</p>
                                        @if($paymentEnabled && $cohort->price > 0)
                                        <p class="text-2xl font-bold text-[#1a1a2e]">{{ \App\Models\Setting::get('currency_symbol', '£') }}{{ number_format($cohort->price, 2) }}</p>
                                        @else
                                        <p class="text-2xl font-bold text-green-600">Free</p>
                                        @endif
                                    </div>
                                    @if($cohort->enrollments()->count() > 0)
                                    <p class="text-xs text-gray-400">{{ $cohort->enrollments()->count() }} students enrolled</p>
                                    @endif
                                </div>

                                @if($cohort->isFull())
                                    <button disabled class="w-full py-3 rounded-xl bg-gray-200 text-gray-500 text-sm font-bold cursor-not-allowed">Cohort Full</button>
                                @elseif($paymentEnabled && $cohort->price > 0)
                                    <div class="space-y-2">
                                        @if($stripeEnabled)
                                        <form method="POST" action="{{ route('payments.stripe.checkout', $cohort) }}">
                                            @csrf
                                            <button type="submit" class="btn-primary w-full justify-center py-3 text-sm">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                                Pay with Card
                                            </button>
                                        </form>
                                        @endif
                                        @if($paypalEnabled)
                                        <form method="POST" action="{{ route('payments.paypal.checkout', $cohort) }}">
                                            @csrf
                                            <button type="submit" class="w-full py-3 rounded-xl text-sm font-bold bg-[#003087] text-white hover:bg-[#002060] transition-colors inline-flex items-center justify-center gap-1">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106z"/></svg>
                                                Pay with PayPal
                                            </button>
                                        </form>
                                        @endif
                                        @if($bankTransferEnabled)
                                        <a href="{{ route('payments.bank-transfer', $cohort) }}" class="w-full py-3 rounded-xl text-sm font-bold bg-gray-700 text-white hover:bg-gray-800 transition-colors inline-flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                            Bank Transfer
                                        </a>
                                        @endif
                                    </div>
                                @else
                                    <form method="POST" action="{{ route('cohorts.enrol-free', $cohort) }}">
                                        @csrf
                                        <button type="submit" class="btn-primary w-full justify-center py-3 text-sm">Enrol Now — Free</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- Modal JS --}}
<script>
function openCohortModal(id) {
    const modal = document.getElementById('cohort-modal-' + id);
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeCohortModal(id) {
    const modal = document.getElementById('cohort-modal-' + id);
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('[id^="cohort-modal-"]').forEach(function(modal) {
            if (!modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
    }
});
</script>

@endsection
