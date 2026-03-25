@extends('layouts.app')

@section('title', 'Products')
@section('page_title', 'Products')
@section('page_subtitle', 'Premium digital resources and assets')

@section('content')

@if($products->count() > 0)
<div class="space-y-4">
    @foreach($products as $product)
    @php
        $ext = strtolower(pathinfo($product->file_name, PATHINFO_EXTENSION));
        if (in_array($ext, ['pdf'])) {
            $iconColor = 'from-red-500 to-rose-600';
            $iconBg = 'bg-red-50';
            $iconText = 'text-red-500';
            $label = 'PDF';
        } elseif (in_array($ext, ['doc', 'docx'])) {
            $iconColor = 'from-blue-500 to-indigo-600';
            $iconBg = 'bg-blue-50';
            $iconText = 'text-blue-500';
            $label = 'DOC';
        } elseif (in_array($ext, ['xls', 'xlsx', 'csv'])) {
            $iconColor = 'from-emerald-500 to-green-600';
            $iconBg = 'bg-emerald-50';
            $iconText = 'text-emerald-500';
            $label = 'XLS';
        } elseif (in_array($ext, ['zip', 'rar', '7z'])) {
            $iconColor = 'from-amber-500 to-yellow-600';
            $iconBg = 'bg-amber-50';
            $iconText = 'text-amber-500';
            $label = 'ZIP';
        } elseif (in_array($ext, ['mp4', 'mov', 'avi'])) {
            $iconColor = 'from-purple-500 to-violet-600';
            $iconBg = 'bg-purple-50';
            $iconText = 'text-purple-500';
            $label = 'VIDEO';
        } elseif (in_array($ext, ['ppt', 'pptx'])) {
            $iconColor = 'from-orange-500 to-red-500';
            $iconBg = 'bg-orange-50';
            $iconText = 'text-orange-500';
            $label = 'PPT';
        } else {
            $iconColor = 'from-gray-500 to-slate-600';
            $iconBg = 'bg-gray-100';
            $iconText = 'text-gray-500';
            $label = strtoupper($ext) ?: 'FILE';
        }
    @endphp

    <a href="{{ route('products.show', $product) }}" class="group block bg-white rounded-2xl border border-gray-100 hover:border-gray-200 hover:shadow-lg transition-all duration-300 overflow-hidden">
        <div class="flex items-center gap-5 p-5">
            {{-- File Type Icon --}}
            <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-gradient-to-br {{ $iconColor }} flex flex-col items-center justify-center shadow-sm group-hover:scale-105 transition-transform duration-300">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <span class="text-[8px] font-black text-white/80 uppercase tracking-wider mt-0.5">{{ $label }}</span>
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <h4 class="text-base font-bold text-[#1a1a2e] group-hover:text-[#e05a3a] transition-colors truncate">{{ $product->title }}</h4>
                        @if($product->description)
                        <p class="text-xs text-gray-400 mt-1 line-clamp-1">{{ $product->description }}</p>
                        @endif
                    </div>

                    {{-- Price --}}
                    <div class="flex-shrink-0 text-right">
                        @if($product->isFree())
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-sm font-bold">Free</span>
                        @else
                        <span class="text-lg font-extrabold text-[#1a1a2e]">{{ $currencySymbol }}{{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>
                </div>

                {{-- Meta --}}
                <div class="flex items-center gap-3 mt-2.5">
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold {{ $iconText }} {{ $iconBg }} px-2.5 py-1 rounded-lg">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        {{ $product->file_size_formatted }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-gray-400 bg-gray-50 px-2.5 py-1 rounded-lg">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Instant Access
                    </span>
                    @if($product->download_count > 0)
                    <span class="hidden sm:inline-flex items-center gap-1.5 text-[11px] font-semibold text-gray-400 bg-gray-50 px-2.5 py-1 rounded-lg">
                        {{ $product->download_count }} {{ Str::plural('download', $product->download_count) }}
                    </span>
                    @endif
                </div>
            </div>

            {{-- Arrow --}}
            <div class="hidden sm:flex flex-shrink-0 w-9 h-9 rounded-full bg-gray-50 group-hover:bg-[#1a2535] items-center justify-center transition-colors duration-300">
                <svg class="w-4 h-4 text-gray-400 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </div>
    </a>
    @endforeach
</div>

@if($products->hasPages())
<div class="mt-8">{{ $products->links() }}</div>
@endif

@else
<div class="bg-white rounded-2xl p-12 border border-dashed border-gray-300 text-center">
    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
    </div>
    <p class="text-gray-500 text-sm">No products available yet. Check back soon!</p>
</div>
@endif

@endsection
