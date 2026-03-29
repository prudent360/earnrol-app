@extends('layouts.app')

@section('title', 'Subscribers — ' . $plan->title)
@section('page_title', $plan->title . ' Subscribers')
@section('page_subtitle', 'Users subscribed to this creator plan')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.creator-plans.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Plans
    </a>
</div>

<div class="card overflow-hidden !p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f5f6fa] border-b border-[#e8eaf0]">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Creator</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Started</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Renews</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e8eaf0]">
                @forelse($subscribers as $sub)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <p class="text-sm font-semibold text-[#1a1a2e]">{{ $sub->user->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-400">{{ $sub->user->email ?? '' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sub->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($sub->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $sub->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $sub->current_period_end?->format('M d, Y') ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-400">No subscribers yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($subscribers->hasPages())
    <div class="px-6 py-4 border-t border-[#e8eaf0]">{{ $subscribers->links() }}</div>
    @endif
</div>
@endsection
