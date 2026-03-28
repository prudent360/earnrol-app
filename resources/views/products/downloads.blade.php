@extends('layouts.app')

@section('title', 'My Downloads')
@section('page_title', 'My Downloads')
@section('page_subtitle', 'Your purchased digital resources')

@section('content')

@if($purchases->count() > 0)
<div class="card overflow-hidden !p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f5f6fa] border-b border-[#e8eaf0]">
                    <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Purchased</th>
                    <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Size</th>
                    <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Downloads</th>
                    <th class="px-4 sm:px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e8eaf0]">
                @foreach($purchases as $purchase)
                @php
                    $product = $purchase->product;
                    if (!$product) continue;
                    $docType = \App\Models\DigitalProduct::DOCUMENT_TYPES[$product->document_type] ?? \App\Models\DigitalProduct::DOCUMENT_TYPES['pdf'];
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 sm:px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gradient-to-br {{ $docType['icon'] }} flex flex-col items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $docType['svg'] }}"/></svg>
                                <span class="text-[5px] font-black text-white/80 uppercase tracking-wider leading-none">{{ $docType['label'] }}</span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-[#1a1a2e] truncate">{{ $product->title }}</p>
                                <p class="text-[11px] text-gray-400 truncate">{{ $product->file_name }}</p>
                                <p class="text-[11px] text-gray-400 sm:hidden mt-0.5">{{ $purchase->purchased_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 sm:px-6 py-4 text-sm text-gray-500 hidden sm:table-cell">{{ $purchase->purchased_at->format('M d, Y') }}</td>
                    <td class="px-4 sm:px-6 py-4 text-sm text-gray-500 hidden md:table-cell">{{ $product->file_size_formatted }}</td>
                    <td class="px-4 sm:px-6 py-4 text-sm text-gray-500 hidden md:table-cell">{{ $purchase->download_count }} {{ Str::plural('time', $purchase->download_count) }}</td>
                    <td class="px-4 sm:px-6 py-4 text-right">
                        <a href="{{ route('products.download', $product) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#e05a3a] hover:text-[#c94e31] transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
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
