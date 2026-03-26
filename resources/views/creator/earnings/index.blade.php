@extends('layouts.app')

@section('title', 'Creator Earnings')
@section('page_title', 'Earnings')
@section('page_subtitle', 'Track your creator earnings and commissions')

@section('content')

{{-- Summary Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="card flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Earnings</p>
            <p class="text-2xl font-extrabold text-green-600">{{ $currencySymbol }}{{ number_format($totalEarnings, 2) }}</p>
        </div>
    </div>

    <div class="card flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Pending</p>
            <p class="text-2xl font-extrabold text-amber-600">{{ $currencySymbol }}{{ number_format($pendingEarnings, 2) }}</p>
        </div>
    </div>

    <div class="card flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Paid Out</p>
            <p class="text-2xl font-extrabold text-blue-600">{{ $currencySymbol }}{{ number_format($paidEarnings, 2) }}</p>
        </div>
    </div>

    <div class="card flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Wallet Balance</p>
            <p class="text-2xl font-extrabold text-[#1a1a2e]">{{ $currencySymbol }}{{ number_format($walletBalance, 2) }}</p>
        </div>
    </div>
</div>

<div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 mb-8 flex items-start gap-3">
    <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <p class="text-sm text-gray-600">Earnings are credited to your wallet balance. You can request a withdrawal from the <a href="{{ route('referrals.index') }}" class="text-[#e05a3a] hover:underline font-medium">Referrals page</a>.</p>
</div>

{{-- Earnings Table --}}
<div class="card overflow-hidden !p-0">
    <div class="px-6 py-4 border-b border-[#e8eaf0]">
        <h3 class="text-sm font-bold text-[#1a1a2e]">Earnings History</h3>
    </div>
    @if($earnings->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Date</th>
                    <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Item</th>
                    <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Rate</th>
                    <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Amount</th>
                    <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($earnings as $earning)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-6 py-4 text-gray-600">{{ $earning->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 font-medium text-[#1a1a2e]">
                        {{ $earning->payment->payable->title ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $earning->commission_rate }}%</td>
                    <td class="px-6 py-4 font-semibold text-green-600">{{ $currencySymbol }}{{ number_format($earning->amount, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $earning->status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($earning->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($earnings->hasPages())
    <div class="px-6 py-4 border-t border-[#e8eaf0]">
        {{ $earnings->links() }}
    </div>
    @endif
    @else
    <div class="px-6 py-12 text-center text-gray-400">
        <p class="text-sm">No earnings yet. Start selling products and cohorts to earn commissions!</p>
    </div>
    @endif
</div>

@endsection
