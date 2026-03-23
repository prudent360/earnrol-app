@extends('layouts.app')

@section('title', 'Manage Jobs')
@section('page_title', 'Jobs')
@section('page_subtitle', 'Manage career opportunities')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-[#1a1a2e]">Job Listings</h2>
    <a href="{{ route('admin.jobs.create') }}" class="btn-primary">Post New Job</a>
</div>

<div class="card p-0 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50 text-left">
                <th class="px-5 py-3.5 font-semibold text-gray-500 uppercase tracking-wider">Job Title</th>
                <th class="px-5 py-3.5 font-semibold text-gray-500 uppercase tracking-wider">Company</th>
                <th class="px-5 py-3.5 font-semibold text-gray-500 uppercase tracking-wider">Posted By</th>
                <th class="px-5 py-3.5 font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3.5 font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($jobs as $job)
            <tr class="hover:bg-gray-50/50">
                <td class="px-5 py-4 font-medium text-[#1a1a2e]">{{ $job->title }}</td>
                <td class="px-5 py-4 text-gray-600">{{ $job->company }}</td>
                <td class="px-5 py-4">
                    <span class="text-xs text-gray-500">{{ $job->poster->name ?? 'System' }}</span>
                </td>
                <td class="px-5 py-4">
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $job->status === 'active' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($job->status) }}
                    </span>
                </td>
                <td class="px-5 py-4 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.jobs.edit', $job) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" onsubmit="return confirm('Delete this job?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-12 text-center text-gray-400">No jobs found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
