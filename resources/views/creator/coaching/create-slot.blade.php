@extends('layouts.app')

@section('title', 'Add Slot — ' . $coaching->title)
@section('page_title', 'Add Time Slot')
@section('page_subtitle', $coaching->title . ' — ' . $coaching->duration_minutes . ' min session')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('creator.coaching.slots.index', $coaching) }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Slots
        </a>
    </div>

    <div class="card">
        <form action="{{ route('creator.coaching.slots.store', $coaching) }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="start_time" class="form-label">Session Date & Time</label>
                <input type="datetime-local" name="start_time" id="start_time" class="form-input @error('start_time') border-red-500 @enderror" value="{{ old('start_time') }}" required min="{{ now()->format('Y-m-d\TH:i') }}">
                @error('start_time') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">The session will last {{ $coaching->duration_minutes }} minutes from this time.</p>
            </div>

            <div class="pt-4 border-t border-[#e8eaf0] flex justify-end">
                <button type="submit" class="btn-primary">Add Slot</button>
            </div>
        </form>
    </div>
</div>
@endsection
