@extends('layouts.app')

@section('title', 'Notifications')
@section('page_title', 'Notifications')
@section('page_subtitle', 'Stay up to date')

@section('content')

@if($notifications->count() > 0)
<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-gray-500">{{ auth()->user()->unreadNotifications->count() }} unread</p>
    @if(auth()->user()->unreadNotifications->count() > 0)
    <form method="POST" action="{{ route('notifications.markAllRead') }}">
        @csrf
        <button type="submit" class="text-sm font-medium text-[#e05a3a] hover:underline">Mark all as read</button>
    </form>
    @endif
</div>

<div class="space-y-2">
    @foreach($notifications as $notification)
    @php
        $data = $notification->data;
        $colorMap = [
            'green' => 'bg-green-100 text-green-600',
            'red' => 'bg-red-100 text-red-600',
            'blue' => 'bg-blue-100 text-blue-600',
            'orange' => 'bg-orange-100 text-orange-600',
            'amber' => 'bg-amber-100 text-amber-600',
            'purple' => 'bg-purple-100 text-purple-600',
        ];
        $iconColor = $colorMap[$data['color'] ?? 'blue'] ?? 'bg-gray-100 text-gray-600';
    @endphp
    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
        @csrf
        <button type="submit" class="w-full text-left card p-4 flex items-start gap-4 hover:shadow-md transition-shadow {{ is_null($notification->read_at) ? 'border-l-4 border-[#e05a3a]' : 'opacity-70' }}">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $iconColor }}">
                @switch($data['icon'] ?? 'bell')
                    @case('check-circle')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @break
                    @case('credit-card')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        @break
                    @case('x-circle')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @break
                    @case('clipboard')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        @break
                    @case('document')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        @break
                    @case('academic-cap')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                        @break
                    @case('user-add')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        @break
                    @case('currency-pound')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @break
                    @default
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                @endswitch
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-[#1a1a2e]">{{ $data['title'] ?? 'Notification' }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $data['message'] ?? '' }}</p>
                <p class="text-[10px] text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
            </div>
            @if(is_null($notification->read_at))
            <div class="w-2.5 h-2.5 bg-[#e05a3a] rounded-full flex-shrink-0 mt-1.5"></div>
            @endif
        </button>
    </form>
    @endforeach
</div>

<div class="mt-6">
    {{ $notifications->links() }}
</div>
@else
<div class="bg-white rounded-2xl p-12 border border-dashed border-gray-300 text-center">
    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
    <p class="text-gray-500 text-sm">No notifications yet.</p>
</div>
@endif

@endsection
