@extends('layouts.app')

@section('title', 'Create Coaching Service')
@section('page_title', 'Create Coaching Service')
@section('page_subtitle', 'Offer 1-on-1 coaching sessions')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('creator.coaching.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to My Coaching
        </a>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-6 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-sm text-blue-700">Your coaching service will be submitted for review. Once approved, you can add available time slots for customers to book.</p>
    </div>

    <div class="card">
        <form action="{{ route('creator.coaching.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="border-b border-gray-100 pb-4">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Service Details</h3>
            </div>

            <div>
                <label for="title" class="form-label">Service Title</label>
                <input type="text" name="title" id="title" class="form-input @error('title') border-red-500 @enderror" value="{{ old('title') }}" required placeholder="e.g. Career Coaching Session">
                @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="4" class="form-input @error('description') border-red-500 @enderror" placeholder="What will you cover in this session?">{{ old('description') }}</textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="price" class="form-label">Price ({{ \App\Models\Setting::get('currency_symbol', '£') }})</label>
                    <input type="number" name="price" id="price" step="0.01" min="0" class="form-input @error('price') border-red-500 @enderror" value="{{ old('price', '50.00') }}" required>
                    @error('price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="duration_minutes" class="form-label">Duration</label>
                    <select name="duration_minutes" id="duration_minutes" class="form-input" required>
                        @foreach(\App\Models\CoachingService::DURATION_OPTIONS as $mins)
                        <option value="{{ $mins }}" {{ old('duration_minutes', 60) == $mins ? 'selected' : '' }}>{{ $mins }} minutes</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="meeting_platform" class="form-label">Meeting Platform</label>
                    <select name="meeting_platform" id="meeting_platform" class="form-input" required>
                        @foreach(\App\Models\CoachingService::MEETING_PLATFORMS as $key => $label)
                        <option value="{{ $key }}" {{ old('meeting_platform', 'google_meet') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="cover_image" class="form-label">Cover Image (optional)</label>
                <input type="file" name="cover_image" id="cover_image" accept="image/*" class="form-input">
                <p class="text-xs text-gray-400 mt-1">Recommended: 800x600px.</p>
            </div>

            <div class="pt-4 border-t border-[#e8eaf0] flex justify-end">
                <button type="submit" class="btn-primary">Submit for Review</button>
            </div>
        </form>
    </div>
</div>
@endsection
