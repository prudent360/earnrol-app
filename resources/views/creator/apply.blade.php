@extends('layouts.app')

@section('title', 'Become a Creator')
@section('page_title', 'Become a Creator')
@section('page_subtitle', 'Apply to teach and sell on our platform')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Pending Application --}}
    @if($application && $application->isPending())
    <div class="card text-center py-10">
        <div class="w-16 h-16 rounded-2xl bg-amber-50 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h3 class="text-xl font-bold text-[#1a1a2e] mb-2">Application Under Review</h3>
        <p class="text-sm text-gray-500 max-w-md mx-auto">Your creator application was submitted on <span class="font-semibold">{{ $application->created_at->format('M d, Y') }}</span>. We'll notify you once it has been reviewed.</p>
        <div class="mt-6 bg-gray-50 rounded-xl p-4 text-left max-w-sm mx-auto space-y-2">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase">Expertise</p>
                <p class="text-sm text-gray-700">{{ Str::limit($application->expertise, 80) }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase">Submitted</p>
                <p class="text-sm text-gray-700">{{ $application->created_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>

    {{-- Rejected — Allow Reapply --}}
    @elseif($application && $application->isRejected())
    <div class="bg-red-50 border border-red-200 rounded-2xl p-5 mb-6">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h4 class="text-sm font-bold text-red-800">Previous Application Rejected</h4>
                <p class="text-sm text-red-700 mt-1">{{ $application->rejection_reason ?? 'Your application did not meet our requirements at this time.' }}</p>
                <p class="text-xs text-red-500 mt-2">You can submit a new application below.</p>
            </div>
        </div>
    </div>

    @include('creator._application_form')

    {{-- Fresh Application --}}
    @else

    {{-- Benefits --}}
    <div class="card mb-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-[#1a1a2e]">Why Become a Creator?</h3>
                <p class="text-sm text-gray-500">Share your knowledge and earn from it</p>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-gray-50 rounded-xl p-4 text-center">
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <p class="text-sm font-bold text-[#1a1a2e]">Create Cohorts</p>
                <p class="text-xs text-gray-400 mt-1">Run live training sessions</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 text-center">
                <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <p class="text-sm font-bold text-[#1a1a2e]">Sell Products</p>
                <p class="text-xs text-gray-400 mt-1">eBooks, templates, courses</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4 text-center">
                <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-sm font-bold text-[#1a1a2e]">Earn Commission</p>
                <p class="text-xs text-gray-400 mt-1">Up to {{ \App\Models\Setting::get('creator_commission', '80') }}% on sales</p>
            </div>
        </div>
    </div>

    @include('creator._application_form')

    @endif
</div>
@endsection
