@extends('layouts.app')

@section('title', 'Bank Transfer — ' . $cohort->title)
@section('page_title', 'Bank Transfer')
@section('page_subtitle', 'Complete your payment via bank transfer')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Dashboard
        </a>
    </div>

    {{-- If already has pending transfer --}}
    @if($pendingPayment)
    <div class="card text-center space-y-4">
        <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto">
            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h2 class="text-xl font-bold text-[#1a1a2e]">Payment Under Review</h2>
        <p class="text-sm text-gray-600">You already submitted a payment receipt for <strong>{{ $cohort->title }}</strong>. Our team is reviewing it and you'll be enrolled once approved.</p>
        <p class="text-xs text-gray-400">Reference: {{ $pendingPayment->reference }}</p>
    </div>
    @else

    <div x-data="{
        code: '{{ $couponData['coupon']?->code ?? '' }}',
        validCode: '{{ $couponData['coupon']?->code ?? '' }}',
        loading: false,
        message: '',
        valid: {{ $couponData['coupon'] ? 'true' : 'false' }},
        discount: {{ $couponData['discount'] ?? 0 }},
        finalAmount: {{ $couponData['final_amount'] ?? $cohort->price }},
        originalPrice: {{ $cohort->price }},
        currencySymbol: '{{ $bankDetails['currency_symbol'] }}',
        async applyCoupon() {
            if (!this.code.trim()) return;
            this.loading = true;
            this.message = '';
            try {
                const res = await fetch('{{ route('coupons.validate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        code: this.code,
                        amount: this.originalPrice,
                        type: 'cohort',
                        item_id: {{ $cohort->id }}
                    })
                });
                const data = await res.json();
                this.valid = data.valid;
                this.message = data.message;
                if (data.valid) {
                    this.discount = data.discount;
                    this.finalAmount = data.final_amount;
                    this.validCode = this.code;
                } else {
                    this.discount = 0;
                    this.finalAmount = this.originalPrice;
                    this.validCode = '';
                }
            } catch (e) {
                this.message = 'Unable to validate coupon.';
                this.valid = false;
            }
            this.loading = false;
        },
        removeCoupon() {
            this.code = '';
            this.validCode = '';
            this.valid = false;
            this.message = '';
            this.discount = 0;
            this.finalAmount = this.originalPrice;
        }
    }">

    {{-- Cohort Summary --}}
    <div class="card mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-[#1a1a2e]">{{ $cohort->title }}</h2>
                <p class="text-xs text-gray-400 mt-1">Starts {{ $cohort->start_date->format('M d, Y') }}</p>
            </div>
            <div class="text-right">
                <template x-if="valid">
                    <div>
                        <p class="text-sm text-gray-400 line-through" x-text="currencySymbol + originalPrice.toFixed(2)"></p>
                        <p class="text-2xl font-bold text-green-600" x-text="currencySymbol + finalAmount.toFixed(2)"></p>
                    </div>
                </template>
                <template x-if="!valid">
                    <p class="text-2xl font-bold text-[#1a1a2e]" x-text="currencySymbol + originalPrice.toFixed(2)"></p>
                </template>
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

    {{-- Coupon Code --}}
    <div class="card mb-6">
        <h3 class="text-sm font-bold text-[#1a1a2e] mb-3 flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
            Have a coupon code?
        </h3>
        <div class="flex gap-2">
            <input type="text" x-model="code" placeholder="Enter coupon code" class="form-input text-sm flex-1" :disabled="valid" @keydown.enter.prevent="applyCoupon()">
            <button type="button" x-show="!valid" @click="applyCoupon()" :disabled="loading || !code.trim()" class="px-4 py-2 text-xs font-bold rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors disabled:opacity-50">
                <span x-show="!loading">Apply</span>
                <span x-show="loading">...</span>
            </button>
            <button type="button" x-show="valid" @click="removeCoupon()" class="px-4 py-2 text-xs font-bold rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                Remove
            </button>
        </div>
        <p x-show="message" x-text="message" class="text-xs mt-1.5" :class="valid ? 'text-green-600' : 'text-red-500'"></p>
        <div x-show="valid" x-transition class="mt-3 bg-green-50 border border-green-200 rounded-xl p-4 space-y-1.5 text-sm">
            <div class="flex justify-between text-gray-500">
                <span>Original price</span>
                <span x-text="currencySymbol + originalPrice.toFixed(2)"></span>
            </div>
            <div class="flex justify-between text-green-600 font-semibold">
                <span>Discount</span>
                <span x-text="'-' + currencySymbol + discount.toFixed(2)"></span>
            </div>
            <div class="flex justify-between text-[#1a1a2e] font-bold border-t border-green-200 pt-1.5">
                <span>Amount to transfer</span>
                <span x-text="currencySymbol + finalAmount.toFixed(2)"></span>
            </div>
        </div>
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

        <form action="{{ route('payments.bank-transfer.submit', $cohort) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <input type="hidden" name="coupon_code" :value="validCode">

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

    </div>{{-- close x-data --}}
    @endif
</div>
@endsection
