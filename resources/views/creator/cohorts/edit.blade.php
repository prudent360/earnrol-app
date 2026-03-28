@extends('layouts.app')

@section('title', 'Edit Cohort')
@section('page_title', 'Edit Cohort')
@section('page_subtitle', $cohort->title)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('creator.cohorts.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to My Cohorts
        </a>
    </div>

    @if($cohort->approval_status === 'rejected' && $cohort->rejection_reason)
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 flex items-start gap-3">
        <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <p class="text-sm font-semibold text-red-800">Rejected by Admin</p>
            <p class="text-sm text-red-600 mt-0.5">{{ $cohort->rejection_reason }}</p>
        </div>
    </div>
    @endif

    @if($cohort->approval_status === 'approved')
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6 flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
        <p class="text-sm text-amber-700">Editing an approved cohort will re-submit it for review. It may be temporarily hidden from students.</p>
    </div>
    @endif

    <div class="card">
        <form action="{{ route('creator.cohorts.update', $cohort) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Basic Info --}}
            <div class="border-b border-gray-100 pb-4">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Basic Information</h3>
            </div>

            <div>
                <label for="title" class="form-label">Cohort Title</label>
                <input type="text" name="title" id="title" class="form-input @error('title') border-red-500 @enderror" value="{{ old('title', $cohort->title) }}" required>
                @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="3" class="form-input @error('description') border-red-500 @enderror">{{ old('description', $cohort->description) }}</textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="cover_image" class="form-label">Cover Image</label>
                @if($cohort->cover_image)
                <div class="mb-2">
                    <img src="{{ Storage::url($cohort->cover_image) }}" alt="Cover" class="h-32 rounded-lg object-cover">
                </div>
                @endif
                <input type="file" name="cover_image" id="cover_image" accept="image/*" class="form-input @error('cover_image') border-red-500 @enderror">
                @error('cover_image') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">Leave empty to keep current image.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="price" class="form-label">Price ({{ \App\Models\Setting::get('currency_symbol', '£') }})</label>
                    <input type="number" name="price" id="price" step="0.01" min="0" class="form-input @error('price') border-red-500 @enderror" value="{{ old('price', $cohort->price) }}" required>
                    @error('price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="max_students" class="form-label">Max Students (optional)</label>
                    <input type="number" name="max_students" id="max_students" min="1" class="form-input @error('max_students') border-red-500 @enderror" value="{{ old('max_students', $cohort->max_students) }}" placeholder="Leave blank for unlimited">
                    @error('max_students') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="google_meet_link" class="form-label">Google Meet Link</label>
                <input type="url" name="google_meet_link" id="google_meet_link" class="form-input @error('google_meet_link') border-red-500 @enderror" value="{{ old('google_meet_link', $cohort->google_meet_link) }}" placeholder="https://meet.google.com/abc-defg-hij">
                @error('google_meet_link') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">Students will see this link after enrolling.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-input @error('start_date') border-red-500 @enderror" value="{{ old('start_date', $cohort->start_date->format('Y-m-d')) }}" required>
                    @error('start_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="end_date" class="form-label">End Date (optional)</label>
                    <input type="date" name="end_date" id="end_date" class="form-input @error('end_date') border-red-500 @enderror" value="{{ old('end_date', $cohort->end_date?->format('Y-m-d')) }}">
                    @error('end_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="schedule" class="form-label">Schedule (optional)</label>
                <input type="text" name="schedule" id="schedule" class="form-input @error('schedule') border-red-500 @enderror" value="{{ old('schedule', $cohort->schedule) }}" placeholder="e.g. Mondays & Wednesdays, 6–8 PM GMT">
                @error('schedule') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Facilitator --}}
            <div class="border-b border-gray-100 pb-4 pt-2">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Facilitator / Instructor</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="facilitator_name" class="form-label">Facilitator Name</label>
                    <input type="text" name="facilitator_name" id="facilitator_name" class="form-input @error('facilitator_name') border-red-500 @enderror" value="{{ old('facilitator_name', $cohort->facilitator_name) }}" placeholder="e.g. John Doe">
                    @error('facilitator_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="facilitator_image" class="form-label">Facilitator Photo</label>
                    @if($cohort->facilitator_image)
                    <div class="mb-2">
                        <img src="{{ Storage::url($cohort->facilitator_image) }}" alt="Facilitator" class="h-16 w-16 rounded-full object-cover">
                    </div>
                    @endif
                    <input type="file" name="facilitator_image" id="facilitator_image" accept="image/*" class="form-input @error('facilitator_image') border-red-500 @enderror">
                    @error('facilitator_image') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="facilitator_bio" class="form-label">Facilitator Bio (optional)</label>
                <textarea name="facilitator_bio" id="facilitator_bio" rows="2" class="form-input @error('facilitator_bio') border-red-500 @enderror">{{ old('facilitator_bio', $cohort->facilitator_bio) }}</textarea>
                @error('facilitator_bio') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Curriculum Details --}}
            <div class="border-b border-gray-100 pb-4 pt-2">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Curriculum Details</h3>
            </div>

            <div>
                <label for="what_you_will_learn" class="form-label">What You'll Learn</label>
                <textarea name="what_you_will_learn" id="what_you_will_learn" rows="4" class="form-input @error('what_you_will_learn') border-red-500 @enderror" placeholder="One learning outcome per line">{{ old('what_you_will_learn', $cohort->what_you_will_learn) }}</textarea>
                @error('what_you_will_learn') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">One item per line. These appear as bullet points on the cohort page.</p>
            </div>

            <div>
                <label for="prerequisites" class="form-label">Prerequisites (optional)</label>
                <textarea name="prerequisites" id="prerequisites" rows="3" class="form-input @error('prerequisites') border-red-500 @enderror" placeholder="One prerequisite per line">{{ old('prerequisites', $cohort->prerequisites) }}</textarea>
                @error('prerequisites') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">One item per line.</p>
            </div>

            <div class="bg-gray-50 rounded-xl p-4 border border-[#e8eaf0]">
                <p class="text-sm text-gray-600"><strong>{{ $cohort->enrollments_count ?? $cohort->enrollments()->count() }}</strong> students enrolled</p>
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
                    <input type="checkbox" name="affiliate_enabled" value="1" class="sr-only peer" {{ old('affiliate_enabled', $cohort->affiliateProduct?->affiliate_enabled ?? false) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-[#e05a3a]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#e05a3a]"></div>
                </label>
            </div>

            <div>
                <label for="affiliate_commission" class="form-label">Affiliate Commission (%)</label>
                <input type="number" name="affiliate_commission" id="affiliate_commission" step="0.1" min="0" max="90" class="form-input" value="{{ old('affiliate_commission', $cohort->affiliateProduct?->commission_percentage ?? '') }}" placeholder="e.g. 20">
                <p class="text-xs text-gray-400 mt-1">Platform fee on affiliate sales: {{ \App\Models\Setting::get('affiliate_admin_fee', '5') }}% (deducted from your share after affiliate commission)</p>
            </div>
            @endif

            <div class="pt-4 border-t border-[#e8eaf0] flex justify-end">
                <button type="submit" class="btn-primary">Update Cohort</button>
            </div>
        </form>
    </div>
</div>
@endsection
