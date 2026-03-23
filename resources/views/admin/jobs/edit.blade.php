@extends('layouts.app')

@section('title', 'Edit Job')
@section('page_title', 'Edit Job Listing')

@section('content')
<div class="max-w-3xl">
    <form action="{{ route('admin.jobs.update', $job) }}" method="POST" class="card space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Job Title</label>
                <input type="text" name="title" value="{{ $job->title }}" class="form-input w-full" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                <input type="text" name="company" value="{{ $job->company }}" class="form-input w-full" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                <input type="text" name="location" value="{{ $job->location }}" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Job Type</label>
                <select name="type" class="form-input w-full">
                    <option value="full-time" {{ $job->type === 'full-time' ? 'selected' : '' }}>Full-time</option>
                    <option value="part-time" {{ $job->type === 'part-time' ? 'selected' : '' }}>Part-time</option>
                    <option value="contract" {{ $job->type === 'contract' ? 'selected' : '' }}>Contract</option>
                    <option value="internship" {{ $job->type === 'internship' ? 'selected' : '' }}>Internship</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="form-input w-full">
                    <option value="active" {{ $job->status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="closed" {{ $job->status === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Owner (Employer/Admin)</label>
            <select name="user_id" class="form-input w-full" required>
                @foreach($employers as $employer)
                    <option value="{{ $employer->id }}" {{ $job->user_id == $employer->id ? 'selected' : '' }}>
                        {{ $employer->name }} ({{ $employer->role }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Salary Range</label>
            <input type="text" name="salary_range" value="{{ $job->salary_range }}" class="form-input w-full" placeholder="e.g. £40k–£60k/yr or Competitive">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="4" class="form-input w-full">{{ $job->description }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Requirements</label>
            <textarea name="requirements" rows="4" class="form-input w-full">{{ $job->requirements }}</textarea>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.jobs.index') }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-primary px-8">Update Job</button>
        </div>
    </form>
</div>
@endsection
