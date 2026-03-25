@extends('layouts.app')

@section('title', 'My Downloads')
@section('page_title', 'My Downloads')
@section('page_subtitle', 'Your purchased digital resources')

@section('content')

@if($purchases->count() > 0)
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
    @foreach($purchases as $purchase)
    @php $product = $purchase->product; @endphp
    @if($product)
    <div class="card">
        @if($product->cover_image)
        <div class="-mx-5 -mt-5 mb-4 rounded-t-2xl overflow-hidden">
            <img src="{{ Storage::url($product->cover_image) }}" alt="{{ $product->title }}" class="w-full h-36 object-cover">
        </div>
        @endif

        <h4 class="font-bold text-[#1a1a2e] mb-1">{{ $product->title }}</h4>
        <p class="text-xs text-gray-400 mb-3">Purchased {{ $purchase->purchased_at->format('M d, Y') }}</p>

        <div class="bg-gray-50 rounded-xl p-3 mb-4 flex items-center gap-3">
            <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            <div>
                <p class="text-xs font-medium text-[#1a1a2e]">{{ $product->file_name }}</p>
                <p class="text-[10px] text-gray-400">{{ $product->file_size_formatted }} &middot; Downloaded {{ $purchase->download_count }} {{ Str::plural('time', $purchase->download_count) }}</p>
            </div>
        </div>

        <a href="{{ route('products.download', $product) }}" class="btn-primary w-full justify-center py-2.5 text-sm inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Download
        </a>
    </div>
    @endif
    @endforeach
</div>

@if($purchases->hasPages())
<div class="mt-6">{{ $purchases->links() }}</div>
@endif

@else
<div class="bg-white rounded-2xl p-12 border border-dashed border-gray-300 text-center">
    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
    <p class="text-gray-500 text-sm mb-2">You haven't purchased any resources yet.</p>
    <a href="{{ route('products.index') }}" class="text-[#e05a3a] text-sm font-bold hover:underline">Browse Products</a>
</div>
@endif

@endsection
