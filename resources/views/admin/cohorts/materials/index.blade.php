@extends('layouts.app')

@section('title', 'Materials — ' . $cohort->title)
@section('page_title', $cohort->title)
@section('page_subtitle', 'Manage materials and assignments')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.cohorts.edit', $cohort) }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Cohort
        </a>
        <a href="{{ route('admin.cohorts.materials.create', $cohort) }}" class="btn-primary text-sm">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Material
        </a>
    </div>

    @if($materials->isEmpty())
    <div class="bg-white rounded-2xl p-12 border border-dashed border-gray-300 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <h3 class="text-lg font-bold text-[#1a1a2e] mb-2">No materials yet</h3>
        <p class="text-gray-500 text-sm mb-6">Upload study materials or create assignments for your students.</p>
        <a href="{{ route('admin.cohorts.materials.create', $cohort) }}" class="btn-primary text-sm">Add First Material</a>
    </div>
    @else
    <div class="space-y-4">
        @foreach($materials as $material)
        <div class="card">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        @if($material->type === 'assignment')
                        <span class="badge bg-orange-100 text-orange-700">Assignment</span>
                        @else
                        <span class="badge bg-blue-100 text-blue-700">Material</span>
                        @endif
                        <h3 class="text-lg font-bold text-[#1a1a2e]">{{ $material->title }}</h3>
                    </div>
                    @if($material->description)
                    <p class="text-sm text-gray-500 mb-2">{{ $material->description }}</p>
                    @endif
                    <div class="flex items-center gap-4 text-xs text-gray-400">
                        <span>Uploaded by {{ $material->uploader->name }}</span>
                        <span>{{ $material->created_at->format('M d, Y') }}</span>
                        @if($material->due_date)
                        <span class="text-orange-600 font-medium">Due {{ $material->due_date->format('M d, Y') }}</span>
                        @endif
                        @if($material->file_name)
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            {{ $material->file_name }}
                        </span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    @if($material->file_path)
                    <a href="{{ Storage::url($material->file_path) }}" target="_blank" class="text-sm font-medium text-[#e05a3a] hover:underline flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download
                    </a>
                    @endif
                    @if($material->isAssignment())
                    <a href="{{ route('admin.cohorts.materials.submissions', [$cohort, $material]) }}" class="text-sm font-medium text-blue-600 hover:underline flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        {{ $material->submissions()->count() }} Submissions
                    </a>
                    @endif
                    <form method="POST" action="{{ route('admin.cohorts.materials.destroy', [$cohort, $material]) }}" onsubmit="return confirm('Delete this material?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
