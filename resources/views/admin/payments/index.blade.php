@extends('layouts.app')

@section('title', 'Payments')
@section('page_title', 'Payments')
@section('page_subtitle', 'Review and manage all payments')

@section('content')

{{-- Filters --}}
<div class="flex flex-wrap items-center gap-3 mb-6">
    <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ !request('status') && !request('gateway') ? 'bg-[#1a2535] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition-colors">
        All
    </a>
    <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') === 'pending' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition-colors">
        Pending
    </a>
    <a href="{{ route('admin.payments.index', ['status' => 'completed']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') === 'completed' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition-colors">
        Completed
    </a>
    <a href="{{ route('admin.payments.index', ['status' => 'failed']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') === 'failed' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition-colors">
        Rejected
    </a>
    <span class="text-gray-300">|</span>
    <a href="{{ route('admin.payments.index', ['gateway' => 'bank_transfer']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('gateway') === 'bank_transfer' ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition-colors">
        Bank Transfers
    </a>
    <a href="{{ route('admin.payments.index', ['gateway' => 'stripe']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('gateway') === 'stripe' ? 'bg-[#635BFF] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition-colors">
        Stripe
    </a>
    <a href="{{ route('admin.payments.index', ['gateway' => 'paypal']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('gateway') === 'paypal' ? 'bg-[#003087] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition-colors">
        PayPal
    </a>
</div>

@if($payments->isEmpty())
<div class="bg-white rounded-2xl p-8 border border-dashed border-gray-300 text-center">
    <p class="text-gray-500 text-sm">No payments found.</p>
</div>
@else
<div class="card p-0 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Student</th>
                    <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Cohort</th>
                    <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Amount</th>
                    <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Gateway</th>
                    <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Receipt</th>
                    <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Date</th>
                    <th class="px-5 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($payments as $payment)
                <tr class="hover:bg-gray-50 transition-colors {{ $payment->status === 'pending' && $payment->gateway === 'bank_transfer' ? 'bg-amber-50/50' : '' }}">
                    <td class="px-5 py-4">
                        <p class="font-medium text-[#1a1a2e]">{{ $payment->user->name ?? 'N/A' }}</p>
                        <p class="text-[11px] text-gray-400">{{ $payment->user->email ?? '' }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-gray-700">{{ $payment->payable->title ?? 'N/A' }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="font-bold text-[#1a1a2e]">{{ \App\Models\Setting::get('currency_symbol', '£') }}{{ number_format($payment->amount, 2) }}</p>
                    </td>
                    <td class="px-5 py-4">
                        @if($payment->gateway === 'bank_transfer')
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-[10px] font-bold bg-gray-100 text-gray-700">
                            BANK
                        </span>
                        @elseif($payment->gateway === 'stripe')
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-[10px] font-bold text-white" style="background:#635BFF">
                            STRIPE
                        </span>
                        @elseif($payment->gateway === 'paypal')
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-[10px] font-bold text-white" style="background:#003087">
                            PAYPAL
                        </span>
                        @else
                        <span class="text-xs text-gray-400">{{ $payment->gateway }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        @if($payment->status === 'pending')
                        <span class="badge bg-amber-100 text-amber-700">Pending</span>
                        @elseif($payment->status === 'completed')
                        <span class="badge bg-green-100 text-green-700">Completed</span>
                        @else
                        <span class="badge bg-red-100 text-red-600">Rejected</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        @if($payment->receipt_path)
                        <a href="{{ Storage::url($payment->receipt_path) }}" target="_blank" class="text-[#e05a3a] text-xs font-bold hover:underline flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            View
                        </a>
                        @else
                        <span class="text-xs text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-xs text-gray-500">{{ $payment->created_at->format('M d, Y') }}</p>
                        <p class="text-[10px] text-gray-400">{{ $payment->created_at->format('h:i A') }}</p>
                    </td>
                    <td class="px-5 py-4">
                        @if($payment->status === 'pending' && $payment->gateway === 'bank_transfer')
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('admin.payments.approve', $payment) }}">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-green-600 text-white hover:bg-green-700 transition-colors" onclick="return confirm('Approve this payment and enrol the student?')">
                                    Approve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.payments.reject', $payment) }}">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-red-100 text-red-600 hover:bg-red-200 transition-colors" onclick="return confirm('Reject this payment?')">
                                    Reject
                                </button>
                            </form>
                        </div>
                        @elseif($payment->admin_note)
                        <p class="text-[11px] text-gray-400">{{ $payment->admin_note }}</p>
                        @else
                        <span class="text-xs text-gray-300">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $payments->links() }}
</div>
@endif

@endsection
