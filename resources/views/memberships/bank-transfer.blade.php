@extends('layouts.app')

@section('title', 'Bank Transfer — ' . $membership->title)
@section('page_title', 'Bank Transfer')
@section('page_subtitle', $membership->title)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('memberships.show', $membership) }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Membership
        </a>
    </div>

    @if($pendingPayment)
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-6">
        <h3 class="text-sm font-bold text-amber-700 mb-1">Payment Under Review</h3>
        <p class="text-sm text-amber-600">Your bank transfer receipt has been submitted and is being reviewed by an admin. You will be notified once approved.</p>
    </div>
    @endif

    <div class="card mb-6">
        <h3 class="text-sm font-bold text-[#1a1a2e] mb-4">Payment Details</h3>
        <div class="bg-gray-50 rounded-xl p-4 space-y-2 text-sm">
            <p><span class="font-medium text-gray-700">Plan:</span> {{ $membership->title }}</p>
            <p><span class="font-medium text-gray-700">Amount:</span> {{ $bankDetails['currency_symbol'] }}{{ number_format($membership->price, 2) }} / {{ $membership->billing_interval }}</p>
        </div>
    </div>

    <div class="card mb-6">
        <h3 class="text-sm font-bold text-[#1a1a2e] mb-4">Bank Transfer Details</h3>
        <div class="bg-gray-50 rounded-xl p-4 space-y-2 text-sm">
            @if($bankDetails['bank_name'])
            <p><span class="font-medium text-gray-700">Bank:</span> {{ $bankDetails['bank_name'] }}</p>
            @endif
            @if($bankDetails['account_name'])
            <p><span class="font-medium text-gray-700">Account Name:</span> {{ $bankDetails['account_name'] }}</p>
            @endif
            @if($bankDetails['account_number'])
            <p><span class="font-medium text-gray-700">Account Number:</span> {{ $bankDetails['account_number'] }}</p>
            @endif
            @if($bankDetails['sort_code'])
            <p><span class="font-medium text-gray-700">Sort Code:</span> {{ $bankDetails['sort_code'] }}</p>
            @endif
            @if($bankDetails['iban'])
            <p><span class="font-medium text-gray-700">IBAN:</span> {{ $bankDetails['iban'] }}</p>
            @endif
            @if($bankDetails['reference_note'])
            <p class="text-xs text-gray-500 pt-2 border-t border-gray-200">{{ $bankDetails['reference_note'] }}</p>
            @endif
        </div>
    </div>

    @if(!$pendingPayment)
    <div class="card">
        <h3 class="text-sm font-bold text-[#1a1a2e] mb-4">Upload Payment Receipt</h3>
        <form action="{{ route('memberships.bank-transfer.submit', $membership) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label for="receipt" class="form-label">Receipt (JPG, PNG, or PDF)</label>
                <input type="file" name="receipt" id="receipt" accept=".jpg,.jpeg,.png,.pdf" class="form-input @error('receipt') border-red-500 @enderror" required>
                @error('receipt') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">Max 5MB. Upload proof of your bank transfer.</p>
            </div>
            <button type="submit" class="btn-primary w-full justify-center">Submit Receipt</button>
        </form>
    </div>
    @endif
</div>
@endsection
