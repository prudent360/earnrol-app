<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'Earnrol offers industry-led tech training, internship placements, and genuine UK work experience — designed for ambitious professionals and immigrants.')">
    <meta name="keywords" content="@yield('meta_keywords', 'UK tech training, tech internships, work experience, tech careers, immigrants in tech')">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="@yield('title', 'Earnrol | UK Tech Training, Internships & Work Experience')">
    <meta property="og:description" content="@yield('meta_description', 'Earnrol offers industry-led tech training, internship placements, and genuine UK work experience — designed for ambitious professionals and immigrants.')">
    <meta property="og:type" content="website">
    <title>@yield('title', 'Earnrol | UK Tech Training, Internships & Work Experience')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if($favicon = \App\Models\Setting::get('favicon_path'))
        <link rel="icon" type="image/x-icon" href="{{ Storage::url($favicon) }}">
    @endif
    @stack('head')
</head>
<body class="bg-[#f5f6fa] font-sans">

    {{-- Public Navbar --}}
    <header class="bg-[#1a2535] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                @if($logo = \App\Models\Setting::get('logo_dark_path'))
                    <img src="{{ Storage::url($logo) }}" alt="{{ \App\Models\Setting::get('app_name', 'EarnRol') }}" class="h-9 w-auto">
                @else
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center shadow-sm" style="background-color: {{ \App\Models\Setting::get('brand_color', '#e05a3a') }};">
                        <span class="text-white font-bold text-lg">{{ substr(\App\Models\Setting::get('app_name', 'EarnRol'), 0, 1) }}</span>
                    </div>
                    <span class="text-white font-bold text-xl tracking-tight">{{ \App\Models\Setting::get('app_name', 'EarnRol') }}</span>
                @endif
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}#how-it-works" class="text-gray-300 hover:text-white text-sm font-medium transition-colors">How It Works</a>
            </nav>

            {{-- Auth buttons --}}
            <div class="flex items-center gap-3">
                @auth
                <a href="{{ route('dashboard') }}" class="btn-primary text-sm px-5 py-2.5">
                    My Account
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                @else
                <a href="{{ route('login') }}" class="text-gray-300 hover:text-white text-sm font-medium transition-colors hidden md:inline">Sign In</a>
                <a href="{{ route('register') }}" class="btn-primary text-sm px-5 py-2.5">
                    Get Started Free
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                @endauth
                {{-- Mobile menu --}}
                <button id="mobile-menu-btn" class="md:hidden text-gray-300 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div id="mobile-menu" class="md:hidden hidden border-t border-white/10 px-6 py-4 space-y-3">
            <a href="{{ route('home') }}#how-it-works" class="block text-gray-300 hover:text-white text-sm font-medium">How It Works</a>
            @auth
            <a href="{{ route('dashboard') }}" class="block text-gray-300 hover:text-white text-sm font-medium">My Account</a>
            @else
            <a href="{{ route('login') }}" class="block text-gray-300 hover:text-white text-sm font-medium">Sign In</a>
            <a href="{{ route('register') }}" class="block text-gray-300 hover:text-white text-sm font-medium">Get Started Free</a>
            @endauth
        </div>
    </header>

    {{-- Main --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-[#1a2535] text-gray-400 py-16 mt-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
                <div class="md:col-span-1">
                    <div class="flex items-center gap-3 mb-4">
                        @if($logo = \App\Models\Setting::get('logo_dark_path'))
                            <img src="{{ Storage::url($logo) }}" alt="{{ \App\Models\Setting::get('app_name', 'EarnRol') }}" class="h-8 w-auto">
                        @else
                            <div class="w-9 h-9 bg-[#e05a3a] rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-lg">{{ substr(\App\Models\Setting::get('app_name', 'EarnRol'), 0, 1) }}</span>
                            </div>
                            <span class="text-white font-bold text-xl">{{ \App\Models\Setting::get('app_name', 'EarnRol') }}</span>
                        @endif
                    </div>
                    <p class="text-sm leading-relaxed">Africa's #1 AI-powered talent platform. Bridging the gap between learning and real-world tech careers.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm">Platform</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Learning Paths</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Projects</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Mentorship</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Jobs</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm">Company</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Careers</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm">Legal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm">&copy; {{ date('Y') }} EarnRol. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </a>
                    <a href="#" class="hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistrations().then(function(registrations) {
                registrations.forEach(function(r) { r.unregister(); });
            });
        }
    </script>
</body>
</html>
