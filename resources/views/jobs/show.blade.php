@extends('layouts.app')

@section('title', $job->title)
@section('page_title', $job->title)
@section('page_subtitle', $job->company . ($job->location ? ' · ' . $job->location : ''))

@section('content')

<div class="max-w-3xl space-y-6">
    {{-- Header card --}}
    <div class="card">
        <div class="flex items-start gap-4">
            @php
                $colors = ['#4285F4','#e05a3a','#f59e0b','#22c55e','#8b5cf6','#00C3F7'];
                $color  = $colors[$job->id % count($colors)];
            @endphp
            <div class="w-14 h-14 rounded-xl flex items-center justify-center text-white font-bold text-2xl flex-shrink-0"
                 style="background-color: {{ $color }};">
                {{ strtoupper(substr($job->company, 0, 1)) }}
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold text-[#1a1a2e]">{{ $job->title }}</h2>
                <p class="text-gray-500 mt-0.5">{{ $job->company }}@if($job->location) · {{ $job->location }}@endif</p>
                <div class="flex flex-wrap gap-2 mt-3">
                    <span class="badge bg-gray-100 text-gray-600 capitalize">{{ str_replace('-', ' ', $job->type) }}</span>
                    @if($job->salary_range)
                    <span class="badge bg-green-50 text-green-700">{{ $job->salary_range }}</span>
                    @endif
                    <span class="badge {{ $job->status === 'active' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ ucfirst($job->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Description --}}
    @if($job->description)
    <div class="card">
        <h3 class="font-bold text-[#1a1a2e] mb-3">Job Description</h3>
        <div class="prose prose-sm max-w-none text-gray-600 whitespace-pre-line">{{ $job->description }}</div>
    </div>
    @endif

    {{-- Requirements --}}
    @if($job->requirements)
    <div class="card">
        <h3 class="font-bold text-[#1a1a2e] mb-3">Requirements</h3>
        <div class="prose prose-sm max-w-none text-gray-600 whitespace-pre-line">{{ $job->requirements }}</div>
    </div>
    @endif

    {{-- Apply --}}
    @if($job->status === 'active')
    <div class="card">
        <h3 class="font-bold text-[#1a1a2e] mb-2">Ready to Apply?</h3>
        <p class="text-sm text-gray-500 mb-4">Submit your application and the employer will be in touch.</p>
        <form method="POST" action="{{ route('jobs.apply', $job) }}">
            @csrf
            <button type="submit" class="btn-primary px-8">Apply Now</button>
        </form>
    </div>
    @endif

    <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#e05a3a] transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Jobs
    </a>
</div>

@endsection
