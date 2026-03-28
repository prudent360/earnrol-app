@extends('layouts.app')

@section('title', 'Affiliate Dashboard')
@section('page_title', 'Affiliate Dashboard')
@section('page_subtitle', 'Promote products and earn commission')

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Earnings</p>
        <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $currencySymbol }}{{ number_format($totalEarnings, 2) }}</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Sales</p>
        <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ $totalSales }}</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Clicks</p>
        <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ $totalClicks }}</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Active Links</p>
        <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ $linksCount }}</p>
    </div>
</div>

<div class="flex gap-3 mb-6">
    <a href="{{ route('affiliate.products') }}" class="btn-primary text-sm py-2">Browse Products</a>
    <a href="{{ route('affiliate.links') }}" class="btn-outline text-sm py-2">My Links</a>
    <a href="{{ route('affiliate.earnings') }}" class="btn-outline text-sm py-2">Earnings History</a>
</div>

@if($recentSales->count() > 0)
<h3 class="text-sm font-bold text-[#1a1a2e] mb-4">Recent Sales</h3>
<div class="card overflow-hidden !p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f5f6fa] border-b border-[#e8eaf0]">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Item</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Sale Amount</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Your Commission</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e8eaf0]">
                @foreach($recentSales as $sale)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-[#1a1a2e]">{{ $sale->affiliateLink->affiliable->title ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $currencySymbol }}{{ number_format($sale->sale_amount, 2) }}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-emerald-600">{{ $currencySymbol }}{{ number_format($sale->affiliate_commission, 2) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $sale->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
