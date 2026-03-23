@extends('layouts.app')

@section('title', $project->title)
@section('page_title', $project->title)
@section('page_subtitle', $project->category ? ucfirst($project->category) : 'Project Details')

@section('content')

<div class="max-w-3xl space-y-6">
    @php
        $statusConfig = [
            'pending'   => ['label' => 'Available',   'bg' => 'bg-yellow-50', 'text' => 'text-yellow-700'],
            'active'    => ['label' => 'In Progress',  'bg' => 'bg-blue-50',   'text' => 'text-blue-700'],
            'completed' => ['label' => 'Completed',    'bg' => 'bg-green-50',  'text' => 'text-green-700'],
        ];
        $sc = $statusConfig[$project->status] ?? $statusConfig['pending'];
        $colors = ['#e05a3a','#4285F4','#f59e0b','#22c55e','#8b5cf6'];
        $color  = $colors[$project->id % count($colors)];
    @endphp

    <div class="card">
        <div class="w-12 h-1.5 rounded-full mb-4" style="background-color: {{ $color }};"></div>
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <h2 class="text-xl font-bold text-[#1a1a2e]">{{ $project->title }}</h2>
                @if($project->category)
                <p class="text-sm text-gray-500 mt-1 capitalize">{{ $project->category }}</p>
                @endif
            </div>
            <span class="inline-flex items-center text-xs font-medium px-3 py-1 rounded-full {{ $sc['bg'] }} {{ $sc['text'] }}">
                {{ $sc['label'] }}
            </span>
        </div>

        @if($project->description)
        <div class="mt-4 text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $project->description }}</div>
        @endif

        <div class="flex flex-wrap gap-3 mt-5 pt-4 border-t border-[#e8eaf0]">
            @if($project->github_url)
            <a href="{{ $project->github_url }}" target="_blank" rel="noopener" class="btn-outline text-sm py-2 px-4 flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.3 3.438 9.8 8.205 11.387.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61-.546-1.387-1.333-1.756-1.333-1.756-1.09-.745.083-.73.083-.73 1.205.085 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 21.795 24 17.295 24 12c0-6.63-5.37-12-12-12"/></svg>
                View on GitHub
            </a>
            @endif
            @if($project->live_url)
            <a href="{{ $project->live_url }}" target="_blank" rel="noopener" class="btn-primary text-sm py-2 px-4 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Live Demo
            </a>
            @endif
        </div>
    </div>

    <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#e05a3a] transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Projects
    </a>
</div>

@endsection
