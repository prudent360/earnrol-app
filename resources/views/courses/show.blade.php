@extends('layouts.app')

@section('title', $course->title)

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Course Header --}}
    <div class="bg-[#1a2535] rounded-3xl p-8 mb-8 relative overflow-hidden">
        <div class="absolute right-0 top-0 bottom-0 w-1/3 opacity-10 hidden lg:block">
            <div class="w-96 h-96 bg-[#e05a3a] rounded-full absolute -right-20 -top-20"></div>
        </div>
        
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
            <div class="lg:col-span-2">
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="px-3 py-1 rounded-full bg-[#e05a3a]/20 text-[#e05a3a] text-xs font-bold uppercase tracking-wider">{{ $course->category_label }}</span>
                    <span class="px-3 py-1 rounded-full bg-white/10 text-white text-xs font-bold uppercase tracking-wider">{{ $course->level }}</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-4 leading-tight">{{ $course->title }}</h1>
                <p class="text-gray-300 text-lg mb-6 max-w-2xl">{{ $course->description }}</p>
                
                <div class="flex flex-wrap items-center gap-6 text-sm text-gray-400">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-xs font-bold text-white">
                            {{ substr($course->instructor->name ?? 'I', 0, 1) }}
                        </div>
                        <span>By <span class="text-white font-medium">{{ $course->instructor->name ?? 'Instructor' }}</span></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0"/></svg>
                        <span>{{ $course->duration_hours }} Hours</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span>{{ number_format($course->student_count) }} Students</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-2xl">
                <div class="text-center mb-6">
                    @if($course->is_free)
                        <p class="text-3xl font-extrabold text-[#1a1a2e]">Free</p>
                    @else
                        <p class="text-3xl font-extrabold text-[#1a1a2e]">${{ number_format($course->price, 2) }}</p>
                    @endif
                    <p class="text-sm text-[#6b7280] mt-1">Full lifetime access</p>
                </div>

                @if($enrolled)
                    <div class="space-y-4">
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-[#1a1a2e]">Your Progress</span>
                                <span class="text-xs font-bold text-[#e05a3a]">{{ $enrolled->progress }}%</span>
                            </div>
                            <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-[#e05a3a]" style="width: {{ $enrolled->progress }}%"></div>
                            </div>
                        </div>
                        <a href="{{ $nextLesson ? route('courses.lessons.show', [$course, $nextLesson]) : '#' }}" class="w-full btn-primary py-4 flex items-center justify-center gap-2">
                            {{ $enrolled->progress > 0 ? 'Continue Learning' : 'Start Learning' }}
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </a>
                    </div>
                @else
                    @guest
                        <a href="{{ route('login') }}" class="w-full btn-primary py-4 flex items-center justify-center gap-2">
                            Sign in to Enroll
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        </a>
                    @else
                        <form action="{{ $course->is_free ? route('courses.enroll', $course) : route('payments.checkout', $course) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full btn-primary py-4 flex items-center justify-center gap-2">
                                {{ $course->is_free ? 'Enroll Now' : 'Buy Now' }}
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </button>
                        </form>
                    @endguest
                    <p class="text-center text-xs text-[#6b7280] mt-4">30-day money-back guarantee</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Course Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            {{-- Description --}}
            <div class="bg-white rounded-2xl p-8 border border-[#e8eaf0]">
                <h2 class="text-2xl font-bold text-[#1a1a2e] mb-4">Course Description</h2>
                <div class="text-[#6b7280] leading-relaxed space-y-4">
                    {!! nl2br(e($course->description)) !!}
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                </div>
            </div>

            {{-- Curriculum --}}
            <div class="bg-white rounded-2xl p-8 border border-[#e8eaf0]">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-[#1a1a2e]">Curriculum</h2>
                    <span class="text-sm text-[#6b7280]">{{ $course->chapters->count() }} sections · {{ $course->lessons->count() }} lessons</span>
                </div>

                <div class="space-y-4">
                    @foreach($course->chapters as $index => $chapter)
                    <div class="border border-[#e8eaf0] rounded-xl overflow-hidden active-tab" x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="w-full p-5 flex items-center justify-between bg-gray-50/50 hover:bg-gray-100/50 transition-colors">
                            <div class="flex items-center gap-4 text-left">
                                <span class="w-8 h-8 rounded-lg bg-white border border-[#e8eaf0] flex items-center justify-center text-sm font-bold text-[#1a1a2e]">{{ $index + 1 }}</span>
                                <h3 class="font-bold text-[#1a1a2e]">{{ $chapter->title }}</h3>
                            </div>
                            <svg class="w-5 h-5 text-[#6b7280] transform transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" class="divide-y divide-[#e8eaf0]">
                            @forelse($chapter->lessons as $lesson)
                            <div class="p-5 flex items-center justify-between hover:bg-gray-50/50">
                                <div class="flex items-center gap-4">
                                    <div class="w-6 h-6 rounded-full bg-orange-100 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-[#e05a3a]" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                    <span class="text-sm text-[#4b5563]">{{ $lesson->title }}</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    @if($lesson->is_preview)
                                        <span class="text-[10px] font-bold text-[#e05a3a] uppercase tracking-wider">Preview</span>
                                    @endif
                                    <span class="text-xs text-[#9ca3af]">{{ $lesson->duration_minutes }}m</span>
                                </div>
                            </div>
                            @empty
                            <div class="p-5 text-center text-sm text-[#9ca3af]">No lessons in this section yet.</div>
                            @endforelse
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Instructor Sidebar --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl p-8 border border-[#e8eaf0] text-center">
                <div class="w-24 h-24 rounded-3xl bg-[#1a2535] flex items-center justify-center text-3xl font-bold text-white mx-auto mb-4">
                    {{ substr($course->instructor->name ?? 'I', 0, 1) }}
                </div>
                <h3 class="text-xl font-bold text-[#1a1a2e] mb-1">{{ $course->instructor->name ?? 'Instructor Name' }}</h3>
                <p class="text-sm text-[#e05a3a] font-medium mb-4">Senior Technical Instructor</p>
                <p class="text-sm text-[#6b7280] leading-relaxed mb-6">Expert in cloud architecture and enterprise security with over 15 years of industry experience.</p>
                <div class="grid grid-cols-2 gap-4 border-t border-[#e8eaf0] pt-6">
                    <div>
                        <p class="text-lg font-bold text-[#1a1a2e]">12k+</p>
                        <p class="text-[10px] text-[#6b7280] uppercase tracking-wider">Students</p>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-[#1a1a2e]">15</p>
                        <p class="text-[10px] text-[#6b7280] uppercase tracking-wider">Courses</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
