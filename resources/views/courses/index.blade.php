@extends('layouts.app')

@section('title', 'Learning')
@section('page_title', 'Learning Centre')
@section('page_subtitle', 'Project-based courses to accelerate your tech career')

@section('content')

{{-- Track filters --}}
<div class="flex flex-wrap gap-2 mb-6">
    @php
    $tracks = ['All Tracks', 'Cloud Computing', 'DevOps & CI/CD', 'Cybersecurity', 'Data Engineering', 'Linux', 'Networking'];
    @endphp
    @foreach($tracks as $i => $track)
    <button class="px-4 py-2 rounded-full text-sm font-medium transition-all
        {{ $i === 0 ? 'bg-[#e05a3a] text-white' : 'bg-white border border-[#e8eaf0] text-[#6b7280] hover:border-[#e05a3a] hover:text-[#e05a3a]' }}">
        {{ $track }}
    </button>
    @endforeach
</div>

{{-- Featured course --}}
<div class="bg-[#1a2535] rounded-2xl p-6 mb-6 flex flex-col lg:flex-row items-center gap-6 relative overflow-hidden">
    <div class="absolute right-0 top-0 bottom-0 w-48 opacity-10">
        <div class="w-64 h-64 bg-[#e05a3a] rounded-full absolute -right-10 top-0"></div>
    </div>
    <div class="w-16 h-16 rounded-2xl flex items-center justify-center flex-shrink-0" style="background-color:#e05a3a30;">
        <svg class="w-9 h-9 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
    </div>
    <div class="flex-1 relative">
        <div class="flex items-center gap-2 mb-2">
            <span class="badge bg-[#e05a3a] text-white">Featured</span>
            <span class="text-gray-400 text-xs">Cloud Computing</span>
        </div>
        <h2 class="text-2xl font-bold text-white mb-2">AWS Solutions Architect — Associate</h2>
        <p class="text-gray-300 text-sm mb-4">Master AWS cloud architecture. Build real-world projects, pass the SAA-C03 exam, and land a cloud engineering job.</p>
        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-400">
            <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> 24 hours</span>
            <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg> 22 lessons</span>
            <span class="flex items-center gap-1"><svg class="w-4 h-4 text-[#f59e0b]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> 4.9 (2.4k reviews)</span>
        </div>
    </div>
    <div class="flex-shrink-0">
        <a href="#" class="btn-primary">Enroll Now — Free</a>
    </div>
</div>

{{-- Course grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @php
    $iconSvgs = [
        'cloud'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>',
        'devops'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
        'security' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
        'linux'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
        'data'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
        'infra'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>',
        'cicd'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>',
        'hacking'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>',
    ];
    $courses = [
        ['icon' => 'cloud',    'title' => 'AWS Solutions Architect',       'track' => 'Cloud Computing', 'level' => 'Intermediate', 'duration' => '24h', 'lessons' => 22, 'rating' => 4.9, 'students' => '2.4k', 'price' => 'Free', 'color' => '#e05a3a', 'badge' => 'Popular'],
        ['icon' => 'devops',   'title' => 'Docker & Kubernetes Mastery',   'track' => 'DevOps & CI/CD',  'level' => 'Intermediate', 'duration' => '18h', 'lessons' => 20, 'rating' => 4.8, 'students' => '1.8k', 'price' => 'Pro',  'color' => '#3b82f6', 'badge' => 'Hot'],
        ['icon' => 'security', 'title' => 'CompTIA Security+ Prep',        'track' => 'Cybersecurity',   'level' => 'Beginner',     'duration' => '20h', 'lessons' => 18, 'rating' => 4.7, 'students' => '956',  'price' => 'Pro',  'color' => '#22c55e', 'badge' => 'New'],
        ['icon' => 'linux',    'title' => 'Linux for Cloud Engineers',     'track' => 'Cloud Computing', 'level' => 'Beginner',     'duration' => '12h', 'lessons' => 21, 'rating' => 4.8, 'students' => '3.1k', 'price' => 'Free', 'color' => '#f59e0b', 'badge' => null],
        ['icon' => 'data',     'title' => 'Apache Kafka & Data Pipelines', 'track' => 'Data Engineering','level' => 'Advanced',     'duration' => '22h', 'lessons' => 16, 'rating' => 4.9, 'students' => '712',  'price' => 'Pro',  'color' => '#8b5cf6', 'badge' => 'Trending'],
        ['icon' => 'infra',    'title' => 'Terraform Infrastructure as Code','track'=> 'DevOps & CI/CD', 'level' => 'Intermediate', 'duration' => '16h', 'lessons' => 14, 'rating' => 4.7, 'students' => '1.2k', 'price' => 'Pro',  'color' => '#3b82f6', 'badge' => null],
        ['icon' => 'cicd',     'title' => 'CI/CD with GitHub Actions',     'track' => 'DevOps & CI/CD',  'level' => 'Beginner',     'duration' => '10h', 'lessons' => 12, 'rating' => 4.6, 'students' => '890',  'price' => 'Free', 'color' => '#e05a3a', 'badge' => null],
        ['icon' => 'hacking',  'title' => 'Ethical Hacking Fundamentals', 'track' => 'Cybersecurity',   'level' => 'Beginner',     'duration' => '28h', 'lessons' => 24, 'rating' => 4.8, 'students' => '1.5k', 'price' => 'Pro',  'color' => '#22c55e', 'badge' => 'Popular'],
        ['icon' => 'cloud',    'title' => 'Google Cloud Professional',     'track' => 'Cloud Computing', 'level' => 'Advanced',     'duration' => '30h', 'lessons' => 26, 'rating' => 4.8, 'students' => '634',  'price' => 'Pro',  'color' => '#e05a3a', 'badge' => null],
    ];
    @endphp

    @foreach($courses as $course)
    <div class="card group hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: {{ $course['color'] }}20;">
                <svg class="w-6 h-6" style="color: {{ $course['color'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $iconSvgs[$course['icon']] !!}</svg>
            </div>
            <div class="flex items-center gap-2">
                @if($course['badge'])
                <span class="badge text-white text-xs" style="background-color: {{ $course['color'] }};">{{ $course['badge'] }}</span>
                @endif
                <span class="badge text-xs {{ $course['price'] === 'Free' ? 'bg-[#22c55e]/10 text-[#22c55e]' : 'bg-[#e05a3a]/10 text-[#e05a3a]' }}">
                    {{ $course['price'] }}
                </span>
            </div>
        </div>
        <p class="text-xs text-[#6b7280] mb-1">{{ $course['track'] }}</p>
        <h3 class="font-bold text-[#1a1a2e] mb-2 group-hover:text-[#e05a3a] transition-colors leading-snug">{{ $course['title'] }}</h3>
        <div class="flex items-center gap-3 text-xs text-[#6b7280] mb-4">
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-[#f59e0b]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                {{ $course['rating'] }}
            </span>
            <span>{{ $course['students'] }} students</span>
            <span>{{ $course['duration'] }}</span>
        </div>
        <div class="flex items-center justify-between">
            <span class="tag">{{ $course['level'] }}</span>
            <a href="#" class="text-sm font-semibold text-[#e05a3a] hover:underline flex items-center gap-1">
                {{ $course['price'] === 'Free' ? 'Enroll Free' : 'Start Course' }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </div>
    @endforeach
</div>

@endsection
