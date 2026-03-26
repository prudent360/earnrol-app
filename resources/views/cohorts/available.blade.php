@extends('layouts.app')

@section('title', 'Available Cohort')
@section('page_title', 'Available Cohort')
@section('page_subtitle', 'Explore and join our upcoming training cohorts')

@section('content')

@if($cohorts->isEmpty())
<div class="bg-white rounded-2xl p-12 border border-dashed border-gray-300 text-center max-w-lg mx-auto">
    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"/></svg>
    </div>
    <h3 class="text-lg font-bold text-[#1a1a2e] mb-2">No new cohorts available</h3>
    <p class="text-gray-500 text-sm mb-6">Check back later for new programs or check your current classes.</p>
    <a href="{{ route('cohorts.index') }}" class="btn-primary text-sm">View My Classes</a>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($cohorts as $cohort)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow flex flex-col h-full">
        @if($cohort->cover_image)
        <div class="h-40 overflow-hidden relative">
            <img src="{{ Storage::url($cohort->cover_image) }}" alt="{{ $cohort->title }}" class="w-full h-full object-cover">
            <div class="absolute top-3 right-3">
                <span class="badge {{ $cohort->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                    {{ ucfirst($cohort->status) }}
                </span>
            </div>
        </div>
        @else
        <div class="h-40 bg-gradient-to-br from-[#1a2535] to-[#2c3e50] flex items-center justify-center p-6 relative">
            <span class="text-white/20 text-4xl font-black uppercase tracking-tighter">{{ Str::limit($cohort->title, 10) }}</span>
            <div class="absolute top-3 right-3">
                <span class="badge {{ $cohort->status === 'active' ? 'bg-green-100/10 text-green-400 border border-green-500/20' : 'bg-blue-100/10 text-blue-400 border border-blue-500/20' }}">
                    {{ ucfirst($cohort->status) }}
                </span>
            </div>
        </div>
        @endif

        <div class="p-5 flex-1 flex flex-col">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-[#1a1a2e] line-clamp-1 mb-2">{{ $cohort->title }}</h3>
                <p class="text-xs text-gray-500 line-clamp-2">{{ $cohort->description }}</p>
            </div>

            <div class="space-y-3 mb-6 flex-1">
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Starts {{ $cohort->start_date->format('M d, Y') }}
                </div>
                @if($cohort->duration)
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $cohort->duration }}
                </div>
                @endif
                <div class="flex items-center gap-2 text-xs text-gray-500 font-bold">
                    <span class="text-[#e05a3a] text-sm">
                        @if($cohort->price == 0) Free @else {{ $cohort->currency ?? '$' }}{{ number_format($cohort->price, 2) }} @endif
                    </span>
                    @if($cohort->max_students)
                    <span class="text-gray-300">|</span>
                    <span class="{{ $cohort->spotsLeft() < 5 ? 'text-red-500' : '' }}">
                        {{ $cohort->spotsLeft() }} spots left
                    </span>
                    @endif
                </div>
            </div>

            <div class="pt-4 border-t border-gray-50 flex items-center gap-3">
                <button type="button" onclick="openCohortModal('{{ $cohort->id }}')" class="flex-1 text-xs font-bold text-gray-500 hover:text-[#1a1a2e] transition-colors">
                    View Details
                </button>
                @if($cohort->isFull())
                    <button disabled class="flex-1 btn-secondary text-[10px] py-2 px-4 opacity-50 cursor-not-allowed">Full</button>
                @elseif($cohort->price == 0)
                    <form action="{{ route('cohorts.enrol-free', $cohort) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full btn-primary text-[10px] py-2 px-4 shadow-sm">Join Free</button>
                    </form>
                @else
                    <button onclick="openCohortModal('{{ $cohort->id }}')" class="flex-1 btn-primary text-[10px] py-2 px-4 shadow-sm">Enroll Now</button>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal (Same as in index.blade.php but with enroll button) --}}
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
                                <span class="badge {{ $cohort->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ ucfirst($cohort->status) }}
                                </span>
                                <span class="text-[#e05a3a] font-bold text-lg ml-auto">
                                    @if($cohort->price == 0) Free @else {{ $cohort->currency ?? '$' }}{{ number_format($cohort->price, 2) }} @endif
                                </span>
                            </div>
                            <h2 class="text-xl font-bold text-[#1a1a2e]">{{ $cohort->title }}</h2>
                            @if($cohort->description)
                            <p class="text-sm text-gray-600 mt-2 leading-relaxed">{{ $cohort->description }}</p>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-center">
                            <div class="bg-gray-50 rounded-xl p-3">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Start Date</p>
                                <p class="text-sm font-semibold text-[#1a1a2e] mt-0.5">{{ $cohort->start_date->format('M d, Y') }}</p>
                            </div>
                            @if($cohort->duration)
                            <div class="bg-gray-50 rounded-xl p-3">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Duration</p>
                                <p class="text-sm font-semibold text-[#1a1a2e] mt-0.5">{{ $cohort->duration }}</p>
                            </div>
                            @endif
                            @if($cohort->max_students)
                            <div class="bg-gray-50 rounded-xl p-3">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Availability</p>
                                <p class="text-sm font-semibold text-[#1a1a2e] mt-0.5">{{ $cohort->spotsLeft() }} spots left</p>
                            </div>
                            @endif
                        </div>

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
                            </div>
                        </div>
                        @endif

                        <div class="pt-4 border-t border-gray-100 flex gap-3">
                            @if($cohort->isFull())
                                <button disabled class="flex-1 btn-secondary py-3 text-sm opacity-50 cursor-not-allowed">Cohort Full</button>
                            @elseif($cohort->price == 0)
                                <form action="{{ route('cohorts.enrol-free', $cohort) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full btn-primary py-3 text-sm shadow-sm">Join Now for Free</button>
                                </form>
                            @else
                                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    @if(\App\Models\Setting::get('stripe_enabled'))
                                    <form action="{{ route('payments.stripe.checkout', $cohort) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full btn-primary py-3 text-sm shadow-sm">Pay with Card</button>
                                    </form>
                                    @endif
                                    @if(\App\Models\Setting::get('bank_transfer_enabled'))
                                    <a href="{{ route('payments.bank-transfer', $cohort) }}" class="btn-secondary py-3 text-sm text-center">Bank Transfer</a>
                                    @endif
                                </div>
                            @endif
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
</script>
@endpush
