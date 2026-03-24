@extends('layouts.app')

@section('title', $lesson->title . ' - ' . $course->title)

@section('content')
<div class="flex flex-col lg:flex-row h-[calc(100vh-80px)] overflow-hidden -m-4 sm:-m-6">
    {{-- Video Area --}}
    <div class="flex-1 bg-black flex flex-col relative">
        <div class="flex-1 flex items-center justify-center p-4">
            @if($lesson->video_url)
                <div class="w-full h-full max-w-5xl aspect-video bg-gray-900 rounded-lg overflow-hidden flex items-center justify-center shadow-2xl">
                    @if(Str::contains($lesson->video_url, 'youtube.com') || Str::contains($lesson->video_url, 'youtu.be'))
                        @php
                            $videoId = Str::afterLast($lesson->video_url, '/');
                            if(Str::contains($videoId, '=')) $videoId = Str::afterLast($videoId, '=');
                        @endphp
                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allowfullscreen></iframe>
                    @else
                        <video class="w-full h-full" controls>
                            <source src="{{ $lesson->video_url }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @endif
                </div>
            @else
                <div class="text-white text-center p-8 max-w-2xl bg-gray-800 rounded-2xl border border-gray-700">
                    <div class="w-20 h-20 bg-gray-700 rounded-3xl flex items-center justify-center mx-auto mb-6 text-gray-500">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h2 class="text-2xl font-bold mb-2">Reading Material</h2>
                    <p class="text-gray-400 mb-6">This lesson doesn't have a video. Please read the content below.</p>
                </div>
            @endif
        </div>

        {{-- Lesson Footer --}}
        <div class="bg-[#1a1a2e] border-t border-gray-800 p-4 sm:p-6 text-white flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <h1 class="text-lg font-bold">{{ $lesson->title }}</h1>
                <p class="text-sm text-gray-400">{{ $lesson->chapter->title }}</p>
            </div>
            <div class="flex items-center gap-4">
                <button id="completeBtn" onclick="markAsCompleted()" class="px-6 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-700 transition-all font-bold text-sm flex items-center gap-2">
                    <span id="btnText">Mark as Completed</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Sidebar Curriculum --}}
    <div class="w-full lg:w-96 bg-white border-l border-[#e8eaf0] flex flex-col h-full overflow-hidden">
        <div class="p-6 border-b border-[#e8eaf0]">
            <h2 class="font-bold text-[#1a1a2e] mb-1">Course Content</h2>
            <div class="flex items-center justify-between">
                <span id="globalProgress" class="text-xs font-bold text-[#e05a3a]">{{ $enrollment->progress }}% completed</span>
                <div class="w-32 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div id="progressBar" class="h-full bg-[#e05a3a]" style="width: {{ $enrollment->progress }}%"></div>
                </div>
            </div>
        </div>
        
        <div class="flex-1 overflow-y-auto custom-scrollbar">
            @foreach($course->chapters as $index => $chapter)
            <div class="border-b border-gray-50">
                <button onclick="toggleChapter({{ $chapter->id }})" class="w-full p-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-lg bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-500">{{ $index + 1 }}</span>
                        <h3 class="text-sm font-bold text-[#1a1a2e] text-left">{{ $chapter->title }}</h3>
                    </div>
                    <svg id="chevron-{{ $chapter->id }}" class="w-4 h-4 text-gray-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="chapter-{{ $chapter->id }}" class="bg-gray-50/50">
                    @foreach($chapter->lessons as $l)
                    <a href="{{ route('courses.lessons.show', [$course, $l]) }}" class="flex items-center gap-3 px-6 py-4 hover:bg-[#e05a3a10] group transition-colors {{ $lesson->id === $l->id ? 'bg-[#e05a3a08] border-r-2 border-[#e05a3a]' : '' }}">
                        <div class="flex-shrink-0">
                            @if(Auth::user()->completedLessons()->where('lesson_id', $l->id)->exists())
                                <div class="w-5 h-5 rounded-full bg-[#22c55e] flex items-center justify-center text-white">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                                </div>
                            @else
                                <div class="w-5 h-5 rounded-full border-2 border-gray-300"></div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm {{ $lesson->id === $l->id ? 'text-[#e05a3a] font-bold' : 'text-[#6b7280] group-hover:text-[#1a1a2e]' }} truncate">{{ $l->title }}</p>
                            <p class="text-[10px] text-[#6b7280]">{{ $l->duration_minutes }} mins</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="mt-8 prose prose-lg max-w-none p-6 bg-white rounded-2xl shadow-sm border border-[#e8eaf0]">
    <h2 class="text-2xl font-bold text-[#1a1a2e] mb-4">About this lesson</h2>
    <div class="text-[#6b7280] leading-relaxed">
        {!! $lesson->content ?: 'No additional content provided for this lesson.' !!}
    </div>
</div>

<script>
function toggleChapter(id) {
    const el = document.getElementById(`chapter-${id}`);
    const chevron = document.getElementById(`chevron-${id}`);
    if (el.classList.contains('hidden')) {
        el.classList.remove('hidden');
        chevron.style.transform = 'rotate(0deg)';
    } else {
        el.classList.add('hidden');
        chevron.style.transform = 'rotate(-90deg)';
    }
}

async function markAsCompleted() {
    const btn = document.getElementById('completeBtn');
    const btnText = document.getElementById('btnText');
    
    btn.disabled = true;
    btnText.innerText = 'Marking...';

    try {
        const response = await fetch('{{ route("courses.lessons.complete", [$course, $lesson]) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            btn.classList.replace('bg-orange-600', 'bg-green-600');
            btnText.innerText = 'Completed!';
            document.getElementById('globalProgress').innerText = data.progress + '% completed';
            document.getElementById('progressBar').style.width = data.progress + '%';

            // Reload after 1s to show checkmarks in sidebar
            setTimeout(() => window.location.reload(), 1000);
        } else {
            btn.disabled = false;
            btnText.innerText = 'Mark as Completed';
        }
    } catch (error) {
        console.error('Error:', error);
        btn.disabled = false;
        btnText.innerText = 'Mark as Completed';
    }
}
</script>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f8f9fa; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #e0e0e0; border-radius: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #e05a3a; }
</style>
@endsection
