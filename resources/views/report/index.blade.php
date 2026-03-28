@extends('layouts.app')

@section('title', 'My Report')
@section('page_title', 'My Report')
@section('page_subtitle', 'Your activity and spending overview')

@section('content')

{{-- Period Filter --}}
<div class="flex items-center justify-end gap-2 mb-6">
    <span class="text-xs text-gray-400">Period:</span>
    @foreach(['7' => '7 days', '30' => '30 days', '90' => '90 days', '365' => '1 year'] as $val => $label)
    <a href="{{ route('user.report', ['period' => $val]) }}"
       class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ $period == $val ? 'bg-[#1a2535] text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

{{-- Customer Summary Cards --}}
<div class="mb-8">
    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Customer Overview</h3>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Spent</p>
            <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ $currencySymbol }}{{ number_format($totalSpent, 2) }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $currencySymbol }}{{ number_format($periodSpent, 2) }} in period</p>
        </div>
        <div class="card">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Enrollments</p>
            <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ $totalEnrollments }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $periodEnrollments }} in period</p>
        </div>
        <div class="card">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Products Purchased</p>
            <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ $totalPurchases }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $periodPurchases }} in period</p>
        </div>
        <div class="card">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Referrals</p>
            <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ $totalReferrals }}</p>
            <p class="text-xs text-emerald-500 mt-1">{{ $currencySymbol }}{{ number_format($referralEarningsTotal, 2) }} earned</p>
        </div>
    </div>
</div>

{{-- Spending Chart --}}
@if(count($spendingChart) > 0)
<div class="card mb-8">
    <h3 class="text-sm font-bold text-[#1a1a2e] mb-4">Spending Over Time</h3>
    <div class="h-48 flex items-end gap-1">
        @php $maxSpend = max($spendingChart) ?: 1; @endphp
        @foreach($spendingChart as $date => $total)
        <div class="flex-1 group relative">
            <div class="bg-[#e05a3a]/80 hover:bg-[#e05a3a] rounded-t transition-colors"
                 style="height: {{ ($total / $maxSpend) * 100 }}%;"
                 title="{{ \Carbon\Carbon::parse($date)->format('M d') }}: {{ $currencySymbol }}{{ number_format($total, 2) }}"></div>
            <div class="hidden group-hover:block absolute bottom-full left-1/2 -translate-x-1/2 mb-1 bg-gray-800 text-white text-[10px] px-2 py-1 rounded whitespace-nowrap z-10">
                {{ \Carbon\Carbon::parse($date)->format('M d') }}: {{ $currencySymbol }}{{ number_format($total, 2) }}
            </div>
        </div>
        @endforeach
    </div>
    <div class="flex justify-between mt-2">
        <span class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse(array_key_first($spendingChart))->format('M d') }}</span>
        <span class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse(array_key_last($spendingChart))->format('M d') }}</span>
    </div>
</div>
@endif

{{-- Creator Section --}}
@if($creatorData)
<div class="mb-8">
    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Creator Overview</h3>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Revenue</p>
            <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $currencySymbol }}{{ number_format($creatorData['totalRevenue'], 2) }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $currencySymbol }}{{ number_format($creatorData['periodRevenue'], 2) }} in period</p>
        </div>
        <div class="card">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Students</p>
            <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ $creatorData['totalStudents'] }}</p>
        </div>
        <div class="card">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Product Sales</p>
            <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ $creatorData['totalProductSales'] }}</p>
        </div>
        <div class="card">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Wallet Balance</p>
            <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ $currencySymbol }}{{ number_format($user->wallet_balance, 2) }}</p>
        </div>
    </div>
</div>

{{-- Creator Revenue Chart --}}
@if(count($creatorData['revenueChart']) > 0)
<div class="card mb-8">
    <h3 class="text-sm font-bold text-[#1a1a2e] mb-4">Creator Revenue Over Time</h3>
    <div class="h-48 flex items-end gap-1">
        @php $maxRev = max($creatorData['revenueChart']) ?: 1; @endphp
        @foreach($creatorData['revenueChart'] as $date => $total)
        <div class="flex-1 group relative">
            <div class="bg-emerald-500/80 hover:bg-emerald-500 rounded-t transition-colors"
                 style="height: {{ ($total / $maxRev) * 100 }}%;"
                 title="{{ \Carbon\Carbon::parse($date)->format('M d') }}: {{ $currencySymbol }}{{ number_format($total, 2) }}"></div>
            <div class="hidden group-hover:block absolute bottom-full left-1/2 -translate-x-1/2 mb-1 bg-gray-800 text-white text-[10px] px-2 py-1 rounded whitespace-nowrap z-10">
                {{ \Carbon\Carbon::parse($date)->format('M d') }}: {{ $currencySymbol }}{{ number_format($total, 2) }}
            </div>
        </div>
        @endforeach
    </div>
    <div class="flex justify-between mt-2">
        <span class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse(array_key_first($creatorData['revenueChart']))->format('M d') }}</span>
        <span class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse(array_key_last($creatorData['revenueChart']))->format('M d') }}</span>
    </div>
</div>
@endif
@endif

{{-- Recent Payments --}}
@if($recentPayments->count() > 0)
<div class="mb-8">
    <h3 class="text-sm font-bold text-[#1a1a2e] mb-4">Recent Payments</h3>
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Item</th>
                        <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Amount</th>
                        <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($recentPayments as $payment)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-6 py-4 text-gray-600">{{ $payment->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 font-medium text-[#1a1a2e]">
                            @if($payment->payable)
                                {{ $payment->payable->title ?? $payment->payable->name ?? 'N/A' }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 font-semibold text-[#1a1a2e]">{{ $currencySymbol }}{{ number_format($payment->amount, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $payment->status === 'completed' ? 'bg-green-100 text-green-700' : ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@endsection
