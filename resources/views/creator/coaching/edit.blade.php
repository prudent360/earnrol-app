@extends('layouts.app')

@section('title', 'Edit Coaching Service')
@section('page_title', 'Edit Coaching Service')
@section('page_subtitle', 'Update your coaching service')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('creator.coaching.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to My Coaching
        </a>
    </div>

    @if($coaching->approval_status === 'rejected' && $coaching->rejection_reason)
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 flex items-start gap-3">
        <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
        <div>
            <p class="text-sm font-semibold text-red-700">Rejection Reason</p>
            <p class="text-sm text-red-600 mt-1">{{ $coaching->rejection_reason }}</p>
        </div>
    </div>
    @endif

    <div class="card">
        <form action="{{ route('creator.coaching.update', $coaching) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf @method('PUT')

            <div class="border-b border-gray-100 pb-4">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Service Details</h3>
            </div>

            <div>
                <label for="title" class="form-label">Service Title</label>
                <input type="text" name="title" id="title" class="form-input @error('title') border-red-500 @enderror" value="{{ old('title', $coaching->title) }}" required>
                @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="4" class="form-input">{{ old('description', $coaching->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="price" class="form-label">Price ({{ \App\Models\Setting::get('currency_symbol', '£') }})</label>
                    <input type="number" name="price" id="price" step="0.01" min="0" class="form-input" value="{{ old('price', $coaching->price) }}" required>
                </div>
                <div>
                    <label for="duration_minutes" class="form-label">Duration</label>
                    <select name="duration_minutes" id="duration_minutes" class="form-input" required>
                        @foreach(\App\Models\CoachingService::DURATION_OPTIONS as $mins)
                        <option value="{{ $mins }}" {{ old('duration_minutes', $coaching->duration_minutes) == $mins ? 'selected' : '' }}>{{ $mins }} minutes</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="meeting_platform" class="form-label">Meeting Platform</label>
                    <select name="meeting_platform" id="meeting_platform" class="form-input" required>
                        @foreach(\App\Models\CoachingService::MEETING_PLATFORMS as $key => $label)
                        <option value="{{ $key }}" {{ old('meeting_platform', $coaching->meeting_platform) == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                @if($coaching->cover_image)
                <img src="{{ Storage::url($coaching->cover_image) }}" alt="{{ $coaching->title }}" class="h-32 rounded-xl object-cover mb-3">
                @endif
                <label for="cover_image" class="form-label">{{ $coaching->cover_image ? 'Replace Cover Image' : 'Cover Image (optional)' }}</label>
                <input type="file" name="cover_image" id="cover_image" accept="image/*" class="form-input">
            </div>

            <div class="pt-4 border-t border-[#e8eaf0] flex justify-end">
                <button type="submit" class="btn-primary">Update Service</button>
            </div>
        </form>
    </div>
</div>
@endsection
