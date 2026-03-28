@extends('layouts.app')

@section('title', 'Coaching')
@section('page_title', 'Coaching')
@section('page_subtitle', 'Book 1-on-1 sessions with expert creators')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($services as $service)
    <a href="{{ route('coaching.show', $service) }}" class="card hover:shadow-lg transition-shadow group !p-0 overflow-hidden">
        @if($service->cover_image)
        <img src="{{ Storage::url($service->cover_image) }}" alt="{{ $service->title }}" class="w-full h-40 object-cover">
        @else
        <div class="w-full h-40 bg-gradient-to-br from-teal-500 to-emerald-600 flex items-center justify-center">
            <svg class="w-12 h-12 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        @endif
        <div class="p-5">
            <h3 class="text-lg font-bold text-[#1a1a2e] group-hover:text-[#e05a3a] transition-colors">{{ $service->title }}</h3>
            @if($service->creator)
            <p class="text-xs text-gray-400 mt-1">by {{ $service->creator->name }}</p>
            @endif
            @if($service->description)
            <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ Str::limit($service->description, 100) }}</p>
            @endif

            <div class="mt-3 flex items-center gap-3 text-xs text-gray-400">
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $service->duration_minutes }} min
                </span>
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    {{ $service->platform_label }}
                </span>
                @if($service->available_slots_count > 0)
                <span class="text-green-500 font-medium">{{ $service->available_slots_count }} {{ Str::plural('slot', $service->available_slots_count) }} open</span>
                @else
                <span class="text-gray-400">No slots available</span>
                @endif
            </div>

            <div class="mt-4 flex items-center justify-between">
                <span class="text-xl font-extrabold text-[#1a1a2e]">{{ $currencySymbol }}{{ number_format($service->price, 2) }}</span>
                <span class="text-xs text-gray-400">per session</span>
            </div>
        </div>
    </a>
    @empty
    <div class="col-span-full text-center py-16 text-gray-400">No coaching services available yet.</div>
    @endforelse
</div>

@if($services->hasPages())
<div class="mt-8">{{ $services->links() }}</div>
@endif
@endsection
