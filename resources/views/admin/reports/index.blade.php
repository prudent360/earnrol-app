@extends('layouts.app')

@section('title', 'Reports')
@section('page_title', 'Reports')
@section('page_subtitle', 'Analytics and insights for your platform')

@section('content')
<div class="mb-8">
    {{-- Tab Navigation --}}
    <div class="flex items-center gap-1 border-b border-gray-200 overflow-x-auto pb-px">
        @php
        $tabs = [
            'overview'  => ['label' => 'Overview',   'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
            'revenue'   => ['label' => 'Revenue',    'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            'users'     => ['label' => 'Users',      'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
            'cohorts'   => ['label' => 'Cohorts',    'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
            'products'  => ['label' => 'Products',   'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
            'referrals' => ['label' => 'Referrals',  'icon' => 'M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7'],
        ];
        @endphp

        @foreach($tabs as $key => $meta)
        <a href="{{ route('admin.reports.index', ['tab' => $key, 'period' => $period]) }}"
           class="flex items-center gap-2 px-5 py-4 text-sm font-medium transition-colors border-b-2 whitespace-nowrap
                  {{ $tab === $key ? 'border-[#e05a3a] text-[#e05a3a]' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $meta['icon'] }}"/>
            </svg>
            {{ $meta['label'] }}
        </a>
        @endforeach
    </div>
</div>

{{-- Period Filter --}}
<div class="flex items-center justify-end gap-2 mb-6">
    <span class="text-xs text-gray-400">Period:</span>
    @foreach(['7' => '7 days', '30' => '30 days', '90' => '90 days', '365' => '1 year'] as $val => $label)
    <a href="{{ route('admin.reports.index', ['tab' => $tab, 'period' => $val]) }}"
       class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ $period == $val ? 'bg-[#1a2535] text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

@php $currencySymbol = \App\Models\Setting::get('currency_symbol', '£'); @endphp


{{-- ============================================================
     OVERVIEW TAB
============================================================ --}}
@if($tab === 'overview')

{{-- Summary Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Revenue</p>
        <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ $currencySymbol }}{{ number_format($totalRevenue, 2) }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $currencySymbol }}{{ number_format($periodRevenue, 2) }} in period</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Users</p>
        <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ number_format($totalUsers) }}</p>
        <p class="text-xs text-gray-400 mt-1">+{{ $newUsers }} new in period</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Cohort Enrollments</p>
        <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ number_format($totalEnrollments) }}</p>
        <p class="text-xs text-gray-400 mt-1">+{{ $periodEnrollments }} in period</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Product Sales</p>
        <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ number_format($totalProductSales) }}</p>
        <p class="text-xs text-gray-400 mt-1">+{{ $periodProductSales }} in period</p>
    </div>
</div>

{{-- Charts --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="card">
        <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">Revenue Trend</h3>
        <div class="h-48" id="revenue-chart"></div>
        @if(empty($revenueChart))
        <p class="text-sm text-gray-400 text-center py-8">No revenue data for this period.</p>
        @endif
    </div>
    <div class="card">
        <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">User Signups</h3>
        <div class="h-48" id="signups-chart"></div>
        @if(empty($signupsChart))
        <p class="text-sm text-gray-400 text-center py-8">No signup data for this period.</p>
        @endif
    </div>
</div>

{{-- Recent Activity --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card">
        <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">Recent Payments</h3>
        <div class="space-y-3">
            @forelse($recentPayments as $payment)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-[#1a1a2e]">{{ $payment->user->name ?? 'Unknown' }}</p>
                        <p class="text-[10px] text-gray-400">{{ $payment->created_at->diffForHumans() }} &middot; {{ ucfirst($payment->gateway ?? 'N/A') }}</p>
                    </div>
                </div>
                <span class="text-sm font-bold text-green-600">{{ $currencySymbol }}{{ number_format($payment->amount, 2) }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">No payments yet.</p>
            @endforelse
        </div>
    </div>

    <div class="card">
        <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">Recent Signups</h3>
        <div class="space-y-3">
            @forelse($recentSignups as $user)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center">
                        <span class="text-xs font-bold text-blue-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-[#1a1a2e]">{{ $user->name }}</p>
                        <p class="text-[10px] text-gray-400">{{ $user->email }}</p>
                    </div>
                </div>
                <span class="text-[10px] text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">No users yet.</p>
            @endforelse
        </div>
    </div>
</div>


{{-- ============================================================
     REVENUE TAB
============================================================ --}}
@elseif($tab === 'revenue')

{{-- Summary Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Revenue</p>
        <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ $currencySymbol }}{{ number_format($totalRevenue, 2) }}</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Period Revenue</p>
        <p class="text-2xl font-extrabold text-green-600 mt-1">{{ $currencySymbol }}{{ number_format($periodRevenue, 2) }}</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Pending</p>
        <p class="text-2xl font-extrabold text-amber-500 mt-1">{{ $currencySymbol }}{{ number_format($pendingRevenue, 2) }}</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Cohort vs Product</p>
        <div class="flex items-center gap-2 mt-2">
            <span class="text-xs font-bold text-blue-600">{{ $currencySymbol }}{{ number_format($cohortRevenue, 2) }}</span>
            <span class="text-[10px] text-gray-300">/</span>
            <span class="text-xs font-bold text-purple-600">{{ $currencySymbol }}{{ number_format($productRevenue, 2) }}</span>
        </div>
        <p class="text-[10px] text-gray-400 mt-1">Cohorts / Products</p>
    </div>
</div>

{{-- Revenue Chart --}}
<div class="card mb-8">
    <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">Revenue Over Time</h3>
    <div class="h-48" id="revenue-chart"></div>
    @if(empty($revenueChart))
    <p class="text-sm text-gray-400 text-center py-8">No revenue data for this period.</p>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    {{-- By Gateway --}}
    <div class="card">
        <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">Revenue by Gateway</h3>
        <div class="space-y-3">
            @forelse($byGateway as $gw)
            @php
                $gwColors = ['stripe' => 'bg-[#635BFF]', 'paypal' => 'bg-[#003087]', 'bank_transfer' => 'bg-gray-700'];
                $gwColor = $gwColors[$gw->gateway] ?? 'bg-gray-400';
                $pct = $periodRevenue > 0 ? ($gw->total / $periodRevenue) * 100 : 0;
            @endphp
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full {{ $gwColor }}"></div>
                        <span class="text-sm font-medium text-[#1a1a2e]">{{ ucfirst(str_replace('_', ' ', $gw->gateway)) }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-bold text-[#1a1a2e]">{{ $currencySymbol }}{{ number_format($gw->total, 2) }}</span>
                        <span class="text-[10px] text-gray-400 ml-1">({{ $gw->count }})</span>
                    </div>
                </div>
                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full {{ $gwColor }} rounded-full" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">No gateway data for this period.</p>
            @endforelse
        </div>
    </div>

    {{-- Payment Status --}}
    <div class="card">
        <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">Payment Status Breakdown</h3>
        <div class="space-y-3">
            @forelse($statusBreakdown as $sb)
            @php
                $statusColors = ['completed' => 'text-green-600 bg-green-50', 'pending' => 'text-amber-600 bg-amber-50', 'failed' => 'text-red-600 bg-red-50', 'rejected' => 'text-red-600 bg-red-50'];
                $sColor = $statusColors[$sb->status] ?? 'text-gray-600 bg-gray-50';
            @endphp
            <div class="flex items-center justify-between p-3 rounded-xl {{ $sColor }}">
                <div>
                    <span class="text-sm font-bold">{{ ucfirst($sb->status) }}</span>
                    <span class="text-xs opacity-75 ml-1">({{ $sb->count }} payments)</span>
                </div>
                <span class="text-sm font-bold">{{ $currencySymbol }}{{ number_format($sb->total, 2) }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">No payment data for this period.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Recent Payments Table --}}
<div class="card">
    <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">Recent Payments</h3>
    <div class="overflow-x-auto -mx-5">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">User</th>
                    <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Amount</th>
                    <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Gateway</th>
                    <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($recentPayments as $payment)
                <tr>
                    <td class="px-5 py-3 text-sm font-medium text-[#1a1a2e]">{{ $payment->user->name ?? 'Unknown' }}</td>
                    <td class="px-5 py-3 text-sm font-bold text-[#1a1a2e]">{{ $currencySymbol }}{{ number_format($payment->amount, 2) }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $payment->gateway ?? 'N/A')) }}</td>
                    <td class="px-5 py-3">
                        <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full {{ $payment->status === 'completed' ? 'bg-green-100 text-green-700' : ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ $payment->status }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-400">{{ $payment->created_at->format('M d, Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


{{-- ============================================================
     USERS TAB
============================================================ --}}
@elseif($tab === 'users')

{{-- Summary Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Users</p>
        <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ number_format($totalUsers) }}</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">New in Period</p>
        <p class="text-2xl font-extrabold text-green-600 mt-1">+{{ number_format($newUsers) }}</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Verified</p>
        <p class="text-2xl font-extrabold text-blue-600 mt-1">{{ number_format($verifiedUsers) }}</p>
        <p class="text-[10px] text-gray-400 mt-1">{{ $totalUsers > 0 ? round(($verifiedUsers / $totalUsers) * 100, 1) : 0 }}% verification rate</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Unverified</p>
        <p class="text-2xl font-extrabold text-amber-500 mt-1">{{ number_format($unverifiedUsers) }}</p>
    </div>
</div>

{{-- Signups Chart --}}
<div class="card mb-8">
    <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">Signup Trend</h3>
    <div class="h-48" id="signups-chart"></div>
    @if(empty($signupsChart))
    <p class="text-sm text-gray-400 text-center py-8">No signup data for this period.</p>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Top Enrollers --}}
    <div class="card">
        <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">Most Active Students</h3>
        <div class="space-y-3">
            @forelse($topEnrollers->where('cohort_enrollments_count', '>', 0) as $i => $user)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-500">{{ $i + 1 }}</span>
                    <div>
                        <p class="text-sm font-medium text-[#1a1a2e]">{{ $user->name }}</p>
                        <p class="text-[10px] text-gray-400">{{ $user->email }}</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">{{ $user->cohort_enrollments_count }} cohorts</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">No enrollment data yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Top Buyers --}}
    <div class="card">
        <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">Top Product Buyers</h3>
        <div class="space-y-3">
            @forelse($topBuyers->where('product_purchases_count', '>', 0) as $i => $user)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-500">{{ $i + 1 }}</span>
                    <div>
                        <p class="text-sm font-medium text-[#1a1a2e]">{{ $user->name }}</p>
                        <p class="text-[10px] text-gray-400">{{ $user->email }}</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-purple-600 bg-purple-50 px-2 py-1 rounded-lg">{{ $user->product_purchases_count }} products</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">No product purchases yet.</p>
            @endforelse
        </div>
    </div>
</div>


{{-- ============================================================
     COHORTS TAB
============================================================ --}}
@elseif($tab === 'cohorts')

{{-- Summary Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Cohorts</p>
        <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ number_format($totalCohorts) }}</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Active</p>
        <p class="text-2xl font-extrabold text-green-600 mt-1">{{ number_format($activeCohorts) }}</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Enrollments</p>
        <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ number_format($totalEnrollments) }}</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Period Enrollments</p>
        <p class="text-2xl font-extrabold text-blue-600 mt-1">+{{ number_format($periodEnrollments) }}</p>
    </div>
</div>

{{-- Enrollment Chart --}}
<div class="card mb-8">
    <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">Enrollment Trend</h3>
    <div class="h-48" id="enrollment-chart"></div>
    @if(empty($enrollmentChart))
    <p class="text-sm text-gray-400 text-center py-8">No enrollment data for this period.</p>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Most Popular Cohorts --}}
    <div class="card">
        <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">Most Popular Cohorts</h3>
        <div class="space-y-3">
            @forelse($popularCohorts->where('enrollments_count', '>', 0) as $i => $cohort)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-500">{{ $i + 1 }}</span>
                    <div>
                        <p class="text-sm font-medium text-[#1a1a2e]">{{ $cohort->title }}</p>
                        <p class="text-[10px] text-gray-400">{{ ucfirst($cohort->status) }}</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">{{ $cohort->enrollments_count }} students</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">No cohort data yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Revenue per Cohort --}}
    <div class="card">
        <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">Revenue by Cohort</h3>
        <div class="space-y-3">
            @forelse($cohortRevenue->where('total_revenue', '>', 0) as $i => $cohort)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-500">{{ $i + 1 }}</span>
                    <div>
                        <p class="text-sm font-medium text-[#1a1a2e]">{{ $cohort->title }}</p>
                        <p class="text-[10px] text-gray-400">{{ $cohort->payment_count }} payments</p>
                    </div>
                </div>
                <span class="text-sm font-bold text-green-600">{{ $currencySymbol }}{{ number_format($cohort->total_revenue, 2) }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">No revenue data yet.</p>
            @endforelse
        </div>
    </div>
</div>


{{-- ============================================================
     PRODUCTS TAB
============================================================ --}}
@elseif($tab === 'products')

{{-- Summary Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Products</p>
        <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ number_format($totalProducts) }}</p>
        <p class="text-[10px] text-gray-400 mt-1">{{ $publishedProducts }} published</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Sales</p>
        <p class="text-2xl font-extrabold text-green-600 mt-1">{{ number_format($totalSales) }}</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Period Sales</p>
        <p class="text-2xl font-extrabold text-blue-600 mt-1">+{{ number_format($periodSales) }}</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Downloads</p>
        <p class="text-2xl font-extrabold text-purple-600 mt-1">{{ number_format($totalDownloads) }}</p>
    </div>
</div>

{{-- Sales Chart --}}
<div class="card mb-8">
    <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">Sales Trend</h3>
    <div class="h-48" id="sales-chart"></div>
    @if(empty($salesChart))
    <p class="text-sm text-gray-400 text-center py-8">No sales data for this period.</p>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Top Selling Products --}}
    <div class="card">
        <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">Top Selling Products</h3>
        <div class="space-y-3">
            @forelse($topProducts->where('purchases_count', '>', 0) as $i => $product)
            @php $docType = \App\Models\DigitalProduct::DOCUMENT_TYPES[$product->document_type] ?? \App\Models\DigitalProduct::DOCUMENT_TYPES['pdf']; @endphp
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br {{ $docType['icon'] }} flex items-center justify-center flex-shrink-0">
                        <span class="text-[7px] font-black text-white/90">{{ $docType['label'] }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-[#1a1a2e]">{{ $product->title }}</p>
                        <p class="text-[10px] text-gray-400">{{ $currencySymbol }}{{ number_format($product->price, 2) }}</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-purple-600 bg-purple-50 px-2 py-1 rounded-lg">{{ $product->purchases_count }} sales</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">No sales data yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Revenue per Product --}}
    <div class="card">
        <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">Revenue by Product</h3>
        <div class="space-y-3">
            @forelse($productRevenue->where('total_revenue', '>', 0) as $i => $product)
            @php $docType = \App\Models\DigitalProduct::DOCUMENT_TYPES[$product->document_type] ?? \App\Models\DigitalProduct::DOCUMENT_TYPES['pdf']; @endphp
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br {{ $docType['icon'] }} flex items-center justify-center flex-shrink-0">
                        <span class="text-[7px] font-black text-white/90">{{ $docType['label'] }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-[#1a1a2e]">{{ $product->title }}</p>
                        <p class="text-[10px] text-gray-400">{{ $product->payment_count }} payments</p>
                    </div>
                </div>
                <span class="text-sm font-bold text-green-600">{{ $currencySymbol }}{{ number_format($product->total_revenue, 2) }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">No revenue data yet.</p>
            @endforelse
        </div>
    </div>
</div>


{{-- ============================================================
     REFERRALS TAB
============================================================ --}}
@elseif($tab === 'referrals')

{{-- Summary Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Commission Paid</p>
        <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ $currencySymbol }}{{ number_format($totalEarnings, 2) }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $currencySymbol }}{{ number_format($periodEarnings, 2) }} in period</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Referred Users</p>
        <p class="text-2xl font-extrabold text-blue-600 mt-1">{{ number_format($totalReferrals) }}</p>
        <p class="text-xs text-gray-400 mt-1">+{{ $periodReferrals }} in period</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Withdrawals</p>
        <p class="text-2xl font-extrabold text-green-600 mt-1">{{ $currencySymbol }}{{ number_format($totalWithdrawals, 2) }}</p>
        <p class="text-xs text-amber-500 mt-1">{{ $currencySymbol }}{{ number_format($pendingWithdrawals, 2) }} pending</p>
    </div>
</div>

{{-- Earnings Chart --}}
<div class="card mb-8">
    <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">Commission Earned Over Time</h3>
    <div class="h-48" id="earnings-chart"></div>
    @if(empty($earningsChart))
    <p class="text-sm text-gray-400 text-center py-8">No referral data for this period.</p>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Top Referrers --}}
    <div class="card">
        <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">Top Referrers</h3>
        <div class="space-y-3">
            @forelse($topReferrers as $i => $user)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-500">{{ $i + 1 }}</span>
                    <div>
                        <p class="text-sm font-medium text-[#1a1a2e]">{{ $user->name }}</p>
                        <p class="text-[10px] text-gray-400">{{ $user->email }}</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">{{ $user->referrals_count }} referrals</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">No referral data yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Top Earners --}}
    <div class="card">
        <h3 class="text-lg font-bold text-[#1a1a2e] mb-4">Top Earners</h3>
        <div class="space-y-3">
            @forelse($topEarners as $i => $user)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-500">{{ $i + 1 }}</span>
                    <div>
                        <p class="text-sm font-medium text-[#1a1a2e]">{{ $user->name }}</p>
                        <p class="text-[10px] text-gray-400">{{ $user->earnings_count }} earnings</p>
                    </div>
                </div>
                <span class="text-sm font-bold text-green-600">{{ $currencySymbol }}{{ number_format($user->total_earned, 2) }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">No earnings data yet.</p>
            @endforelse
        </div>
    </div>
</div>

@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple bar chart renderer using CSS
    function renderChart(containerId, data, color) {
        const container = document.getElementById(containerId);
        if (!container || Object.keys(data).length === 0) return;

        const values = Object.values(data).map(Number);
        const labels = Object.keys(data);
        const max = Math.max(...values, 1);

        let html = '<div class="flex items-end gap-1 h-full px-1">';
        labels.forEach((label, i) => {
            const pct = (values[i] / max) * 100;
            const dateObj = new Date(label + 'T00:00:00');
            const shortLabel = dateObj.toLocaleDateString('en', { month: 'short', day: 'numeric' });
            html += `
                <div class="flex-1 flex flex-col items-center justify-end h-full group relative">
                    <div class="absolute -top-6 bg-[#1a2535] text-white text-[10px] px-2 py-1 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none z-10">
                        ${shortLabel}: ${typeof values[i] === 'number' && values[i] % 1 !== 0 ? Number(values[i]).toFixed(2) : values[i]}
                    </div>
                    <div class="w-full rounded-t-md transition-all duration-300 hover:opacity-80" style="height: ${Math.max(pct, 2)}%; background: ${color};"></div>
                </div>
            `;
        });
        html += '</div>';
        html += '<div class="flex justify-between mt-2 px-1">';
        if (labels.length <= 14) {
            labels.forEach(label => {
                const dateObj = new Date(label + 'T00:00:00');
                html += `<span class="text-[9px] text-gray-400 flex-1 text-center">${dateObj.toLocaleDateString('en', { month: 'short', day: 'numeric' })}</span>`;
            });
        } else {
            const first = new Date(labels[0] + 'T00:00:00');
            const last = new Date(labels[labels.length - 1] + 'T00:00:00');
            html += `<span class="text-[9px] text-gray-400">${first.toLocaleDateString('en', { month: 'short', day: 'numeric' })}</span>`;
            html += `<span class="text-[9px] text-gray-400">${last.toLocaleDateString('en', { month: 'short', day: 'numeric' })}</span>`;
        }
        html += '</div>';

        container.innerHTML = html;
    }

    // Render charts based on current tab
    @if(isset($revenueChart) && !empty($revenueChart))
    renderChart('revenue-chart', @json($revenueChart), '#10b981');
    @endif

    @if(isset($signupsChart) && !empty($signupsChart))
    renderChart('signups-chart', @json($signupsChart), '#3b82f6');
    @endif

    @if(isset($enrollmentChart) && !empty($enrollmentChart))
    renderChart('enrollment-chart', @json($enrollmentChart), '#6366f1');
    @endif

    @if(isset($salesChart) && !empty($salesChart))
    renderChart('sales-chart', @json($salesChart), '#8b5cf6');
    @endif

    @if(isset($earningsChart) && !empty($earningsChart))
    renderChart('earnings-chart', @json($earningsChart), '#f59e0b');
    @endif
});
</script>
@endpush
