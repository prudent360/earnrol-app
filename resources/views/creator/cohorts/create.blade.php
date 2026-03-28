@extends('layouts.app')

@section('title', 'Create Cohort')
@section('page_title', 'Create New Cohort')
@section('page_subtitle', 'Set up a new training cohort')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('creator.cohorts.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to My Cohorts
        </a>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-6 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-sm text-blue-700">Your cohort will be submitted for review. It will become visible to students once approved by an admin.</p>
    </div>

    <div class="card">
        <form action="{{ route('creator.cohorts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
            </div>

            <div>
                <label for="schedule" class="form-label">Schedule (optional)</label>
                <input type="text" name="schedule" id="schedule" class="form-input @error('schedule') border-red-500 @enderror" value="{{ old('schedule') }}" placeholder="e.g. Mondays & Wednesdays, 6–8 PM GMT">
                @error('schedule') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Facilitator Info --}}
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm text-blue-700">Your profile name, bio, and photo will be used as the facilitator information for this cohort. <a href="{{ route('profile.edit') }}" class="font-bold underline">Update your profile</a> to change how you appear.</p>
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

            @if(\App\Models\Setting::get('affiliate_enabled'))
            <div class="border-b border-gray-100 pb-4 pt-2">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Affiliate Promotion</h3>
            </div>

            <div class="flex items-center justify-between bg-gray-50 rounded-xl p-4">
                <div>
                    <p class="text-sm font-medium text-[#1a1a2e]">Allow Affiliate Promotion</p>
                    <p class="text-xs text-gray-400 mt-0.5">Let affiliates promote this item and earn commission per sale</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="affiliate_enabled" value="0">
                    <input type="checkbox" name="affiliate_enabled" value="1" class="sr-only peer" {{ old('affiliate_enabled', '') ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-[#e05a3a]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#e05a3a]"></div>
                </label>
            </div>

            <div>
                <label for="affiliate_commission" class="form-label">Affiliate Commission (%)</label>
                <input type="number" name="affiliate_commission" id="affiliate_commission" step="0.1" min="0" max="90" class="form-input" value="{{ old('affiliate_commission', '') }}" placeholder="e.g. 20">
                <p class="text-xs text-gray-400 mt-1">Platform fee on affiliate sales: {{ \App\Models\Setting::get('affiliate_admin_fee', '5') }}% (deducted from your share after affiliate commission)</p>
            </div>
            @endif

            <div class="pt-4 border-t border-[#e8eaf0] flex justify-end">
                <button type="submit" class="btn-primary">Submit for Review</button>
            </div>
        </form>
    </div>
</div>
@endsection
