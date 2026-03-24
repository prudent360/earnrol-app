@extends('layouts.app')

@section('title', 'Submissions — ' . $material->title)
@section('page_title', $material->title)
@section('page_subtitle', 'Student submissions for ' . $cohort->title)

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.cohorts.materials.index', $cohort) }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Materials
        </a>
    </div>

    @if($material->description)
    <div class="card mb-6">
        <p class="text-sm text-gray-600">{{ $material->description }}</p>
        @if($material->due_date)
        <p class="text-xs text-orange-600 font-medium mt-2">Due: {{ $material->due_date->format('M d, Y') }}</p>
        @endif
    </div>
    @endif

    @if($submissions->isEmpty())
    <div class="bg-white rounded-2xl p-12 border border-dashed border-gray-300 text-center">
        <h3 class="text-lg font-bold text-[#1a1a2e] mb-2">No submissions yet</h3>
        <p class="text-gray-500 text-sm">Students haven't submitted work for this assignment yet.</p>
    </div>
    @else
    <div class="space-y-4">
        @foreach($submissions as $submission)
        <div class="card">
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-9 h-9 rounded-full bg-[#1a2535] flex items-center justify-center text-white font-bold text-xs">
                            {{ strtoupper(substr($submission->user->name, 0, 2)) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-[#1a1a2e]">{{ $submission->user->name }}</h4>
                            <p class="text-xs text-gray-400">{{ $submission->user->email }} &middot; Submitted {{ $submission->created_at->format('M d, Y g:i A') }}</p>
                        </div>
                    </div>
                    @if($submission->notes)
                    <p class="text-sm text-gray-500 mt-2 ml-12">{{ $submission->notes }}</p>
                    @endif
                    <div class="mt-2 ml-12">
                        <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="text-sm text-[#e05a3a] hover:underline flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            {{ $submission->file_name }}
                        </a>
                    </div>
                </div>
                <div class="sm:w-64 flex-shrink-0">
                    <form method="POST" action="{{ route('admin.cohorts.submissions.grade', [$cohort, $submission]) }}" class="space-y-3">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="form-label text-xs">Grade</label>
                            <input type="text" name="grade" value="{{ $submission->grade }}" class="form-input text-sm" placeholder="e.g. A, 85%, Pass">
                        </div>
                        <div>
                            <label class="form-label text-xs">Feedback</label>
                            <textarea name="feedback" rows="2" class="form-input text-sm" placeholder="Optional feedback...">{{ $submission->feedback }}</textarea>
                        </div>
                        <button type="submit" class="btn-primary text-xs py-2 w-full justify-center">
                            {{ $submission->grade ? 'Update Grade' : 'Save Grade' }}
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
