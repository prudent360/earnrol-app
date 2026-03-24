@extends('layouts.app')

@section('title', 'My Classes')
@section('page_title', 'My Classes')
@section('page_subtitle', 'Your enrolled training cohorts')

@section('content')

@if($enrollments->isEmpty())
<div class="bg-white rounded-2xl p-12 border border-dashed border-gray-300 text-center max-w-lg mx-auto">
    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
    </div>
    <h3 class="text-lg font-bold text-[#1a1a2e] mb-2">No classes yet</h3>
    <p class="text-gray-500 text-sm mb-6">You haven't enrolled in any cohorts. Head to the dashboard to see available classes.</p>
    <a href="{{ route('dashboard') }}" class="btn-primary text-sm">Browse Available Cohorts</a>
</div>
@else
<div class="space-y-4">
    @foreach($enrollments as $enrollment)
    @php $cohort = $enrollment->cohort; @endphp
    <div class="card">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <h3 class="text-lg font-bold text-[#1a1a2e]">{{ $cohort->title }}</h3>
                    <span class="badge {{ $cohort->status === 'active' ? 'bg-green-100 text-green-700' : ($cohort->status === 'upcoming' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500') }}">
                        {{ ucfirst($cohort->status) }}
                    </span>
                </div>
                @if($cohort->description)
                <p class="text-sm text-gray-500 mb-2">{{ $cohort->description }}</p>
                @endif
                <div class="flex items-center gap-4 text-xs text-gray-400">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Starts {{ $cohort->start_date->format('M d, Y') }}
                    </span>
                    <span>Enrolled {{ $enrollment->enrolled_at->diffForHumans() }}</span>
                </div>
            </div>

            <div class="flex items-center gap-3 flex-shrink-0">
                <a href="{{ route('cohorts.materials', $cohort) }}" class="text-sm font-medium text-blue-600 hover:underline flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Materials
                </a>
                @if($cohort->status === 'active' && $cohort->google_meet_link)
                <a href="{{ $cohort->google_meet_link }}" target="_blank" rel="noopener noreferrer"
                   class="btn-primary text-sm py-3 px-6">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Join Class
                </a>
                @elseif($cohort->status === 'upcoming')
                <span class="text-sm text-gray-400 font-medium">Starts soon</span>
                @elseif($cohort->status === 'completed')
                <span class="text-sm text-gray-400 font-medium">Completed</span>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection
