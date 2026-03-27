{{-- Creator Application Form --}}
<div class="card">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: {{ \App\Models\Setting::get('brand_color', '#e05a3a') }}20;">
            <svg class="w-6 h-6" style="color: {{ \App\Models\Setting::get('brand_color', '#e05a3a') }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div>
            <h3 class="text-lg font-bold text-[#1a1a2e]">Creator Application</h3>
            <p class="text-sm text-gray-500">Tell us about yourself and what you'd like to teach</p>
        </div>
    </div>

    <form method="POST" action="{{ route('creator.apply.submit') }}" class="space-y-5">
        @csrf

        <div>
            <label for="expertise" class="form-label">Area of Expertise <span class="text-red-400">*</span></label>
            <input type="text" id="expertise" name="expertise" value="{{ old('expertise') }}" required maxlength="500"
                class="form-input @error('expertise') border-red-400 @enderror"
                placeholder="e.g. Frontend Development, Data Science, UI/UX Design">
            @error('expertise')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="experience" class="form-label">Relevant Experience <span class="text-red-400">*</span></label>
            <textarea id="experience" name="experience" rows="3" required maxlength="1000"
                class="form-input @error('experience') border-red-400 @enderror"
                placeholder="Describe your professional background, teaching experience, certifications, or any relevant qualifications...">{{ old('experience') }}</textarea>
            @error('experience')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="portfolio_url" class="form-label">Portfolio / LinkedIn URL</label>
            <input type="url" id="portfolio_url" name="portfolio_url" value="{{ old('portfolio_url') }}" maxlength="255"
                class="form-input @error('portfolio_url') border-red-400 @enderror"
                placeholder="https://linkedin.com/in/yourprofile">
            @error('portfolio_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="reason" class="form-label">Why do you want to be a creator? <span class="text-red-400">*</span></label>
            <textarea id="reason" name="reason" rows="3" required maxlength="1000"
                class="form-input @error('reason') border-red-400 @enderror"
                placeholder="What would you like to teach or sell? What value will you bring to the community?">{{ old('reason') }}</textarea>
            @error('reason')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="btn-primary py-2.5 px-6">
                Submit Application
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </button>
        </div>
    </form>
</div>
