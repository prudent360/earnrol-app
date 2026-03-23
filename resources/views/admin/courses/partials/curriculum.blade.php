<div class="space-y-6">
    {{-- Add Chapter --}}
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-bold text-[#1a1a2e]">Course Curriculum</h3>
        <button type="button" onclick="showAddChapterModal()" class="btn-primary py-2 px-4 shadow-sm hover:shadow-md transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Chapter
        </button>
    </div>

    {{-- Chapters List --}}
    <div id="chapters-container" class="space-y-4">
        @forelse($course->chapters as $chapter)
        <div class="chapter-card bg-white border border-gray-200 rounded-xl overflow-hidden" data-chapter-id="{{ $chapter->id }}">
            <div class="flex items-center justify-between px-5 py-4 bg-gray-50/50 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-500">{{ $loop->iteration }}</span>
                    <h4 class="font-bold text-[#1a1a2e] chapter-title">{{ $chapter->title }}</h4>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="showAddLessonModal({{ $chapter->id }})" class="text-xs font-semibold text-[#e05a3a] hover:bg-[#e05a3a]/10 px-3 py-1.5 rounded-lg transition-colors">
                        Add Lesson
                    </button>
                    <button type="button" onclick="editChapter({{ $chapter->id }}, '{{ addslashes($chapter->title) }}')" class="p-1.5 text-gray-400 hover:text-blue-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    </button>
                    <button type="button" onclick="deleteChapter({{ $chapter->id }})" class="p-1.5 text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>

            <div class="lessons-list divide-y divide-gray-100">
                @forelse($chapter->lessons as $lesson)
                <div class="lesson-item flex items-center justify-between px-5 py-3.5 hover:bg-gray-50 transition-colors group" data-lesson-id="{{ $lesson->id }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-sm text-gray-700 lesson-title">{{ $lesson->title }}</span>
                        @if($lesson->is_preview)
                        <span class="text-[9px] font-bold uppercase tracking-wider bg-green-100 text-green-700 px-1.5 py-0.5 rounded">Preview</span>
                        @endif
                        <span class="text-[10px] text-gray-400 font-mono">{{ $lesson->duration }}</span>
                    </div>
                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button type="button" onclick='editLesson(@json($lesson))' class="p-1.5 text-gray-400 hover:text-blue-500 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        </button>
                        <button type="button" onclick="deleteLesson({{ $lesson->id }})" class="p-1.5 text-gray-400 hover:text-red-500 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
                @empty
                <div class="px-5 py-4 text-center text-xs text-gray-400 italic">No lessons in this chapter yet.</div>
                @endforelse
            </div>
        </div>
        @empty
        <div class="card p-10 text-center border-dashed border-2 border-gray-100">
            <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <p class="text-sm font-medium text-gray-500">No chapters yet. Start by adding a chapter.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- Chapter Modal --}}
<div id="chapter-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 mx-4">
        <form id="chapter-form" onsubmit="saveChapter(event)">
            <input type="hidden" id="chapter-id" name="id">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-[#1a1a2e]" id="chapter-modal-title">Add Chapter</h3>
                <button type="button" onclick="closeChapterModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Chapter Title</label>
                    <input type="text" id="chapter-title-input" name="title" required class="form-input bg-gray-50 border-transparent focus:bg-white transition-all shadow-sm" placeholder="e.g. Introduction to ...">
                </div>
            </div>
            <div class="px-6 py-5 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeChapterModal()" class="px-5 py-2.5 text-sm font-semibold text-gray-500 hover:bg-gray-50 rounded-xl transition-colors">Cancel</button>
                <button type="submit" class="btn-primary py-2.5 px-6 shadow-sm hover:shadow-md">Save Chapter</button>
            </div>
        </form>
    </div>
</div>

{{-- Lesson Modal --}}
<div id="lesson-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform scale-95 transition-transform duration-300 mx-4">
        <form id="lesson-form" onsubmit="saveLesson(event)">
            <input type="hidden" id="lesson-chapter-id" name="chapter_id">
            <input type="hidden" id="lesson-id" name="id">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-[#1a1a2e]" id="lesson-modal-title">Add Lesson</h3>
                <button type="button" onclick="closeLessonModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Lesson Title</label>
                    <input type="text" id="lesson-title-input" name="title" required class="form-input bg-gray-50 border-transparent focus:bg-white transition-all shadow-sm" placeholder="e.g. Environment Setup">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Duration</label>
                        <input type="text" id="lesson-duration-input" name="duration" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all shadow-sm" placeholder="e.g. 10:45">
                    </div>
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Preview Lesson</label>
                        <div class="flex items-center gap-3 h-[42px]">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="lesson-is_preview-input" name="is_preview" value="1" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#e05a3a] after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                            </label>
                            <span class="text-xs text-gray-500">Free to watch</span>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Video URL / Provider ID</label>
                    <input type="text" id="lesson-video_url-input" name="video_url" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all shadow-sm font-mono text-sm" placeholder="e.g. dQw4w9WgXcQ (YouTube ID) or full URL">
                </div>
            </div>
            <div class="px-6 py-5 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeLessonModal()" class="px-5 py-2.5 text-sm font-semibold text-gray-500 hover:bg-gray-50 rounded-xl transition-colors">Cancel</button>
                <button type="submit" class="btn-primary py-2.5 px-6 shadow-sm hover:shadow-md">Save Lesson</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Tab Switching
function switchTab(tab) {
    const contents = document.querySelectorAll('.tab-content');
    const buttons = document.querySelectorAll('.tab-btn');
    
    if (!contents.length || !buttons.length) return;

    contents.forEach(el => el.classList.add('hidden'));
    buttons.forEach(el => {
        el.classList.remove('border-[#e05a3a]', 'text-[#e05a3a]');
        el.classList.add('border-transparent', 'text-gray-500');
    });

    const activeContent = document.getElementById(tab + '-content');
    if (activeContent) activeContent.classList.remove('hidden');
    
    const activeBtn = document.querySelector(`[onclick="switchTab('${tab}')"]`);
    if (activeBtn) {
        activeBtn.classList.add('border-[#e05a3a]', 'text-[#e05a3a]');
        activeBtn.classList.remove('border-transparent', 'text-gray-500');
    }

    // Update URL without reload
    const url = new URL(window.location);
    url.searchParams.set('tab', tab);
    window.history.pushState({}, '', url);
}

// Check initial tab from URL
window.addEventListener('load', () => {
    const params = new URLSearchParams(window.location.search);
    const tab = params.get('tab') || 'details';
    switchTab(tab);
});

// Modal Helpers
function openModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        const transformEl = modal.querySelector('.transform');
        if (transformEl) transformEl.classList.remove('scale-95');
    }, 10);
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.add('opacity-0');
    const transformEl = modal.querySelector('.transform');
    if (transformEl) transformEl.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Chapter Functions
function showAddChapterModal() {
    document.getElementById('chapter-modal-title').innerText = 'Add Chapter';
    document.getElementById('chapter-id').value = '';
    document.getElementById('chapter-form').reset();
    openModal('chapter-modal');
}

function closeChapterModal() {
    closeModal('chapter-modal');
}

function editChapter(id, title) {
    document.getElementById('chapter-modal-title').innerText = 'Edit Chapter';
    document.getElementById('chapter-id').value = id;
    document.getElementById('chapter-title-input').value = title;
    openModal('chapter-modal');
}

async function saveChapter(e) {
    e.preventDefault();
    const id = document.getElementById('chapter-id').value;
    const title = document.getElementById('chapter-title-input').value;
    const url = id 
        ? `{{ route('admin.chapters.update', ':id') }}`.replace(':id', id)
        : `{{ route('admin.chapters.store', $course->id) }}`;
    const method = id ? 'PUT' : 'POST';

    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ title: title })
        });
        
        const data = await response.json();
        if (data.success) {
            window.location.reload(); // Refresh to show new order/structure for now
        }
    } catch (error) {
        console.error('Error saving chapter:', error);
    }
}

async function deleteChapter(id) {
    if (!confirm('Are you sure you want to delete this chapter and all its lessons?')) return;
    
    try {
        const response = await fetch(`{{ route('admin.chapters.destroy', ':id') }}`.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        if (data.success) {
            const el = document.querySelector(`[data-chapter-id="${id}"]`);
            if (el) el.remove();
        }
    } catch (error) {
        console.error('Error deleting chapter:', error);
    }
}

// Lesson Functions
function showAddLessonModal(chapterId) {
    document.getElementById('lesson-modal-title').innerText = 'Add Lesson';
    document.getElementById('lesson-chapter-id').value = chapterId;
    document.getElementById('lesson-id').value = '';
    document.getElementById('lesson-form').reset();
    openModal('lesson-modal');
}

function closeLessonModal() {
    closeModal('lesson-modal');
}

function editLesson(lesson) {
    document.getElementById('lesson-modal-title').innerText = 'Edit Lesson';
    document.getElementById('lesson-id').value = lesson.id;
    document.getElementById('lesson-chapter-id').value = lesson.chapter_id;
    document.getElementById('lesson-title-input').value = lesson.title;
    document.getElementById('lesson-duration-input').value = lesson.duration || '';
    document.getElementById('lesson-video_url-input').value = lesson.video_url || '';
    document.getElementById('lesson-is_preview-input').checked = !!lesson.is_preview;
    openModal('lesson-modal');
}

async function saveLesson(e) {
    e.preventDefault();
    const id = document.getElementById('lesson-id').value;
    const chapterId = document.getElementById('lesson-chapter-id').value;
    const form = document.getElementById('lesson-form');
    const formData = new FormData(form);
    
    const url = id 
        ? `{{ route('admin.lessons.update', ':id') }}`.replace(':id', id)
        : `{{ route('admin.lessons.store', ':chapter_id') }}`.replace(':chapter_id', chapterId);
    
    // Convert FormData to JSON for PUT which is easier in PHP without special middleware
    const payload = {};
    formData.forEach((value, key) => payload[key] = value);
    if (!payload.is_preview) payload.is_preview = 0;

    try {
        const response = await fetch(url, {
            method: id ? 'PUT' : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });
        
        const data = await response.json();
        if (data.success) {
            window.location.reload();
        }
    } catch (error) {
        console.error('Error saving lesson:', error);
    }
}

async function deleteLesson(id) {
    if (!confirm('Delete this lesson?')) return;
    
    try {
        const response = await fetch(`{{ route('admin.lessons.destroy', ':id') }}`.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        if (data.success) {
            const el = document.querySelector(`[data-lesson-id="${id}"]`);
            if (el) el.remove();
        }
    } catch (error) {
        console.error('Error deleting lesson:', error);
    }
}
</script>
@endpush
