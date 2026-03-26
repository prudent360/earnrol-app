@extends('layouts.app')

@section('title', 'My Products')
@section('page_title', 'My Products')
@section('page_subtitle', 'Manage your digital products')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-xl font-bold text-[#1a1a2e]">Your Products</h3>
    <a href="{{ route('creator.products.create') }}" class="btn-primary text-sm py-2">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Product
    </a>
</div>

<div class="card overflow-hidden !p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f5f6fa] border-b border-[#e8eaf0]">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Approval</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">File</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e8eaf0]">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        @php $docType = \App\Models\DigitalProduct::DOCUMENT_TYPES[$product->document_type] ?? \App\Models\DigitalProduct::DOCUMENT_TYPES['pdf']; @endphp
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br {{ $docType['icon'] }} flex flex-col items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $docType['svg'] }}"/></svg>
                                <span class="text-[6px] font-black text-white/80 uppercase tracking-wider">{{ $docType['label'] }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-[#1a1a2e]">{{ $product->title }}</p>
                                <p class="text-xs text-gray-400 truncate max-w-xs">{{ Str::limit($product->description, 50) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($product->approval_status === 'approved')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Approved</span>
                        @elseif($product->approval_status === 'pending')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Pending Review</span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Rejected</span>
                        @if($product->rejection_reason)
                        <p class="text-[10px] text-red-500 mt-1 max-w-xs">{{ $product->rejection_reason }}</p>
                        @endif
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                        @if($product->price > 0)
                        {{ \App\Models\Setting::get('currency_symbol', '£') }}{{ number_format($product->price, 2) }}
                        @else
                        <span class="text-green-600 font-bold">Free</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-xs text-gray-500">{{ $product->file_name }}</p>
                        <p class="text-[10px] text-gray-400">{{ $product->file_size_formatted }}</p>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('creator.products.edit', $product) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</a>
                        <form action="{{ route('creator.products.destroy', $product) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium" onclick="return confirm('Delete this product?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                        No products yet. <a href="{{ route('creator.products.create') }}" class="text-[#e05a3a] hover:underline">Create your first product</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div class="px-6 py-4 border-t border-[#e8eaf0]">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
