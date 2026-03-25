@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Welcome back to your learning hub')

@section('content')

{{-- Welcome Hero --}}
<div class="relative rounded-3xl overflow-hidden mb-8">
    <div class="absolute inset-0 bg-gradient-to-br from-[#1a2535] via-[#243347] to-[#1a2535]"></div>
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-[#e05a3a]/20 blur-3xl"></div>
        <div class="absolute -bottom-32 -left-20 w-80 h-80 rounded-full bg-blue-500/10 blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 rounded-full bg-[#e05a3a]/5 blur-2xl"></div>
    </div>
    <div class="relative px-8 py-10 sm:py-12">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div>
                <p class="text-gray-400 text-sm font-medium mb-1">Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }},</p>
                <h2 class="text-3xl font-extrabold text-white tracking-tight">{{ auth()->user()->name ?? 'Learner' }}</h2>
                @if($activeCohort)
                <div class="mt-3 inline-flex items-center gap-2 bg-white/10 backdrop-blur-md rounded-full px-4 py-2">
                    <span class="w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse"></span>
                    <span class="text-sm text-gray-200"><span class="font-semibold text-white">{{ $activeCohort->cohort->title }}</span> is live</span>
                </div>
                @else
                <p class="text-gray-400 text-sm mt-2 max-w-md">Explore available cohorts below and enrol to start your learning journey.</p>
                @endif
            </div>
            @if($activeCohort && $activeCohort->cohort->google_meet_link)
            <a href="{{ $activeCohort->cohort->google_meet_link }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-2 bg-[#e05a3a] hover:bg-[#c94e31] text-white font-bold text-sm px-6 py-3.5 rounded-xl shadow-lg shadow-[#e05a3a]/25 transition-all hover:shadow-xl hover:shadow-[#e05a3a]/30 hover:-translate-y-0.5 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                Join Live Class
            </a>
            @endif
        </div>

        {{-- Quick Stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-8">
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl px-4 py-3">
                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Enrolled</p>
                <p class="text-2xl font-bold text-white mt-0.5">{{ $enrolledCohorts->count() }}</p>
            </div>
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl px-4 py-3">
                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Active</p>
                <p class="text-2xl font-bold text-green-400 mt-0.5">{{ $enrolledCohorts->filter(fn($e) => $e->cohort && $e->cohort->status === 'active')->count() }}</p>
            </div>
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl px-4 py-3">
                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Upcoming</p>
                <p class="text-2xl font-bold text-blue-400 mt-0.5">{{ $enrolledCohorts->filter(fn($e) => $e->cohort && $e->cohort->status === 'upcoming')->count() }}</p>
            </div>
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl px-4 py-3">
                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Available</p>
                <p class="text-2xl font-bold text-[#e05a3a] mt-0.5">{{ $availableCohorts->count() }}</p>
            </div>
        </div>
    </div>
</div>

{{-- My Enrolled Cohorts --}}
@if($enrolledCohorts->count() > 0)
<div class="mb-10">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h3 class="text-xl font-bold text-[#1a1a2e]">My Cohorts</h3>
            <p class="text-xs text-gray-400 mt-0.5">Your enrolled training programmes</p>
        </div>
        <a href="{{ route('cohorts.index') }}" class="text-sm font-semibold text-[#e05a3a] hover:underline flex items-center gap-1">
            View all
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($enrolledCohorts as $enrollment)
        @php $ec = $enrollment->cohort; @endphp
        <div class="group relative bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-300 hover:-translate-y-1">
            {{-- Cover or gradient header --}}
            @if($ec->cover_image)
            <div class="h-32 overflow-hidden relative">
                <img src="{{ Storage::url($ec->cover_image) }}" alt="{{ $ec->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                <div class="absolute bottom-3 left-3 flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold backdrop-blur-md {{ $ec->status === 'active' ? 'bg-green-500/90 text-white' : ($ec->status === 'upcoming' ? 'bg-blue-500/90 text-white' : 'bg-gray-500/90 text-white') }}">
                        {{ ucfirst($ec->status) }}
                    </span>
                </div>
            </div>
            @else
            <div class="h-3 bg-gradient-to-r {{ $ec->status === 'active' ? 'from-green-400 to-emerald-500' : ($ec->status === 'upcoming' ? 'from-blue-400 to-indigo-500' : 'from-gray-300 to-gray-400') }}"></div>
            @endif

            <div class="p-5">
                @if(!$ec->cover_image)
                <div class="flex items-center gap-2 mb-3">
                    <span class="badge {{ $ec->status === 'active' ? 'bg-green-100 text-green-700' : ($ec->status === 'upcoming' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500') }}">
                        {{ ucfirst($ec->status) }}
                    </span>
                    <span class="badge bg-emerald-100 text-emerald-700">Enrolled</span>
                </div>
                @endif

                <h4 class="font-bold text-[#1a1a2e] text-base mb-1.5 line-clamp-1">{{ $ec->title }}</h4>

                <div class="flex items-center gap-3 text-xs text-gray-400 mb-4">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $ec->start_date->format('M d, Y') }}
                    </span>
                    @if($ec->duration)
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $ec->duration }}
                    </span>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" onclick="openCohortModal('enrolled-{{ $ec->id }}')" class="flex-1 py-2 rounded-xl text-xs font-bold bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors text-center">
                        Details
                    </button>
                    <a href="{{ route('cohorts.materials', $ec) }}" class="flex-1 py-2 rounded-xl text-xs font-bold bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors text-center">
                        Materials
                    </a>
                    @if($ec->status === 'active' && $ec->google_meet_link)
                    <a href="{{ $ec->google_meet_link }}" target="_blank" rel="noopener noreferrer"
                       class="flex-1 py-2 rounded-xl text-xs font-bold bg-[#e05a3a] text-white hover:bg-[#c94e31] transition-colors text-center">
                        Join
                    </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Enrolled Cohort Detail Modal --}}
        <div id="cohort-modal-enrolled-{{ $ec->id }}" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeCohortModal('enrolled-{{ $ec->id }}')"></div>
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
    <div class="flex items-center justify-between mb-5">
        <div>
            <h3 class="text-xl font-bold text-[#1a1a2e]">Available Cohorts</h3>
            <p class="text-xs text-gray-400 mt-0.5">Explore and enrol in new training programmes</p>
        </div>
    </div>

    @if($availableCohorts->isEmpty())
    <div class="relative rounded-2xl overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-gray-50 to-gray-100"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 rounded-full bg-gray-200/50 blur-3xl"></div>
        <div class="relative p-12 text-center">
            <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            </div>
            <p class="text-gray-500 text-sm font-medium">No new cohorts available right now.</p>
            <p class="text-gray-400 text-xs mt-1">Check back soon for upcoming programmes!</p>
        </div>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($availableCohorts as $cohort)
        <div class="group relative bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-300 hover:-translate-y-1">
            {{-- Cover image or gradient --}}
            @if($cohort->cover_image)
            <div class="h-40 overflow-hidden relative">
                <img src="{{ Storage::url($cohort->cover_image) }}" alt="{{ $cohort->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                {{-- Price badge --}}
                <div class="absolute top-3 right-3">
                    @if($paymentEnabled && $cohort->price > 0)
                    <span class="bg-white/90 backdrop-blur-md text-[#1a1a2e] font-bold text-sm px-3 py-1.5 rounded-full shadow-sm">
                        {{ \App\Models\Setting::get('currency_symbol', '£') }}{{ number_format($cohort->price, 2) }}
                    </span>
                    @else
                    <span class="bg-green-500/90 backdrop-blur-md text-white font-bold text-xs px-3 py-1.5 rounded-full">Free</span>
                    @endif
                </div>
                <div class="absolute bottom-3 left-3 flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold backdrop-blur-md {{ $cohort->status === 'active' ? 'bg-green-500/90 text-white' : 'bg-blue-500/90 text-white' }}">
                        {{ ucfirst($cohort->status) }}
                    </span>
                    @if($cohort->isFull())
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-red-500/90 backdrop-blur-md text-white">Full</span>
                    @elseif($cohort->spotsLeft() !== null)
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-white/80 backdrop-blur-md text-gray-700">{{ $cohort->spotsLeft() }} spots left</span>
                    @endif
                </div>
            </div>
            @else
            <div class="h-24 bg-gradient-to-br from-[#1a2535] to-[#2a3d55] relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-[#e05a3a]/20 blur-2xl"></div>
                <div class="absolute bottom-3 left-4 flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $cohort->status === 'active' ? 'bg-green-500/90 text-white' : 'bg-blue-500/90 text-white' }}">
                        {{ ucfirst($cohort->status) }}
                    </span>
                    @if($cohort->isFull())
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-red-500/90 text-white">Full</span>
                    @elseif($cohort->spotsLeft() !== null)
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-white/20 text-white">{{ $cohort->spotsLeft() }} spots left</span>
                    @endif
                </div>
                <div class="absolute top-3 right-3">
                    @if($paymentEnabled && $cohort->price > 0)
                    <span class="bg-white/20 backdrop-blur-md text-white font-bold text-sm px-3 py-1.5 rounded-full">
                        {{ \App\Models\Setting::get('currency_symbol', '£') }}{{ number_format($cohort->price, 2) }}
                    </span>
                    @else
                    <span class="bg-green-500/80 backdrop-blur-md text-white font-bold text-xs px-3 py-1.5 rounded-full">Free</span>
                    @endif
                </div>
            </div>
            @endif

            <div class="p-5">
                <h4 class="font-bold text-[#1a1a2e] text-base mb-1.5 line-clamp-1">{{ $cohort->title }}</h4>
                @if($cohort->description)
                <p class="text-xs text-gray-500 mb-3 line-clamp-2 leading-relaxed">{{ $cohort->description }}</p>
                @endif

                <div class="flex items-center gap-3 text-xs text-gray-400 mb-4">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $cohort->start_date->format('M d, Y') }}
                    </span>
                    @if($cohort->duration)
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $cohort->duration }}
                    </span>
                    @endif
                    @if($cohort->facilitator_name)
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        {{ $cohort->facilitator_name }}
                    </span>
                    @endif
                </div>

                <button type="button" onclick="openCohortModal({{ $cohort->id }})"
                        class="w-full py-3 rounded-xl text-sm font-bold bg-[#1a2535] text-white hover:bg-[#2a3d55] transition-all hover:shadow-lg hover:shadow-[#1a2535]/20">
                    View Details
                </button>
            </div>
        </div>

        {{-- Cohort Detail Modal --}}
        <div id="cohort-modal-{{ $cohort->id }}" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeCohortModal({{ $cohort->id }})"></div>
            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                        <button onclick="closeCohortModal({{ $cohort->id }})" class="absolute top-4 right-4 z-10 w-8 h-8 rounded-full bg-white/90 shadow flex items-center justify-center hover:bg-gray-100 transition-colors">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>

                        @if($cohort->cover_image)
                        <div class="rounded-t-2xl overflow-hidden">
                            <img src="{{ Storage::url($cohort->cover_image) }}" alt="{{ $cohort->title }}" class="w-full h-48 object-cover">
                        </div>
                        @endif

                        <div class="p-6 space-y-5">
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
    if (modal) { modal.classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
}
function closeCohortModal(id) {
    const modal = document.getElementById('cohort-modal-' + id);
    if (modal) { modal.classList.add('hidden'); document.body.style.overflow = ''; }
}
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
