@extends('layouts.app')

@section('title', 'Add Content — ' . $membership->title)
@section('page_title', 'Add Content')
@section('page_subtitle', $membership->title)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('creator.memberships.contents.index', $membership) }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Content
        </a>
    </div>

    <div class="card">
        <form action="{{ route('creator.memberships.contents.store', $membership) }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ contentType: '{{ old('content_type', 'file') }}' }">
            @csrf

            <div>
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-input @error('title') border-red-500 @enderror" value="{{ old('title') }}" required placeholder="e.g. Week 1 — Getting Started">
                @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="form-label">Description (optional)</label>
                <textarea name="description" id="description" rows="2" class="form-input @error('description') border-red-500 @enderror" placeholder="Brief description of this content...">{{ old('description') }}</textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="content_type" class="form-label">Content Type</label>
                <select name="content_type" id="content_type" class="form-input" x-model="contentType">
                    <option value="file">File Upload</option>
                    <option value="video">Video</option>
                    <option value="link">External Link</option>
                    <option value="text">Text Content</option>
                </select>
            </div>

            <div x-show="contentType === 'file'" x-cloak>
                <label for="file" class="form-label">Upload File</label>
                <input type="file" name="file" id="file" class="form-input @error('file') border-red-500 @enderror">
                @error('file') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">Max 50MB.</p>
            </div>

            <div x-show="contentType === 'link' || contentType === 'video'" x-cloak>
                <label for="external_url" class="form-label">URL</label>
                <input type="url" name="external_url" id="external_url" class="form-input @error('external_url') border-red-500 @enderror" value="{{ old('external_url') }}" placeholder="https://...">
                @error('external_url') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div x-show="contentType === 'text'" x-cloak>
                <label for="body" class="form-label">Content</label>
                <textarea name="body" id="body" rows="8" class="form-input @error('body') border-red-500 @enderror" placeholder="Write your content here...">{{ old('body') }}</textarea>
                @error('body') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 border-t border-[#e8eaf0] flex justify-end">
                <button type="submit" class="btn-primary">Add Content</button>
            </div>
        </form>
    </div>
</div>
@endsection
