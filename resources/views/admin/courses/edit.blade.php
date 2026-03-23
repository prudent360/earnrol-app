@extends('layouts.app')

@section('title', 'Edit Course')
@section('page_title', 'Edit Course')
@section('page_subtitle', $course->title)

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#e05a3a] transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Courses
    </a>
</div>

<form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Details --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="card space-y-5">
                <h3 class="text-base font-bold text-[#1a1a2e] border-b border-gray-100 pb-4">Course Details</h3>

                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $course->title) }}"
                           class="form-input bg-gray-50 border-transparent focus:bg-white transition-all @error('title') border-red-400 @enderror">
                    @error('title')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Description</label>
                    <textarea name="description" rows="4"
                              class="form-input bg-gray-50 border-transparent focus:bg-white transition-all resize-none">{{ old('description', $course->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Category <span class="text-red-500">*</span></label>
                        <select name="category" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                            @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ old('category', $course->category) === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Level</label>
                        <select name="level" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                            @foreach(['beginner','intermediate','advanced'] as $lvl)
                            <option value="{{ $lvl }}" {{ old('level', $course->level) === $lvl ? 'selected' : '' }}>{{ ucfirst($lvl) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Price ($)</label>
                        <input type="number" name="price" value="{{ old('price', $course->price) }}" min="0" step="0.01"
                               class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Duration (hrs)</label>
                        <input type="number" name="duration_hours" value="{{ old('duration_hours', $course->duration_hours) }}" min="0"
                               class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Lessons</label>
                        <input type="number" name="lesson_count" value="{{ old('lesson_count', $course->lesson_count) }}" min="0"
                               class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Students</label>
                        <input type="number" name="student_count" value="{{ old('student_count', $course->student_count) }}" min="0"
                               class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Rating (0–5)</label>
                        <input type="number" name="rating" value="{{ old('rating', $course->rating) }}" min="0" max="5" step="0.1"
                               class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Badge</label>
                        <select name="badge" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                            <option value="">None</option>
                            @foreach(['Popular','Hot','New','Trending'] as $b)
                            <option value="{{ $b }}" {{ old('badge', $course->badge) === $b ? 'selected' : '' }}>{{ $b }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Instructor</label>
                    <select name="instructor_id" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                        <option value="">None / Platform</option>
                        @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}" {{ old('instructor_id', $course->instructor_id) == $instructor->id ? 'selected' : '' }}>
                            {{ $instructor->name }} ({{ $instructor->email }})
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Enrollment Stats --}}
            <div class="card">
                <h3 class="text-base font-bold text-[#1a1a2e] border-b border-gray-100 pb-4 mb-4">Enrollments</h3>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-2xl font-bold text-[#1a1a2e]">{{ $course->enrollments()->count() }}</p>
                        <p class="text-xs text-gray-400 mt-1">Total Enrolled</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-green-600">{{ $course->enrollments()->whereNotNull('completed_at')->count() }}</p>
                        <p class="text-xs text-gray-400 mt-1">Completed</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-blue-600">{{ $course->enrollments()->whereNull('completed_at')->count() }}</p>
                        <p class="text-xs text-gray-400 mt-1">In Progress</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            <div class="card space-y-4">
                <h3 class="text-base font-bold text-[#1a1a2e] border-b border-gray-100 pb-4">Publish</h3>
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Status</label>
                    <select name="status" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                        <option value="draft"     {{ old('status', $course->status) === 'draft'     ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $course->status) === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Featured Course</p>
                        <p class="text-xs text-gray-400 mt-0.5">Show in the hero banner</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="is_featured" value="0">
                        <input type="checkbox" name="is_featured" value="1" class="sr-only peer" {{ $course->is_featured ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#e05a3a] after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                    </label>
                </div>
                <button type="submit" class="btn-primary w-full justify-center">Update Course</button>
            </div>

            {{-- Thumbnail --}}
            <div class="card space-y-4">
                <h3 class="text-base font-bold text-[#1a1a2e] border-b border-gray-100 pb-4">Thumbnail</h3>
                @if($course->thumbnail)
                <img src="{{ Storage::url($course->thumbnail) }}" class="w-full h-32 object-cover rounded-xl mb-2">
                @endif
                <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:border-[#e05a3a] transition-colors cursor-pointer group" onclick="document.getElementById('thumbnail_input').click()">
                    <div id="thumb_preview_wrap" class="hidden mb-2">
                        <img id="thumb_preview" src="" class="h-20 mx-auto object-cover rounded-lg">
                    </div>
                    <p class="text-sm text-gray-400 group-hover:text-[#e05a3a] transition-colors">
                        {{ $course->thumbnail ? 'Replace thumbnail' : 'Upload thumbnail' }}
                    </p>
                    <input type="file" id="thumbnail_input" name="thumbnail" accept="image/*" class="sr-only" onchange="previewThumb(this)">
                </div>
            </div>

            {{-- Icon Color --}}
            <div class="card space-y-4">
                <h3 class="text-base font-bold text-[#1a1a2e] border-b border-gray-100 pb-4">Icon Color</h3>
                <div class="flex items-center gap-3">
                    <input type="color" name="icon_color" value="{{ old('icon_color', $course->icon_color) }}" id="icon_color"
                           class="w-12 h-12 rounded-xl border border-gray-200 cursor-pointer p-1 bg-transparent"
                           oninput="document.getElementById('icon_color_hex').value = this.value">
                    <input type="text" id="icon_color_hex" value="{{ old('icon_color', $course->icon_color) }}"
                           class="form-input bg-gray-50 border-transparent focus:bg-white transition-all font-mono text-sm flex-1"
                           oninput="document.getElementById('icon_color').value = this.value">
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="card border-red-100 space-y-3">
                <h3 class="text-sm font-bold text-red-600">Danger Zone</h3>
                <p class="text-xs text-gray-400">Deleting a course removes all enrollments permanently.</p>
                <form method="POST" action="{{ route('admin.courses.destroy', $course) }}"
                      onsubmit="return confirm('Delete {{ addslashes($course->title) }}? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full text-sm text-red-600 border border-red-200 hover:bg-red-50 transition-colors rounded-lg px-4 py-2 font-medium">
                        Delete Course
                    </button>
                </form>
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
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endsection
