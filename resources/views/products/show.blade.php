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

        {{-- Reviews Section --}}
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-[#1a1a2e] flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    Reviews
                </h3>
                @if($averageRating)
                <div class="flex items-center gap-1.5">
                    <div class="flex items-center gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        @endfor
                    </div>
                    <span class="text-sm font-bold text-[#1a1a2e]">{{ number_format($averageRating, 1) }}</span>
                    <span class="text-xs text-gray-400">({{ $reviewCount }})</span>
                </div>
                @endif
            </div>

            {{-- Review Form --}}
            @if($purchased && !$userReview)
            <div x-data="{ rating: 0, hoverRating: 0 }" class="mb-5 pb-5 border-b border-gray-100">
                <form method="POST" action="{{ route('products.reviews.store', $product) }}">
                    @csrf
                    <p class="text-xs font-medium text-gray-500 mb-2">Leave a review</p>
                    <div class="flex items-center gap-1 mb-3">
                        @for($i = 1; $i <= 5; $i++)
                        <button type="button" @click="rating = {{ $i }}" @mouseenter="hoverRating = {{ $i }}" @mouseleave="hoverRating = 0" class="focus:outline-none">
                            <svg class="w-7 h-7 transition-colors" :class="(hoverRating || rating) >= {{ $i }} ? 'text-yellow-400' : 'text-gray-200'" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" :value="rating">
                    <textarea name="comment" rows="3" class="form-input text-sm" placeholder="Share your thoughts (optional)..." maxlength="1000"></textarea>
                    <button type="submit" class="btn-primary text-sm py-2 mt-2" :disabled="rating === 0" :class="rating === 0 ? 'opacity-50 cursor-not-allowed' : ''">Submit Review</button>
                </form>
            </div>
            @elseif($userReview)
            <div class="mb-5 pb-5 border-b border-gray-100">
                <div class="bg-gray-50 rounded-xl p-3">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="flex items-center gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= $userReview->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            @endfor
                        </div>
                        <span class="text-xs text-gray-400">Your review {{ $userReview->is_approved ? '' : '(pending approval)' }}</span>
                    </div>
                    @if($userReview->comment)
                    <p class="text-sm text-gray-600">{{ $userReview->comment }}</p>
                    @endif
                </div>
            </div>
            @endif

            {{-- Review List --}}
            @if($reviews->count() > 0)
            <div class="space-y-4">
                @foreach($reviews as $review)
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-full bg-[#1a2535] flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-semibold text-[#1a1a2e]">{{ $review->user->name }}</p>
                            <div class="flex items-center gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="w-3 h-3 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                @endfor
                            </div>
                            <span class="text-[10px] text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        @if($review->comment)
                        <p class="text-sm text-gray-600 mt-1">{{ $review->comment }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-400 text-center py-4">No reviews yet. Be the first to review!</p>
            @endif
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
                {{-- Coupon Code Input --}}
                <div x-data="{
                    code: '',
                    validCode: '',
                    loading: false,
                    message: '',
                    valid: false,
                    discount: 0,
                    finalAmount: {{ $product->price }},
                    originalPrice: {{ $product->price }},
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
                                    type: 'product',
                                    item_id: {{ $product->id }}
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
                }" class="mb-4">
                    <div class="flex gap-2">
                        <input type="text" x-model="code" placeholder="Coupon code" class="form-input text-sm flex-1" :disabled="valid" @keydown.enter.prevent="applyCoupon()">
                        <button type="button" x-show="!valid" @click="applyCoupon()" :disabled="loading || !code.trim()" class="px-3 py-2 text-xs font-bold rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors disabled:opacity-50">
                            <span x-show="!loading">Apply</span>
                            <span x-show="loading">...</span>
                        </button>
                        <button type="button" x-show="valid" @click="removeCoupon()" class="px-3 py-2 text-xs font-bold rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                            Remove
                        </button>
                    </div>
                    <p x-show="message" x-text="message" class="text-xs mt-1.5" :class="valid ? 'text-green-600' : 'text-red-500'"></p>
                    <div x-show="valid" x-transition class="mt-2 bg-green-50 rounded-lg p-2.5 text-xs space-y-1">
                        <div class="flex justify-between text-gray-500">
                            <span>Original price</span>
                            <span>{{ \App\Models\Setting::get('currency_symbol', '£') }}<span x-text="originalPrice.toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between text-green-600 font-semibold">
                            <span>Discount</span>
                            <span>-{{ \App\Models\Setting::get('currency_symbol', '£') }}<span x-text="discount.toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between text-[#1a1a2e] font-bold border-t border-green-200 pt-1">
                            <span>Total</span>
                            <span>{{ \App\Models\Setting::get('currency_symbol', '£') }}<span x-text="finalAmount.toFixed(2)"></span></span>
                        </div>
                    </div>

                    {{-- Paid product — payment buttons --}}
                    <div class="space-y-2 mt-3">
                        @if($stripeEnabled)
                        <form method="POST" action="{{ route('products.stripe.checkout', $product) }}">
                            @csrf
                            <input type="hidden" name="coupon_code" :value="validCode">
                            <button type="submit" class="btn-primary w-full justify-center py-3 text-sm inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                Pay with Card
                            </button>
                        </form>
                        @endif
                        @if($paypalEnabled)
                        <form method="POST" action="{{ route('products.paypal.checkout', $product) }}">
                            @csrf
                            <input type="hidden" name="coupon_code" :value="validCode">
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
