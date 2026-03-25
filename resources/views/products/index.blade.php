@extends('layouts.app')

@section('title', 'Products')
@section('page_title', 'Products')
@section('page_subtitle', 'Premium digital resources and assets')

@section('content')

@if($products->count() > 0)
<div class="space-y-6">
    @foreach($products as $product)
    @php
        // Logic to determine a default icon and color based on title or type
        $title = strtolower($product->title);
        $icon = 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z';
        $gradient = 'from-blue-500 to-indigo-600';
        $bgLight = 'bg-blue-50';
        
        if (str_contains($title, 'code') || str_contains($title, 'script') || str_contains($title, 'app')) {
            $icon = 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4';
            $gradient = 'from-emerald-500 to-teal-600';
            $bgLight = 'bg-emerald-50';
        } elseif (str_contains($title, 'book') || str_contains($title, 'guide') || str_contains($title, 'pdf')) {
            $icon = 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253';
            $gradient = 'from-amber-500 to-orange-600';
            $bgLight = 'bg-amber-50';
        } elseif (str_contains($title, 'video') || str_contains($title, 'course')) {
            $icon = 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z';
            $gradient = 'from-rose-500 to-pink-600';
            $bgLight = 'bg-rose-50';
        }
    @endphp
    
    <a href="{{ route('products.show', $product) }}" class="group relative bg-white rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col md:flex-row overflow-hidden">
        {{-- Product Image / Icon (Left Side) --}}
        <div class="w-full md:w-80 h-56 md:h-auto flex-shrink-0 relative overflow-hidden bg-gray-50 flex items-center justify-center">
            @if($product->cover_image)
                <img src="{{ Storage::url($product->cover_image) }}" alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="w-full h-full bg-gradient-to-br {{ $gradient }} flex items-center justify-center relative overflow-hidden">
                    {{-- Large watermark icon --}}
                    <svg class="absolute -right-8 -bottom-8 w-48 h-48 text-white/10 rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                    
                    {{-- Central icon --}}
                    <div class="relative w-20 h-20 bg-white/20 backdrop-blur-md rounded-3xl flex items-center justify-center shadow-2xl border border-white/30 group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                        </svg>
                    </div>
                </div>
            @endif
            
            {{-- Badge Overlay --}}
            <div class="absolute top-4 left-4">
                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white/90 backdrop-blur-sm text-gray-800 shadow-sm">
                    {{ $product->isFree() ? 'Free Resource' : 'Premium Asset' }}
                </span>
            </div>
        </div>

        {{-- Content (Right Side) --}}
        <div class="flex-1 p-6 md:p-8 flex flex-col justify-center">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-4">
                <div class="flex-1">
                    <h4 class="text-xl md:text-2xl font-black text-[#1a1a2e] group-hover:text-[#e05a3a] transition-colors mb-2 leading-tight">{{ $product->title }}</h4>
                    @if($product->description)
                        <p class="text-sm text-gray-500 leading-relaxed line-clamp-2 md:line-clamp-3">{{ $product->description }}</p>
                    @endif
                </div>
                
                <div class="flex-shrink-0 text-left md:text-right">
                    @if($product->isFree())
                        <span class="text-2xl font-black text-emerald-500 tracking-tight">FREE</span>
                    @else
                        <span class="text-2xl font-black text-[#1a1a2e] tracking-tight">{{ \App\Models\Setting::get('currency_symbol', '£') }}{{ number_format($product->price, 2) }}</span>
                    @endif
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">One-time payment</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-4 mt-2">
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl {{ $bgLight }} text-gray-600">
                    <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span class="text-xs font-bold tracking-tight">{{ $product->file_size_formatted }} Resource</span>
                </div>
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-gray-50 text-gray-500">
                    <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span class="text-xs font-bold tracking-tight">Verified Download</span>
                </div>
            </div>
        </div>

        {{-- Hover Action Indicator (Optional, since the card itself is the link) --}}
        <div class="absolute right-8 top-1/2 -translate-y-1/2 hidden lg:flex items-center justify-center w-12 h-12 rounded-full bg-[#1a1a2e] text-white opacity-0 group-hover:opacity-100 group-hover:scale-110 transition-all duration-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
    </a>
    @endforeach
</div>

@if($products->hasPages())
<div class="mt-12 flex justify-center">{{ $products->links() }}</div>
@endif

@else
<div class="bg-white rounded-[2.5rem] p-20 border border-dashed border-gray-200 text-center flex flex-col items-center">
    <div class="w-24 h-24 bg-gray-50 rounded-[2rem] flex items-center justify-center mb-6 text-gray-300">
        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
    </div>
    <h3 class="text-2xl font-black text-gray-800 mb-2">No products found</h3>
    <p class="text-gray-400 text-base max-w-sm">We haven't added any premium products yet. Check back soon for exclusive resources!</p>
</div>
@endif

@endsection
