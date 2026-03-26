@extends('layouts.app')

@section('title', 'Coupons')
@section('page_title', 'Coupons')
@section('page_subtitle', 'Manage discount codes')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h3 class="text-xl font-bold text-[#1a1a2e]">All Coupons</h3>
    <a href="{{ route('admin.coupons.create') }}" class="btn-primary text-sm py-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Coupon
    </a>
</div>

<div class="card overflow-hidden !p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f5f6fa] border-b border-[#e8eaf0]">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Discount</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Applies To</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Usage</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Expires</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e8eaf0]">
                @forelse($coupons as $coupon)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <span class="font-mono text-sm font-bold text-[#e05a3a]">{{ $coupon->code }}</span>
                        @if($coupon->description)
                        <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($coupon->description, 40) }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold text-[#1a1a2e]">
                        @if($coupon->discount_type === 'percentage')
                            {{ rtrim(rtrim(number_format($coupon->discount_value, 2), '0'), '.') }}%
                        @else
                            {{ \App\Models\Setting::get('currency_symbol', '£') }}{{ number_format($coupon->discount_value, 2) }}
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        @if($coupon->applies_to === 'all')
                            <span class="badge bg-blue-100 text-blue-700">All Items</span>
                        @elseif($coupon->applies_to === 'cohort')
                            <span class="badge bg-purple-100 text-purple-700">Cohort</span>
                        @else
                            <span class="badge bg-teal-100 text-teal-700">Product</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $coupon->used_count }}{{ $coupon->usage_limit ? ' / ' . $coupon->usage_limit : '' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y') : '—' }}
                    </td>
                    <td class="px-6 py-4">
                        @if($coupon->isValid())
                            <span class="badge bg-green-100 text-green-700">Active</span>
                        @elseif($coupon->expires_at && now()->gt($coupon->expires_at))
                            <span class="badge bg-red-100 text-red-600">Expired</span>
                        @elseif(!$coupon->is_active)
                            <span class="badge bg-gray-100 text-gray-500">Inactive</span>
                        @else
                            <span class="badge bg-yellow-100 text-yellow-700">Exhausted</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-sm text-[#e05a3a] hover:underline font-medium">Edit</a>
                            <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" onsubmit="return confirm('Delete this coupon?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-sm text-red-500 hover:underline font-medium">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                        No coupons yet. Create your first coupon to offer discounts.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $coupons->links() }}</div>
@endsection
