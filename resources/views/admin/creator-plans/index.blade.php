@extends('layouts.app')

@section('title', 'Creator Plans')
@section('page_title', 'Creator Plans')
@section('page_subtitle', 'Manage subscription tiers for creators')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div></div>
    <a href="{{ route('admin.creator-plans.create') }}" class="btn-primary text-sm">+ Add Plan</a>
</div>

<div class="card overflow-hidden !p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f5f6fa] border-b border-[#e8eaf0]">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Plan</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Limits</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Subscribers</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e8eaf0]">
                @php $currencySymbol = \App\Models\Setting::get('currency_symbol', '£'); @endphp
                @forelse($plans as $plan)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="text-sm font-bold text-[#1a1a2e]">{{ $plan->title }}</p>
                        <p class="text-xs text-gray-400">{{ $plan->slug }}</p>
                        @if($plan->is_featured)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-amber-100 text-amber-700 mt-1">Featured</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-bold text-[#1a1a2e]">{{ $currencySymbol }}{{ number_format($plan->price, 2) }}</p>
                        <p class="text-xs text-gray-400">{{ $plan->billing_label }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $plan->max_products ? $plan->max_products . ' products' : 'Unlimited' }}<br>
                        {{ $plan->max_cohorts ? $plan->max_cohorts . ' cohorts' : 'Unlimited' }}
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.creator-plans.subscribers', $plan) }}" class="text-sm font-bold text-[#e05a3a] hover:underline">{{ $plan->subscriptions_count }}</a>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $plan->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ ucfirst($plan->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.creator-plans.edit', $plan) }}" class="text-xs text-[#e05a3a] hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.creator-plans.destroy', $plan) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:underline" onclick="return confirm('Delete this plan?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">No creator plans yet. Create one to get started.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
