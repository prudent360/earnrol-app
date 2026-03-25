@extends('layouts.app')

@section('title', 'Edit User')
@section('page_title', 'Edit User')
@section('page_subtitle', 'Update user information and permissions')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Users
        </a>
    </div>

    {{-- User Details --}}
    <div class="card mb-6">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" name="name" id="name" class="form-input @error('name') border-red-500 @enderror" value="{{ old('name', $user->name) }}" required>
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-input @error('email') border-red-500 @enderror" value="{{ old('email', $user->email) }}" required>
                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="form-label">Password <span class="text-xs font-normal text-gray-400">(Leave blank to keep current)</span></label>
                    <input type="password" name="password" id="password" class="form-input @error('password') border-red-500 @enderror" placeholder="••••••••">
                    @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" placeholder="••••••••">
                </div>
            </div>

            <div>
                <label for="role" class="form-label">User Role</label>
                <select name="role" id="role" class="form-input @error('role') border-red-500 @enderror" required>
                    <option value="learner" {{ old('role', $user->role) == 'learner' ? 'selected' : '' }}>Learner</option>
                    <option value="mentor" {{ old('role', $user->role) == 'mentor' ? 'selected' : '' }}>Mentor</option>
                    <option value="employer" {{ old('role', $user->role) == 'employer' ? 'selected' : '' }}>Employer</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="superadmin" {{ old('role', $user->role) == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                </select>
                @error('role') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 border-t border-[#e8eaf0] flex justify-end">
                <button type="submit" class="btn-primary">
                    Update User
                </button>
            </div>
        </form>
    </div>

    {{-- Wallet & Commission --}}
    <div class="card mb-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: {{ \App\Models\Setting::get('brand_color', '#e05a3a') }}20;">
                <svg class="w-6 h-6" style="color: {{ \App\Models\Setting::get('brand_color', '#e05a3a') }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-[#1a1a2e]">Wallet & Commission</h3>
                <p class="text-sm text-[#6b7280]">Manage this user's wallet balance</p>
            </div>
        </div>

        {{-- Current Balance --}}
        <div class="bg-[#f5f6fa] rounded-xl p-4 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Current Wallet Balance</p>
                    <p class="text-3xl font-bold text-[#1a1a2e] mt-1">{{ \App\Models\Setting::get('currency_symbol', '£') }}{{ number_format($user->wallet_balance, 2) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400">Total Earnings</p>
                    <p class="text-sm font-bold text-[#1a1a2e]">{{ \App\Models\Setting::get('currency_symbol', '£') }}{{ number_format($user->referralEarnings()->sum('amount'), 2) }}</p>
                </div>
            </div>
        </div>

        {{-- Credit Form --}}
        <form action="{{ route('admin.users.credit-wallet', $user) }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="amount" class="form-label">Amount ({{ \App\Models\Setting::get('currency_symbol', '£') }})</label>
                    <input type="number" name="amount" id="amount" step="0.01" min="0.01" required
                           class="form-input @error('amount') border-red-500 @enderror"
                           placeholder="e.g. 50.00" value="{{ old('amount') }}">
                    @error('amount') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="note" class="form-label">Note <span class="text-xs font-normal text-gray-400">(Optional)</span></label>
                    <input type="text" name="note" id="note"
                           class="form-input @error('note') border-red-500 @enderror"
                           placeholder="e.g. Bonus for top referrer" value="{{ old('note') }}">
                    @error('note') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <button type="submit" class="btn-primary py-2.5 text-sm" onclick="return confirm('Credit this amount to {{ $user->name }}\'s wallet?')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Credit Wallet
            </button>
        </form>
    </div>

    {{-- Transaction History --}}
    @php
        $transactions = $user->referralEarnings()->with('referredUser')->latest()->take(10)->get();
    @endphp
    @if($transactions->count() > 0)
    <div class="card">
        <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">Recent Transactions</h3>
        <div class="overflow-x-auto -mx-5">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f5f6fa] border-b border-[#e8eaf0]">
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Note</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e8eaf0]">
                    @foreach($transactions as $txn)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 text-sm text-gray-500">{{ $txn->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-5 py-3">
                            @if($txn->payment_id)
                                <span class="badge bg-green-100 text-green-700">Referral</span>
                            @else
                                <span class="badge bg-blue-100 text-blue-700">Manual Credit</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-sm font-bold text-[#1a1a2e]">+{{ \App\Models\Setting::get('currency_symbol', '£') }}{{ number_format($txn->amount, 2) }}</td>
                        <td class="px-5 py-3 text-sm text-gray-500">{{ $txn->note ?? ($txn->payment_id ? 'Commission from ' . ($txn->referredUser->name ?? 'user') : '—') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
