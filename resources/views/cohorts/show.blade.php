@extends('layouts.app')

@section('title', $cohort->title)
@section('page_title', $cohort->title)
@section('page_subtitle', 'Materials and assignments')

@section('content')
<div class="max-w-5xl mx-auto">

    {{-- Cohort header --}}
    <div class="card mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="badge {{ $cohort->status === 'active' ? 'bg-green-100 text-green-700' : ($cohort->status === 'upcoming' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500') }}">
                        {{ ucfirst($cohort->status) }}
                    </span>
                </div>
                @if($cohort->description)
                <p class="text-sm text-gray-500 mb-2">{{ $cohort->description }}</p>
                @endif
                <p class="text-xs text-gray-400">Started {{ $cohort->start_date->format('M d, Y') }}</p>
            </div>
            @if($cohort->status === 'active' && $cohort->google_meet_link)
            <a href="{{ $cohort->google_meet_link }}" target="_blank" rel="noopener noreferrer" class="btn-primary text-sm py-3 px-6 flex-shrink-0 inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                Join Live Class
            </a>
            @endif
        </div>
    </div>

    {{-- Materials section --}}
    <div class="mb-8">
        <h3 class="text-lg font-bold text-[#1a1a2e] mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            Study Materials
        </h3>
        @if($materials->isEmpty())
        <div class="bg-white rounded-2xl p-8 border border-dashed border-gray-300 text-center">
            <p class="text-gray-500 text-sm">No study materials uploaded yet. Check back soon!</p>
        </div>
        @else
        <div class="space-y-3">
            @foreach($materials as $material)
            <div class="card flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-[#1a1a2e]">{{ $material->title }}</h4>
                    @if($material->description)
                    <p class="text-sm text-gray-500 mt-1">{{ $material->description }}</p>
                    @endif
                    <p class="text-xs text-gray-400 mt-1">{{ $material->created_at->format('M d, Y') }}</p>
                </div>
                @if($material->file_path)
                <a href="{{ Storage::url($material->file_path) }}" target="_blank" class="btn-primary text-sm py-2 px-4 flex-shrink-0">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download
                </a>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Assignments section --}}
    <div>
        <h3 class="text-lg font-bold text-[#1a1a2e] mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            Assignments
        </h3>
        @if($assignments->isEmpty())
        <div class="bg-white rounded-2xl p-8 border border-dashed border-gray-300 text-center">
            <p class="text-gray-500 text-sm">No assignments yet. Your instructor will post them here.</p>
        </div>
        @else
        <div class="space-y-4">
            @foreach($assignments as $assignment)
            @php $submission = $assignment->submissionBy(auth()->id()); @endphp
            <div class="card">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <h4 class="font-bold text-[#1a1a2e]">{{ $assignment->title }}</h4>
                            @if($submission)
                            <span class="badge bg-green-100 text-green-700">Submitted</span>
                                @if($submission->grade)
                                <span class="badge bg-purple-100 text-purple-700">Grade: {{ $submission->grade }}</span>
                                @endif
                            @elseif($assignment->due_date && $assignment->due_date->isPast())
                            <span class="badge bg-red-100 text-red-600">Overdue</span>
                            @else
                            <span class="badge bg-yellow-100 text-yellow-700">Pending</span>
                            @endif
                        </div>
                        @if($assignment->description)
                        <p class="text-sm text-gray-500 mt-1">{{ $assignment->description }}</p>
                        @endif
                        <div class="flex items-center gap-4 text-xs text-gray-400 mt-2">
                            <span>Posted {{ $assignment->created_at->format('M d, Y') }}</span>
                            @if($assignment->due_date)
                            <span class="font-medium {{ $assignment->due_date->isPast() ? 'text-red-500' : 'text-orange-600' }}">
                                Due {{ $assignment->due_date->format('M d, Y') }}
                            </span>
                            @endif
                        </div>
                        @if($assignment->file_path)
                        <a href="{{ Storage::url($assignment->file_path) }}" target="_blank" class="text-sm text-[#e05a3a] hover:underline flex items-center gap-1 mt-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download Brief ({{ $assignment->file_name }})
                        </a>
                        @endif

                        {{-- Show feedback --}}
                        @if($submission && $submission->feedback)
                        <div class="mt-3 p-3 bg-purple-50 rounded-lg border border-purple-100">
                            <p class="text-xs font-bold text-purple-700 mb-1">Instructor Feedback</p>
                            <p class="text-sm text-purple-800">{{ $submission->feedback }}</p>
                        </div>
                        @endif
                    </div>

                    {{-- Submission form or status --}}
                    <div class="sm:w-72 flex-shrink-0">
                        @if($submission)
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                            <p class="text-xs font-bold text-green-700 mb-1">Your Submission</p>
                            <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="text-sm text-green-700 hover:underline flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                {{ $submission->file_name }}
                            </a>
                            <p class="text-xs text-green-600 mt-1">Submitted {{ $submission->created_at->format('M d, Y g:i A') }}</p>
                        </div>
                        @else
                        <form method="POST" action="{{ route('cohorts.submit', [$cohort, $assignment]) }}" enctype="multipart/form-data" class="space-y-3 bg-gray-50 border border-gray-200 rounded-xl p-4">
                            @csrf
                            <div>
                                <label class="form-label text-xs">Upload Your Work</label>
                                <input type="file" name="file" required class="form-input text-sm">
                            </div>
                            <div>
                                <label class="form-label text-xs">Notes (optional)</label>
                                <textarea name="notes" rows="2" class="form-input text-sm" placeholder="Any comments..."></textarea>
                            </div>
                            <button type="submit" class="btn-primary text-xs py-2 w-full justify-center">Submit Assignment</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
