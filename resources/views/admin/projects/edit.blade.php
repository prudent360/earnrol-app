@extends('layouts.app')

@section('title', 'Edit Project')
@section('page_title', 'Edit Project')

@section('content')
<div class="max-w-3xl">
    <form action="{{ route('admin.projects.update', $project) }}" method="POST" class="card space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Project Title</label>
            <input type="text" name="title" value="{{ $project->title }}" class="form-input w-full" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <input type="text" name="category" value="{{ $project->category }}" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="form-input w-full">
                    <option value="pending" {{ $project->status === 'pending' ? 'selected' : '' }}>Available (Pending)</option>
                    <option value="active" {{ $project->status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Difficulty</label>
                <select name="difficulty" class="form-input w-full">
                    <option value="beginner" {{ $project->difficulty === 'beginner' ? 'selected' : '' }}>Beginner</option>
                    <option value="intermediate" {{ $project->difficulty === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                    <option value="advanced" {{ $project->difficulty === 'advanced' ? 'selected' : '' }}>Advanced</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Points</label>
                <input type="number" name="points" value="{{ $project->points }}" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tags (Comma separated)</label>
                <input type="text" name="tags" value="{{ $project->tags }}" class="form-input w-full">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">GitHub URL</label>
                <input type="url" name="github_url" value="{{ $project->github_url }}" class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Live URL</label>
                <input type="url" name="live_url" value="{{ $project->live_url }}" class="form-input w-full">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Owner</label>
            <select name="user_id" class="form-input w-full" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $project->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->role }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="5" class="form-input w-full">{{ $project->description }}</textarea>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.projects.index') }}" class="btn-outline">Cancel</a>
            <button type="submit" class="btn-primary px-8">Update Project</button>
        </div>
    </form>
</div>
@endsection
