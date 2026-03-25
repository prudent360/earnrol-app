@extends('layouts.app')

@section('title', 'Manage Withdrawals')
@section('page_title', 'Withdrawals')
@section('page_subtitle', 'Review and process withdrawal requests')

@section('content')

{{-- Filter tabs --}}
<div class="flex items-center gap-2 flex-wrap mb-6">
    <a href="{{ route('admin.withdrawals.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ !request('status') ? 'bg-[#1a2535] text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }} transition-colors">All</a>
    <a href="{{ route('admin.withdrawals.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') === 'pending' ? 'bg-[#1a2535] text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }} transition-colors">Pending</a>
    <a href="{{ route('admin.withdrawals.index', ['status' => 'approved']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') === 'approved' ? 'bg-[#1a2535] text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }} transition-colors">Approved</a>
    <a href="{{ route('admin.withdrawals.index', ['status' => 'rejected']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') === 'rejected' ? 'bg-[#1a2535] text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }} transition-colors">Rejected</a>
</div>

@if($withdrawals->count() > 0)
<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">User</th>
                    <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Amount</th>
                    <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Bank Details</th>
                    <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Date</th>
                    <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($withdrawals as $withdrawal)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-6 py-4">
                        <p class="font-medium text-[#1a1a2e]">{{ $withdrawal->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $withdrawal->user->email }}</p>
                    </td>
                    <td class="px-6 py-4 font-semibold text-[#1a1a2e]">{{ \App\Models\Setting::get('currency_symbol', '£') }}{{ number_format($withdrawal->amount, 2) }}</td>
                    <td class="px-6 py-4 text-xs text-gray-600">
                        <p class="font-medium">{{ $withdrawal->bank_account_name }}</p>
                        <p>{{ $withdrawal->bank_name }} — {{ $withdrawal->bank_account_number }}</p>
                        @if($withdrawal->bank_sort_code)
                        <p>Sort: {{ $withdrawal->bank_sort_code }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $withdrawal->status === 'approved' ? 'bg-green-100 text-green-700' : ($withdrawal->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($withdrawal->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $withdrawal->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        @if($withdrawal->status === 'pending')
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal) }}" onsubmit="return confirm('Approve this withdrawal?')">
                                @csrf
                                <button type="submit" class="text-xs font-bold text-green-600 hover:underline">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal) }}" onsubmit="return confirm('Reject this withdrawal? Amount will be refunded to wallet.')">
                                @csrf
                                <input type="hidden" name="admin_note" value="Withdrawal rejected by admin.">
                                <button type="submit" class="text-xs font-bold text-red-600 hover:underline">Reject</button>
                            </form>
                        </div>
                        @elseif($withdrawal->admin_note)
                        <p class="text-xs text-gray-400">{{ $withdrawal->admin_note }}</p>
                        @else
                        <span class="text-xs text-gray-400">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $withdrawals->links() }}</div>
@else
<div class="bg-white rounded-2xl p-12 border border-dashed border-gray-300 text-center">
    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <p class="text-gray-500 text-sm">No withdrawal requests found.</p>
</div>
@endif

@endsection
