<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password — EarnRol</title>
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
            <h2 class="text-4xl font-extrabold text-white mb-4">Don't worry,<br><span class="text-[#e05a3a]">we've got you</span></h2>
            <p class="text-gray-300 text-lg">We'll send you a link to reset your password and get you back on track.</p>
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

            <h1 class="text-3xl font-extrabold text-[#1a1a2e] mb-2">Forgot your password?</h1>
            <p class="text-[#6b7280] mb-8">Enter your email and we'll send you a reset link.</p>

            @if(session('status'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('status') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="form-input @error('email') border-red-400 @enderror"
                        placeholder="you@example.com">
                </div>
                <button type="submit" class="btn-primary w-full justify-center py-3.5 text-base">
                    Send Reset Link
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </button>
            </form>

            <p class="text-center text-sm text-[#6b7280] mt-6">
                Remember your password? <a href="{{ route('login') }}" class="text-[#e05a3a] font-semibold hover:underline">Sign in</a>
            </p>
        </div>
    </div>
</body>
</html>
