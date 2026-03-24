<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'EarnRol — Live tech training cohorts with expert instructors. Pay, join, and learn via Google Meet.')">
    <title>@yield('title', 'EarnRol') | Live Tech Training</title>
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

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        <aside id="sidebar" class="w-64 bg-[#1a2535] flex flex-col flex-shrink-0 transition-transform duration-300 z-40 fixed inset-y-0 left-0 -translate-x-full lg:relative lg:translate-x-0">
            {{-- Logo --}}
            <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
                @if($logo = \App\Models\Setting::get('logo_dark_path'))
                    <img src="{{ Storage::url($logo) }}" alt="{{ \App\Models\Setting::get('app_name', 'EarnRol') }}" class="h-8 w-auto">
                @else
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center shadow-sm" style="background-color: {{ \App\Models\Setting::get('brand_color', '#e05a3a') }};">
                        <span class="text-white font-bold text-lg">{{ substr(\App\Models\Setting::get('app_name', 'EarnRol'), 0, 1) }}</span>
                    </div>
                    <span class="text-white font-bold text-xl tracking-tight">{{ \App\Models\Setting::get('app_name', 'EarnRol') }}</span>
                @endif
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 mb-3">Main</p>

                <a href="{{ route('dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('cohorts.index') }}"
                   class="sidebar-link {{ request()->routeIs('cohorts*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    My Classes
                </a>

                <div class="pt-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-4 mb-3">Account</p>
                </div>

                <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profile
                </a>

                {{-- Admin Section --}}
                @if(auth()->user()->isAdmin())
                <div class="pt-4">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-4 mb-3">Admin</p>
                    <nav class="space-y-1 px-2">
                        <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            User Management
                        </a>
                        <a href="{{ route('admin.cohorts.index') }}" class="sidebar-link {{ request()->routeIs('admin.cohorts.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            Manage Cohorts
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            System Settings
                        </a>
                    </nav>
                </div>
                @endif
            </nav>

            {{-- User info --}}
            <div class="px-4 py-4 border-t border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-[#e05a3a] flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white text-sm font-semibold truncate">{{ auth()->user()->name ?? 'Guest' }}</p>
                        <p class="text-gray-400 text-xs truncate">{{ auth()->user()->email ?? '' }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-white transition-colors" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Overlay for mobile --}}
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-30 lg:hidden hidden" onclick="toggleSidebar()"></div>

        {{-- Main content area --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden lg:ml-0">
            {{-- Top navbar --}}
            <header class="bg-white border-b border-[#e8eaf0] flex-shrink-0">
                <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <button onclick="toggleSidebar()" class="lg:hidden text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <div>
                            <h1 class="text-lg font-semibold text-[#1a1a2e]">@yield('page_title', 'Dashboard')</h1>
                            <p class="text-xs text-gray-400">@yield('page_subtitle', '')</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-[#e05a3a] flex items-center justify-center text-white font-bold text-sm cursor-pointer">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page content --}}
            <main class="flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto p-6">
                    @if(session('success'))
                        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            {{ session('error') }}
                        </div>
                    @endif
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
    @stack('scripts')
</body>
</html>
