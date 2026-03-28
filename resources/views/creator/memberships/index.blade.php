@extends('layouts.app')

@section('title', 'My Memberships')
@section('page_title', 'My Memberships')
@section('page_subtitle', 'Manage your membership plans')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-xl font-bold text-[#1a1a2e]">Your Membership Plans</h3>
    <a href="{{ route('creator.memberships.create') }}" class="btn-primary text-sm py-2">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Plan
    </a>
</div>

<div class="card overflow-hidden !p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f5f6fa] border-b border-[#e8eaf0]">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Plan</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Approval</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Subscribers</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e8eaf0]">
                @forelse($memberships as $plan)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-[#1a1a2e]">{{ $plan->title }}</p>
                                <p class="text-xs text-gray-400">{{ $plan->billing_label }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($plan->approval_status === 'approved')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Approved</span>
                        @elseif($plan->approval_status === 'pending')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Pending Review</span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Rejected</span>
                        @if($plan->rejection_reason)
                        <p class="text-[10px] text-red-500 mt-1 max-w-xs">{{ $plan->rejection_reason }}</p>
                        @endif
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                        {{ \App\Models\Setting::get('currency_symbol', '£') }}{{ number_format($plan->price, 2) }}
                        <span class="text-xs text-gray-400">/{{ $plan->billing_interval }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        <a href="{{ route('creator.memberships.subscribers', $plan) }}" class="text-[#e05a3a] hover:underline font-medium">
                            {{ $plan->active_subscriptions_count }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('creator.memberships.contents.index', $plan) }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium">Content</a>
                        <a href="{{ route('creator.memberships.edit', $plan) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</a>
                        <form action="{{ route('creator.memberships.destroy', $plan) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium" onclick="return confirm('Delete this membership plan?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                        No membership plans yet. <a href="{{ route('creator.memberships.create') }}" class="text-[#e05a3a] hover:underline">Create your first plan</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($memberships->hasPages())
    <div class="px-6 py-4 border-t border-[#e8eaf0]">
        {{ $memberships->links() }}
    </div>
    @endif
</div>
@endsection
