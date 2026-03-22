@extends('layouts.app')

@section('title', 'Jobs')
@section('page_title', 'Jobs & Talent Matching')
@section('page_subtitle', 'AI-matched opportunities based on your verified skills')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

    {{-- Filters sidebar --}}
    <div class="lg:col-span-1 space-y-4">
        <div class="card">
            <h3 class="font-bold text-[#1a1a2e] mb-4">Filter Jobs</h3>

            <div class="mb-4">
                <label class="form-label">Search</label>
                <div class="relative">
                    <input type="text" placeholder="Job title, skills..." class="form-input pl-9 text-sm">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Job Type</label>
                <div class="space-y-2">
                    @foreach(['Full-time', 'Contract', 'Part-time', 'Internship'] as $type)
                    <label class="flex items-center gap-2 text-sm text-[#6b7280] cursor-pointer">
                        <input type="checkbox" class="accent-[#e05a3a]"> {{ $type }}
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Work Mode</label>
                <div class="space-y-2">
                    @foreach(['Remote', 'Hybrid', 'On-site'] as $mode)
                    <label class="flex items-center gap-2 text-sm text-[#6b7280] cursor-pointer">
                        <input type="checkbox" class="accent-[#e05a3a]"> {{ $mode }}
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Track</label>
                <div class="space-y-2">
                    @foreach(['Cloud Computing', 'DevOps', 'Cybersecurity', 'Data Engineering'] as $track)
                    <label class="flex items-center gap-2 text-sm text-[#6b7280] cursor-pointer">
                        <input type="checkbox" class="accent-[#e05a3a]"> {{ $track }}
                    </label>
                    @endforeach
                </div>
            </div>

            <button class="btn-primary w-full justify-center text-sm py-2.5">Apply Filters</button>
        </div>

        {{-- AI match score --}}
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
            <p class="text-sm text-[#6b7280]"><span class="font-bold text-[#1a1a2e]">48 jobs</span> matching your profile</p>
            <select class="text-sm border border-[#e8eaf0] rounded-lg px-3 py-2 bg-white text-[#6b7280] focus:outline-none focus:ring-2 focus:ring-[#e05a3a]">
                <option>Best Match</option>
                <option>Most Recent</option>
                <option>Salary: High to Low</option>
            </select>
        </div>

        @php
        $jobs = [
            ['title' => 'Cloud Engineer', 'company' => 'Google', 'avatar' => 'G', 'location' => 'Remote · Worldwide', 'type' => 'Full-time', 'salary' => '$85k–$120k/yr', 'match' => 94, 'tags' => ['AWS', 'GCP', 'Kubernetes', 'Terraform'], 'posted' => '2 days ago', 'track' => 'Cloud Computing', 'color' => '#4285F4'],
            ['title' => 'Senior DevOps Engineer', 'company' => 'Andela', 'avatar' => 'A', 'location' => 'Remote · Africa', 'type' => 'Full-time', 'salary' => '$60k–$90k/yr', 'match' => 87, 'tags' => ['Jenkins', 'Docker', 'CI/CD', 'Linux'], 'posted' => '1 day ago', 'track' => 'DevOps', 'color' => '#e05a3a'],
            ['title' => 'AWS Solutions Architect', 'company' => 'Flutterwave', 'avatar' => 'F', 'location' => 'Lagos, Nigeria · Hybrid', 'type' => 'Full-time', 'salary' => '$70k–$100k/yr', 'match' => 81, 'tags' => ['AWS', 'CDK', 'Lambda', 'DynamoDB'], 'posted' => '3 days ago', 'track' => 'Cloud Computing', 'color' => '#f59e0b'],
            ['title' => 'Cybersecurity Analyst', 'company' => 'Microsoft', 'avatar' => 'M', 'location' => 'Remote · UK/Europe', 'type' => 'Full-time', 'salary' => '$75k–$110k/yr', 'match' => 76, 'tags' => ['SOC', 'SIEM', 'Incident Response', 'Python'], 'posted' => '5 days ago', 'track' => 'Cybersecurity', 'color' => '#00A4EF'],
            ['title' => 'Data Engineer', 'company' => 'Paystack', 'avatar' => 'P', 'location' => 'Accra, Ghana · On-site', 'type' => 'Full-time', 'salary' => '$55k–$80k/yr', 'match' => 68, 'tags' => ['Kafka', 'Spark', 'Python', 'SQL'], 'posted' => '1 week ago', 'track' => 'Data Engineering', 'color' => '#00C3F7'],
            ['title' => 'Platform Engineer', 'company' => 'Interswitch', 'avatar' => 'I', 'location' => 'Lagos, Nigeria · Hybrid', 'type' => 'Full-time', 'salary' => '$50k–$75k/yr', 'match' => 62, 'tags' => ['Kubernetes', 'Terraform', 'Go', 'AWS'], 'posted' => '1 week ago', 'track' => 'DevOps', 'color' => '#22c55e'],
        ];
        @endphp

        @foreach($jobs as $job)
        <div class="card hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold text-lg flex-shrink-0" style="background-color: {{ $job['color'] }};">
                    {{ $job['avatar'] }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-4 flex-wrap">
                        <div>
                            <h3 class="font-bold text-[#1a1a2e] text-base mb-0.5">{{ $job['title'] }}</h3>
                            <p class="text-sm text-[#6b7280]">{{ $job['company'] }} · {{ $job['location'] }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <div class="flex items-center gap-1 justify-end mb-1">
                                <svg class="w-4 h-4 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                <span class="font-bold text-[#22c55e]">{{ $job['match'] }}% match</span>
                            </div>
                            <p class="text-xs text-[#6b7280]">AI matched</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-[#6b7280]">
                        <span class="badge bg-[#f5f6fa] text-[#6b7280]">{{ $job['type'] }}</span>
                        <span class="font-semibold text-[#1a1a2e]">{{ $job['salary'] }}</span>
                        <span>{{ $job['posted'] }}</span>
                    </div>
                    <div class="flex flex-wrap gap-1 mt-3">
                        @foreach($job['tags'] as $tag)
                        <span class="tag">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between mt-4 pt-4 border-t border-[#e8eaf0]">
                <span class="tag">{{ $job['track'] }}</span>
                <div class="flex gap-2">
                    <button class="p-2 rounded-lg border border-[#e8eaf0] text-[#6b7280] hover:text-[#e05a3a] hover:border-[#e05a3a] transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                    </button>
                    <button class="btn-primary text-sm py-2 px-5">Apply Now</button>
                </div>
            </div>
        </div>
        @endforeach

        {{-- Pagination --}}
        <div class="flex items-center justify-center gap-2 pt-2">
            <button class="w-10 h-10 rounded-lg border border-[#e8eaf0] bg-white text-[#6b7280] hover:border-[#e05a3a] hover:text-[#e05a3a] transition-colors flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button class="w-10 h-10 rounded-lg bg-[#e05a3a] text-white font-bold text-sm">1</button>
            <button class="w-10 h-10 rounded-lg border border-[#e8eaf0] bg-white text-[#6b7280] hover:border-[#e05a3a] hover:text-[#e05a3a] transition-colors text-sm">2</button>
            <button class="w-10 h-10 rounded-lg border border-[#e8eaf0] bg-white text-[#6b7280] hover:border-[#e05a3a] hover:text-[#e05a3a] transition-colors text-sm">3</button>
            <button class="w-10 h-10 rounded-lg border border-[#e8eaf0] bg-white text-[#6b7280] hover:border-[#e05a3a] hover:text-[#e05a3a] transition-colors flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>
</div>

@endsection
