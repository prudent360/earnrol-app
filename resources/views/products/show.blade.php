@extends('layouts.app')

@section('title', $product->title)
@section('page_title', $product->title)
@section('page_subtitle', 'Digital Resource')

@section('content')

<div class="mb-6">
    <a href="{{ route('products.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Products
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Product Details --}}
    <div class="lg:col-span-2 space-y-5">
        @if($product->cover_image)
        <div class="rounded-2xl overflow-hidden">
            <img src="{{ Storage::url($product->cover_image) }}" alt="{{ $product->title }}" class="w-full h-64 object-cover">
        </div>
        @endif

        <div class="card">
            <h2 class="text-xl font-bold text-[#1a1a2e] mb-3">{{ $product->title }}</h2>
            @if($product->description)
            <div class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $product->description }}</div>
            @endif
        </div>

        {{-- File Info --}}
        <div class="card">
            <h3 class="text-sm font-bold text-[#1a1a2e] mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                What You Get
            </h3>
            @php $docType = \App\Models\DigitalProduct::DOCUMENT_TYPES[$product->document_type] ?? \App\Models\DigitalProduct::DOCUMENT_TYPES['pdf']; @endphp
            <div class="bg-gray-50 rounded-xl p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br {{ $docType['icon'] }} flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $docType['svg'] }}"/></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-[#1a1a2e]">{{ $product->file_name }}</p>
                    <p class="text-xs text-gray-400">{{ $product->file_size_formatted }} &middot; Instant download after purchase</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Purchase Sidebar --}}
    <div class="space-y-5">
        <div class="card sticky top-6">
            {{-- Price --}}
            <div class="text-center mb-5">
                @if($product->isFree())
                <p class="text-3xl font-extrabold text-green-600">Free</p>
                @else
                <p class="text-3xl font-extrabold text-[#1a1a2e]">{{ $currencySymbol }}{{ number_format($product->price, 2) }}</p>
                @endif
                <p class="text-xs text-gray-400 mt-1">One-time purchase &middot; Lifetime access</p>
            </div>

            @if($purchased)
                {{-- Already purchased — show download --}}
                <a href="{{ route('products.download', $product) }}" class="btn-primary w-full justify-center py-3 text-sm inline-flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download Now
                </a>
                <p class="text-center text-xs text-green-600 font-semibold">You own this product</p>
            @elseif($product->isFree())
                {{-- Free product --}}
                <form method="POST" action="{{ route('products.get-free', $product) }}">
                    @csrf
                    <button type="submit" class="btn-primary w-full justify-center py-3 text-sm">Get Free Download</button>
                </form>
            @elseif($paymentEnabled)
                {{-- Paid product — payment buttons --}}
                <div class="space-y-2">
                    @if($stripeEnabled)
                    <form method="POST" action="{{ route('products.stripe.checkout', $product) }}">
                        @csrf
                        <button type="submit" class="btn-primary w-full justify-center py-3 text-sm inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Pay with Card
                        </button>
                    </form>
                    @endif
                    @if($paypalEnabled)
                    <form method="POST" action="{{ route('products.paypal.checkout', $product) }}">
                        @csrf
                        <button type="submit" class="w-full py-3 rounded-xl text-sm font-bold bg-[#003087] text-white hover:bg-[#002060] transition-colors inline-flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106z"/></svg>
                            Pay with PayPal
                        </button>
                    </form>
                    @endif
                    @if($bankTransferEnabled)
                    <a href="{{ route('products.bank-transfer', $product) }}" class="w-full py-3 rounded-xl text-sm font-bold bg-gray-700 text-white hover:bg-gray-800 transition-colors inline-flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        Bank Transfer
                    </a>
                    @endif
                </div>
            @else
                <p class="text-center text-sm text-gray-500">Payments are currently disabled.</p>
            @endif

            <div class="mt-5 pt-4 border-t border-gray-100 space-y-2 text-xs text-gray-400">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Instant download after payment
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Lifetime access — download anytime
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
