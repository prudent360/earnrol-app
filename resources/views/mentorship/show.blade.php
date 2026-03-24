@extends('layouts.app')

@section('title', $mentor->user->name . ' — Mentorship')
@section('page_title', 'Mentor Profile')
@section('page_subtitle', 'Learn more about this mentor and book a session')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Mentor header --}}
    <div class="card mb-6">
        <div class="flex flex-col sm:flex-row items-start gap-6">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-white font-bold text-2xl flex-shrink-0 shadow-lg" style="background-color: {{ $mentor->icon_color ?? '#e05a3a' }};">
                {{ $mentor->avatar_text ?? substr($mentor->user->name, 0, 2) }}
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-1">
                    <h2 class="text-2xl font-bold text-[#1a1a2e]">{{ $mentor->user->name }}</h2>
                    @if($mentor->is_available)
                    <span class="flex items-center gap-1.5 text-xs text-[#22c55e] font-medium bg-green-50 px-2.5 py-1 rounded-full border border-green-100">
                        <span class="w-1.5 h-1.5 bg-[#22c55e] rounded-full animate-pulse"></span> Available
                    </span>
                    @else
                    <span class="flex items-center gap-1.5 text-xs text-[#6b7280] font-medium bg-gray-50 px-2.5 py-1 rounded-full border border-gray-200">
                        <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span> Unavailable
                    </span>
                    @endif
                </div>
                <p class="text-[#6b7280] text-sm mb-1">{{ $mentor->role_title }}</p>
                <p class="text-[#e05a3a] font-medium text-sm mb-3">@ {{ $mentor->company }}</p>

                <div class="flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4 text-[#f59e0b]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span class="font-bold text-[#1a1a2e]">{{ number_format($mentor->rating, 1) }}</span>
                        <span class="text-[#6b7280]">rating</span>
                    </div>
                    <div class="text-[#6b7280]">·</div>
                    <div class="text-[#6b7280]">{{ $mentor->sessions_count }} sessions completed</div>
                    <div class="text-[#6b7280]">·</div>
                    <div class="font-semibold text-[#1a1a2e]">{{ $mentor->price_label }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left column: Bio + Expertise --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- About --}}
            <div class="card">
                <h3 class="text-lg font-bold text-[#1a1a2e] mb-3">About</h3>
                <p class="text-[#6b7280] leading-relaxed">{{ $mentor->bio }}</p>
            </div>

            {{-- Expertise --}}
            <div class="card">
                <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">Expertise</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($mentor->expertise ?? [] as $skill)
                    <span class="px-3 py-1.5 bg-[#f5f6fa] border border-[#e8eaf0] text-[#1a1a2e] text-sm font-medium rounded-lg">{{ $skill }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Recent Sessions --}}
            <div class="card">
                <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">Recent Sessions</h3>
                @php
                    $recentSessions = $mentor->sessions()->with('user')->latest('scheduled_at')->take(5)->get();
                @endphp
                @forelse($recentSessions as $session)
                <div class="flex items-center gap-3 py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <div class="w-8 h-8 rounded-full bg-[#1a2535] flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr($session->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-[#1a1a2e] truncate">{{ $session->topic }}</p>
                        <p class="text-xs text-[#6b7280]">{{ $session->scheduled_at->format('M d, Y') }} · {{ $session->duration_minutes }} min</p>
                    </div>
                    <span class="badge {{ $session->status === 'confirmed' ? 'bg-green-100 text-green-700' : ($session->status === 'completed' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                        {{ ucfirst($session->status) }}
                    </span>
                </div>
                @empty
                <p class="text-sm text-[#6b7280] py-4 text-center">No sessions yet. Be the first to book!</p>
                @endforelse
            </div>
        </div>

        {{-- Right column: Booking --}}
        <div class="space-y-6">
            {{-- Booking Card --}}
            <div class="card border-2 border-[#e05a3a]/20">
                <h3 class="text-lg font-bold text-[#1a1a2e] mb-2">Book a Session</h3>
                <p class="text-sm text-[#6b7280] mb-4">Schedule a 1-on-1 with {{ $mentor->user->name }}</p>

                @if($mentor->is_available)
                <form action="{{ route('mentorship.book', $mentor) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Date & Time</label>
                            <input type="datetime-local" name="scheduled_at" required class="form-input" min="{{ date('Y-m-d\TH:i', strtotime('+1 hour')) }}">
                            @error('scheduled_at')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Session Topic</label>
                            <input type="text" name="topic" placeholder="e.g. AWS Career Strategy" required class="form-input" value="{{ old('topic') }}">
                            @error('topic')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Notes <span class="text-[#6b7280] font-normal">(optional)</span></label>
                            <textarea name="notes" placeholder="What would you like to focus on?" class="form-input" rows="3">{{ old('notes') }}</textarea>
                        </div>
                        <button type="submit" class="btn-primary w-full justify-center py-3">
                            Confirm Booking
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </button>
                    </div>
                </form>
                @else
                <div class="text-center py-6 px-4 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-sm text-[#6b7280] font-medium">This mentor is currently unavailable.</p>
                    <p class="text-xs text-gray-400 mt-1">Check back soon or browse other mentors.</p>
                </div>
                @endif
            </div>

            {{-- Quick Info --}}
            <div class="card">
                <h3 class="font-bold text-[#1a1a2e] mb-4">Quick Info</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-[#6b7280]">Session Duration</span>
                        <span class="font-medium text-[#1a1a2e]">45 minutes</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-[#6b7280]">Response Time</span>
                        <span class="font-medium text-[#1a1a2e]">Within 24 hours</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-[#6b7280]">Sessions Done</span>
                        <span class="font-medium text-[#1a1a2e]">{{ $mentor->sessions_count }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-[#6b7280]">Price</span>
                        <span class="font-bold text-[#1a1a2e]">{{ $mentor->price_label }}</span>
                    </div>
                </div>
            </div>

            <a href="{{ route('mentorship.index') }}" class="text-sm text-[#6b7280] hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to all mentors
            </a>
        </div>
    </div>
</div>

@endsection
