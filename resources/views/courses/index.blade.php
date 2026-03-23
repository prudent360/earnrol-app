@extends('layouts.app')

@section('title', 'Learning Centre')
@section('page_title', 'Learning Centre')
@section('page_subtitle', 'Project-based courses to accelerate your tech career')

@section('content')

{{-- Category Filter Tabs --}}
<div class="flex items-center gap-2 flex-wrap mb-6">
    <a href="{{ route('courses.index') }}"
       class="px-4 py-2 rounded-full text-sm font-medium transition-all
              {{ !$category ? 'bg-[#e05a3a] text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-[#e05a3a] hover:text-[#e05a3a]' }}">
        All Tracks
    </a>
    @foreach($categories as $key => $label)
    <a href="{{ route('courses.index', ['category' => $key]) }}"
       class="px-4 py-2 rounded-full text-sm font-medium transition-all
              {{ $category === $key ? 'bg-[#e05a3a] text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-[#e05a3a] hover:text-[#e05a3a]' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

{{-- Featured Course Banner --}}
@if($featured && !$category)
<div class="rounded-2xl overflow-hidden mb-8 bg-[#1a2535] relative">
    <div class="absolute inset-0 overflow-hidden opacity-10">
        <div class="absolute top-0 right-0 w-72 h-72 rounded-full" style="background: radial-gradient(circle, #e05a3a, transparent)"></div>
    </div>
    <div class="relative flex flex-col md:flex-row items-center gap-6 p-6 md:p-8">
        <div class="flex-shrink-0">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background: {{ $featured->icon_color }}30">
                @if($featured->thumbnail)
                <img src="{{ Storage::url($featured->thumbnail) }}" class="w-14 h-14 rounded-xl object-cover">
                @else
                <div class="w-8 h-8 rounded-full" style="background: {{ $featured->icon_color }}"></div>
                @endif
            </div>
        </div>
        <div class="flex-1 text-center md:text-left">
            <div class="flex items-center gap-2 justify-center md:justify-start mb-2">
                <span class="text-xs font-semibold bg-[#e05a3a] text-white px-2.5 py-1 rounded-full">Featured</span>
                <span class="text-xs text-gray-400">{{ $featured->category_label }}</span>
            </div>
            <h2 class="text-xl md:text-2xl font-bold text-white">{{ $featured->title }}</h2>
            @if($featured->description)
            <p class="text-gray-400 text-sm mt-2 max-w-2xl">{{ Str::limit($featured->description, 120) }}</p>
            @endif
            <div class="flex items-center gap-4 mt-3 text-sm text-gray-400 justify-center md:justify-start flex-wrap">
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $featured->duration_hours }} hours
                </span>
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    {{ $featured->lesson_count }} lessons
                </span>
                @if($featured->rating > 0)
                <span class="flex items-center gap-1 text-yellow-400">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    {{ $featured->rating }} ({{ number_format($featured->student_count) }} students)
                </span>
                @endif
            </div>
        </div>
        <div class="flex-shrink-0">
            @if(in_array($featured->id, $enrolledIds))
            <a href="{{ route('courses.show', $featured) }}"
               class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-3 rounded-xl transition-colors">
                Continue Learning
            </a>
            @else
            <form method="POST" action="{{ route('courses.enroll', $featured) }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 bg-[#e05a3a] hover:bg-[#c74e30] text-white font-semibold px-6 py-3 rounded-xl transition-colors whitespace-nowrap">
                    Enroll Now — {{ $featured->is_free ? 'Free' : '$'.number_format($featured->price,2) }}
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endif

{{-- Course Grid --}}
@if($courses->count() > 0)
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
    @foreach($courses as $course)
    @php
    $badgeColors = [
        'Popular'  => 'bg-[#e05a3a] text-white',
        'Hot'      => 'bg-blue-600 text-white',
        'New'      => 'bg-green-600 text-white',
        'Trending' => 'bg-purple-600 text-white',
    ];
    @endphp
    <div class="card flex flex-col gap-4 hover:border-gray-300 transition-colors">
        <div class="flex items-start justify-between">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: {{ $course->icon_color }}20">
                @if($course->thumbnail)
                <img src="{{ Storage::url($course->thumbnail) }}" class="w-10 h-10 rounded-lg object-cover">
                @else
                <div class="w-5 h-5 rounded-full" style="background: {{ $course->icon_color }}"></div>
                @endif
            </div>
            <div class="flex items-center gap-1.5">
                @if($course->badge)
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $badgeColors[$course->badge] ?? 'bg-gray-100 text-gray-600' }}">
                    {{ $course->badge }}
                </span>
                @endif
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $course->is_free ? 'text-green-700 bg-green-50 border border-green-200' : 'text-blue-700 bg-blue-50 border border-blue-200' }}">
                    {{ $course->is_free ? 'Free' : '$'.number_format($course->price,2) }}
                </span>
            </div>
        </div>

        <div class="flex-1">
            <p class="text-xs text-gray-400 mb-1">{{ $course->category_label }}</p>
            <h3 class="font-bold text-[#1a1a2e] leading-snug">{{ $course->title }}</h3>
            @if($course->rating > 0)
            <div class="flex items-center gap-1.5 mt-2">
                <svg class="w-3.5 h-3.5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <span class="text-xs font-medium text-gray-700">{{ $course->rating }}</span>
                <span class="text-xs text-gray-400">{{ number_format($course->student_count) }} students · {{ $course->duration_hours }}h</span>
            </div>
            @else
            <p class="text-xs text-gray-400 mt-2">{{ number_format($course->student_count) }} students · {{ $course->duration_hours }}h</p>
            @endif
        </div>

        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
            <span class="text-xs border border-gray-200 text-gray-500 px-2.5 py-1 rounded-full capitalize">{{ $course->level }}</span>
            @if(in_array($course->id, $enrolledIds))
            <a href="{{ route('courses.show', $course) }}"
               class="text-sm font-semibold text-green-600 hover:text-green-700 flex items-center gap-1 transition-colors">
                Continue
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            @else
            <form method="POST" action="{{ route('courses.enroll', $course) }}">
                @csrf
                <button type="submit" class="text-sm font-semibold text-[#e05a3a] hover:text-[#c74e30] flex items-center gap-1 transition-colors">
                    {{ $course->is_free ? 'Enroll Free' : 'Start Course' }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </form>
            @endif
        </div>
    </div>
    @endforeach
</div>
@else
<div class="card text-center py-20">
    <svg class="w-16 h-16 mx-auto text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
    </svg>
    <p class="text-gray-500 font-medium">No courses found in this category.</p>
    <a href="{{ route('courses.index') }}" class="text-[#e05a3a] text-sm mt-2 inline-block hover:underline">View all courses</a>
</div>
@endif

@endsection
