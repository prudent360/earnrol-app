@extends('layouts.app')

@section('title', 'Affiliate Sales')
@section('page_title', 'Affiliate Sales')
@section('page_subtitle', 'Sales driven by affiliates for your items')

@section('content')
<div class="card overflow-hidden !p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f5f6fa] border-b border-[#e8eaf0]">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Item</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Affiliate</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Sale Amount</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Affiliate Got</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">You Received</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e8eaf0]">
                @forelse($sales as $sale)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $sale->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-[#1a1a2e]">{{ $sale->affiliateLink->affiliable->title ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $sale->affiliate->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $currencySymbol }}{{ number_format($sale->sale_amount, 2) }}</td>
                    <td class="px-6 py-4 text-sm text-amber-600">{{ $currencySymbol }}{{ number_format($sale->affiliate_commission, 2) }} ({{ $sale->commission_rate }}%)</td>
                    <td class="px-6 py-4 text-sm font-semibold text-emerald-600">{{ $currencySymbol }}{{ number_format($sale->creator_amount, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">No affiliate sales yet.</td>
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
