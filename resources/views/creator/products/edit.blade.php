@extends('layouts.app')

@section('title', 'Edit Product')
@section('page_title', 'Edit Product')
@section('page_subtitle', $product->title)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('creator.products.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to My Products
        </a>
    </div>

    @if($product->approval_status === 'rejected' && $product->rejection_reason)
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 flex items-start gap-3">
        <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <p class="text-sm font-semibold text-red-800">Rejected by Admin</p>
            <p class="text-sm text-red-600 mt-0.5">{{ $product->rejection_reason }}</p>
        </div>
    </div>
    @endif

    @if($product->approval_status === 'approved')
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6 flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
        <p class="text-sm text-amber-700">Editing an approved product will re-submit it for review. It may be temporarily hidden from buyers.</p>
    </div>
    @endif

    <div class="card">
        <form action="{{ route('creator.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Basic Info --}}
            <div class="border-b border-gray-100 pb-4">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Product Information</h3>
            </div>

            <div>
                <label for="title" class="form-label">Product Title</label>
                <input type="text" name="title" id="title" class="form-input @error('title') border-red-500 @enderror" value="{{ old('title', $product->title) }}" required>
                @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="4" class="form-input @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="price" class="form-label">Price ({{ \App\Models\Setting::get('currency_symbol', '£') }})</label>
                    <input type="number" name="price" id="price" step="0.01" min="0" class="form-input @error('price') border-red-500 @enderror" value="{{ old('price', $product->price) }}" required>
                    @error('price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="document_type" class="form-label">Document Type</label>
                    <select name="document_type" id="document_type" class="form-input @error('document_type') border-red-500 @enderror" required>
                        @foreach(\App\Models\DigitalProduct::DOCUMENT_TYPES as $key => $type)
                        <option value="{{ $key }}" {{ old('document_type', $product->document_type) == $key ? 'selected' : '' }}>{{ $type['label'] }}</option>
                        @endforeach
                    </select>
                    @error('document_type') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Files --}}
            <div class="border-b border-gray-100 pb-4 pt-2">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Files</h3>
            </div>

            <div>
                <label for="cover_image" class="form-label">Cover Image</label>
                @if($product->cover_image)
                <div class="mb-3 flex items-start gap-4">
                    <img src="{{ Storage::url($product->cover_image) }}" alt="" class="w-32 h-24 object-cover rounded-lg">
                </div>
                @endif
                <input type="file" name="cover_image" id="cover_image" accept="image/*" class="form-input @error('cover_image') border-red-500 @enderror">
                @error('cover_image') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">Leave empty to keep the current image.</p>
            </div>

            <div>
                <label for="file" class="form-label">Downloadable File</label>
                <div class="mb-2 bg-gray-50 rounded-xl px-4 py-3 flex items-center gap-3">
                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <div>
                        <p class="text-sm font-medium text-[#1a1a2e]">{{ $product->file_name }}</p>
                        <p class="text-xs text-gray-400">{{ $product->file_size_formatted }}</p>
                    </div>
                </div>
                <input type="file" name="file" id="file" class="form-input @error('file') border-red-500 @enderror">
                @error('file') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">Leave empty to keep the current file. Max 50MB.</p>
            </div>

            <div class="pt-4 border-t border-[#e8eaf0] flex justify-end">
                <button type="submit" class="btn-primary">Update Product</button>
            </div>
        </form>
    </div>
</div>
@endsection
