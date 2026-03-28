@extends('layouts.app')

@section('title', 'Content — ' . $membership->title)
@section('page_title', $membership->title)
@section('page_subtitle', 'Manage membership content')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('creator.memberships.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to My Memberships
    </a>
    <a href="{{ route('creator.memberships.contents.create', $membership) }}" class="btn-primary text-sm py-2">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Content
    </a>
</div>

<div class="space-y-3">
    @forelse($contents as $content)
    <div class="card flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0
                {{ $content->content_type === 'file' ? 'bg-blue-100 text-blue-600' : ($content->content_type === 'video' ? 'bg-purple-100 text-purple-600' : ($content->content_type === 'link' ? 'bg-amber-100 text-amber-600' : 'bg-gray-100 text-gray-600')) }}">
                @if($content->content_type === 'file')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                @elseif($content->content_type === 'video')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @elseif($content->content_type === 'link')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                @else
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                @endif
            </div>
            <div>
                <p class="text-sm font-semibold text-[#1a1a2e]">{{ $content->title }}</p>
                <p class="text-xs text-gray-400">{{ ucfirst($content->content_type) }} {{ $content->file_name ? '— ' . $content->file_name : '' }}</p>
            </div>
        </div>
        <form action="{{ route('creator.memberships.contents.destroy', [$membership, $content]) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium" onclick="return confirm('Remove this content?')">Remove</button>
        </form>
    </div>
    @empty
    <div class="card text-center text-gray-400 py-12">
        No content added yet. <a href="{{ route('creator.memberships.contents.create', $membership) }}" class="text-[#e05a3a] hover:underline">Add your first content</a>
    </div>
    @endforelse
</div>
@endsection
