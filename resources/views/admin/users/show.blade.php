@extends('layouts.app')

@section('title', $user->name . ' — User Profile')
@section('page_title', 'User Profile')
@section('page_subtitle', $user->email)

@section('content')

{{-- Back + Actions --}}
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Users
    </a>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary text-sm py-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit User
        </a>
        @if($user->id !== auth()->id())
        <form method="POST" action="{{ route('admin.users.impersonate', $user) }}">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition-colors" onclick="return confirm('Impersonate {{ $user->name }}?')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Impersonate
            </button>
        </form>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Left Column: User Info --}}
    <div class="space-y-5">
        {{-- Profile Card --}}
        <div class="card text-center">
            <div class="w-20 h-20 rounded-full bg-[#1a2535] flex items-center justify-center text-white font-bold text-3xl mx-auto mb-4">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h2 class="text-xl font-bold text-[#1a1a2e]">{{ $user->name }}</h2>
            <p class="text-sm text-gray-400">{{ $user->email }}</p>
            <div class="mt-3">
                <span class="badge {{ $user->isSuperAdmin() ? 'bg-purple-100 text-purple-700' : ($user->isAdmin() ? 'bg-blue-100 text-blue-700' : ($user->role === 'mentor' ? 'bg-teal-100 text-teal-700' : ($user->role === 'employer' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-700'))) }}">
                    {{ ucfirst($user->role ?? 'learner') }}
                </span>
                @if($user->hasVerifiedEmail())
                <span class="badge bg-green-100 text-green-700 ml-1">Verified</span>
                @else
                <span class="badge bg-red-100 text-red-700 ml-1">Unverified</span>
                @endif
            </div>
        </div>

        {{-- Personal Details --}}
        <div class="card">
            <h3 class="text-sm font-bold text-[#1a1a2e] mb-3">Personal Details</h3>
            <dl class="space-y-2 text-sm">
                @if($user->phone)
                <div class="flex justify-between">
                    <dt class="text-gray-400">Phone</dt>
                    <dd class="font-medium text-[#1a1a2e]">{{ $user->phone }}</dd>
                </div>
                @endif
                @if($user->date_of_birth)
                <div class="flex justify-between">
                    <dt class="text-gray-400">Date of Birth</dt>
                    <dd class="font-medium text-[#1a1a2e]">{{ \Carbon\Carbon::parse($user->date_of_birth)->format('M d, Y') }}</dd>
                </div>
                @endif
                @if($user->city || $user->state || $user->country)
                <div class="flex justify-between">
                    <dt class="text-gray-400">Location</dt>
                    <dd class="font-medium text-[#1a1a2e]">{{ collect([$user->city, $user->state, $user->country])->filter()->implode(', ') }}</dd>
                </div>
                @endif
                @if($user->address)
                <div class="flex justify-between">
                    <dt class="text-gray-400">Address</dt>
                    <dd class="font-medium text-[#1a1a2e] text-right max-w-[60%]">{{ $user->address }}</dd>
                </div>
                @endif
                <div class="flex justify-between">
                    <dt class="text-gray-400">Joined</dt>
                    <dd class="font-medium text-[#1a1a2e]">{{ $user->created_at->format('M d, Y') }}</dd>
                </div>
                @if($user->referral_code)
                <div class="flex justify-between">
                    <dt class="text-gray-400">Referral Code</dt>
                    <dd class="font-mono text-xs font-medium text-[#e05a3a]">{{ $user->referral_code }}</dd>
                </div>
                @endif
                @if($user->referrer)
                <div class="flex justify-between">
                    <dt class="text-gray-400">Referred By</dt>
                    <dd class="font-medium text-[#1a1a2e]">
                        <a href="{{ route('admin.users.show', $user->referrer) }}" class="text-[#e05a3a] hover:underline">{{ $user->referrer->name }}</a>
                    </dd>
                </div>
                @endif
            </dl>
        </div>

        {{-- Financial Summary --}}
        <div class="card">
            <h3 class="text-sm font-bold text-[#1a1a2e] mb-3">Financial Summary</h3>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-400">Total Spent</dt>
                    <dd class="font-bold text-[#1a1a2e]">{{ $currencySymbol }}{{ number_format($totalSpent, 2) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-400">Wallet Balance</dt>
                    <dd class="font-bold text-green-600">{{ $currencySymbol }}{{ number_format($user->wallet_balance, 2) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-400">Total Earnings</dt>
                    <dd class="font-bold text-[#1a1a2e]">{{ $currencySymbol }}{{ number_format($totalEarnings, 2) }}</dd>
                </div>
            </dl>
        </div>

        {{-- Bank Details --}}
        @if($user->bank_name || $user->bank_account_name)
        <div class="card">
            <h3 class="text-sm font-bold text-[#1a1a2e] mb-3">Bank Details</h3>
            <dl class="space-y-2 text-sm">
                @if($user->bank_name)
                <div class="flex justify-between">
                    <dt class="text-gray-400">Bank</dt>
                    <dd class="font-medium text-[#1a1a2e]">{{ $user->bank_name }}</dd>
                </div>
                @endif
                @if($user->bank_account_name)
                <div class="flex justify-between">
                    <dt class="text-gray-400">Account Name</dt>
                    <dd class="font-medium text-[#1a1a2e]">{{ $user->bank_account_name }}</dd>
                </div>
                @endif
                @if($user->bank_account_number)
                <div class="flex justify-between">
                    <dt class="text-gray-400">Account No.</dt>
                    <dd class="font-mono text-xs font-medium text-[#1a1a2e]">{{ $user->bank_account_number }}</dd>
                </div>
                @endif
                @if($user->bank_sort_code)
                <div class="flex justify-between">
                    <dt class="text-gray-400">Sort Code</dt>
                    <dd class="font-mono text-xs font-medium text-[#1a1a2e]">{{ $user->bank_sort_code }}</dd>
                </div>
                @endif
            </dl>
        </div>
        @endif
    </div>

    {{-- Right Column: Activity --}}
    <div class="lg:col-span-2 space-y-5">
        {{-- Stats Row --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="stat-card">
                <div class="stat-icon bg-blue-50 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <div>
                    <p class="text-lg font-bold text-[#1a1a2e]">{{ $enrolledCohorts->count() }}</p>
                    <p class="text-[11px] text-gray-400">Cohorts</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-green-50 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <div>
                    <p class="text-lg font-bold text-[#1a1a2e]">{{ $user->payments_count }}</p>
                    <p class="text-[11px] text-gray-400">Payments</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-purple-50 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </div>
                <div>
                    <p class="text-lg font-bold text-[#1a1a2e]">{{ $user->product_purchases_count }}</p>
                    <p class="text-[11px] text-gray-400">Purchases</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon bg-orange-50 text-orange-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                </div>
                <div>
                    <p class="text-lg font-bold text-[#1a1a2e]">{{ $user->referrals_count }}</p>
                    <p class="text-[11px] text-gray-400">Referrals</p>
                </div>
            </div>
        </div>

        {{-- Enrolled Cohorts --}}
        <div class="card">
            <h3 class="text-sm font-bold text-[#1a1a2e] mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Enrolled Cohorts
            </h3>
            @if($enrolledCohorts->count() > 0)
            <div class="space-y-2">
                @foreach($enrolledCohorts as $cohort)
                <div class="flex items-center justify-between bg-gray-50 rounded-xl px-4 py-3">
                    <div>
                        <p class="text-sm font-semibold text-[#1a1a2e]">{{ $cohort->title }}</p>
                        <p class="text-xs text-gray-400">Enrolled {{ $cohort->pivot->enrolled_at ? \Carbon\Carbon::parse($cohort->pivot->enrolled_at)->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <span class="badge {{ $cohort->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ ucfirst($cohort->status) }}
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-400 text-center py-4">No cohort enrollments</p>
            @endif
        </div>

        {{-- Product Purchases --}}
        <div class="card">
            <h3 class="text-sm font-bold text-[#1a1a2e] mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Product Purchases
            </h3>
            @if($purchases->count() > 0)
            <div class="space-y-2">
                @foreach($purchases as $purchase)
                @if($purchase->product)
                <div class="flex items-center justify-between bg-gray-50 rounded-xl px-4 py-3">
                    <div>
                        <p class="text-sm font-semibold text-[#1a1a2e]">{{ $purchase->product->title }}</p>
                        <p class="text-xs text-gray-400">Purchased {{ $purchase->purchased_at->format('M d, Y') }} &middot; Downloaded {{ $purchase->download_count }} {{ Str::plural('time', $purchase->download_count) }}</p>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-400 text-center py-4">No product purchases</p>
            @endif
        </div>

        {{-- Recent Payments --}}
        <div class="card">
            <h3 class="text-sm font-bold text-[#1a1a2e] mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Recent Payments
            </h3>
            @if($payments->count() > 0)
            <div class="overflow-x-auto -mx-6">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="px-6 py-2 text-[11px] font-semibold text-gray-400 uppercase">For</th>
                            <th class="px-6 py-2 text-[11px] font-semibold text-gray-400 uppercase">Amount</th>
                            <th class="px-6 py-2 text-[11px] font-semibold text-gray-400 uppercase">Gateway</th>
                            <th class="px-6 py-2 text-[11px] font-semibold text-gray-400 uppercase">Status</th>
                            <th class="px-6 py-2 text-[11px] font-semibold text-gray-400 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($payments as $payment)
                        <tr>
                            <td class="px-6 py-2.5 text-xs text-[#1a1a2e] font-medium">{{ $payment->payable?->title ?? 'N/A' }}</td>
                            <td class="px-6 py-2.5 text-xs font-bold text-[#1a1a2e]">{{ $currencySymbol }}{{ number_format($payment->amount, 2) }}</td>
                            <td class="px-6 py-2.5 text-xs text-gray-500">{{ ucfirst($payment->gateway ?? '-') }}</td>
                            <td class="px-6 py-2.5">
                                <span class="badge {{ $payment->status === 'completed' ? 'bg-green-100 text-green-700' : ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-2.5 text-xs text-gray-400">{{ $payment->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-sm text-gray-400 text-center py-4">No payments</p>
            @endif
        </div>

        {{-- Referrals --}}
        <div class="card">
            <h3 class="text-sm font-bold text-[#1a1a2e] mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                Referrals ({{ $user->referrals_count }})
            </h3>
            @if($referrals->count() > 0)
            <div class="space-y-2">
                @foreach($referrals as $referral)
                <div class="flex items-center justify-between bg-gray-50 rounded-xl px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-[#1a2535] flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr($referral->name, 0, 1)) }}
                        </div>
                        <div>
                            <a href="{{ route('admin.users.show', $referral) }}" class="text-sm font-semibold text-[#1a1a2e] hover:text-[#e05a3a]">{{ $referral->name }}</a>
                            <p class="text-xs text-gray-400">{{ $referral->email }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-400">{{ $referral->created_at->format('M d, Y') }}</span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-400 text-center py-4">No referrals</p>
            @endif
        </div>
    </div>
</div>

@endsection
