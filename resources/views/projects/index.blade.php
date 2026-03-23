@extends('layouts.app')

@section('title', 'Projects')
@section('page_title', 'Projects')
@section('page_subtitle', 'Build real-world projects to grow your portfolio and skills')

@section('content')

@php
$statusConfig = [
    'pending'    => ['label' => 'Pending',     'bg' => 'bg-yellow-50',  'text' => 'text-yellow-700',  'border' => 'border-yellow-200', 'dot' => 'bg-yellow-400'],
    'active'     => ['label' => 'In Progress', 'bg' => 'bg-blue-50',    'text' => 'text-blue-700',    'border' => 'border-blue-200',   'dot' => 'bg-blue-500'],
    'completed'  => ['label' => 'Completed',   'bg' => 'bg-green-50',   'text' => 'text-green-700',   'border' => 'border-green-200',  'dot' => 'bg-green-500'],
];
$colors = ['#e05a3a','#4285F4','#f59e0b','#22c55e','#8b5cf6','#00C3F7','#ef4444','#06b6d4'];
@endphp

{{-- Stats --}}
@php
$total     = $projects->total();
$completed = \App\Models\Project::where('status','completed')->count();
$active    = \App\Models\Project::where('status','active')->count();
$pending   = \App\Models\Project::where('status','pending')->count();
@endphp
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background-color:#e05a3a20;">
            <svg class="w-6 h-6 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div><p class="text-2xl font-bold text-[#1a1a2e]">{{ $total }}</p><p class="text-sm text-[#6b7280]">Total Projects</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background-color:#22c55e20;">
            <svg class="w-6 h-6 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
        </div>
        <div><p class="text-2xl font-bold text-[#1a1a2e]">{{ $completed }}</p><p class="text-sm text-[#6b7280]">Completed</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background-color:#3b82f620;">
            <svg class="w-6 h-6 text-[#3b82f6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div><p class="text-2xl font-bold text-[#1a1a2e]">{{ $active }}</p><p class="text-sm text-[#6b7280]">In Progress</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background-color:#f59e0b20;">
            <svg class="w-6 h-6 text-[#f59e0b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        </div>
        <div><p class="text-2xl font-bold text-[#1a1a2e]">{{ $pending }}</p><p class="text-sm text-[#6b7280]">Available</p></div>
    </div>
</div>

{{-- Filter tabs --}}
<form method="GET" action="{{ route('projects.index') }}" class="flex flex-wrap gap-2 mb-6 items-center">
    <a href="{{ route('projects.index') }}"
       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ !request('status') ? 'bg-[#e05a3a] text-white' : 'bg-white border border-[#e8eaf0] text-[#6b7280] hover:border-[#e05a3a] hover:text-[#e05a3a]' }}">
        All Projects
    </a>
    @foreach(['pending' => 'Available', 'active' => 'In Progress', 'completed' => 'Completed'] as $val => $label)
    <a href="{{ route('projects.index', ['status' => $val] + request()->except('status')) }}"
       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === $val ? 'bg-[#e05a3a] text-white' : 'bg-white border border-[#e8eaf0] text-[#6b7280] hover:border-[#e05a3a] hover:text-[#e05a3a]' }}">
        {{ $label }}
    </a>
    @endforeach

    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Search projects..."
           class="form-input py-2 text-sm ml-auto w-48">
    <button type="submit" class="btn-primary py-2 px-4 text-sm">Search</button>
</form>

@if($projects->isEmpty())
<div class="card text-center py-16 text-gray-400">
    <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
    </svg>
    <p class="font-medium">No projects found</p>
    <p class="text-sm mt-1">Check back soon for new projects</p>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @foreach($projects as $project)
    @php
        $sc    = $statusConfig[$project->status] ?? $statusConfig['pending'];
        $color = $colors[$project->id % count($colors)];
    @endphp
    <div class="card hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
        <div class="flex items-start justify-between mb-3">
            <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full border {{ $sc['bg'] }} {{ $sc['text'] }} {{ $sc['border'] }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }}"></span>
                {{ $sc['label'] }}
            </span>
            @if($project->owner)
            <span class="text-xs text-gray-400">{{ $project->owner->name }}</span>
            @endif
        </div>

        <div class="w-10 h-1 rounded-full mb-3" style="background-color: {{ $color }};"></div>

        @if($project->category)
        <p class="text-xs text-[#6b7280] mb-1 capitalize">{{ $project->category }}</p>
        @endif

        <h3 class="font-bold text-[#1a1a2e] mb-2 leading-snug">{{ $project->title }}</h3>

        @if($project->description)
        <p class="text-sm text-[#6b7280] leading-relaxed mb-4 line-clamp-3">{{ $project->description }}</p>
        @endif

        <div class="flex flex-wrap gap-2 mt-auto pt-3 border-t border-[#e8eaf0]">
            @if($project->github_url)
            <a href="{{ $project->github_url }}" target="_blank" rel="noopener"
               class="flex items-center gap-1.5 text-xs text-gray-600 hover:text-[#1a1a2e] transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.3 3.438 9.8 8.205 11.387.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61-.546-1.387-1.333-1.756-1.333-1.756-1.09-.745.083-.73.083-.73 1.205.085 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 21.795 24 17.295 24 12c0-6.63-5.37-12-12-12"/></svg>
                GitHub
            </a>
            @endif
            @if($project->live_url)
            <a href="{{ $project->live_url }}" target="_blank" rel="noopener"
               class="flex items-center gap-1.5 text-xs text-gray-600 hover:text-[#1a1a2e] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Live Demo
            </a>
            @endif
        </div>
    </div>
    @endforeach
</div>

@if($projects->hasPages())
<div class="mt-6">
    {{ $projects->links() }}
</div>
@endif
@endif

@endsection
