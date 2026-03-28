@extends('layouts.app')

@section('title', $membership->title)
@section('page_title', $membership->title)
@section('page_subtitle', 'Membership plan details')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.memberships.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Memberships
    </a>
</div>

@php $currencySymbol = \App\Models\Setting::get('currency_symbol', '£'); @endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Price</p>
        <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ $currencySymbol }}{{ number_format($membership->price, 2) }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $membership->billing_label }}</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Active Subscribers</p>
        <p class="text-2xl font-extrabold text-[#1a1a2e] mt-1">{{ $membership->activeSubscriptions()->count() }}</p>
    </div>
    <div class="card">
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Creator</p>
        <p class="text-lg font-bold text-[#1a1a2e] mt-1">{{ $membership->creator->name ?? 'N/A' }}</p>
        <p class="text-xs text-gray-400">{{ $membership->creator->email ?? '' }}</p>
    </div>
</div>

@if($membership->description)
<div class="card mb-8">
    <h3 class="text-sm font-bold text-[#1a1a2e] mb-3">Description</h3>
    <p class="text-sm text-gray-600 whitespace-pre-line">{{ $membership->description }}</p>
</div>
@endif

@if(count($membership->features_list) > 0)
<div class="card mb-8">
    <h3 class="text-sm font-bold text-[#1a1a2e] mb-3">Features</h3>
    <ul class="space-y-2">
        @foreach($membership->features_list as $feature)
        <li class="flex items-center gap-2 text-sm text-gray-600">
            <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ $feature }}
        </li>
        @endforeach
    </ul>
</div>
@endif

<h3 class="text-sm font-bold text-[#1a1a2e] mb-4">Subscribers</h3>
<div class="card overflow-hidden !p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f5f6fa] border-b border-[#e8eaf0]">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Gateway</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Subscribed</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Period End</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e8eaf0]">
                @forelse($subscribers as $sub)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <p class="text-sm font-medium text-[#1a1a2e]">{{ $sub->user->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-400">{{ $sub->user->email ?? '' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $sub->status === 'active' ? 'bg-green-100 text-green-700' : ($sub->status === 'past_due' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($sub->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $sub->gateway)) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $sub->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $sub->current_period_end?->format('M d, Y') ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">No subscribers yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($subscribers->hasPages())
    <div class="px-6 py-4 border-t border-[#e8eaf0]">
        {{ $subscribers->links() }}
    </div>
    @endif
</div>
@endsection
