@extends('layouts.app')

@section('title', 'Manage Projects')
@section('page_title', 'Projects')
@section('page_subtitle', 'Manage student and community projects')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-[#1a1a2e]">Projects</h2>
    <a href="{{ route('admin.projects.create') }}" class="btn-primary">Create Project</a>
</div>

<div class="card p-0 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50 text-left">
                <th class="px-5 py-3.5 font-semibold text-gray-500 uppercase tracking-wider">Project Title</th>
                <th class="px-5 py-3.5 font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                <th class="px-5 py-3.5 font-semibold text-gray-500 uppercase tracking-wider">Owner</th>
                <th class="px-5 py-3.5 font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3.5 font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($projects as $project)
            <tr class="hover:bg-gray-50/50">
                <td class="px-5 py-4 font-medium text-[#1a1a2e]">{{ $project->title }}</td>
                <td class="px-5 py-4 text-gray-600 capitalize">{{ $project->category ?? 'N/A' }}</td>
                <td class="px-5 py-4">
                    <span class="text-xs text-gray-500">{{ $project->owner->name ?? 'System' }}</span>
                </td>
                <td class="px-5 py-4">
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $project->status === 'active' ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }}">
                        {{ ucfirst($project->status) }}
                    </span>
                </td>
                <td class="px-5 py-4 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.projects.edit', $project) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Delete this project?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-12 text-center text-gray-400">No projects found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
