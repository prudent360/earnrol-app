@extends('layouts.app')

@section('title', 'Create Role')
@section('page_title', 'Create Role')
@section('page_subtitle', 'Add a new user role')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.roles.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Roles
        </a>
    </div>

    <div class="card">
        <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="name" class="form-label">Role Name</label>
                <input type="text" name="name" id="name" class="form-input @error('name') border-red-500 @enderror" value="{{ old('name') }}" required placeholder="e.g. Manager">
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="slug" class="form-label">Slug (optional)</label>
                <input type="text" name="slug" id="slug" class="form-input @error('slug') border-red-500 @enderror" value="{{ old('slug') }}" placeholder="Auto-generated from name">
                @error('slug') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="description" class="form-label">Description (optional)</label>
                <textarea name="description" id="description" rows="2" class="form-input" placeholder="What this role is for...">{{ old('description') }}</textarea>
            </div>
            <div class="pt-4 border-t border-[#e8eaf0] flex justify-end">
                <button type="submit" class="btn-primary">Create Role</button>
            </div>
        </form>
    </div>
</div>
@endsection
