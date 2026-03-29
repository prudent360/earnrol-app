<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $content['hero']['title'] ?? $pageable->title ?? 'Sales Page' }}</title>
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#0f0f0f] text-white font-sans">
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

{{-- Hero --}}
<section class="py-24 px-6 border-b border-white/10">
    <div class="max-w-3xl mx-auto text-center">
        @if($pageable->price ?? false)
        <span class="inline-block bg-[#e05a3a] text-white text-xs font-bold uppercase tracking-wider px-4 py-1.5 rounded-full mb-6">{{ $currencySymbol }}{{ number_format($pageable->price, 2) }}</span>
        @endif
        <h1 class="text-5xl md:text-6xl font-bold leading-tight">{{ $content['hero']['title'] ?? $pageable->title }}</h1>
        @if(!empty($content['hero']['subtitle']))
        <p class="text-xl text-gray-400 mt-6 max-w-xl mx-auto">{{ $content['hero']['subtitle'] }}</p>
        @endif
        <a href="{{ $productUrl }}" class="inline-block mt-10 bg-[#e05a3a] hover:bg-[#c94d30] text-white font-bold py-4 px-10 rounded-xl text-lg transition-colors">
            {{ $content['hero']['cta_text'] ?? 'Get Started' }}
        </a>
    </div>
</section>

{{-- Features --}}
@if(!empty($content['features']))
<section class="py-20 px-6 border-b border-white/10">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-center mb-12">What's Inside</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($content['features'] as $feature)
            @if(!empty($feature['title']))
            <div class="bg-white/5 rounded-xl p-6 border border-white/10">
                <h3 class="text-lg font-bold text-white">{{ $feature['title'] }}</h3>
                @if(!empty($feature['description']))
                <p class="text-sm text-gray-400 mt-2">{{ $feature['description'] }}</p>
                @endif
            </div>
            @endif
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Testimonials --}}
@if(!empty($content['testimonials']))
<section class="py-20 px-6 border-b border-white/10">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-center mb-12">Real Results</h2>
        <div class="space-y-6">
            @foreach($content['testimonials'] as $testimonial)
            @if(!empty($testimonial['quote']))
            <div class="bg-white/5 rounded-xl p-8 border border-white/10">
                <p class="text-lg text-gray-300 italic">"{{ $testimonial['quote'] }}"</p>
                <p class="mt-4 text-sm"><span class="text-white font-bold">{{ $testimonial['name'] ?? '' }}</span> <span class="text-gray-500">{{ !empty($testimonial['role']) ? '- ' . $testimonial['role'] : '' }}</span></p>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- FAQ --}}
@if(!empty($content['faq']))
<section class="py-20 px-6 border-b border-white/10" x-data="{ active: null }">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-3xl font-bold text-center mb-12">Got Questions?</h2>
        <div class="space-y-3">
            @foreach($content['faq'] as $i => $faq)
            @if(!empty($faq['question']))
            <div class="bg-white/5 rounded-xl border border-white/10 overflow-hidden">
                <button @click="active = active === {{ $i }} ? null : {{ $i }}" class="w-full px-6 py-4 text-left flex justify-between items-center">
                    <span class="font-semibold">{{ $faq['question'] }}</span>
                    <svg class="w-4 h-4 text-gray-500 transition-transform" :class="active === {{ $i }} && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="active === {{ $i }}" x-transition class="px-6 pb-4">
                    <p class="text-gray-400">{{ $faq['answer'] ?? '' }}</p>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Final CTA --}}
@if(!empty($content['cta']['title']))
<section class="py-24 px-6">
    <div class="max-w-2xl mx-auto text-center">
        <h2 class="text-4xl font-bold">{{ $content['cta']['title'] }}</h2>
        @if(!empty($content['cta']['subtitle']))
        <p class="text-gray-400 mt-4 text-lg">{{ $content['cta']['subtitle'] }}</p>
        @endif
        <a href="{{ $productUrl }}" class="inline-block mt-8 bg-[#e05a3a] hover:bg-[#c94d30] text-white font-bold py-4 px-12 rounded-xl text-lg transition-colors">
            {{ $content['cta']['button_text'] ?? 'Buy Now' }}
        </a>
    </div>
</section>
@endif

<footer class="py-8 text-center text-xs text-gray-600 border-t border-white/10">
    Powered by {{ \App\Models\Setting::get('app_name', 'EarnRol') }}
</footer>
</body>
</html>
