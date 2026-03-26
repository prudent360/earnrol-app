@extends('layouts.app')

@section('title', 'Profile')
@section('page_title', 'Profile')
@section('page_subtitle', 'Manage your account settings')

@section('content')

<div class="max-w-2xl mx-auto space-y-6">

    {{-- Email Verification Banner --}}
    @if(!$user->hasVerifiedEmail())
    <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: {{ \App\Models\Setting::get('brand_color', '#e05a3a') }}20;">
                <svg class="w-5 h-5" style="color: {{ \App\Models\Setting::get('brand_color', '#e05a3a') }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-bold text-[#1a1a2e]">Email Not Verified</h4>
                <p class="text-xs text-[#6b7280] mt-1">Please verify your email address to access all features. Check your inbox for the verification link.</p>
                @if(session('status') === 'verification-link-sent')
                <p class="text-xs font-semibold mt-2" style="color: #22c55e;">A new verification link has been sent to your email!</p>
                @endif
            </div>
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors flex-shrink-0" style="background-color: {{ \App\Models\Setting::get('brand_color', '#e05a3a') }}; color: #fff;">
                    Resend Link
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Personal Information --}}
    <div class="card">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: {{ \App\Models\Setting::get('brand_color', '#e05a3a') }}20;">
                <svg class="w-6 h-6" style="color: {{ \App\Models\Setting::get('brand_color', '#e05a3a') }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-[#1a1a2e]">Personal Information</h3>
                <p class="text-sm text-[#6b7280]">Update your personal details</p>
            </div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="form-input @error('name') border-red-400 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="username" class="form-label">Username</label>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-400">{{ url('/c/') }}/</span>
                        <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required class="form-input flex-1 @error('username') border-red-400 @enderror" placeholder="your-username" pattern="[a-z0-9\-]+">
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Lowercase letters, numbers, and hyphens only. This is your public profile URL.</p>
                    @error('username')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="bio" class="form-label">Bio</label>
                    <textarea id="bio" name="bio" rows="3" maxlength="500" class="form-input @error('bio') border-red-400 @enderror" placeholder="Tell people a bit about yourself...">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input @error('email') border-red-400 @enderror">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input @error('phone') border-red-400 @enderror" placeholder="e.g. +44 7700 900000">
                    @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}" class="form-input @error('date_of_birth') border-red-400 @enderror">
                    @error('date_of_birth')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Role</label>
                    <input type="text" value="{{ ucfirst($user->role ?? 'Learner') }}" disabled class="form-input bg-gray-50 text-[#6b7280] cursor-not-allowed">
                </div>
            </div>

            <div class="pt-2 border-t border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Address</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label for="address" class="form-label">Street Address</label>
                        <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}" class="form-input @error('address') border-red-400 @enderror" placeholder="e.g. 123 High Street">
                        @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="city" class="form-label">City</label>
                        <input type="text" id="city" name="city" value="{{ old('city', $user->city) }}" class="form-input @error('city') border-red-400 @enderror" placeholder="e.g. London">
                        @error('city')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="state" class="form-label">State / County</label>
                        <input type="text" id="state" name="state" value="{{ old('state', $user->state) }}" class="form-input @error('state') border-red-400 @enderror" placeholder="e.g. Greater London">
                        @error('state')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="postal_code" class="form-label">Postal Code</label>
                        <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" class="form-input @error('postal_code') border-red-400 @enderror" placeholder="e.g. SW1A 1AA">
                        @error('postal_code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="country" class="form-label">Country</label>
                        <input type="text" id="country" name="country" value="{{ old('country', $user->country) }}" class="form-input @error('country') border-red-400 @enderror" placeholder="e.g. United Kingdom">
                        @error('country')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
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
