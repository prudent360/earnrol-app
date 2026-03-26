@extends('layouts.app')

@section('title', 'Discussion — ' . $cohort->title)
@section('page_title', $cohort->title)
@section('page_subtitle', 'Cohort Discussion')

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Back link + header --}}
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('cohorts.materials', $cohort) }}" class="text-sm text-gray-500 hover:text-[#1a1a2e] flex items-center gap-1 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Materials
        </a>
        <span class="badge {{ $cohort->status === 'active' ? 'bg-green-100 text-green-700' : ($cohort->status === 'upcoming' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500') }}">
            {{ ucfirst($cohort->status) }}
        </span>
    </div>

    {{-- New Discussion Form --}}
    <div class="card mb-8">
        <h3 class="text-sm font-bold text-[#1a1a2e] mb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Start a Discussion
        </h3>
        <form method="POST" action="{{ route('cohorts.discussions.store', $cohort) }}">
            @csrf
            <textarea name="body" rows="3" required maxlength="2000" class="form-input text-sm @error('body') border-red-400 @enderror" placeholder="Ask a question, share an insight, or start a conversation...">{{ old('body') }}</textarea>
            @error('body')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            <div class="flex items-center justify-between mt-3">
                <p class="text-xs text-gray-400">Be respectful and stay on topic.</p>
                <button type="submit" class="btn-primary text-sm py-2 px-5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Post
                </button>
            </div>
        </form>
    </div>

    {{-- Discussion threads --}}
    @if($discussions->isEmpty())
    <div class="bg-white rounded-2xl p-12 border border-dashed border-gray-300 text-center">
        <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        </div>
        <h3 class="text-base font-bold text-[#1a1a2e] mb-1">No discussions yet</h3>
        <p class="text-sm text-gray-400">Be the first to start a conversation in this cohort!</p>
    </div>
    @else
    <div class="space-y-5">
        @foreach($discussions as $post)
        <div class="card" x-data="{ showReply: false }">
            {{-- Main post --}}
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0" style="background-color: {{ '#' . substr(md5($post->user->name), 0, 6) }};">
                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-sm font-bold text-[#1a1a2e]">{{ $post->user->name }}</span>
                        @if($post->user->isAdmin())
                        <span class="badge bg-purple-100 text-purple-700 text-[10px]">Admin</span>
                        @endif
                        @if($cohort->creator_id && $post->user_id === $cohort->creator_id)
                        <span class="badge bg-blue-100 text-blue-700 text-[10px]">Creator</span>
                        @endif
                        @if($cohort->facilitator_name && $post->user->name === $cohort->facilitator_name)
                        <span class="badge bg-amber-100 text-amber-700 text-[10px]">Instructor</span>
                        @endif
                        <span class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-gray-700 mt-1.5 leading-relaxed whitespace-pre-line">{{ $post->body }}</p>
                    <div class="flex items-center gap-4 mt-2">
                        <button @click="showReply = !showReply" class="text-xs font-medium text-gray-400 hover:text-[#e05a3a] transition-colors flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                            Reply ({{ $post->replies->count() }})
                        </button>
                        @if($post->user_id === auth()->id() || auth()->user()->isAdmin())
                        <form method="POST" action="{{ route('cohorts.discussions.destroy', [$cohort, $post]) }}" onsubmit="return confirm('Delete this post and all its replies?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-medium text-gray-400 hover:text-red-500 transition-colors flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Delete
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Replies --}}
            @if($post->replies->count() > 0)
            <div class="ml-12 mt-4 space-y-3 border-l-2 border-gray-100 pl-4">
                @foreach($post->replies as $reply)
                <div class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0" style="background-color: {{ '#' . substr(md5($reply->user->name), 0, 6) }};">
                        {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-xs font-bold text-[#1a1a2e]">{{ $reply->user->name }}</span>
                            @if($reply->user->isAdmin())
                            <span class="badge bg-purple-100 text-purple-700 text-[10px]">Admin</span>
                            @endif
                            <span class="text-[11px] text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1 leading-relaxed whitespace-pre-line">{{ $reply->body }}</p>
                        @if($reply->user_id === auth()->id() || auth()->user()->isAdmin())
                        <form method="POST" action="{{ route('cohorts.discussions.destroy', [$cohort, $reply]) }}" onsubmit="return confirm('Delete this reply?')" class="mt-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[11px] font-medium text-gray-400 hover:text-red-500 transition-colors">Delete</button>
                        </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Reply form --}}
            <div x-show="showReply" x-transition class="ml-12 mt-3 pl-4" style="display: none;">
                <form method="POST" action="{{ route('cohorts.discussions.store', $cohort) }}" class="flex items-start gap-3">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $post->id }}">
                    <div class="flex-1">
                        <textarea name="body" rows="2" required maxlength="2000" class="form-input text-sm" placeholder="Write a reply..."></textarea>
                    </div>
                    <button type="submit" class="btn-primary text-xs py-2 px-4 flex-shrink-0 mt-0.5">Reply</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $discussions->links() }}
    </div>
    @endif
</div>
@endsection
