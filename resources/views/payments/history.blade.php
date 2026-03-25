@extends('layouts.app')

@section('title', 'My Payments')
@section('page_title', 'My Payments')
@section('page_subtitle', 'Your payment history')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    {{-- Filter tabs --}}
    <div class="flex items-center gap-2 flex-wrap">
        <a href="{{ route('payments.history') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ !request('status') ? 'bg-[#1a2535] text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }} transition-colors">All</a>
        <a href="{{ route('payments.history', ['status' => 'pending']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') === 'pending' ? 'bg-[#1a2535] text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }} transition-colors">Pending</a>
        <a href="{{ route('payments.history', ['status' => 'completed']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') === 'completed' ? 'bg-[#1a2535] text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }} transition-colors">Completed</a>
        <a href="{{ route('payments.history', ['status' => 'failed']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') === 'failed' ? 'bg-[#1a2535] text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }} transition-colors">Rejected</a>
    </div>

    {{-- Export --}}
    <a href="{{ route('payments.history.export') }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 font-medium text-sm px-4 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Export CSV
    </a>
</div>

@if($payments->count() > 0)
<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Date</th>
                    <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Cohort</th>
                    <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Amount</th>
                    <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Gateway</th>
                    <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="text-left px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-wider">Reference</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($payments as $payment)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-6 py-4 text-gray-600">{{ $payment->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 font-medium text-[#1a1a2e]">{{ $payment->payable->title ?? 'N/A' }}</td>
                    <td class="px-6 py-4 font-semibold text-[#1a1a2e]">{{ $payment->currency ?? 'GBP' }} {{ number_format($payment->amount, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $payment->gateway === 'stripe' ? 'bg-purple-100 text-purple-700' : ($payment->gateway === 'paypal' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                            {{ ucfirst(str_replace('_', ' ', $payment->gateway)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $payment->status === 'completed' ? 'bg-green-100 text-green-700' : ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-400 font-mono">{{ Str::limit($payment->reference, 20) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $payments->links() }}</div>
@else
<div class="bg-white rounded-2xl p-12 border border-dashed border-gray-300 text-center">
    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
    <p class="text-gray-500 text-sm">No payments found.</p>
</div>
@endif

@endsection
