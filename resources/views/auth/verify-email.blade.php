@extends('layouts.app')

@section('title', 'Verify Email')
@section('page_title', 'Verify Your Email')
@section('page_subtitle', 'One last step before you get started')

@section('content')

<div class="max-w-lg mx-auto">
    <div class="card text-center">
        <div class="w-16 h-16 rounded-2xl mx-auto mb-6 flex items-center justify-center" style="background-color: {{ \App\Models\Setting::get('brand_color', '#e05a3a') }}20;">
            <svg class="w-8 h-8" style="color: {{ \App\Models\Setting::get('brand_color', '#e05a3a') }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>

        <h2 class="text-2xl font-bold text-[#1a1a2e] mb-2">Check your inbox</h2>
        <p class="text-[#6b7280] mb-6">
            We've sent a verification link to <span class="font-semibold text-[#1a1a2e]">{{ auth()->user()->email }}</span>. 
            Click the link to verify your email address and access all features.
        </p>

        @if(session('status') === 'verification-link-sent')
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            A new verification link has been sent!
        </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-primary justify-center py-3 w-full">
                Resend Verification Email
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-[#6b7280] hover:text-[#e05a3a] transition-colors">
                    Sign out and use a different account
                </button>
            </form>
        </div>
    </div>
</div>

@endsection
