@extends('layouts.guest')

@section('title', 'EarnRol')
@section('meta_description', 'EarnRol is Africa\'s AI-Powered Talent OS. Hire, upskill, and retain top tech talent through project-based learning, mentorship, and AI matching.')
@section('meta_keywords', 'AI talent platform, tech upskilling, cloud computing, DevOps, Africa, mentorship, hiring, project-based learning')

@section('content')

{{-- HERO --}}
<section class="bg-[#1a2535] text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-0 left-0 w-96 h-96 bg-[#e05a3a] rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-[#e05a3a] rounded-full translate-x-1/2 translate-y-1/2"></div>
    </div>
    <div class="max-w-7xl mx-auto px-6 py-24 lg:py-32 relative">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="inline-flex items-center gap-2 bg-[#e05a3a]/20 border border-[#e05a3a]/30 text-[#e05a3a] px-4 py-1.5 rounded-full text-sm font-medium mb-6">
                    <span class="w-2 h-2 bg-[#e05a3a] rounded-full animate-pulse"></span>
                    Africa's #1 AI-Powered Talent Platform
                </div>
                <h1 class="text-4xl lg:text-6xl font-extrabold leading-tight mb-6">
                    Hire. Upskill.<br>
                    <span class="text-[#e05a3a]">Retain.</span> Repeat.
                </h1>
                <p class="text-gray-300 text-lg lg:text-xl leading-relaxed mb-8 max-w-xl">
                    EarnRol connects organizations with top tech talent and helps individuals build real-world skills through project-based learning, AI-powered mentorship, and career matching.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('register') }}" class="btn-primary text-base px-8 py-4">
                        Start Learning Free
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <a href="#how-it-works" class="inline-flex items-center gap-2 border-2 border-white text-white hover:bg-white hover:text-[#1a2535] font-semibold px-8 py-4 rounded-lg transition-all duration-200">
                        See How It Works
                    </a>
                </div>
                <div class="mt-10 flex items-center gap-6 text-sm text-gray-400">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#e05a3a]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        No credit card required
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#e05a3a]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Free tier available
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-6">
                    <div class="text-3xl font-extrabold text-white mb-1">50K+</div>
                    <div class="text-gray-300 text-sm">Active Learners</div>
                    <div class="mt-3 flex items-center gap-1 text-[#e05a3a] text-xs font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        +24% this month
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-6">
                    <div class="text-3xl font-extrabold text-white mb-1">1,200+</div>
                    <div class="text-gray-300 text-sm">Companies Hiring</div>
                    <div class="mt-3 flex items-center gap-1 text-[#22c55e] text-xs font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        +18% this month
                    </div>
                </div>
                <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-6">
                    <div class="text-3xl font-extrabold text-white mb-1">300+</div>
                    <div class="text-gray-300 text-sm">Expert Mentors</div>
                    <div class="mt-3 flex items-center gap-1 text-[#3b82f6] text-xs font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        Across 15 countries
                    </div>
                </div>
                <div class="bg-[#e05a3a] rounded-2xl p-6">
                    <div class="text-3xl font-extrabold text-white mb-1">94%</div>
                    <div class="text-white/80 text-sm">Job Placement Rate</div>
                    <div class="mt-3 text-white/60 text-xs">Within 6 months</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- TRUSTED BY --}}
<section class="bg-white border-y border-[#e8eaf0] py-10">
    <div class="max-w-7xl mx-auto px-6">
        <p class="text-center text-sm text-gray-400 font-medium mb-6 uppercase tracking-wider">Trusted by teams at</p>
        <div class="flex flex-wrap items-center justify-center gap-10 opacity-40 grayscale">
            <span class="text-2xl font-bold text-gray-700">Google</span>
            <span class="text-2xl font-bold text-gray-700">Microsoft</span>
            <span class="text-2xl font-bold text-gray-700">Andela</span>
            <span class="text-2xl font-bold text-gray-700">Flutterwave</span>
            <span class="text-2xl font-bold text-gray-700">AWS</span>
            <span class="text-2xl font-bold text-gray-700">Paystack</span>
        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section id="how-it-works" class="py-20 bg-[#f5f6fa]">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-14">
            <span class="text-[#e05a3a] font-semibold text-sm uppercase tracking-wider">How It Works</span>
            <h2 class="section-title mt-2">Your path from learning to earning</h2>
            <p class="section-subtitle mx-auto mt-3">Three simple steps to transform your tech career with EarnRol</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="card text-center relative">
                <div class="absolute top-4 right-4 w-8 h-8 bg-[#e05a3a] rounded-full flex items-center justify-center text-white font-bold text-sm">1</div>
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-5" style="background-color: #e05a3a20;">
                    <svg class="w-7 h-7 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <h3 class="text-xl font-bold text-[#1a1a2e] mb-3">Learn by Doing</h3>
                <p class="text-[#6b7280] leading-relaxed">Enroll in project-based courses. Build real-world cloud and DevOps projects with guided mentorship, not just video lectures.</p>
            </div>
            <div class="card text-center relative">
                <div class="absolute top-4 right-4 w-8 h-8 bg-[#3b82f6] rounded-full flex items-center justify-center text-white font-bold text-sm">2</div>
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-5" style="background-color: #3b82f620;">
                    <svg class="w-7 h-7 text-[#3b82f6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-[#1a1a2e] mb-3">Earn Certifications</h3>
                <p class="text-[#6b7280] leading-relaxed">Complete projects, pass assessments, and earn industry-recognized certifications that prove your real skills to employers.</p>
            </div>
            <div class="card text-center relative">
                <div class="absolute top-4 right-4 w-8 h-8 bg-[#22c55e] rounded-full flex items-center justify-center text-white font-bold text-sm">3</div>
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-5" style="background-color: #22c55e20;">
                    <svg class="w-7 h-7 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-[#1a1a2e] mb-3">Get Hired</h3>
                <p class="text-[#6b7280] leading-relaxed">Our AI matches you with 1,200+ hiring companies based on your verified skills and project portfolio. Land your dream job.</p>
            </div>
        </div>
    </div>
</section>

{{-- FEATURES --}}
<section id="features" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-14">
            <span class="text-[#e05a3a] font-semibold text-sm uppercase tracking-wider">Platform Features</span>
            <h2 class="section-title mt-2">Everything you need to succeed</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="card hover:shadow-md hover:-translate-y-1 transition-all duration-200">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background-color:#e05a3a20;">
                    <svg class="w-6 h-6 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[#1a1a2e] mb-2">AI-Powered Learning</h3>
                <p class="text-[#6b7280] text-sm leading-relaxed">Personalized learning paths built by AI based on your goals, current skills, and career aspirations.</p>
            </div>
            <div class="card hover:shadow-md hover:-translate-y-1 transition-all duration-200">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background-color:#3b82f620;">
                    <svg class="w-6 h-6 text-[#3b82f6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[#1a1a2e] mb-2">Real-World Projects</h3>
                <p class="text-[#6b7280] text-sm leading-relaxed">Hands-on projects simulating actual workplace challenges. Build a portfolio that impresses employers.</p>
            </div>
            <div class="card hover:shadow-md hover:-translate-y-1 transition-all duration-200">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background-color:#8b5cf620;">
                    <svg class="w-6 h-6 text-[#8b5cf6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[#1a1a2e] mb-2">Expert Mentorship</h3>
                <p class="text-[#6b7280] text-sm leading-relaxed">Book 1-on-1 sessions with 300+ industry experts from Google, AWS, Microsoft and top African tech companies.</p>
            </div>
            <div class="card hover:shadow-md hover:-translate-y-1 transition-all duration-200">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background-color:#22c55e20;">
                    <svg class="w-6 h-6 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[#1a1a2e] mb-2">Industry Certifications</h3>
                <p class="text-[#6b7280] text-sm leading-relaxed">Earn verified digital credentials recognized by 1,200+ companies across Africa, UK, and North America.</p>
            </div>
            <div class="card hover:shadow-md hover:-translate-y-1 transition-all duration-200">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background-color:#f59e0b20;">
                    <svg class="w-6 h-6 text-[#f59e0b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[#1a1a2e] mb-2">AI Talent Matching</h3>
                <p class="text-[#6b7280] text-sm leading-relaxed">Our AI engine matches your skills, projects, and certifications with open roles at top hiring companies.</p>
            </div>
            <div class="card hover:shadow-md hover:-translate-y-1 transition-all duration-200">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background-color:#e05a3a20;">
                    <svg class="w-6 h-6 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[#1a1a2e] mb-2">Career Analytics</h3>
                <p class="text-[#6b7280] text-sm leading-relaxed">Track your learning progress, skill growth, and application insights with detailed career analytics.</p>
            </div>
        </div>
    </div>
</section>

{{-- LEARNING TRACKS --}}
<section class="py-20 bg-[#f5f6fa]">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-14">
            <span class="text-[#e05a3a] font-semibold text-sm uppercase tracking-wider">Learning Tracks</span>
            <h2 class="section-title mt-2">Master in-demand tech skills</h2>
            <p class="section-subtitle mx-auto mt-3">Project-based learning paths designed with industry leaders</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{ route('courses.index') }}" class="card group hover:shadow-lg hover:-translate-y-1 transition-all duration-200 cursor-pointer">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background-color:#e05a3a20;">
                    <svg class="w-6 h-6 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
                </div>
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-lg font-bold text-[#1a1a2e] group-hover:text-[#e05a3a] transition-colors">Cloud Computing</h3>
                    <span class="bg-[#e05a3a] text-white text-xs font-semibold px-2 py-0.5 rounded-full">Popular</span>
                </div>
                <p class="text-[#6b7280] text-sm mb-4">24 courses</p>
                <div class="flex items-center gap-1 text-[#e05a3a] text-sm font-medium group-hover:gap-2 transition-all">Explore Track <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></div>
            </a>
            <a href="{{ route('courses.index') }}" class="card group hover:shadow-lg hover:-translate-y-1 transition-all duration-200 cursor-pointer">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background-color:#f59e0b20;">
                    <svg class="w-6 h-6 text-[#f59e0b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-lg font-bold text-[#1a1a2e] group-hover:text-[#e05a3a] transition-colors">DevOps & CI/CD</h3>
                    <span class="bg-[#f59e0b] text-white text-xs font-semibold px-2 py-0.5 rounded-full">Hot</span>
                </div>
                <p class="text-[#6b7280] text-sm mb-4">18 courses</p>
                <div class="flex items-center gap-1 text-[#e05a3a] text-sm font-medium group-hover:gap-2 transition-all">Explore Track <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></div>
            </a>
            <a href="{{ route('courses.index') }}" class="card group hover:shadow-lg hover:-translate-y-1 transition-all duration-200 cursor-pointer">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background-color:#22c55e20;">
                    <svg class="w-6 h-6 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-lg font-bold text-[#1a1a2e] group-hover:text-[#e05a3a] transition-colors">Cybersecurity</h3>
                    <span class="bg-[#22c55e] text-white text-xs font-semibold px-2 py-0.5 rounded-full">New</span>
                </div>
                <p class="text-[#6b7280] text-sm mb-4">15 courses</p>
                <div class="flex items-center gap-1 text-[#e05a3a] text-sm font-medium group-hover:gap-2 transition-all">Explore Track <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></div>
            </a>
            <a href="{{ route('courses.index') }}" class="card group hover:shadow-lg hover:-translate-y-1 transition-all duration-200 cursor-pointer">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background-color:#3b82f620;">
                    <svg class="w-6 h-6 text-[#3b82f6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-lg font-bold text-[#1a1a2e] group-hover:text-[#e05a3a] transition-colors">Data Engineering</h3>
                    <span class="bg-[#3b82f6] text-white text-xs font-semibold px-2 py-0.5 rounded-full">Trending</span>
                </div>
                <p class="text-[#6b7280] text-sm mb-4">12 courses</p>
                <div class="flex items-center gap-1 text-[#e05a3a] text-sm font-medium group-hover:gap-2 transition-all">Explore Track <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg></div>
            </a>
        </div>
    </div>
</section>

{{-- TESTIMONIALS --}}
<section class="py-20 bg-[#1a2535]">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-14">
            <span class="text-[#e05a3a] font-semibold text-sm uppercase tracking-wider">Success Stories</span>
            <h2 class="text-3xl md:text-4xl font-bold text-white mt-2">Real results from real learners</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-6">
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)<svg class="w-4 h-4 text-[#f59e0b]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                </div>
                <p class="text-gray-300 text-sm leading-relaxed mb-5">"EarnRol transformed my career. I went from a junior developer to a Cloud Engineer at Google in under 12 months. The project-based approach is unlike anything else."</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#e05a3a] flex items-center justify-center text-white font-bold text-sm">AO</div>
                    <div>
                        <p class="text-white font-semibold text-sm">Amara Osei</p>
                        <p class="text-gray-400 text-xs">Cloud Engineer @ Google</p>
                    </div>
                </div>
            </div>
            <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-6">
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)<svg class="w-4 h-4 text-[#f59e0b]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                </div>
                <p class="text-gray-300 text-sm leading-relaxed mb-5">"The mentorship program connected me with industry experts who gave me real guidance. I landed my dream job after completing the DevOps track. 100% recommend."</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#3b82f6] flex items-center justify-center text-white font-bold text-sm">FD</div>
                    <div>
                        <p class="text-white font-semibold text-sm">Fatima Diallo</p>
                        <p class="text-gray-400 text-xs">DevOps Lead @ Andela</p>
                    </div>
                </div>
            </div>
            <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-6">
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)<svg class="w-4 h-4 text-[#f59e0b]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                </div>
                <p class="text-gray-300 text-sm leading-relaxed mb-5">"The AI talent matching feature matched me with Microsoft before I even finished my cybersecurity certification. This platform is a game changer for Africa."</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#22c55e] flex items-center justify-center text-white font-bold text-sm">KA</div>
                    <div>
                        <p class="text-white font-semibold text-sm">Kwame Asante</p>
                        <p class="text-gray-400 text-xs">Security Engineer @ Microsoft</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- PRICING --}}
<section id="pricing" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-14">
            <span class="text-[#e05a3a] font-semibold text-sm uppercase tracking-wider">Pricing</span>
            <h2 class="section-title mt-2">Simple, transparent pricing</h2>
            <p class="section-subtitle mx-auto mt-3">Start free, upgrade when you're ready</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <div class="card border-2 border-[#e8eaf0]">
                <p class="text-sm font-semibold text-[#6b7280] uppercase tracking-wider mb-2">Starter</p>
                <div class="flex items-baseline gap-1 mb-1"><span class="text-4xl font-extrabold text-[#1a1a2e]">Free</span></div>
                <p class="text-[#6b7280] text-sm mb-6">Perfect to get started</p>
                <ul class="space-y-3 mb-8">
                    @foreach(['3 free courses', 'Community access', 'Basic projects', '1 mentor session/month'] as $f)
                    <li class="flex items-center gap-2 text-sm text-[#1a1a2e]"><svg class="w-5 h-5 text-[#22c55e] flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>{{ $f }}</li>
                    @endforeach
                </ul>
                <a href="{{ route('register') }}" class="btn-outline w-full justify-center">Get Started Free</a>
            </div>
            <div class="card border-2 border-[#e05a3a] relative shadow-xl">
                <div class="absolute -top-4 left-1/2 -translate-x-1/2"><span class="bg-[#e05a3a] text-white text-xs font-bold px-4 py-1.5 rounded-full">Most Popular</span></div>
                <p class="text-sm font-semibold text-[#e05a3a] uppercase tracking-wider mb-2">Pro</p>
                <div class="flex items-baseline gap-1 mb-1"><span class="text-4xl font-extrabold text-[#1a1a2e]">$29</span><span class="text-[#6b7280] text-sm">/month</span></div>
                <p class="text-[#6b7280] text-sm mb-6">For serious career changers</p>
                <ul class="space-y-3 mb-8">
                    @foreach(['Unlimited courses', 'All learning tracks', 'Real-world projects', '4 mentor sessions/month', 'AI job matching', 'Industry certifications'] as $f)
                    <li class="flex items-center gap-2 text-sm text-[#1a1a2e]"><svg class="w-5 h-5 text-[#e05a3a] flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>{{ $f }}</li>
                    @endforeach
                </ul>
                <a href="{{ route('register') }}" class="btn-primary w-full justify-center">Start Pro Trial</a>
            </div>
            <div class="card border-2 border-[#1a2535]">
                <p class="text-sm font-semibold text-[#1a2535] uppercase tracking-wider mb-2">Enterprise</p>
                <div class="flex items-baseline gap-1 mb-1"><span class="text-4xl font-extrabold text-[#1a1a2e]">Custom</span></div>
                <p class="text-[#6b7280] text-sm mb-6">For organizations & teams</p>
                <ul class="space-y-3 mb-8">
                    @foreach(['Everything in Pro', 'Team management', 'Custom learning paths', 'Unlimited mentors', 'Dedicated support', 'Talent pipeline access'] as $f)
                    <li class="flex items-center gap-2 text-sm text-[#1a1a2e]"><svg class="w-5 h-5 text-[#22c55e] flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>{{ $f }}</li>
                    @endforeach
                </ul>
                <a href="#" class="btn-dark w-full justify-center">Contact Sales</a>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="bg-[#e05a3a] py-16">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">Ready to transform your tech career?</h2>
        <p class="text-white/80 text-lg mb-8">Join 50,000+ learners already building their future with EarnRol</p>
        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-white text-[#e05a3a] font-bold px-10 py-4 rounded-lg hover:bg-gray-100 transition-colors text-lg">
            Get Started Free Today
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </a>
        <p class="text-white/60 text-sm mt-4">No credit card required · Free plan available · Cancel anytime</p>
    </div>
</section>

@endsection
