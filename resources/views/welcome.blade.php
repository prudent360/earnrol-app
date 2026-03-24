@extends('layouts.guest')

@section('meta_description', 'EarnRol offers industry-led live tech training cohorts with expert instructors. Sign up, join a cohort, and attend live classes via Google Meet.')
@section('meta_keywords', 'UK tech training, live classes, tech cohorts, cloud computing, DevOps, cybersecurity')

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
                    Live Instructor-Led Training
                </div>
                <h1 class="text-4xl lg:text-6xl font-extrabold leading-tight mb-6">
                    Learn Tech.<br>
                    <span class="text-[#e05a3a]">Go Live.</span> Get Hired.
                </h1>
                <p class="text-gray-300 text-lg lg:text-xl leading-relaxed mb-8 max-w-xl">
                    Join live cohort-based classes taught by industry experts. Sign up, pick a cohort, and attend live sessions via Google Meet — real learning, real skills, real results.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('register') }}" class="btn-primary text-base px-8 py-4">
                        Join a Cohort
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
                        Live classes via Google Meet
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#e05a3a]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Small class sizes
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-6">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3" style="background-color:#e05a3a30;">
                        <svg class="w-6 h-6 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="text-lg font-bold text-white mb-1">Live Classes</div>
                    <div class="text-gray-400 text-sm">Interactive sessions with real instructors</div>
                </div>
                <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-6">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3" style="background-color:#3b82f630;">
                        <svg class="w-6 h-6 text-[#3b82f6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="text-lg font-bold text-white mb-1">Cohort-Based</div>
                    <div class="text-gray-400 text-sm">Learn together with a small group</div>
                </div>
                <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-6">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3" style="background-color:#22c55e30;">
                        <svg class="w-6 h-6 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div class="text-lg font-bold text-white mb-1">Industry Expert</div>
                    <div class="text-gray-400 text-sm">Taught by working professionals</div>
                </div>
                <div class="bg-[#e05a3a] rounded-2xl p-6">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3 bg-white/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="text-lg font-bold text-white mb-1">Flexible Schedule</div>
                    <div class="text-white/70 text-sm">Classes that fit your life</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section id="how-it-works" class="py-20 bg-[#f5f6fa]">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-14">
            <span class="text-[#e05a3a] font-semibold text-sm uppercase tracking-wider">How It Works</span>
            <h2 class="section-title mt-2">Three simple steps to start learning</h2>
            <p class="section-subtitle mx-auto mt-3">No complicated setup — just sign up and join a live class</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="card text-center relative">
                <div class="absolute top-4 right-4 w-8 h-8 bg-[#e05a3a] rounded-full flex items-center justify-center text-white font-bold text-sm">1</div>
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-5" style="background-color: #e05a3a20;">
                    <svg class="w-7 h-7 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-[#1a1a2e] mb-3">Create Your Account</h3>
                <p class="text-[#6b7280] leading-relaxed">Sign up in seconds with your email. No credit card required to get started.</p>
            </div>
            <div class="card text-center relative">
                <div class="absolute top-4 right-4 w-8 h-8 bg-[#3b82f6] rounded-full flex items-center justify-center text-white font-bold text-sm">2</div>
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-5" style="background-color: #3b82f620;">
                    <svg class="w-7 h-7 text-[#3b82f6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-[#1a1a2e] mb-3">Enrol in a Cohort</h3>
                <p class="text-[#6b7280] leading-relaxed">Browse available cohorts, pick one that fits your schedule, and complete your payment via Stripe.</p>
            </div>
            <div class="card text-center relative">
                <div class="absolute top-4 right-4 w-8 h-8 bg-[#22c55e] rounded-full flex items-center justify-center text-white font-bold text-sm">3</div>
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-5" style="background-color: #22c55e20;">
                    <svg class="w-7 h-7 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-[#1a1a2e] mb-3">Join Live Classes</h3>
                <p class="text-[#6b7280] leading-relaxed">Get instant access to your Google Meet link. Attend live sessions with your instructor and classmates.</p>
            </div>
        </div>
    </div>
</section>

{{-- WHAT YOU'LL LEARN --}}
<section id="features" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-14">
            <span class="text-[#e05a3a] font-semibold text-sm uppercase tracking-wider">Training Topics</span>
            <h2 class="section-title mt-2">In-demand skills taught by experts</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="card hover:shadow-md hover:-translate-y-1 transition-all duration-200 text-center">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4" style="background-color:#e05a3a20;">
                    <svg class="w-6 h-6 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[#1a1a2e] mb-2">Cloud Computing</h3>
                <p class="text-[#6b7280] text-sm">AWS, Azure, GCP — hands-on cloud infrastructure</p>
            </div>
            <div class="card hover:shadow-md hover:-translate-y-1 transition-all duration-200 text-center">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4" style="background-color:#f59e0b20;">
                    <svg class="w-6 h-6 text-[#f59e0b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[#1a1a2e] mb-2">DevOps & CI/CD</h3>
                <p class="text-[#6b7280] text-sm">Docker, Kubernetes, Jenkins, Terraform</p>
            </div>
            <div class="card hover:shadow-md hover:-translate-y-1 transition-all duration-200 text-center">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4" style="background-color:#22c55e20;">
                    <svg class="w-6 h-6 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[#1a1a2e] mb-2">Cybersecurity</h3>
                <p class="text-[#6b7280] text-sm">Security fundamentals, ethical hacking, compliance</p>
            </div>
            <div class="card hover:shadow-md hover:-translate-y-1 transition-all duration-200 text-center">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4" style="background-color:#3b82f620;">
                    <svg class="w-6 h-6 text-[#3b82f6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[#1a1a2e] mb-2">Software Engineering</h3>
                <p class="text-[#6b7280] text-sm">Full-stack development, APIs, databases</p>
            </div>
        </div>
    </div>
</section>

{{-- WHY EARNROL --}}
<section class="py-20 bg-[#f5f6fa]">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-14">
            <span class="text-[#e05a3a] font-semibold text-sm uppercase tracking-wider">Why EarnRol</span>
            <h2 class="section-title mt-2">Not just another online course</h2>
            <p class="section-subtitle mx-auto mt-3">We believe in real, live instruction — not pre-recorded videos you'll never finish</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="card">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background-color:#e05a3a20;">
                    <svg class="w-6 h-6 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[#1a1a2e] mb-2">100% Live Classes</h3>
                <p class="text-[#6b7280] text-sm leading-relaxed">Every session is live on Google Meet. Ask questions, get instant feedback, and learn in real time.</p>
            </div>
            <div class="card">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background-color:#3b82f620;">
                    <svg class="w-6 h-6 text-[#3b82f6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[#1a1a2e] mb-2">Small Cohort Sizes</h3>
                <p class="text-[#6b7280] text-sm leading-relaxed">Limited seats per cohort means more personal attention and better learning outcomes.</p>
            </div>
            <div class="card">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background-color:#22c55e20;">
                    <svg class="w-6 h-6 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[#1a1a2e] mb-2">Industry Experts</h3>
                <p class="text-[#6b7280] text-sm leading-relaxed">Our instructors are working professionals with real-world experience at top tech companies.</p>
            </div>
            <div class="card">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background-color:#8b5cf620;">
                    <svg class="w-6 h-6 text-[#8b5cf6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[#1a1a2e] mb-2">Structured Schedule</h3>
                <p class="text-[#6b7280] text-sm leading-relaxed">Fixed class times keep you accountable. No more "I'll watch it later" — show up and learn.</p>
            </div>
            <div class="card">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background-color:#f59e0b20;">
                    <svg class="w-6 h-6 text-[#f59e0b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[#1a1a2e] mb-2">Simple Pricing</h3>
                <p class="text-[#6b7280] text-sm leading-relaxed">Pay per cohort via Stripe. No subscriptions, no hidden fees. One payment, full access.</p>
            </div>
            <div class="card">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4" style="background-color:#e05a3a20;">
                    <svg class="w-6 h-6 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[#1a1a2e] mb-2">Learn From Anywhere</h3>
                <p class="text-[#6b7280] text-sm leading-relaxed">All you need is a laptop and internet connection. Join from anywhere in the world via Google Meet.</p>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="bg-[#e05a3a] py-16">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">Ready to start learning?</h2>
        <p class="text-white/80 text-lg mb-8">Sign up today and join the next available live cohort</p>
        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-white text-[#e05a3a] font-bold px-10 py-4 rounded-lg hover:bg-gray-100 transition-colors text-lg">
            Create Your Account
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </a>
        <p class="text-white/60 text-sm mt-4">Sign up is free — pay only when you enrol in a cohort</p>
    </div>
</section>

@endsection
