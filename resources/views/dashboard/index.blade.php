@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Track your learning progress and career growth')

@section('content')

{{-- Welcome banner --}}
<div class="bg-[#1a2535] rounded-2xl p-4 sm:p-6 mb-6 relative overflow-hidden">
    <div class="absolute right-0 top-0 bottom-0 w-1/3 opacity-10">
        <div class="w-64 h-64 rounded-full absolute -right-10 -top-10" style="background-color: {{ \App\Models\Setting::get('brand_color', '#e05a3a') }};"></div>
    </div>
    <div class="relative">
        <p class="text-gray-400 text-sm mb-1">Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }},</p>
        <h2 class="text-2xl font-bold text-white mb-1">{{ auth()->user()->name ?? 'Learner' }} 👋</h2>
        @if($streakDays > 0)
        <p class="text-gray-300 text-sm">You're on a <span class="font-bold" style="color: {{ \App\Models\Setting::get('brand_color', '#e05a3a') }};">{{ $streakDays }}-day streak!</span> Keep it up to earn your next badge.</p>
        @else
        <p class="text-gray-300 text-sm">Start learning today to <span class="font-bold" style="color: {{ \App\Models\Setting::get('brand_color', '#e05a3a') }};">begin your streak!</span></p>
        @endif
        <div class="mt-4 flex items-center gap-4">
            <a href="{{ route('courses.index') }}" class="btn-primary text-sm py-2.5">
                Continue Learning
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
            <a href="{{ route('jobs.index') }}" class="text-white/70 hover:text-white text-sm font-medium transition-colors flex items-center gap-1">
                Browse Jobs
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </div>
</div>

{{-- Stats row --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background-color: {{ \App\Models\Setting::get('brand_color', '#e05a3a') }}20;">
            <svg class="w-6 h-6" style="color: {{ \App\Models\Setting::get('brand_color', '#e05a3a') }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-[#1a1a2e]">{{ $enrolledCoursesCount }}</p>
            <p class="text-sm text-[#6b7280]">Courses Enrolled</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background-color:#22c55e20;">
            <svg class="w-6 h-6 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-[#1a1a2e]">{{ $certificationsCount }}</p>
            <p class="text-sm text-[#6b7280]">Certifications</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background-color:#3b82f620;">
            <svg class="w-6 h-6 text-[#3b82f6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-[#1a1a2e]">{{ $projectsDoneCount }}</p>
            <p class="text-sm text-[#6b7280]">Projects Done</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background-color:#8b5cf620;">
            <svg class="w-6 h-6 text-[#8b5cf6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-[#1a1a2e]">{{ $mentorSessionsCount }}</p>
            <p class="text-sm text-[#6b7280]">Mentor Sessions</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Continue Learning --}}
    <div class="lg:col-span-2 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-[#1a1a2e]">Continue Learning</h3>
            <a href="{{ route('courses.index') }}" class="text-sm font-medium hover:underline" style="color: {{ \App\Models\Setting::get('brand_color', '#e05a3a') }};">View all</a>
        </div>

        @forelse($activeCourses as $enrolled)
        <div class="card hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 bg-orange-100">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2 mb-1">
                        <h4 class="font-semibold text-[#1a1a2e] text-sm truncate">{{ $enrolled->course->title }}</h4>
                        <span class="text-xs font-bold text-[#1a1a2e] flex-shrink-0">{{ $enrolled->progress }}%</span>
                    </div>
                    <p class="text-xs text-[#6b7280] mb-2">{{ $enrolled->course->category_label }} · By {{ $enrolled->course->instructor->name ?? 'Instructor' }}</p>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $enrolled->progress }}%; background-color: {{ \App\Models\Setting::get('brand_color', '#e05a3a') }};"></div>
                    </div>
                </div>
                <a href="{{ route('courses.show', $enrolled->course) }}" class="flex-shrink-0 p-2 rounded-lg hover:bg-gray-100 text-[#6b7280] hover:text-[#e05a3a] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </a>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl p-8 border border-dashed border-gray-300 text-center">
            <p class="text-gray-500 text-sm mb-4">You haven't enrolled in any courses yet.</p>
            <a href="{{ route('courses.index') }}" class="text-[#e05a3a] font-bold text-sm hover:underline">Browse Catalog →</a>
        </div>
        @endforelse

        {{-- Recent Projects --}}
        <div class="flex items-center justify-between mt-6">
            <h3 class="text-lg font-bold text-[#1a1a2e]">Recent Projects</h3>
            <a href="{{ route('projects.index') }}" class="text-sm text-[#e05a3a] font-medium hover:underline">View all</a>
        </div>
        @php
            $userProjects = \App\Models\ProjectEnrollment::where('user_id', auth()->id())
                ->with('project')
                ->latest()
                ->take(2)
                ->get();
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @forelse($userProjects as $pe)
            <div class="card">
                <div class="flex items-start justify-between mb-3">
                    @if($pe->completed_at)
                    <span class="badge bg-[#22c55e]/10 text-[#22c55e]">Completed</span>
                    @else
                    <span class="badge bg-[#f59e0b]/10 text-[#f59e0b]">In Progress</span>
                    @endif
                    <span class="text-xs text-[#6b7280]">{{ $pe->created_at->diffForHumans() }}</span>
                </div>
                <h4 class="font-semibold text-[#1a1a2e] mb-1">{{ $pe->project->title ?? 'Untitled Project' }}</h4>
                <p class="text-xs text-[#6b7280] mb-3">{{ Str::limit($pe->project->description ?? '', 60) }}</p>
            </div>
            @empty
            <div class="col-span-2 bg-white rounded-2xl p-8 border border-dashed border-gray-300 text-center">
                <p class="text-gray-500 text-sm mb-4">You haven't started any projects yet.</p>
                <a href="{{ route('projects.index') }}" class="text-[#e05a3a] font-bold text-sm hover:underline">Browse Projects →</a>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Right sidebar --}}
    <div class="space-y-4">

        {{-- Job Matches --}}
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-[#1a1a2e]">Latest Jobs</h3>
                @if($recentJobs->count() > 0)
                <span class="badge bg-[#e05a3a]/10 text-[#e05a3a]">{{ $recentJobs->count() }} new</span>
                @endif
            </div>
            <div class="space-y-3">
                @forelse($recentJobs as $job)
                <a href="{{ route('jobs.show', $job) }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-[#f5f6fa] transition-colors">
                    <div class="w-9 h-9 bg-[#1a2535] rounded-lg flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                        {{ substr($job->company ?? 'J', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-[#1a1a2e] truncate">{{ $job->title }}</p>
                        <p class="text-xs text-[#6b7280]">{{ $job->company }} · {{ $job->type ?? 'Full-time' }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-xs text-[#6b7280]">{{ $job->location ?? 'Remote' }}</p>
                    </div>
                </a>
                @empty
                <div class="text-center py-4">
                    <p class="text-xs text-gray-400">No jobs posted yet.</p>
                </div>
                @endforelse
                <a href="{{ route('jobs.index') }}" class="block text-center text-sm text-[#e05a3a] font-medium hover:underline pt-1">View all jobs →</a>
            </div>
        </div>

        {{-- Upcoming Mentorship --}}
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-[#1a1a2e]">Next Session</h3>
                <a href="{{ route('mentorship.index') }}" class="text-xs text-[#e05a3a] hover:underline">{{ $nextSession ? 'View profile' : 'Book one' }}</a>
            </div>
            @if($nextSession)
            <div class="bg-[#e05a3a]/5 border border-[#e05a3a]/20 rounded-xl p-4">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-[#1a2535] flex items-center justify-center text-white font-bold text-sm">
                        {{ substr($nextSession->mentor->user->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-[#1a1a2e] text-sm truncate">{{ $nextSession->mentor->user->name }}</p>
                        <p class="text-xs text-[#6b7280] truncate">{{ $nextSession->mentor->role_title }} @ {{ $nextSession->mentor->company }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-xs text-[#6b7280] mb-3">
                    <svg class="w-4 h-4 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ $nextSession->scheduled_at->format('M d · g:i A') }}
                </div>
                <span class="badge {{ $nextSession->status === 'confirmed' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }} text-xs uppercase tracking-wider">{{ $nextSession->status }}</span>
            </div>
            @else
            <div class="text-center py-6 px-4 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                <p class="text-xs text-gray-500 mb-3">No upcoming sessions. Gain insights from experts today!</p>
                <a href="{{ route('mentorship.index') }}" class="btn-primary text-[10px] py-2 px-4 shadow-none">Schedule Session</a>
            </div>
            @endif
        </div>

        {{-- Skill Progress --}}
        <div class="card">
            <h3 class="font-bold text-[#1a1a2e] mb-4">Skill Progress</h3>
            <div class="space-y-4">
                @forelse($skillProgress as $skill)
                <div>
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="text-[#1a1a2e] font-medium">{{ $skill['name'] }}</span>
                        <span class="text-[#6b7280]">{{ $skill['pct'] }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width:{{ $skill['pct'] }}%; background-color:{{ $skill['color'] }};"></div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <p class="text-xs text-gray-400">Enroll in courses to start tracking skill progress.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>

</div>

@endsection
