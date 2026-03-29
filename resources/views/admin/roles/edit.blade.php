@extends('layouts.app')

@section('title', 'Edit Role')
@section('page_title', 'Edit Role')
@section('page_subtitle', $role->name)

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.roles.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Roles
        </a>
    </div>

    <div class="card">
        <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label for="name" class="form-label">Role Name</label>
                <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $role->name) }}" required>
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="form-label">Slug</label>
                <input type="text" class="form-input bg-gray-50 cursor-not-allowed" value="{{ $role->slug }}" disabled>
                <p class="text-xs text-gray-400 mt-1">Slug cannot be changed after creation.</p>
            </div>
            <div>
                <label for="description" class="form-label">Description (optional)</label>
                <textarea name="description" id="description" rows="2" class="form-input">{{ old('description', $role->description) }}</textarea>
            </div>
            <div class="pt-4 border-t border-[#e8eaf0] flex justify-end">
                <button type="submit" class="btn-primary">Update Role</button>
            </div>
        </form>
    </div>
</div>
@endsection
