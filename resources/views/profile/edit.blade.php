@extends('layouts.app')

@section('title', 'Profile')
@section('page_title', 'Profile')
@section('page_subtitle', 'Manage your account settings')

@section('content')

<div class="max-w-2xl mx-auto space-y-6">

    {{-- Personal Information --}}
    <div class="card">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: {{ \App\Models\Setting::get('brand_color', '#e05a3a') }}20;">
                <svg class="w-6 h-6" style="color: {{ \App\Models\Setting::get('brand_color', '#e05a3a') }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-[#1a1a2e]">Personal Information</h3>
                <p class="text-sm text-[#6b7280]">Update your name and email address</p>
            </div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label for="name" class="form-label">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="form-input @error('name') border-red-400 @enderror">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input @error('email') border-red-400 @enderror">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Role</label>
                <input type="text" value="{{ ucfirst($user->role ?? 'Learner') }}" disabled class="form-input bg-gray-50 text-[#6b7280] cursor-not-allowed">
                <p class="text-xs text-[#6b7280] mt-1">Your role cannot be changed from here.</p>
            </div>
            <div class="flex justify-end pt-2">
                <button type="submit" class="btn-primary py-2.5">
                    Save Changes
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </button>
            </div>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="card">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 bg-yellow-50">
                <svg class="w-6 h-6 text-[#f59e0b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-[#1a1a2e]">Change Password</h3>
                <p class="text-sm text-[#6b7280]">Ensure your account stays secure</p>
            </div>
        </div>

        <form action="{{ route('profile.password') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label for="current_password" class="form-label">Current Password</label>
                <input type="password" id="current_password" name="current_password" required class="form-input @error('current_password') border-red-400 @enderror" placeholder="••••••••">
                @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password" class="form-label">New Password</label>
                <input type="password" id="password" name="password" required class="form-input @error('password') border-red-400 @enderror" placeholder="••••••••">
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="form-input" placeholder="••••••••">
            </div>
            <div class="flex justify-end pt-2">
                <button type="submit" class="btn-primary py-2.5">
                    Update Password
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </button>
            </div>
        </form>
    </div>

    {{-- Account Info --}}
    <div class="card">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 bg-blue-50">
                <svg class="w-6 h-6 text-[#3b82f6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-[#1a1a2e]">Account Info</h3>
                <p class="text-sm text-[#6b7280]">Your account details</p>
            </div>
        </div>
        <div class="space-y-3 text-sm">
            <div class="flex items-center justify-between py-2 border-b border-gray-50">
                <span class="text-[#6b7280]">Member Since</span>
                <span class="font-medium text-[#1a1a2e]">{{ $user->created_at->format('M d, Y') }}</span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-gray-50">
                <span class="text-[#6b7280]">Email Verified</span>
                <span class="font-medium {{ $user->email_verified_at ? 'text-[#22c55e]' : 'text-[#f59e0b]' }}">
                    {{ $user->email_verified_at ? 'Yes' : 'Not yet' }}
                </span>
            </div>
            <div class="flex items-center justify-between py-2">
                <span class="text-[#6b7280]">Account Type</span>
                <span class="badge bg-[#e05a3a]/10 text-[#e05a3a]">{{ ucfirst($user->role ?? 'Learner') }}</span>
            </div>
        </div>
    </div>

</div>

@endsection
