@extends('layouts.app')

@section('title', 'Creator Applications')
@section('page_title', 'Creator Applications')
@section('page_subtitle', 'Review and manage creator applications')

@section('content')

{{-- Status Tabs --}}
<div class="flex items-center gap-2 mb-6 border-b border-gray-200 pb-px overflow-x-auto">
    <a href="{{ route('admin.creator-applications.index', ['status' => 'pending']) }}"
       class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ $status === 'pending' ? 'border-[#e05a3a] text-[#e05a3a]' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
        Pending
        @if($counts['pending'] > 0)
        <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">{{ $counts['pending'] }}</span>
        @endif
    </a>
    <a href="{{ route('admin.creator-applications.index', ['status' => 'approved']) }}"
       class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ $status === 'approved' ? 'border-[#e05a3a] text-[#e05a3a]' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
        Approved
        <span class="ml-1 text-xs text-gray-400">{{ $counts['approved'] }}</span>
    </a>
    <a href="{{ route('admin.creator-applications.index', ['status' => 'rejected']) }}"
       class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ $status === 'rejected' ? 'border-[#e05a3a] text-[#e05a3a]' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
        Rejected
        <span class="ml-1 text-xs text-gray-400">{{ $counts['rejected'] }}</span>
    </a>
    <a href="{{ route('admin.creator-applications.index', ['status' => 'all']) }}"
       class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap {{ $status === 'all' ? 'border-[#e05a3a] text-[#e05a3a]' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
        All
    </a>
</div>

@if($applications->isEmpty())
<div class="bg-white rounded-2xl p-12 border border-dashed border-gray-300 text-center max-w-md mx-auto">
    <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    </div>
    <h3 class="text-base font-bold text-[#1a1a2e] mb-1">No {{ $status !== 'all' ? $status : '' }} applications</h3>
    <p class="text-sm text-gray-400">Creator applications will appear here.</p>
</div>
@else
<div class="space-y-4">
    @foreach($applications as $application)
    <div class="card" x-data="{ expanded: false, showReject: false }">
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-start gap-4 flex-1 min-w-0">
                <div class="w-10 h-10 rounded-full bg-[#e05a3a] flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr($application->user->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h4 class="text-sm font-bold text-[#1a1a2e]">{{ $application->user->name }}</h4>
                        <span class="text-xs text-gray-400">{{ $application->user->email }}</span>
                        <span class="badge {{ $application->status === 'pending' ? 'bg-amber-100 text-amber-700' : ($application->status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($application->status) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1"><span class="font-semibold text-gray-700">Expertise:</span> {{ $application->expertise }}</p>
                    <p class="text-xs text-gray-400 mt-1">Applied {{ $application->created_at->diffForHumans() }}</p>
                </div>
            </div>

            <button @click="expanded = !expanded" class="text-gray-400 hover:text-gray-600 flex-shrink-0">
                <svg class="w-5 h-5 transition-transform" :class="expanded && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
        </div>

        {{-- Expanded details --}}
        <div x-show="expanded" x-transition class="mt-4 pt-4 border-t border-gray-100 space-y-4" style="display: none;">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Experience</p>
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $application->experience }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Why They Want to Create</p>
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $application->reason }}</p>
                </div>
            </div>

            @if($application->portfolio_url)
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Portfolio / LinkedIn</p>
                <a href="{{ $application->portfolio_url }}" target="_blank" rel="noopener" class="text-sm text-[#e05a3a] hover:underline flex items-center gap-1">
                    {{ $application->portfolio_url }}
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            </div>
            @endif

            @if($application->rejection_reason)
            <div class="bg-red-50 rounded-xl p-3">
                <p class="text-[10px] font-bold text-red-400 uppercase tracking-wider mb-1">Rejection Reason</p>
                <p class="text-sm text-red-700">{{ $application->rejection_reason }}</p>
            </div>
            @endif

            @if($application->reviewed_at)
            <p class="text-xs text-gray-400">Reviewed {{ $application->reviewed_at->diffForHumans() }}</p>
            @endif

            {{-- Actions --}}
            @if($application->isPending())
            <div class="flex items-center gap-3 pt-2">
                <form method="POST" action="{{ route('admin.creator-applications.approve', $application) }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold bg-green-600 text-white hover:bg-green-700 transition-colors" onclick="return confirm('Approve {{ $application->user->name }} as a creator?')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Approve
                    </button>
                </form>

                <button @click="showReject = !showReject" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Reject
                </button>
            </div>

            {{-- Reject form --}}
            <div x-show="showReject" x-transition class="mt-3" style="display: none;">
                <form method="POST" action="{{ route('admin.creator-applications.reject', $application) }}" class="flex items-start gap-3">
                    @csrf
                    <textarea name="rejection_reason" rows="2" required maxlength="500" class="form-input flex-1 text-sm" placeholder="Reason for rejection..."></textarea>
                    <button type="submit" class="btn-primary text-xs py-2 px-4 flex-shrink-0 bg-red-600 hover:bg-red-700">Confirm Reject</button>
                </form>
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>

<div class="mt-6">
    {{ $applications->withQueryString()->links() }}
</div>
@endif

@endsection
