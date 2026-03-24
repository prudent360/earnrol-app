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
    <div class="card" id="apply">
        <h3 class="font-bold text-[#1a1a2e] mb-2">Ready to Apply?</h3>
        
        @if(auth()->check() && auth()->user()->jobApplications()->where('job_id', $job->id)->exists())
            <div class="bg-green-50 border border-green-100 rounded-xl p-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div>
                    <p class="text-green-800 font-bold text-sm">Application Sent!</p>
                    <p class="text-green-600 text-xs">You have already applied for this position. The employer will contact you if they're interested.</p>
                </div>
            </div>
        @else
            <p class="text-sm text-gray-500 mb-4">Submit your application details below.</p>
            <form method="POST" action="{{ route('jobs.apply', $job) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Upload Resume (PDF/Docx)</label>
                    <input type="file" name="resume" required class="form-input w-full text-sm block px-3 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Cover Letter (Optional)</label>
                    <textarea name="cover_letter" rows="4" placeholder="Tell the employer why you're a good fit..." class="form-input w-full text-sm block px-3 py-2 border rounded-lg focus:ring-orange-500 focus:border-orange-500"></textarea>
                </div>
                <button type="submit" class="btn-primary w-full sm:w-auto px-8">Submit Application</button>
            </form>
        @endif
    </div>
    @endif

    <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#e05a3a] transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Jobs
    </a>
</div>

@endsection
