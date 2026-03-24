@extends('layouts.app')

@section('title', 'Add Material — ' . $cohort->title)
@section('page_title', 'Add Material')
@section('page_subtitle', $cohort->title)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.cohorts.materials.index', $cohort) }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Materials
        </a>
    </div>

    <div class="card">
        <form action="{{ route('admin.cohorts.materials.store', $cohort) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="type" class="form-label">Type</label>
                <select name="type" id="type" class="form-input @error('type') border-red-500 @enderror" required onchange="toggleDueDate(this.value)">
                    <option value="material" {{ old('type') === 'material' ? 'selected' : '' }}>Study Material</option>
                    <option value="assignment" {{ old('type') === 'assignment' ? 'selected' : '' }}>Assignment</option>
                </select>
                @error('type') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1"><strong>Material</strong> = downloadable files (slides, PDFs, notes). <strong>Assignment</strong> = students can upload submissions.</p>
            </div>

            <div>
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-input @error('title') border-red-500 @enderror" value="{{ old('title') }}" required placeholder="e.g. Week 1 Slides or Assignment 1: Cloud Setup">
                @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="form-label">Description (optional)</label>
                <textarea name="description" id="description" rows="3" class="form-input @error('description') border-red-500 @enderror" placeholder="Brief description or instructions...">{{ old('description') }}</textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div id="due_date_field" class="hidden">
                <label for="due_date" class="form-label">Due Date (optional)</label>
                <input type="date" name="due_date" id="due_date" class="form-input @error('due_date') border-red-500 @enderror" value="{{ old('due_date') }}">
                @error('due_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="file" class="form-label">File (optional)</label>
                <input type="file" name="file" id="file" class="form-input @error('file') border-red-500 @enderror">
                @error('file') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">Max 20MB. PDFs, documents, slides, images, etc.</p>
            </div>

            <div class="pt-4 border-t border-[#e8eaf0] flex justify-end">
                <button type="submit" class="btn-primary">Upload Material</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleDueDate(type) {
    document.getElementById('due_date_field').classList.toggle('hidden', type !== 'assignment');
}
toggleDueDate(document.getElementById('type').value);
</script>
@endsection
