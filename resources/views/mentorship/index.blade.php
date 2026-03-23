@extends('layouts.app')

@section('title', 'Mentorship')
@section('page_title', 'Mentorship')
@section('page_subtitle', 'Book 1-on-1 sessions with industry experts')

@section('content')

{{-- Session status banner --}}
@if($upcomingSession)
<div class="bg-[#e05a3a]/5 border border-[#e05a3a]/20 rounded-2xl p-5 mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-4">
    <div class="w-12 h-12 rounded-xl bg-[#e05a3a]/10 flex items-center justify-center flex-shrink-0">
        <svg class="w-6 h-6 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    </div>
    <div class="flex-1">
        <p class="font-semibold text-[#1a1a2e]">Upcoming session with {{ $upcomingSession->mentor->user->name }}</p>
        <p class="text-sm text-[#6b7280]">{{ $upcomingSession->scheduled_at->format('M d, Y · g:i A') }} · {{ $upcomingSession->topic }} · {{ $upcomingSession->duration_minutes }} minutes</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('mentorship.sessions.join', $upcomingSession) }}" target="_blank" class="btn-primary text-sm py-2.5 px-6">Join Session</a>
        <a href="{{ route('mentorship.sessions.index') }}" class="text-sm text-[#6b7280] hover:text-[#1a1a2e] font-medium self-center">Manage Bookings</a>
    </div>
</div>
@endif

{{-- Filter --}}
<div class="flex flex-wrap gap-2 mb-6">
    <button class="px-4 py-2 rounded-full text-sm font-medium bg-[#e05a3a] text-white">All Mentors</button>
    @foreach(['Cloud Computing', 'DevOps', 'Cybersecurity', 'Data Engineering', 'Career Coaching'] as $f)
    <button class="px-4 py-2 rounded-full text-sm font-medium bg-white border border-[#e8eaf0] text-[#6b7280] hover:border-[#e05a3a] hover:text-[#e05a3a] transition-all">
        {{ $f }}
    </button>
    @endforeach
</div>

{{-- Mentor grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" x-data="{ bookingModal: false, selectedMentor: null }">
    @forelse($mentors as $mentor)
    <div class="card hover:shadow-lg transition-shadow">
        <div class="flex items-start gap-4 mb-4">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white font-bold text-lg flex-shrink-0 shadow-sm" style="background-color: {{ $mentor->icon_color ?? '#e05a3a' }};">
                {{ $mentor->avatar_text ?? substr($mentor->user->name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-[#1a1a2e] truncate">{{ $mentor->user->name }}</h3>
                    <div class="flex items-center gap-1 text-sm bg-yellow-50 px-2 py-0.5 rounded-full border border-yellow-100">
                        <svg class="w-3.5 h-3.5 text-[#f59e0b]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span class="font-bold text-[#1a1a2e]">{{ number_format($mentor->rating, 1) }}</span>
                    </div>
                </div>
                <p class="text-sm text-[#6b7280] truncate">{{ $mentor->role_title }}</p>
                <p class="text-xs text-[#e05a3a] font-medium">@ {{ $mentor->company }}</p>
            </div>
        </div>

        <p class="text-sm text-[#6b7280] leading-relaxed mb-4 line-clamp-2">{{ $mentor->bio }}</p>

        <div class="flex flex-wrap gap-1 mb-4">
            @foreach($mentor->expertise ?? [] as $skill)
            <span class="tag text-[10px]">{{ $skill }}</span>
            @endforeach
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-[#e8eaf0]">
            <div>
                <p class="text-sm font-bold text-[#1a1a2e]">{{ $mentor->price_label }}</p>
                <p class="text-xs text-[#6b7280]">{{ $mentor->sessions_count }} sessions done</p>
            </div>
            <div class="flex items-center gap-2">
                @if($mentor->is_available)
                <span class="flex items-center gap-1.5 text-xs text-[#22c55e] font-medium">
                    <span class="w-1.5 h-1.5 bg-[#22c55e] rounded-full animate-pulse"></span> Available
                </span>
                <button @click="selectedMentor = {{ $mentor->jsonSerialize() }}; bookingModal = true;" class="btn-primary text-[10px] uppercase font-bold tracking-wider py-2 px-3">
                    Book Session
                </button>
                @else
                <span class="flex items-center gap-1.5 text-xs text-[#6b7280] font-medium">
                    <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span> Booked
                </span>
                <button class="btn-primary opacity-30 cursor-not-allowed text-[10px] uppercase font-bold tracking-wider py-2 px-3" disabled>
                    Book Session
                </button>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full card p-12 text-center border-dashed border-2 border-gray-100">
        <p class="text-gray-500 font-medium">No mentors found at the moment.</p>
    </div>
    @endforelse

    {{-- Booking Modal --}}
    <div x-show="bookingModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="bookingModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
                
                <div class="px-8 pt-6 pb-8 bg-white">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-[#1a1a2e]">Book 1-on-1 Session</h3>
                        <button @click="bookingModal = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="flex items-center gap-4 mb-8 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold" 
                             :style="{ backgroundColor: selectedMentor?.icon_color || '#e05a3a' }"
                             x-text="selectedMentor?.avatar_text">
                        </div>
                        <div>
                            <p class="font-bold text-[#1a1a2e]" x-text="selectedMentor?.user?.name"></p>
                            <p class="text-xs text-[#6b7280]" x-text="selectedMentor?.role_title"></p>
                        </div>
                    </div>

                    <form :action="'/mentorship/' + selectedMentor?.id + '/book'" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-[#1a1a2e] uppercase mb-1.5">Scheduled Date & Time</label>
                                <input type="datetime-local" name="scheduled_at" required class="form-input" min="{{ date('Y-m-d\TH:i', strtotime('+1 hour')) }}">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[#1a1a2e] uppercase mb-1.5">Session Topic</label>
                                <input type="text" name="topic" placeholder="e.g. AWS Career Strategy" required class="form-input">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-[#1a1a2e] uppercase mb-1.5">Additional Notes (Optional)</label>
                                <textarea name="notes" placeholder="Tell the mentor what you'd like to focus on..." class="form-input" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex gap-3">
                            <button type="button" @click="bookingModal = false" class="btn-outline flex-1 py-3 text-sm">Cancel</button>
                            <button type="submit" class="btn-primary flex-1 py-3 text-sm">Confirm Booking</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
