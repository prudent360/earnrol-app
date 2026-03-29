<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $content['hero']['title'] ?? $pageable->title ?? 'Sales Page' }}</title>
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#fafafa] text-gray-800 font-sans">
@php
    $currencySymbol = \App\Models\Setting::get('currency_symbol', '£');
    $productUrl = match(class_basename(get_class($pageable))) {
        'DigitalProduct' => route('products.show', $pageable),
        'Cohort' => route('cohorts.show', $pageable),
        'MembershipPlan' => route('memberships.show', $pageable),
        'CoachingService' => route('coaching.show', $pageable),
        default => '#',
    };
@endphp

<div class="max-w-2xl mx-auto px-6 py-16">
    {{-- Hero --}}
    <header class="mb-16">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight">{{ $content['hero']['title'] ?? $pageable->title }}</h1>
        @if(!empty($content['hero']['subtitle']))
        <p class="text-lg text-gray-500 mt-4 leading-relaxed">{{ $content['hero']['subtitle'] }}</p>
        @endif
        <div class="mt-8 flex items-center gap-4">
            <a href="{{ $productUrl }}" class="inline-block bg-gray-900 hover:bg-gray-800 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                {{ $content['hero']['cta_text'] ?? 'Get Started' }}
            </a>
            @if($pageable->price ?? false)
            <span class="text-gray-400 font-medium">{{ $currencySymbol }}{{ number_format($pageable->price, 2) }}</span>
            @endif
        </div>
    </header>

    {{-- Features --}}
    @if(!empty($content['features']))
    <section class="mb-16">
        <h2 class="text-xl font-bold text-gray-900 mb-6">What you get</h2>
        <ul class="space-y-4">
            @foreach($content['features'] as $feature)
            @if(!empty($feature['title']))
            <li class="flex items-start gap-3">
                <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <div>
                    <p class="font-medium text-gray-900">{{ $feature['title'] }}</p>
                    @if(!empty($feature['description']))
                    <p class="text-sm text-gray-500 mt-0.5">{{ $feature['description'] }}</p>
                    @endif
                </div>
            </li>
            @endif
            @endforeach
        </ul>
    </section>
    @endif

    {{-- Testimonials --}}
    @if(!empty($content['testimonials']))
    <section class="mb-16">
        <h2 class="text-xl font-bold text-gray-900 mb-6">What people are saying</h2>
        <div class="space-y-6">
            @foreach($content['testimonials'] as $testimonial)
            @if(!empty($testimonial['quote']))
            <blockquote class="border-l-2 border-gray-300 pl-4">
                <p class="text-gray-600 italic">"{{ $testimonial['quote'] }}"</p>
                <footer class="mt-2 text-sm text-gray-400">
                    {{ $testimonial['name'] ?? '' }}{{ !empty($testimonial['role']) ? ', ' . $testimonial['role'] : '' }}
                </footer>
            </blockquote>
            @endif
            @endforeach
        </div>
    </section>
    @endif

    {{-- FAQ --}}
    @if(!empty($content['faq']))
    <section class="mb-16" x-data="{ active: null }">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Questions</h2>
        <div class="divide-y divide-gray-200">
            @foreach($content['faq'] as $i => $faq)
            @if(!empty($faq['question']))
            <div>
                <button @click="active = active === {{ $i }} ? null : {{ $i }}" class="w-full py-4 text-left flex justify-between items-center">
                    <span class="font-medium text-gray-900">{{ $faq['question'] }}</span>
                    <span class="text-gray-400" x-text="active === {{ $i }} ? '−' : '+'"></span>
                </button>
                <div x-show="active === {{ $i }}" x-transition class="pb-4">
                    <p class="text-gray-500">{{ $faq['answer'] ?? '' }}</p>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </section>
    @endif

    {{-- Creator --}}
    @if($creator)
    <section class="mb-16 flex items-center gap-4">
        @if($creator->avatar)
        <img src="{{ Storage::url($creator->avatar) }}" class="w-12 h-12 rounded-full object-cover" alt="">
        @else
        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">{{ strtoupper(substr($creator->name, 0, 1)) }}</div>
        @endif
        <div>
            <p class="font-medium text-gray-900">{{ $creator->name }}</p>
            @if($creator->bio)
            <p class="text-sm text-gray-400">{{ Str::limit($creator->bio, 100) }}</p>
            @endif
        </div>
    </section>
    @endif

    {{-- Final CTA --}}
    @if(!empty($content['cta']['title']))
    <section class="bg-gray-900 text-white rounded-2xl p-10 text-center">
        <h2 class="text-2xl font-bold">{{ $content['cta']['title'] }}</h2>
        @if(!empty($content['cta']['subtitle']))
        <p class="text-gray-400 mt-2">{{ $content['cta']['subtitle'] }}</p>
        @endif
        <a href="{{ $productUrl }}" class="inline-block mt-6 bg-white text-gray-900 font-semibold py-3 px-8 rounded-lg hover:bg-gray-100 transition-colors">
            {{ $content['cta']['button_text'] ?? 'Buy Now' }}
        </a>
    </section>
    @endif

    <footer class="mt-12 text-center text-xs text-gray-400">
        Powered by {{ \App\Models\Setting::get('app_name', 'EarnRol') }}
    </footer>
</div>
</body>
</html>
