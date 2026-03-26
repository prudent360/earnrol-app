@extends('layouts.app')

@section('title', 'Creator Dashboard')
@section('page_title', 'Creator Dashboard')
@section('page_subtitle', 'Manage your products, cohorts, and earnings')

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="card flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Products</p>
            <p class="text-2xl font-extrabold text-[#1a1a2e]">{{ $productCount }}</p>
        </div>
    </div>

    <div class="card flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Cohorts</p>
            <p class="text-2xl font-extrabold text-[#1a1a2e]">{{ $cohortCount }}</p>
        </div>
    </div>

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
</div>

{{-- Pending Approvals Alert --}}
@if($pendingProducts > 0 || $pendingCohorts > 0)
<div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-8 flex items-start gap-3">
    <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
    <div>
        <p class="text-sm font-semibold text-amber-800">Items Pending Approval</p>
        <p class="text-xs text-amber-600 mt-0.5">
            @if($pendingProducts > 0) {{ $pendingProducts }} product{{ $pendingProducts > 1 ? 's' : '' }} @endif
            @if($pendingProducts > 0 && $pendingCohorts > 0) and @endif
            @if($pendingCohorts > 0) {{ $pendingCohorts }} cohort{{ $pendingCohorts > 1 ? 's' : '' }} @endif
            awaiting admin review.
        </p>
    </div>
</div>
@endif

{{-- Quick Actions --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
    <a href="{{ route('creator.products.create') }}" class="card hover:shadow-md transition-shadow flex items-center gap-4 group">
        <div class="w-10 h-10 rounded-xl bg-[#e05a3a]/10 flex items-center justify-center flex-shrink-0 group-hover:bg-[#e05a3a]/20 transition-colors">
            <svg class="w-5 h-5 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        </div>
        <div>
            <p class="text-sm font-bold text-[#1a1a2e]">Create New Product</p>
            <p class="text-xs text-gray-400">Upload a digital product for sale</p>
        </div>
    </a>

    <a href="{{ route('creator.cohorts.create') }}" class="card hover:shadow-md transition-shadow flex items-center gap-4 group">
        <div class="w-10 h-10 rounded-xl bg-[#e05a3a]/10 flex items-center justify-center flex-shrink-0 group-hover:bg-[#e05a3a]/20 transition-colors">
            <svg class="w-5 h-5 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        </div>
        <div>
            <p class="text-sm font-bold text-[#1a1a2e]">Create New Cohort</p>
            <p class="text-xs text-gray-400">Set up a new training cohort</p>
        </div>
    </a>
</div>

{{-- Recent Earnings --}}
<div class="card !p-0 overflow-hidden">
    <div class="px-6 py-4 border-b border-[#e8eaf0] flex items-center justify-between">
        <h3 class="text-sm font-bold text-[#1a1a2e]">Recent Earnings</h3>
        <a href="{{ route('creator.earnings.index') }}" class="text-xs text-[#e05a3a] hover:underline font-medium">View All</a>
    </div>
    @if($recentEarnings->count() > 0)
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
                @foreach($recentEarnings as $earning)
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
    @else
    <div class="px-6 py-12 text-center text-gray-400">
        <p class="text-sm">No earnings yet. Start by creating products or cohorts!</p>
    </div>
    @endif
</div>

@endsection
