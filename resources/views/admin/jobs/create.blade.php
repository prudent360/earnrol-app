@extends('layouts.app')

@section('title', 'Post Job')
@section('page_title', 'Post a New Job')

@section('content')
<div class="max-w-3xl">
    <form action="{{ route('admin.jobs.store') }}" method="POST" class="card space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Job Title</label>
                <input type="text" name="title" class="form-input w-full" required placeholder="e.g. Senior Laravel Developer">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                <input type="text" name="company" class="form-input w-full" required placeholder="e.g. EarnRol Tech">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                <input type="text" name="location" class="form-input w-full" placeholder="e.g. London / Remote">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Job Type</label>
                <select name="type" class="form-input w-full">
                    <option value="full-time">Full-time</option>
                    <option value="part-time">Part-time</option>
                    <option value="contract">Contract</option>
                    <option value="internship">Internship</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="form-input w-full">
                    <option value="active">Active</option>
                    <option value="closed">Closed</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Assign to Employer (Owner)</label>
            <select name="user_id" class="form-input w-full" required>
                @foreach($employers as $employer)
                    <option value="{{ $employer->id }}" {{ $employer->id == auth()->id() ? 'selected' : '' }}>
                        {{ $employer->name }} ({{ $employer->role }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Salary Range</label>
            <input type="text" name="salary_range" class="form-input w-full" placeholder="e.g. £40k–£60k/yr or Competitive">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="4" class="form-input w-full" placeholder="Describe the role..."></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Requirements</label>
            <textarea name="requirements" rows="4" class="form-input w-full" placeholder="Skills, qualifications, experience required..."></textarea>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.jobs.index') }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-primary px-8">Post Job</button>
        </div>
    </form>
</div>
@endsection
