@extends('layouts.app')

@section('title', 'Affiliate Earnings')
@section('page_title', 'Affiliate Earnings')
@section('page_subtitle', 'Your commission history')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('affiliate.dashboard') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Dashboard
    </a>
    <div class="text-right">
        <p class="text-xs text-gray-400">Total Earned</p>
        <p class="text-xl font-extrabold text-emerald-600">{{ $currencySymbol }}{{ number_format($totalEarnings, 2) }}</p>
    </div>
</div>

<div class="card overflow-hidden !p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f5f6fa] border-b border-[#e8eaf0]">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Item</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Sale Amount</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Rate</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Commission</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e8eaf0]">
                @forelse($sales as $sale)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $sale->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-[#1a1a2e]">{{ $sale->affiliateLink->affiliable->title ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $currencySymbol }}{{ number_format($sale->sale_amount, 2) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $sale->commission_rate }}%</td>
                    <td class="px-6 py-4 text-sm font-semibold text-emerald-600">{{ $currencySymbol }}{{ number_format($sale->affiliate_commission, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">No earnings yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($sales->hasPages())
    <div class="px-6 py-4 border-t border-[#e8eaf0]">{{ $sales->links() }}</div>
    @endif
</div>
@endsection
