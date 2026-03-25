@extends('layouts.app')

@section('title', 'Referrals')
@section('page_title', 'Referrals')
@section('page_subtitle', 'Refer friends and earn commission')

@section('content')

{{-- Referral Link & Wallet Overview --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">
    {{-- Referral Link Card --}}
    <div class="lg:col-span-2 rounded-2xl p-6 text-white relative overflow-hidden" style="background: linear-gradient(135deg, #1a2535 0%, #243347 50%, #e05a3a 100%);">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute right-32 bottom-0 w-28 h-28 bg-[#e05a3a]/30 rounded-full blur-2xl"></div>
        <div class="absolute left-1/3 -top-8 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
        <div class="relative">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-[#e05a3a]/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-lg">Refer & Earn</h3>
                    <p class="text-gray-400 text-xs">Share your link and earn {{ $commissionRate }}% commission on your friend's first payment</p>
                </div>
            </div>

            <div class="mt-4">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Your Referral Code</label>
                <div class="mt-1.5 flex items-center gap-2">
                    <div class="flex-1 bg-white/10 border border-white/20 rounded-xl px-4 py-3 font-mono text-lg font-bold tracking-widest">
                        {{ $user->referral_code ?? 'N/A' }}
                    </div>
                    <button onclick="copyToClipboard('{{ $user->referralLink() }}')" class="bg-[#e05a3a] hover:bg-[#c94e31] text-white px-4 py-3 rounded-xl transition-colors flex items-center gap-2 text-sm font-bold flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                        Copy Link
                    </button>
                </div>
                <p class="text-[10px] text-gray-500 mt-2 truncate">{{ $user->referralLink() }}</p>
            </div>
        </div>
    </div>

    {{-- Wallet Balance --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Wallet Balance</p>
        <p class="text-3xl font-extrabold text-[#1a1a2e]">{{ $currencySymbol }}{{ number_format($user->wallet_balance, 2) }}</p>
        <div class="mt-4 grid grid-cols-2 gap-3">
            <div class="bg-gray-50 rounded-xl p-3 text-center">
                <p class="text-[10px] font-bold text-gray-400 uppercase">Referrals</p>
                <p class="text-xl font-bold text-[#1a1a2e]">{{ $referrals->count() }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-3 text-center">
                <p class="text-[10px] font-bold text-gray-400 uppercase">Earned</p>
                <p class="text-xl font-bold text-green-600">{{ $currencySymbol }}{{ number_format($earnings->sum('amount'), 2) }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Bank Details & Withdrawal --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-8">
    {{-- Bank Details --}}
    <div class="card">
        <h3 class="text-sm font-bold text-[#1a1a2e] mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            Bank Details
        </h3>
        <form method="POST" action="{{ route('referrals.bank-details') }}" class="space-y-3">
            @csrf
            @method('PUT')
            <div>
                <label class="form-label text-xs">Bank Name</label>
                <input type="text" name="bank_name" value="{{ old('bank_name', $user->bank_name) }}" class="form-input" placeholder="e.g. Barclays">
                @error('bank_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="form-label text-xs">Account Name</label>
                <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $user->bank_account_name) }}" class="form-input" placeholder="e.g. John Doe">
                @error('bank_account_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="form-label text-xs">Account Number</label>
                <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $user->bank_account_number) }}" class="form-input" placeholder="e.g. 12345678">
                @error('bank_account_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="form-label text-xs">Sort Code</label>
                <input type="text" name="bank_sort_code" value="{{ old('bank_sort_code', $user->bank_sort_code) }}" class="form-input" placeholder="e.g. 12-34-56">
                @error('bank_sort_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="btn-primary text-sm w-full justify-center py-2.5">Save Bank Details</button>
        </form>
    </div>

    {{-- Request Withdrawal --}}
    <div class="card">
        <h3 class="text-sm font-bold text-[#1a1a2e] mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Request Withdrawal
        </h3>

        @if(!$user->bank_name || !$user->bank_account_number)
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-700">
            Please add your bank details first before requesting a withdrawal.
        </div>
        @elseif($user->wallet_balance < $minWithdrawal)
        <div class="bg-gray-50 rounded-xl p-4 text-center">
            <p class="text-sm text-gray-500">Minimum withdrawal: <span class="font-bold text-[#1a1a2e]">{{ $currencySymbol }}{{ number_format($minWithdrawal, 2) }}</span></p>
            <p class="text-xs text-gray-400 mt-1">Your balance: {{ $currencySymbol }}{{ number_format($user->wallet_balance, 2) }}</p>
        </div>
        @else
        <form method="POST" action="{{ route('referrals.withdraw') }}" class="space-y-4">
            @csrf
            <div>
                <label class="form-label text-xs">Amount ({{ $currencySymbol }})</label>
                <input type="number" name="amount" step="0.01" min="{{ $minWithdrawal }}" max="{{ $user->wallet_balance }}" value="{{ old('amount') }}" class="form-input" placeholder="Enter amount">
                @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <p class="text-[10px] text-gray-400 mt-1">Available: {{ $currencySymbol }}{{ number_format($user->wallet_balance, 2) }} | Min: {{ $currencySymbol }}{{ number_format($minWithdrawal, 2) }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-3 text-xs text-gray-500">
                <p><span class="font-semibold text-gray-700">Paying to:</span> {{ $user->bank_account_name }} — {{ $user->bank_name }}</p>
                <p>Account: {{ $user->bank_account_number }} @if($user->bank_sort_code) | Sort: {{ $user->bank_sort_code }} @endif</p>
            </div>
            <button type="submit" class="btn-primary text-sm w-full justify-center py-2.5" onclick="return confirm('Are you sure you want to request this withdrawal?')">Request Withdrawal</button>
        </form>
        @endif
    </div>
</div>

{{-- Earnings History --}}
@if($earnings->count() > 0)
<div class="mb-8">
    <h3 class="text-sm font-bold text-[#1a1a2e] mb-4">Earnings History</h3>
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Referred User</th>
                        <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Rate</th>
                        <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Commission</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($earnings as $earning)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-6 py-4 text-gray-600">{{ $earning->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 font-medium text-[#1a1a2e]">{{ $earning->referredUser->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $earning->commission_rate }}%</td>
                        <td class="px-6 py-4 font-semibold text-green-600">{{ $currencySymbol }}{{ number_format($earning->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

{{-- Withdrawal History --}}
@if($withdrawals->count() > 0)
<div class="mb-8">
    <h3 class="text-sm font-bold text-[#1a1a2e] mb-4">Withdrawal History</h3>
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Amount</th>
                        <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Note</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($withdrawals as $withdrawal)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-6 py-4 text-gray-600">{{ $withdrawal->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 font-semibold text-[#1a1a2e]">{{ $currencySymbol }}{{ number_format($withdrawal->amount, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $withdrawal->status === 'approved' ? 'bg-green-100 text-green-700' : ($withdrawal->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                {{ ucfirst($withdrawal->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500">{{ $withdrawal->admin_note ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

{{-- Referrals List --}}
@if($referrals->count() > 0)
<div>
    <h3 class="text-sm font-bold text-[#1a1a2e] mb-4">People You Referred</h3>
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Name</th>
                        <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($referrals as $referral)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-6 py-4 font-medium text-[#1a1a2e]">{{ $referral->name }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $referral->created_at->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Referral link copied!');
    });
}
</script>

@endsection
