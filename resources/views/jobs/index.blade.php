@extends('layouts.app')

@section('title', 'Jobs')
@section('page_title', 'Jobs & Opportunities')
@section('page_subtitle', 'Browse and apply for open positions')

@section('content')

@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl px-5 py-4 flex items-center gap-3">
    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

    {{-- Filters sidebar --}}
    <div class="lg:col-span-1 space-y-4">
        <div class="card">
            <h3 class="font-bold text-[#1a1a2e] mb-4">Filter Jobs</h3>

            <form method="GET" action="{{ route('jobs.index') }}" class="space-y-4">
                <div>
                    <label class="form-label">Search</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Job title, company..."
                               class="form-input pl-9 text-sm w-full">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                <div>
                    <label class="form-label">Job Type</label>
                    <div class="space-y-2">
                        @foreach(['full-time' => 'Full-time', 'part-time' => 'Part-time', 'contract' => 'Contract', 'internship' => 'Internship'] as $val => $label)
                        <label class="flex items-center gap-2 text-sm text-[#6b7280] cursor-pointer">
                            <input type="radio" name="type" value="{{ $val }}" class="accent-[#e05a3a]"
                                   {{ request('type') === $val ? 'checked' : '' }}>
                            {{ $label }}
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="form-label">Location</label>
                    <input type="text" name="location" value="{{ request('location') }}"
                           placeholder="e.g. Remote, London..."
                           class="form-input text-sm w-full">
                </div>

                <button type="submit" class="btn-primary w-full justify-center text-sm py-2.5">Apply Filters</button>
                @if(request()->hasAny(['search','type','location']))
                <a href="{{ route('jobs.index') }}" class="btn-outline w-full justify-center text-sm py-2 text-center block">Clear Filters</a>
                @endif
            </form>
        </div>

        {{-- AI match card --}}
        <div class="card border-2 border-[#e05a3a]/20 bg-[#e05a3a]/5">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                <p class="font-bold text-[#1a1a2e] text-sm">Your AI Profile</p>
            </div>
            <p class="text-xs text-[#6b7280] mb-3">Complete your profile to improve match accuracy</p>
            <div class="progress-bar mb-1">
                <div class="progress-fill" style="width: 72%;"></div>
            </div>
            <p class="text-xs text-[#e05a3a] font-semibold">72% complete</p>
        </div>
    </div>

    {{-- Job listings --}}
    <div class="lg:col-span-3 space-y-4">
        <div class="flex items-center justify-between">
            <p class="text-sm text-[#6b7280]">
                <span class="font-bold text-[#1a1a2e]">{{ $jobs->total() }}</span> job{{ $jobs->total() === 1 ? '' : 's' }} found
            </p>
        </div>

        @forelse($jobs as $job)
        @php
            $colors = ['#4285F4','#e05a3a','#f59e0b','#22c55e','#8b5cf6','#00C3F7','#ef4444','#06b6d4'];
            $color = $colors[$job->id % count($colors)];
            $initial = strtoupper(substr($job->company, 0, 1));
        @endphp
        <div class="card hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold text-lg flex-shrink-0"
                     style="background-color: {{ $color }};">
                    {{ $initial }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-4 flex-wrap">
                        <div>
                            <h3 class="font-bold text-[#1a1a2e] text-base mb-0.5">{{ $job->title }}</h3>
                            <p class="text-sm text-[#6b7280]">
                                {{ $job->company }}
                                @if($job->location) · {{ $job->location }} @endif
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0 text-xs text-gray-400">
                            {{ $job->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-[#6b7280]">
                        <span class="badge bg-[#f5f6fa] text-[#6b7280] capitalize">{{ str_replace('-', ' ', $job->type) }}</span>
                        @if($job->salary_range)
                        <span class="font-semibold text-[#1a1a2e]">{{ $job->salary_range }}</span>
                        @endif
                    </div>
                    @if($job->description)
                    <p class="text-sm text-[#6b7280] mt-2 line-clamp-2">{{ $job->description }}</p>
                    @endif
                </div>
            </div>
            <div class="flex items-center justify-between mt-4 pt-4 border-t border-[#e8eaf0]">
                <span class="text-xs text-gray-400">Posted by {{ $job->poster->name ?? 'Admin' }}</span>
                <form method="POST" action="{{ route('jobs.apply', $job) }}">
                    @csrf
                    <button type="submit" class="btn-primary text-sm py-2 px-5">Apply Now</button>
                </form>
            </div>
        </div>
        @empty
        <div class="card text-center py-16 text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <p class="font-medium">No jobs found</p>
            <p class="text-sm mt-1">
                @if(request()->hasAny(['search','type','location']))
                    Try adjusting your filters
                @else
                    Check back soon for new opportunities
                @endif
            </p>
        </div>
        @endforelse

        @if($jobs->hasPages())
        <div class="pt-2">
            {{ $jobs->links() }}
        </div>
        @endif
    </div>
</div>

@endsection
