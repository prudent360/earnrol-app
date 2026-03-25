@extends('layouts.app')

@section('title', 'Shop')
@section('page_title', 'Shop')
@section('page_subtitle', 'Browse and purchase digital products')

@section('content')

@if($products->count() > 0)
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
    @foreach($products as $product)
    <a href="{{ route('products.show', $product) }}" class="card hover:shadow-md transition-shadow group">
        @if($product->cover_image)
        <div class="-mx-5 -mt-5 mb-4 rounded-t-2xl overflow-hidden">
            <img src="{{ Storage::url($product->cover_image) }}" alt="{{ $product->title }}" class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-300">
        </div>
        @else
        <div class="-mx-5 -mt-5 mb-4 rounded-t-2xl overflow-hidden bg-gradient-to-br from-[#1a2535] to-[#2a3f55] h-40 flex items-center justify-center">
            <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
        </div>
        @endif

        <h4 class="font-bold text-[#1a1a2e] mb-1">{{ $product->title }}</h4>
        @if($product->description)
        <p class="text-xs text-gray-500 mb-3 line-clamp-2">{{ $product->description }}</p>
        @endif

        <div class="flex items-center justify-between mt-auto pt-3 border-t border-gray-100">
            <span class="text-xs text-gray-400">{{ $product->file_size_formatted }}</span>
            @if($product->isFree())
            <span class="text-sm font-bold text-green-600">Free</span>
            @else
            <span class="text-sm font-bold text-[#1a1a2e]">{{ $currencySymbol }}{{ number_format($product->price, 2) }}</span>
            @endif
        </div>
    </a>
    @endforeach
</div>

@if($products->hasPages())
<div class="mt-6">{{ $products->links() }}</div>
@endif

@else
<div class="bg-white rounded-2xl p-12 border border-dashed border-gray-300 text-center">
    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
    <p class="text-gray-500 text-sm">No products available yet. Check back soon!</p>
</div>
@endif

@endsection
