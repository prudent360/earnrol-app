@extends('layouts.app')

@section('title', 'Add Course')
@section('page_title', 'Add Course')
@section('page_subtitle', 'Create a new learning course')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#e05a3a] transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Courses
    </a>
</div>

<form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Details --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="card space-y-5">
                <h3 class="text-base font-bold text-[#1a1a2e] border-b border-gray-100 pb-4">Course Details</h3>

                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="form-input bg-gray-50 border-transparent focus:bg-white transition-all @error('title') border-red-400 @enderror"
                           placeholder="e.g. AWS Solutions Architect — Associate">
                    @error('title')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Description</label>
                    <textarea name="description" rows="4"
                              class="form-input bg-gray-50 border-transparent focus:bg-white transition-all resize-none"
                              placeholder="What will students learn from this course?">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Category <span class="text-red-500">*</span></label>
                        <select name="category" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                            <option value="">Select category</option>
                            @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('category')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Level <span class="text-red-500">*</span></label>
                        <select name="level" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                            <option value="beginner"     {{ old('level') === 'beginner'     ? 'selected' : '' }}>Beginner</option>
                            <option value="intermediate" {{ old('level') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="advanced"     {{ old('level') === 'advanced'     ? 'selected' : '' }}>Advanced</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Price ($)</label>
                        <input type="number" name="price" value="{{ old('price', 0) }}" min="0" step="0.01"
                               class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                        <p class="text-[10px] text-gray-400 mt-1">Set 0 for free</p>
                    </div>
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Duration (hrs)</label>
                        <input type="number" name="duration_hours" value="{{ old('duration_hours', 0) }}" min="0"
                               class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Lessons</label>
                        <input type="number" name="lesson_count" value="{{ old('lesson_count', 0) }}" min="0"
                               class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Students</label>
                        <input type="number" name="student_count" value="{{ old('student_count', 0) }}" min="0"
                               class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Rating (0–5)</label>
                        <input type="number" name="rating" value="{{ old('rating', 0) }}" min="0" max="5" step="0.1"
                               class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Badge</label>
                        <select name="badge" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                            <option value="">None</option>
                            @foreach(['Popular','Hot','New','Trending'] as $b)
                            <option value="{{ $b }}" {{ old('badge') === $b ? 'selected' : '' }}>{{ $b }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Instructor</label>
                    <select name="instructor_id" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                        <option value="">None / Platform</option>
                        @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                            {{ $instructor->name }} ({{ $instructor->email }})
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Publish --}}
            <div class="card space-y-4">
                <h3 class="text-base font-bold text-[#1a1a2e] border-b border-gray-100 pb-4">Publish</h3>
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Status</label>
                    <select name="status" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                        <option value="draft"     {{ old('status', 'draft') === 'draft'     ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Featured Course</p>
                        <p class="text-xs text-gray-400 mt-0.5">Show in the hero banner</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" value="1" class="sr-only peer" {{ old('is_featured') ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#e05a3a] after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                    </label>
                </div>
                <button type="submit" class="btn-primary w-full justify-center">Save Course</button>
            </div>

            {{-- Thumbnail --}}
            <div class="card space-y-4">
                <h3 class="text-base font-bold text-[#1a1a2e] border-b border-gray-100 pb-4">Thumbnail</h3>
                <div class="border-2 border-dashed border-gray-200 rounded-xl p-5 text-center hover:border-[#e05a3a] transition-colors cursor-pointer group" onclick="document.getElementById('thumbnail_input').click()">
                    <div id="thumb_preview_wrap" class="hidden mb-3">
                        <img id="thumb_preview" src="" class="h-24 mx-auto object-cover rounded-lg">
                    </div>
                    <svg id="thumb_icon" class="w-8 h-8 mx-auto text-gray-300 group-hover:text-[#e05a3a] mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm text-gray-400 group-hover:text-[#e05a3a] transition-colors">Click to upload</p>
                    <p class="text-[11px] text-gray-300 mt-1">PNG, JPG · Max 2MB</p>
                    <input type="file" id="thumbnail_input" name="thumbnail" accept="image/*" class="sr-only"
                           onchange="previewThumb(this)">
                </div>
            </div>

            {{-- Icon Color --}}
            <div class="card space-y-4">
                <h3 class="text-base font-bold text-[#1a1a2e] border-b border-gray-100 pb-4">Icon Color</h3>
                <div class="flex items-center gap-3">
                    <input type="color" name="icon_color" value="{{ old('icon_color', '#e05a3a') }}" id="icon_color"
                           class="w-12 h-12 rounded-xl border border-gray-200 cursor-pointer p-1 bg-transparent"
                           oninput="document.getElementById('icon_color_hex').value = this.value">
                    <input type="text" id="icon_color_hex" value="{{ old('icon_color', '#e05a3a') }}"
                           class="form-input bg-gray-50 border-transparent focus:bg-white transition-all font-mono text-sm flex-1"
                           oninput="document.getElementById('icon_color').value = this.value">
                </div>
                <p class="text-[11px] text-gray-400">Used for the course icon background tint</p>
            </div>
        </div>
    </div>
</form>

<script>
function previewThumb(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('thumb_preview').src = e.target.result;
        document.getElementById('thumb_preview_wrap').classList.remove('hidden');
        document.getElementById('thumb_icon').classList.add('hidden');
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endsection
