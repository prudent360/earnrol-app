@extends('layouts.app')

@section('title', 'Products')
@section('page_title', 'Products')
@section('page_subtitle', 'Premium digital resources and assets')

@section('content')

@if($products->count() > 0)
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($products as $product)
    @php
        // Logic to determine a default icon and color based on title or type
        $title = strtolower($product->title);
        $icon = 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z';
        $gradient = 'from-blue-500 to-indigo-600';
        
        if (str_contains($title, 'code') || str_contains($title, 'script') || str_contains($title, 'app')) {
            $icon = 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4';
            $gradient = 'from-emerald-500 to-teal-600';
        } elseif (str_contains($title, 'book') || str_contains($title, 'guide') || str_contains($title, 'pdf')) {
            $icon = 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253';
            $gradient = 'from-amber-500 to-orange-600';
        } elseif (str_contains($title, 'video') || str_contains($title, 'course')) {
            $icon = 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z';
            $gradient = 'from-rose-500 to-pink-600';
        }
    @endphp
    
    <a href="{{ route('products.show', $product) }}" class="group relative bg-white rounded-[2rem] p-5 border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col h-full">
        {{-- Product Image / Icon --}}
        <div class="relative aspect-[4/3] rounded-2xl overflow-hidden mb-5 flex-shrink-0 bg-gray-50 flex items-center justify-center">
            @if($product->cover_image)
                <img src="{{ Storage::url($product->cover_image) }}" alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            @else
                <div class="w-full h-full bg-gradient-to-br {{ $gradient }} flex items-center justify-center opacity-90 group-hover:opacity-100 transition-opacity">
                    <svg class="w-16 h-16 text-white/40 group-hover:scale-110 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                    </svg>
                </div>
            @endif
            
            {{-- Category / Badge Overlay --}}
            <div class="absolute top-3 left-3">
                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white/90 backdrop-blur-sm text-gray-800 shadow-sm">
                    {{ $product->isFree() ? 'Free Resource' : 'Premium Asset' }}
                </span>
            </div>
        </div>

        {{-- Content --}}
        <div class="flex-1 flex flex-col">
            <h4 class="text-lg font-bold text-[#1a1a2e] mb-1 group-hover:text-[#e05a3a] transition-colors line-clamp-1 truncate">{{ $product->title }}</h4>
            @if($product->description)
                <p class="text-[13px] text-gray-500 leading-relaxed line-clamp-2 mb-4">{{ $product->description }}</p>
            @endif

            <div class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-1.5">
                    <div class="w-7 h-7 rounded-lg bg-gray-50 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </div>
                    <span class="text-[11px] font-medium text-gray-400">{{ $product->file_size_formatted }}</span>
                </div>
                
                <div class="text-right">
                    @if($product->isFree())
                        <span class="text-lg font-black text-emerald-500">FREE</span>
                    @else
                        <span class="text-lg font-black text-[#1a1a2e]">{{ \App\Models\Setting::get('currency_symbol', '£') }}{{ number_format($product->price, 2) }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Hover Button --}}
        <div class="absolute inset-0 bg-black/60 backdrop-blur-[2px] rounded-[2rem] opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center">
            <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                <span class="bg-white text-[#1a1a2e] px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg">View Details</span>
            </div>
        </div>
    </a>
    @endforeach
</div>

@if($products->hasPages())
<div class="mt-10">{{ $products->links() }}</div>
@endif

@else
<div class="bg-white rounded-3xl p-16 border border-dashed border-gray-200 text-center flex flex-col items-center">
    <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center mb-4 text-gray-300">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
    </div>
    <h3 class="text-lg font-bold text-gray-800 mb-1">No products found</h3>
    <p class="text-gray-400 text-sm max-w-xs">We haven't added any premium products yet. Check back soon!</p>
</div>
@endif

@endsection
