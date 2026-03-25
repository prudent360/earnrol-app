@extends('layouts.app')

@section('title', 'Edit Product')
@section('page_title', 'Edit Product')
@section('page_subtitle', $product->title)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Products
        </a>
    </div>

    <div class="card">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-input @error('status') border-red-500 @enderror" required>
                        <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $product->status) == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="archived" {{ old('status', $product->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                    @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
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
                    <label class="flex items-center gap-2 cursor-pointer mt-2 group">
                        <input type="checkbox" name="remove_cover_image" value="1" class="w-4 h-4 rounded border-gray-300 text-red-500 focus:ring-red-400">
                        <span class="text-sm text-gray-500 group-hover:text-red-600 transition-colors">Remove cover image</span>
                    </label>
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
                        <p class="text-xs text-gray-400">{{ $product->file_size_formatted }} &middot; {{ $product->download_count }} downloads</p>
                    </div>
                </div>
                <input type="file" name="file" id="file" class="form-input @error('file') border-red-500 @enderror">
                @error('file') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">Leave empty to keep the current file. Max 50MB.</p>
            </div>

            {{-- Stats --}}
            <div class="bg-gray-50 rounded-xl p-4 grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Purchases</p>
                    <p class="text-lg font-bold text-[#1a1a2e]">{{ $product->purchases()->count() }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Downloads</p>
                    <p class="text-lg font-bold text-[#1a1a2e]">{{ $product->download_count }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Revenue</p>
                    <p class="text-lg font-bold text-green-600">{{ \App\Models\Setting::get('currency_symbol', '£') }}{{ number_format($product->payments()->where('status', 'completed')->sum('amount'), 2) }}</p>
                </div>
            </div>

            <div class="pt-4 border-t border-[#e8eaf0] flex justify-end">
                <button type="submit" class="btn-primary">Update Product</button>
            </div>
        </form>
    </div>
</div>
@endsection
