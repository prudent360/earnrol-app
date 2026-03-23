@extends('layouts.app')

@section('title', 'Manage Courses')
@section('page_title', 'Courses')
@section('page_subtitle', 'Create and manage learning content')

@section('content')

@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl px-5 py-4 flex items-center gap-3">
    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
</div>
@endif

{{-- Toolbar --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <form method="GET" action="{{ route('admin.courses.index') }}" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search courses..."
               class="form-input py-2 text-sm w-56">

        <select name="category" class="form-input py-2 text-sm w-44">
            <option value="">All Categories</option>
            @foreach($categories as $key => $label)
            <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>

        <select name="status" class="form-input py-2 text-sm w-36">
            <option value="">All Status</option>
            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
        </select>

        <button type="submit" class="btn-primary py-2 px-4 text-sm">Filter</button>
        @if(request()->hasAny(['search','category','status']))
        <a href="{{ route('admin.courses.index') }}" class="btn-outline py-2 px-4 text-sm">Clear</a>
        @endif
    </form>

    <a href="{{ route('admin.courses.create') }}" class="btn-primary whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Course
    </a>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
    $allCourses = \App\Models\Course::all();
    $stats = [
        ['label' => 'Total Courses',   'value' => $allCourses->count(),                              'color' => 'bg-blue-100 text-blue-600'],
        ['label' => 'Published',        'value' => $allCourses->where('status','published')->count(), 'color' => 'bg-green-100 text-green-600'],
        ['label' => 'Drafts',           'value' => $allCourses->where('status','draft')->count(),     'color' => 'bg-yellow-100 text-yellow-600'],
        ['label' => 'Total Enrollments','value' => \App\Models\Enrollment::count(),                   'color' => 'bg-purple-100 text-purple-600'],
    ];
    @endphp
    @foreach($stats as $stat)
    <div class="card py-4">
        <p class="text-xs text-gray-400 uppercase tracking-wider">{{ $stat['label'] }}</p>
        <p class="text-2xl font-bold text-[#1a1a2e] mt-1">{{ $stat['value'] }}</p>
    </div>
    @endforeach
</div>

{{-- Table --}}
<div class="card p-0 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50 text-left">
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Course</th>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Instructor</th>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Category</th>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Level</th>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Price</th>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Students</th>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($courses as $course)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                             style="background: {{ $course->icon_color }}20">
                            <div class="w-3 h-3 rounded-full" style="background: {{ $course->icon_color }}"></div>
                        </div>
                        <div>
                            <p class="font-medium text-[#1a1a2e]">{{ $course->title }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $course->lesson_count }} lessons · {{ $course->duration_hours }}h</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4 hidden md:table-cell">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-500">
                            {{ strtoupper(substr($course->instructor->name ?? 'U', 0, 1)) }}
                        </div>
                        <span class="text-xs text-gray-600">{{ $course->instructor->name ?? 'Unknown' }}</span>
                    </div>
                </td>
                <td class="px-5 py-4 hidden md:table-cell">
                    <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full">{{ $course->category_label }}</span>
                </td>
                <td class="px-5 py-4 hidden lg:table-cell capitalize text-gray-600">{{ $course->level }}</td>
                <td class="px-5 py-4 hidden lg:table-cell">
                    @if($course->is_free)
                    <span class="text-xs font-semibold text-green-600">Free</span>
                    @else
                    <span class="text-sm font-medium text-gray-700">${{ number_format($course->price, 2) }}</span>
                    @endif
                </td>
                <td class="px-5 py-4 hidden md:table-cell text-gray-600">{{ number_format($course->student_count) }}</td>
                <td class="px-5 py-4">
                    @if($course->status === 'published')
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-green-700 bg-green-50 border border-green-200 px-2.5 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Published
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-yellow-700 bg-yellow-50 border border-yellow-200 px-2.5 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> Draft
                    </span>
                    @endif
                </td>
                <td class="px-5 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.courses.edit', $course) }}"
                           class="text-xs text-blue-600 hover:text-blue-800 font-medium px-3 py-1.5 rounded-lg hover:bg-blue-50 transition-colors">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.courses.destroy', $course) }}"
                              onsubmit="return confirm('Delete this course? All enrollments will also be removed.')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="text-xs text-red-500 hover:text-red-700 font-medium px-3 py-1.5 rounded-lg hover:bg-red-50 transition-colors">
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-5 py-16 text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <p class="font-medium">No courses found</p>
                    <a href="{{ route('admin.courses.create') }}" class="text-[#e05a3a] text-sm mt-2 inline-block hover:underline">Add your first course</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($courses->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $courses->links() }}
    </div>
    @endif
</div>

@endsection
