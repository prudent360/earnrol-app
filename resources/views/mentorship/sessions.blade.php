@extends('layouts.app')

@section('title', 'My Sessions')
@section('page_title', 'Mentorship Sessions')
@section('page_subtitle', 'Manage your 1-on-1 bookings')

@section('content')

<div class="max-w-5xl mx-auto">
    {{-- Tabs --}}
    <div class="flex border-b border-[#e8eaf0] mb-8">
        <button class="px-6 py-4 border-b-2 border-[#e05a3a] text-[#1a1a2e] font-bold text-sm">Upcoming</button>
        <button class="px-6 py-4 border-b-2 border-transparent text-[#6b7280] font-medium text-sm hover:text-[#1a1a2e]">Past Sessions</button>
    </div>

    <div class="grid grid-cols-1 gap-4">
        @forelse($upcomingSessions as $session)
        <div class="card flex flex-col md:flex-row items-start md:items-center gap-6 p-6">
            <div class="w-16 h-16 rounded-2xl bg-[#1a1a2e] flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                {{ substr($session->mentor->user->name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-green-100 text-green-700">Confirmed</span>
                    <span class="text-xs text-[#6b7280]">{{ $session->scheduled_at->format('M d, Y') }}</span>
                </div>
                <h3 class="font-bold text-[#1a1a2e] text-lg">{{ $session->topic }}</h3>
                <p class="text-sm text-[#6b7280]">with <span class="font-semibold text-[#1a1a2e]">{{ $session->mentor->user->name }}</span> · {{ $session->duration_minutes }} mins</p>
            </div>
            <div class="flex items-center gap-3 w-full md:w-auto">
                <a href="{{ route('mentorship.sessions.join', $session) }}" target="_blank" class="btn-primary flex-1 md:flex-none text-center py-2.5 px-6 text-sm">Join Meeting</a>
                <button class="btn-outline flex-1 md:flex-none py-2.5 px-6 text-sm text-[#6b7280]">Reschedule</button>
            </div>
        </div>
        @empty
        <div class="card p-12 text-center border-dashed border-2 border-gray-100">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <p class="text-sm font-semibold text-gray-500">No upcoming sessions</p>
            <a href="{{ route('mentorship.index') }}" class="text-[#e05a3a] text-xs font-semibold mt-2 inline-block hover:underline">Book a session now →</a>
        </div>
        @endforelse
    </div>

    @if($pastSessions->isNotEmpty())
    <h2 class="text-lg font-bold text-[#1a1a2e] mt-12 mb-6">Past Sessions</h2>
    <div class="grid grid-cols-1 gap-4 opacity-75">
        @foreach($pastSessions as $session)
        <div class="card flex items-center gap-6 p-6 grayscale-[0.5]">
            <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 font-bold">
                {{ substr($session->mentor->user->name, 0, 1) }}
            </div>
            <div class="flex-1">
                <p class="text-xs text-[#6b7280] mb-1">{{ $session->scheduled_at->format('M d, Y') }}</p>
                <h3 class="font-bold text-[#1a1a2e]">{{ $session->topic }}</h3>
                <p class="text-xs text-[#6b7280]">with {{ $session->mentor->user->name }}</p>
            </div>
            <span class="text-xs font-bold text-gray-400 uppercase">Completed</span>
        </div>
        @endforeach
    </div>
    @endif
</div>

@endsection
