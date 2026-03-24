<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In — EarnRol</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f5f6fa] font-sans min-h-screen flex">

    {{-- Left panel --}}
    <div class="hidden lg:flex flex-col justify-between w-1/2 bg-[#1a2535] p-12 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-80 h-80 bg-[#e05a3a] rounded-full translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-[#e05a3a] rounded-full -translate-x-1/2 translate-y-1/2"></div>
        </div>
        <div class="relative">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#e05a3a] rounded-xl flex items-center justify-center">
                    <span class="text-white font-bold text-xl">E</span>
                </div>
                <span class="text-white font-bold text-2xl">EarnRol</span>
            </a>
        </div>
        <div class="relative">
            <h2 class="text-4xl font-extrabold text-white mb-4">Welcome back to your<br><span class="text-[#e05a3a]">learning journey</span></h2>
            <p class="text-gray-300 text-lg mb-10">Continue building the skills that will transform your tech career.</p>
            <div class="space-y-4">
                <div class="flex items-center gap-3 bg-white/10 rounded-xl p-4">
                    <div class="w-10 h-10 rounded-full bg-[#e05a3a] flex items-center justify-center text-white font-bold text-sm flex-shrink-0">AO</div>
                    <div>
                        <p class="text-white font-semibold text-sm">Amara Osei</p>
                        <p class="text-gray-400 text-xs">"Landed my Cloud Engineer role at Google!" ⭐⭐⭐⭐⭐</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 bg-white/10 rounded-xl p-4">
                    <div class="w-10 h-10 rounded-full bg-[#3b82f6] flex items-center justify-center text-white font-bold text-sm flex-shrink-0">FD</div>
                    <div>
                        <p class="text-white font-semibold text-sm">Fatima Diallo</p>
                        <p class="text-gray-400 text-xs">"DevOps Lead at Andela in 8 months!" ⭐⭐⭐⭐⭐</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="relative text-gray-500 text-sm">
            &copy; {{ date('Y') }} EarnRol. All rights reserved.
        </div>
    </div>

    {{-- Right panel --}}
    <div class="flex-1 flex items-center justify-center p-6 lg:p-12">
        <div class="w-full max-w-md">
            {{-- Mobile logo --}}
            <div class="lg:hidden mb-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-[#e05a3a] rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-lg">E</span>
                    </div>
                    <span class="text-[#1a2535] font-bold text-xl">EarnRol</span>
                </a>
            </div>

            <h1 class="text-3xl font-extrabold text-[#1a1a2e] mb-2">Sign in to your account</h1>
            <p class="text-[#6b7280] mb-8">Don't have an account? <a href="{{ route('register') }}" class="text-[#e05a3a] font-semibold hover:underline">Create one free</a></p>

            @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="form-input @error('email') border-red-400 @enderror"
                        placeholder="you@example.com">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="form-label mb-0">Password</label>
                        <a href="{{ route('password.request') }}" class="text-sm text-[#e05a3a] hover:underline">Forgot password?</a>
                    </div>
                    <input type="password" id="password" name="password" required
                        class="form-input @error('password') border-red-400 @enderror"
                        placeholder="••••••••">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 accent-[#e05a3a]">
                    <label for="remember" class="text-sm text-[#6b7280]">Remember me for 30 days</label>
                </div>
                <button type="submit" class="btn-primary w-full justify-center py-3.5 text-base">
                    Sign In
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </form>

            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-[#e8eaf0]"></div></div>
                <div class="relative flex justify-center text-sm"><span class="px-4 bg-[#f5f6fa] text-[#6b7280]">or continue with</span></div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <button class="flex items-center justify-center gap-2 border border-[#e8eaf0] bg-white px-4 py-3 rounded-lg text-sm font-medium text-[#1a1a2e] hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    Google
                </button>
                <button class="flex items-center justify-center gap-2 border border-[#e8eaf0] bg-white px-4 py-3 rounded-lg text-sm font-medium text-[#1a1a2e] hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="#0A66C2" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    LinkedIn
                </button>
            </div>

            <p class="text-center text-xs text-[#6b7280] mt-6">
                By signing in, you agree to our <a href="#" class="text-[#e05a3a] hover:underline">Terms of Service</a> and <a href="#" class="text-[#e05a3a] hover:underline">Privacy Policy</a>
            </p>
        </div>
    </div>
</body>
</html>
