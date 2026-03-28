@extends('layouts.app')

@section('title', 'Discount Codes')
@section('page_title', 'Discount Codes')
@section('page_subtitle', 'Manage discount codes for your items')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-xl font-bold text-[#1a1a2e]">Your Discount Codes</h3>
    <a href="{{ route('creator.coupons.create') }}" class="btn-primary text-sm py-2">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Code
    </a>
</div>

@php $currencySymbol = \App\Models\Setting::get('currency_symbol', '£'); @endphp

<div class="card overflow-hidden !p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f5f6fa] border-b border-[#e8eaf0]">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Discount</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Applies To</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Usage</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e8eaf0]">
                @forelse($coupons as $coupon)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <span class="font-mono font-bold text-sm text-[#1a1a2e]">{{ $coupon->code }}</span>
                        @if($coupon->description)
                        <p class="text-xs text-gray-400 mt-0.5">{{ $coupon->description }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-700">
                        @if($coupon->discount_type === 'percentage')
                        {{ $coupon->discount_value }}%
                        @else
                        {{ $currencySymbol }}{{ number_format($coupon->discount_value, 2) }}
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $coupon->applies_to === 'cohort' ? 'bg-blue-100 text-blue-700' : ($coupon->applies_to === 'product' ? 'bg-purple-100 text-purple-700' : 'bg-amber-100 text-amber-700') }}">
                            {{ ucfirst($coupon->applies_to) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $coupon->used_count }}{{ $coupon->usage_limit ? '/' . $coupon->usage_limit : '' }}
                    </td>
                    <td class="px-6 py-4">
                        @if($coupon->is_active && $coupon->isValid())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('creator.coupons.edit', $coupon) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</a>
                        <form action="{{ route('creator.coupons.destroy', $coupon) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium" onclick="return confirm('Delete this discount code?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        No discount codes yet. <a href="{{ route('creator.coupons.create') }}" class="text-[#e05a3a] hover:underline">Create your first code</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($coupons->hasPages())
    <div class="px-6 py-4 border-t border-[#e8eaf0]">
        {{ $coupons->links() }}
    </div>
    @endif
</div>
@endsection
