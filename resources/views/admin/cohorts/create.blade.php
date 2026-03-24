@extends('layouts.app')

@section('title', 'Create Cohort')
@section('page_title', 'Create New Cohort')
@section('page_subtitle', 'Set up a new training cohort')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.cohorts.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Cohorts
        </a>
    </div>

    <div class="card">
        <form action="{{ route('admin.cohorts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Basic Info --}}
            <div class="border-b border-gray-100 pb-4">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Basic Information</h3>
            </div>

            <div>
                <label for="title" class="form-label">Cohort Title</label>
                <input type="text" name="title" id="title" class="form-input @error('title') border-red-500 @enderror" value="{{ old('title') }}" required placeholder="e.g. Cloud Engineering — April 2026">
                @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="3" class="form-input @error('description') border-red-500 @enderror" placeholder="Brief description of what students will learn...">{{ old('description') }}</textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="cover_image" class="form-label">Cover Image (optional)</label>
                <input type="file" name="cover_image" id="cover_image" accept="image/*" class="form-input @error('cover_image') border-red-500 @enderror">
                @error('cover_image') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">Recommended: 1200x630px. Shown on the cohort detail page.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="price" class="form-label">Price ({{ \App\Models\Setting::get('currency_symbol', '£') }})</label>
                    <input type="number" name="price" id="price" step="0.01" min="0" class="form-input @error('price') border-red-500 @enderror" value="{{ old('price', '0.00') }}" required>
                    @error('price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="max_students" class="form-label">Max Students (optional)</label>
                    <input type="number" name="max_students" id="max_students" min="1" class="form-input @error('max_students') border-red-500 @enderror" value="{{ old('max_students') }}" placeholder="Leave blank for unlimited">
                    @error('max_students') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="google_meet_link" class="form-label">Google Meet Link</label>
                <input type="url" name="google_meet_link" id="google_meet_link" class="form-input @error('google_meet_link') border-red-500 @enderror" value="{{ old('google_meet_link') }}" placeholder="https://meet.google.com/abc-defg-hij">
                @error('google_meet_link') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">Students will see this link after enrolling.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-input @error('start_date') border-red-500 @enderror" value="{{ old('start_date') }}" required>
                    @error('start_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="end_date" class="form-label">End Date (optional)</label>
                    <input type="date" name="end_date" id="end_date" class="form-input @error('end_date') border-red-500 @enderror" value="{{ old('end_date') }}">
                    @error('end_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-input @error('status') border-red-500 @enderror" required>
                        <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @error('status') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="schedule" class="form-label">Schedule (optional)</label>
                <input type="text" name="schedule" id="schedule" class="form-input @error('schedule') border-red-500 @enderror" value="{{ old('schedule') }}" placeholder="e.g. Mondays & Wednesdays, 6–8 PM GMT">
                @error('schedule') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Facilitator --}}
            <div class="border-b border-gray-100 pb-4 pt-2">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Facilitator / Instructor</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="facilitator_name" class="form-label">Facilitator Name</label>
                    <input type="text" name="facilitator_name" id="facilitator_name" class="form-input @error('facilitator_name') border-red-500 @enderror" value="{{ old('facilitator_name') }}" placeholder="e.g. John Doe">
                    @error('facilitator_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="facilitator_image" class="form-label">Facilitator Photo (optional)</label>
                    <input type="file" name="facilitator_image" id="facilitator_image" accept="image/*" class="form-input @error('facilitator_image') border-red-500 @enderror">
                    @error('facilitator_image') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="facilitator_bio" class="form-label">Facilitator Bio (optional)</label>
                <textarea name="facilitator_bio" id="facilitator_bio" rows="2" class="form-input @error('facilitator_bio') border-red-500 @enderror" placeholder="Brief bio of the facilitator...">{{ old('facilitator_bio') }}</textarea>
                @error('facilitator_bio') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Curriculum Details --}}
            <div class="border-b border-gray-100 pb-4 pt-2">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Curriculum Details</h3>
            </div>

            <div>
                <label for="what_you_will_learn" class="form-label">What You'll Learn</label>
                <textarea name="what_you_will_learn" id="what_you_will_learn" rows="4" class="form-input @error('what_you_will_learn') border-red-500 @enderror" placeholder="One learning outcome per line, e.g.&#10;Build REST APIs with Laravel&#10;Deploy to cloud platforms&#10;Write automated tests">{{ old('what_you_will_learn') }}</textarea>
                @error('what_you_will_learn') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">One item per line. These appear as bullet points on the cohort page.</p>
            </div>

            <div>
                <label for="prerequisites" class="form-label">Prerequisites (optional)</label>
                <textarea name="prerequisites" id="prerequisites" rows="3" class="form-input @error('prerequisites') border-red-500 @enderror" placeholder="One prerequisite per line, e.g.&#10;Basic understanding of HTML/CSS&#10;A laptop with internet access">{{ old('prerequisites') }}</textarea>
                @error('prerequisites') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">One item per line.</p>
            </div>

            <div class="pt-4 border-t border-[#e8eaf0] flex justify-end">
                <button type="submit" class="btn-primary">Create Cohort</button>
            </div>
        </form>
    </div>
</div>
@endsection
