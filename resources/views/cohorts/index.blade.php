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
                <p class="text-sm text-gray-500 mb-2">{{ Str::limit($cohort->description, 100) }}</p>
                @endif
                <div class="flex items-center gap-4 text-xs text-gray-400">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Starts {{ $cohort->start_date->format('M d, Y') }}
                    </span>
                    @if($cohort->duration)
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $cohort->duration }}
                    </span>
                    @endif
                    <span>Enrolled {{ $enrollment->enrolled_at->diffForHumans() }}</span>
                </div>
            </div>

            <div class="flex items-center gap-3 flex-shrink-0">
                <button type="button" onclick="openCohortModal('{{ $cohort->id }}')" class="text-sm font-bold text-[#1a2535] hover:underline flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    View Details
                </button>
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

    {{-- Cohort Detail Modal --}}
    <div id="cohort-modal-{{ $cohort->id }}" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeCohortModal('{{ $cohort->id }}')"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                    <button onclick="closeCohortModal('{{ $cohort->id }}')" class="absolute top-4 right-4 z-10 w-8 h-8 rounded-full bg-white/90 shadow flex items-center justify-center hover:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    @if($cohort->cover_image)
                    <div class="rounded-t-2xl overflow-hidden">
                        <img src="{{ Storage::url($cohort->cover_image) }}" alt="{{ $cohort->title }}" class="w-full h-48 object-cover">
                    </div>
                    @endif

                    <div class="p-6 space-y-5">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="badge {{ $cohort->status === 'active' ? 'bg-green-100 text-green-700' : ($cohort->status === 'upcoming' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500') }}">
                                    {{ ucfirst($cohort->status) }}
                                </span>
                                <span class="badge bg-emerald-100 text-emerald-700">Enrolled</span>
                            </div>
                            <h2 class="text-xl font-bold text-[#1a1a2e]">{{ $cohort->title }}</h2>
                            @if($cohort->description)
                            <p class="text-sm text-gray-600 mt-2 leading-relaxed">{{ $cohort->description }}</p>
                            @endif
                        </div>

                        {{-- Key Details --}}
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <div class="bg-gray-50 rounded-xl p-3">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Start Date</p>
                                <p class="text-sm font-semibold text-[#1a1a2e] mt-0.5">{{ $cohort->start_date->format('M d, Y') }}</p>
                            </div>
                            @if($cohort->end_date)
                            <div class="bg-gray-50 rounded-xl p-3">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">End Date</p>
                                <p class="text-sm font-semibold text-[#1a1a2e] mt-0.5">{{ $cohort->end_date->format('M d, Y') }}</p>
                            </div>
                            @endif
                            @if($cohort->duration)
                            <div class="bg-gray-50 rounded-xl p-3">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Duration</p>
                                <p class="text-sm font-semibold text-[#1a1a2e] mt-0.5">{{ $cohort->duration }}</p>
                            </div>
                            @endif
                            @if($cohort->schedule)
                            <div class="bg-gray-50 rounded-xl p-3 col-span-2 sm:col-span-1">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Schedule</p>
                                <p class="text-sm font-semibold text-[#1a1a2e] mt-0.5">{{ $cohort->schedule }}</p>
                            </div>
                            @endif
                            <div class="bg-gray-50 rounded-xl p-3">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Format</p>
                                <p class="text-sm font-semibold text-[#1a1a2e] mt-0.5">Live Online</p>
                            </div>
                        </div>

                        {{-- Facilitator --}}
                        @if($cohort->facilitator_name)
                        <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-4">
                            @if($cohort->facilitator_image)
                            <img src="{{ Storage::url($cohort->facilitator_image) }}" alt="{{ $cohort->facilitator_name }}" class="w-12 h-12 rounded-full object-cover flex-shrink-0">
                            @else
                            <div class="w-12 h-12 rounded-full bg-[#1a2535] flex items-center justify-center flex-shrink-0">
                                <span class="text-lg font-bold text-white">{{ strtoupper(substr($cohort->facilitator_name, 0, 1)) }}</span>
                            </div>
                            @endif
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Facilitator</p>
                                <p class="text-sm font-bold text-[#1a1a2e]">{{ $cohort->facilitator_name }}</p>
                                @if($cohort->facilitator_bio)
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $cohort->facilitator_bio }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- What You'll Learn --}}
                        @if(count($cohort->what_you_will_learn_list) > 0)
                        <div>
                            <h3 class="text-sm font-bold text-[#1a1a2e] mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                What You'll Learn
                            </h3>
                            <ul class="space-y-2">
                                @foreach($cohort->what_you_will_learn_list as $item)
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span class="text-sm text-gray-700">{{ $item }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- Prerequisites --}}
                        @if(count($cohort->prerequisites_list) > 0)
                        <div>
                            <h3 class="text-sm font-bold text-[#1a1a2e] mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Prerequisites
                            </h3>
                            <ul class="space-y-2">
                                @foreach($cohort->prerequisites_list as $item)
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    <span class="text-sm text-gray-700">{{ $item }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- Actions --}}
                        <div class="pt-4 border-t border-gray-100 space-y-3">
                            @if($cohort->status === 'active' && $cohort->google_meet_link)
                            <a href="{{ $cohort->google_meet_link }}" target="_blank" rel="noopener noreferrer" class="btn-primary w-full justify-center py-3 text-sm inline-flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                Join Live Class
                            </a>
                            @endif
                            <a href="{{ route('cohorts.materials', $cohort) }}" class="w-full py-2.5 rounded-xl text-sm font-bold bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors inline-flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                View Materials
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection

@push('scripts')
<script>
    function openCohortModal(id) {
        const modal = document.getElementById('cohort-modal-' + id);
        if (modal) { modal.classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    }
    function closeCohortModal(id) {
        const modal = document.getElementById('cohort-modal-' + id);
        if (modal) { modal.classList.add('hidden'); document.body.style.overflow = ''; }
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('[id^="cohort-modal-"]').forEach(function(modal) {
                modal.classList.add('hidden');
            });
            document.body.style.overflow = '';
        }
    });
</script>
@endpush
