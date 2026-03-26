<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Certificate Verification | {{ $appName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-[#f5f6fa] font-sans min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-xl font-bold text-[#1a1a2e]">{{ $appName }}</h1>
            <p class="text-sm text-gray-400">Certificate Verification</p>
        </div>

        @if($certificate)
        <div class="card text-center">
            <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h2 class="text-lg font-bold text-green-700 mb-1">Valid Certificate</h2>
            <p class="text-sm text-gray-500 mb-5">This certificate is authentic and verified.</p>

            <div class="bg-gray-50 rounded-xl p-4 text-left space-y-3 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Recipient</span>
                    <span class="font-semibold text-[#1a1a2e]">{{ $certificate->user->name }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Cohort</span>
                    <span class="font-semibold text-[#1a1a2e]">{{ $certificate->cohort->title }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Issued On</span>
                    <span class="font-semibold text-[#1a1a2e]">{{ $certificate->issued_at->format('F d, Y') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Certificate No.</span>
                    <span class="font-mono text-xs font-semibold text-[#e05a3a]">{{ $certificate->certificate_number }}</span>
                </div>
            </div>
        </div>
        @else
        <div class="card text-center">
            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h2 class="text-lg font-bold text-red-600 mb-1">Certificate Not Found</h2>
            <p class="text-sm text-gray-500">This certificate number could not be verified. Please check the number and try again.</p>
        </div>
        @endif

        <p class="text-center text-xs text-gray-400 mt-4">Powered by {{ $appName }}</p>
    </div>
</body>
</html>
