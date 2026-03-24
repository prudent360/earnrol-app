@extends('layouts.admin')

@section('title', 'Application: ' . $application->user->name)

@section('admin_content')
<div class="max-w-4xl space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.jobs.applications', $application->job) }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#e05a3a] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Applications
        </a>
        
        <div class="flex items-center gap-2">
            <form action="{{ route('admin.job-applications.update-status', $application) }}" method="POST" class="flex items-center gap-2">
                @csrf
                @method('PATCH')
                <select name="status" class="form-input text-xs py-1.5 pr-8 border-gray-200 rounded-lg focus:ring-[#e05a3a]">
                    <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="reviewed" {{ $application->status === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                    <option value="accepted" {{ $application->status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <button type="submit" class="btn-primary text-xs py-1.5 px-3">Update Status</button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Candidate Info --}}
        <div class="md:col-span-1 space-y-6">
            <div class="card text-center py-8">
                <div class="w-20 h-20 rounded-full bg-orange-50 text-[#e05a3a] text-3xl font-bold flex items-center justify-center mx-auto mb-4">
                    {{ strtoupper(substr($application->user->name, 0, 1)) }}
                </div>
                <h3 class="text-xl font-bold text-[#1a1a2e]">{{ $application->user->name }}</h3>
                <p class="text-sm text-gray-500">{{ $application->user->email }}</p>
                <div class="mt-4 pt-4 border-t border-gray-50 flex flex-col gap-2">
                    <p class="text-xs text-gray-400">Application Date</p>
                    <p class="text-sm font-bold text-gray-700">{{ $application->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <div class="card">
                <h4 class="text-sm font-bold text-[#1a1a2e] mb-3">Job Details</h4>
                <div class="space-y-3">
                    <div>
                        <p class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Position</p>
                        <p class="text-sm text-gray-700 font-medium">{{ $application->job->title }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Company</p>
                        <p class="text-sm text-gray-700 font-medium">{{ $application->job->company }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Application Details --}}
        <div class="md:col-span-2 space-y-6">
            <div class="card">
                <h4 class="text-lg font-bold text-[#1a1a2e] mb-4">Resume</h4>
                @if($application->resume_path)
                <div class="bg-gray-50 rounded-xl p-6 border border-dashed border-gray-200 flex flex-col items-center justify-center gap-3">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <div class="text-center">
                        <p class="text-sm font-bold text-gray-700">resume_{{ str_replace(' ', '_', strtolower($application->user->name)) }}.pdf</p>
                        <p class="text-xs text-gray-500">Document Uploaded</p>
                    </div>
                    <a href="{{ Storage::url($application->resume_path) }}" target="_blank" class="btn-primary text-xs px-6 py-2">
                        Download / View Resume
                    </a>
                </div>
                @else
                <p class="text-sm text-gray-500 italic">No resume uploaded.</p>
                @endif
            </div>

            <div class="card">
                <h4 class="text-lg font-bold text-[#1a1a2e] mb-4">Cover Letter</h4>
                <div class="bg-gray-50 rounded-xl p-6 text-gray-600 text-sm leading-relaxed whitespace-pre-line border border-gray-100">
                    {{ $application->cover_letter ?: 'No cover letter provided.' }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
