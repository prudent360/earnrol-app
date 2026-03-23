@extends('layouts.app')

@section('title', 'Notifications')
@section('page_title', 'Notifications')
@section('page_subtitle', 'Stay up to date with your learning journey')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-bold text-[#1a1a2e]">All Notifications</h2>
            <p class="text-xs text-gray-400 mt-1">{{ auth()->user()->unreadNotifications->count() }} unread</p>
        </div>
        @if(auth()->user()->unreadNotifications->count() > 0)
        <form method="POST" action="{{ route('notifications.readAll') }}">
            @csrf
            <button type="submit" class="btn-primary py-2 px-4 text-sm shadow-sm hover:shadow-md flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Mark All as Read
            </button>
        </form>
        @endif
    </div>

    {{-- Notification List --}}
    <div class="space-y-2">
        @forelse($notifications as $notification)
        <div class="card flex items-start gap-4 p-4 {{ $notification->read_at ? 'opacity-60' : 'border-l-4 border-[#e05a3a]' }}">
            <span class="text-2xl mt-0.5">{{ $notification->data['icon'] ?? '🔔' }}</span>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-bold text-[#1a1a2e]">{{ $notification->data['title'] ?? 'Notification' }}</p>
                    <span class="text-[10px] text-gray-400 flex-shrink-0 ml-4">{{ $notification->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-sm text-gray-600 mt-1">{{ $notification->data['message'] ?? '' }}</p>
                <div class="flex items-center gap-3 mt-3">
                    @if(!empty($notification->data['url']))
                    <a href="{{ $notification->data['url'] }}" class="text-xs font-semibold text-[#e05a3a] hover:underline">View →</a>
                    @endif
                    @if(!$notification->read_at)
                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs text-gray-400 hover:text-gray-600">Mark as read</button>
                    </form>
                    @else
                    <span class="text-[10px] text-gray-300 italic">Read</span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="card p-12 text-center border-dashed border-2 border-gray-100">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-gray-500">No notifications yet</p>
            <p class="text-xs text-gray-400 mt-1">You'll see notifications here when something happens.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
    @endif
</div>

@endsection
