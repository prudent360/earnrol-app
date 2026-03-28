@extends('layouts.app')

@section('title', 'My Coaching Sessions')
@section('page_title', 'My Coaching Sessions')
@section('page_subtitle', 'Your booked 1-on-1 sessions')

@section('content')
<div class="space-y-4">
    @forelse($bookings as $booking)
    <div class="card flex flex-col sm:flex-row sm:items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-teal-500 to-emerald-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div class="flex-1">
            <h3 class="text-sm font-bold text-[#1a1a2e]">{{ $booking->service->title }}</h3>
            <p class="text-xs text-gray-400">with {{ $booking->service->creator->name ?? 'Creator' }}</p>
            <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                <span>{{ $booking->slot->start_time->format('M d, Y — g:i A') }}</span>
                <span>{{ $booking->service->duration_minutes }} min</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold
                    {{ $booking->status === 'confirmed' ? 'bg-blue-100 text-blue-700' : ($booking->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>
        </div>
        <div class="flex-shrink-0">
            @if($booking->meeting_link)
            <a href="{{ $booking->meeting_link }}" target="_blank" class="btn-primary text-sm py-2">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                Join Meeting
            </a>
            @else
            <span class="text-xs text-gray-400">Meeting link pending</span>
            @endif
        </div>
    </div>
    @empty
    <div class="card text-center text-gray-400 py-12">
        <p>No coaching sessions booked yet.</p>
        <a href="{{ route('coaching.index') }}" class="text-[#e05a3a] hover:underline mt-2 inline-block">Browse Coaching Services</a>
    </div>
    @endforelse
</div>
@endsection
