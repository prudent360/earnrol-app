@extends('layouts.app')

@section('title', 'Bank Transfer — ' . $product->title)
@section('page_title', 'Bank Transfer')
@section('page_subtitle', 'Complete your payment via bank transfer')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('products.show', $product) }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Product
        </a>
    </div>

    @if($pendingPayment)
    <div class="card text-center space-y-4">
        <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto">
            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h2 class="text-xl font-bold text-[#1a1a2e]">Payment Under Review</h2>
        <p class="text-sm text-gray-600">You already submitted a payment receipt for <strong>{{ $product->title }}</strong>. Our team is reviewing it and you'll get access once approved.</p>
        <p class="text-xs text-gray-400">Reference: {{ $pendingPayment->reference }}</p>
    </div>
    @else

    {{-- Product Summary --}}
    <div class="card mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-[#1a1a2e]">{{ $product->title }}</h2>
                <p class="text-xs text-gray-400 mt-1">Digital Product</p>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold text-[#1a1a2e]">{{ $bankDetails['currency_symbol'] }}{{ number_format($product->price, 2) }}</p>
                <p class="text-[10px] text-gray-400 uppercase tracking-wider">Amount to pay</p>
            </div>
        </div>
    </div>

    {{-- Bank Details --}}
    <div class="card mb-6 space-y-4">
        <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
            <div class="w-10 h-10 bg-gray-700 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-[#1a1a2e]">Bank Account Details</h3>
                <p class="text-xs text-gray-400">Transfer the exact amount to the account below</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @if($bankDetails['bank_name'])
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Bank Name</p>
                <p class="text-sm font-semibold text-[#1a1a2e] mt-1">{{ $bankDetails['bank_name'] }}</p>
            </div>
            @endif
            @if($bankDetails['account_name'])
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Account Name</p>
                <p class="text-sm font-semibold text-[#1a1a2e] mt-1">{{ $bankDetails['account_name'] }}</p>
            </div>
            @endif
            @if($bankDetails['sort_code'])
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Sort Code</p>
                <p class="text-sm font-semibold text-[#1a1a2e] mt-1 font-mono">{{ $bankDetails['sort_code'] }}</p>
            </div>
            @endif
            @if($bankDetails['account_number'])
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Account Number</p>
                <p class="text-sm font-semibold text-[#1a1a2e] mt-1 font-mono">{{ $bankDetails['account_number'] }}</p>
            </div>
            @endif
            @if($bankDetails['iban'])
            <div class="bg-gray-50 rounded-xl p-4 sm:col-span-2">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">IBAN</p>
                <p class="text-sm font-semibold text-[#1a1a2e] mt-1 font-mono">{{ $bankDetails['iban'] }}</p>
            </div>
            @endif
        </div>

        @if($bankDetails['reference_note'])
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
            <p class="text-xs font-medium text-amber-800">{{ $bankDetails['reference_note'] }}</p>
        </div>
        @endif
    </div>

    {{-- Upload Receipt --}}
    <div class="card">
        <div class="flex items-center gap-3 border-b border-gray-100 pb-4 mb-5">
            <div class="w-10 h-10 bg-[#e05a3a] rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-[#1a1a2e]">Upload Payment Receipt</h3>
                <p class="text-xs text-gray-400">After making the transfer, upload your receipt or screenshot</p>
            </div>
        </div>

        <form action="{{ route('products.bank-transfer.submit', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="form-label">Payment Receipt</label>
                <div class="mt-1 border-2 border-dashed border-gray-200 rounded-xl p-8 text-center hover:border-[#e05a3a] transition-colors cursor-pointer" onclick="document.getElementById('receipt_input').click()">
                    <svg class="w-10 h-10 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <p class="text-sm text-gray-500" id="receipt_label">Click to upload receipt</p>
                    <p class="text-[11px] text-gray-400 mt-1">JPG, PNG or PDF — max 5MB</p>
                </div>
                <input type="file" name="receipt" id="receipt_input" accept=".jpg,.jpeg,.png,.pdf" class="hidden" onchange="document.getElementById('receipt_label').textContent = this.files[0]?.name || 'Click to upload receipt'" required>
                @error('receipt') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn-primary w-full justify-center py-3 text-base">
                Submit Receipt for Review
            </button>
        </form>
    </div>
    @endif
</div>
@endsection
