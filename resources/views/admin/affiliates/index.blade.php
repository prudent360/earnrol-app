@extends('layouts.app')

@section('title', 'Affiliate Overview')
@section('page_title', 'Affiliate Overview')
@section('page_subtitle', 'Platform affiliate performance')

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Sales</p>
        <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ $totalSales }}</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Affiliate Commissions</p>
        <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $currencySymbol }}{{ number_format($totalCommissions, 2) }}</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Platform Fees</p>
        <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $currencySymbol }}{{ number_format($totalPlatformFees, 2) }}</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Active Links</p>
        <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ $totalLinks }}</p>
    </div>
</div>

<h3 class="text-sm font-bold text-[#1a1a2e] mb-4">Recent Affiliate Sales</h3>
<div class="card overflow-hidden !p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f5f6fa] border-b border-[#e8eaf0]">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Item</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Affiliate</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Sale</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Affiliate Got</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Platform Fee</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e8eaf0]">
                @forelse($recentSales as $sale)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $sale->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-[#1a1a2e]">{{ $sale->affiliateLink->affiliable->title ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $sale->affiliate->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $currencySymbol }}{{ number_format($sale->sale_amount, 2) }}</td>
                    <td class="px-6 py-4 text-sm text-amber-600">{{ $currencySymbol }}{{ number_format($sale->affiliate_commission, 2) }}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-emerald-600">{{ $currencySymbol }}{{ number_format($sale->admin_commission, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">No affiliate sales yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($recentSales->hasPages())
    <div class="px-6 py-4 border-t border-[#e8eaf0]">{{ $recentSales->links() }}</div>
    @endif
</div>
@endsection
