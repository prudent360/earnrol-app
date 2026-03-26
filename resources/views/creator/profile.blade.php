@extends('layouts.guest')

@section('title', $creator->name . ' — Creator Storefront')
@section('meta_description', $creator->bio ?? $creator->name . ' is a creator on ' . \App\Models\Setting::get('app_name', 'EarnRol') . '. Browse their courses and digital products.')

@section('content')

{{-- Hero / Profile Header --}}
<section class="bg-[#1a2535] text-white">
    <div class="max-w-5xl mx-auto px-6 py-16 text-center">
        {{-- Avatar --}}
        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-[#e05a3a] to-[#ff7f5c] flex items-center justify-center text-white text-4xl font-bold mx-auto mb-5 shadow-lg">
            {{ strtoupper(substr($creator->name, 0, 1)) }}
        </div>
        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">{{ $creator->name }}</h1>
        <p class="text-gray-400 text-sm mt-2">{{ '@' . $creator->username }}</p>
        @if($creator->bio)
        <p class="text-gray-300 text-base mt-4 max-w-xl mx-auto leading-relaxed">{{ $creator->bio }}</p>
        @endif

        {{-- Stats --}}
        <div class="flex items-center justify-center gap-8 mt-8">
            <div class="text-center">
                <p class="text-2xl font-extrabold">{{ $products->count() }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ Str::plural('Product', $products->count()) }}</p>
            </div>
            <div class="w-px h-10 bg-white/10"></div>
            <div class="text-center">
                <p class="text-2xl font-extrabold">{{ $cohorts->count() }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ Str::plural('Cohort', $cohorts->count()) }}</p>
            </div>
        </div>
    </div>
</section>

<div class="max-w-5xl mx-auto px-6 py-12 space-y-16">

    {{-- Cohorts Section --}}
    @if($cohorts->count() > 0)
    <section>
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-[#1a1a2e]">Cohorts</h2>
                <p class="text-sm text-gray-400">Live training sessions by {{ $creator->name }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($cohorts as $cohort)
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 group">
                @if($cohort->cover_image)
                <div class="h-40 overflow-hidden">
                    <img src="{{ Storage::url($cohort->cover_image) }}" alt="{{ $cohort->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                @endif
                <div class="p-5">
                    <h3 class="text-base font-bold text-[#1a1a2e] group-hover:text-[#e05a3a] transition-colors">{{ $cohort->title }}</h3>
                    @if($cohort->description)
                    <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ Str::limit($cohort->description, 120) }}</p>
                    @endif
                    <div class="flex items-center gap-4 mt-4 text-xs text-gray-400">
                        @if($cohort->start_date)
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $cohort->start_date->format('M d, Y') }}
                        </span>
                        @endif
                        @if($cohort->duration)
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $cohort->duration }}
                        </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-50">
                        @if($cohort->price > 0)
                        <span class="text-lg font-extrabold text-[#1a1a2e]">{{ $currencySymbol }}{{ number_format($cohort->price, 2) }}</span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-sm font-bold">Free</span>
                        @endif
                        @auth
                        <a href="{{ route('cohorts.index') }}" class="text-sm font-semibold text-[#e05a3a] hover:underline">View Details &rarr;</a>
                        @else
                        <a href="{{ route('register') }}" class="text-sm font-semibold text-[#e05a3a] hover:underline">Sign Up to Enrol &rarr;</a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Products Section --}}
    @if($products->count() > 0)
    <section>
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-[#1a1a2e]">Digital Products</h2>
                <p class="text-sm text-gray-400">Resources and downloads by {{ $creator->name }}</p>
            </div>
        </div>

        <div class="space-y-4">
            @foreach($products as $product)
            @php
                $docType = \App\Models\DigitalProduct::DOCUMENT_TYPES[$product->document_type] ?? \App\Models\DigitalProduct::DOCUMENT_TYPES['pdf'];
            @endphp
            <a href="{{ route('products.show', $product) }}" class="group block bg-white rounded-2xl border border-gray-100 hover:border-gray-200 hover:shadow-lg transition-all duration-300 overflow-hidden">
                <div class="flex items-center gap-5 p-5">
                    <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-gradient-to-br {{ $docType['icon'] }} flex flex-col items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $docType['svg'] }}"/>
                        </svg>
                        <span class="text-[7px] font-black text-white/80 uppercase tracking-wider mt-0.5">{{ $docType['label'] }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-base font-bold text-[#1a1a2e] group-hover:text-[#e05a3a] transition-colors truncate">{{ $product->title }}</h4>
                        @if($product->description)
                        <p class="text-xs text-gray-400 mt-1 line-clamp-1">{{ $product->description }}</p>
                        @endif
                    </div>
                    <div class="flex-shrink-0 text-right">
                        @if($product->isFree())
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-sm font-bold">Free</span>
                        @else
                        <span class="text-lg font-extrabold text-[#1a1a2e]">{{ $currencySymbol }}{{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Empty State --}}
    @if($products->count() === 0 && $cohorts->count() === 0)
    <div class="text-center py-16">
        <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-[#1a1a2e]">Nothing here yet</h3>
        <p class="text-sm text-gray-400 mt-2">{{ $creator->name }} hasn't published any products or cohorts yet. Check back soon!</p>
    </div>
    @endif

</div>

@endsection
