<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password — EarnRol</title>
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
            <h2 class="text-4xl font-extrabold text-white mb-4">Almost there,<br><span class="text-[#e05a3a]">set your new password</span></h2>
            <p class="text-gray-300 text-lg">Choose a strong password to keep your account secure.</p>
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

            <h1 class="text-3xl font-extrabold text-[#1a1a2e] mb-2">Reset your password</h1>
            <p class="text-[#6b7280] mb-8">Enter your new password below.</p>

            @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div>
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $email ?? '') }}" required
                        class="form-input @error('email') border-red-400 @enderror"
                        placeholder="you@example.com">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" id="password" name="password" required
                        class="form-input @error('password') border-red-400 @enderror"
                        placeholder="••••••••">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="form-input"
                        placeholder="••••••••">
                </div>
                <button type="submit" class="btn-primary w-full justify-center py-3.5 text-base">
                    Reset Password
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </button>
            </form>

            <p class="text-center text-sm text-[#6b7280] mt-6">
                Remember your password? <a href="{{ route('login') }}" class="text-[#e05a3a] font-semibold hover:underline">Sign in</a>
            </p>
        </div>
    </div>
</body>
</html>
