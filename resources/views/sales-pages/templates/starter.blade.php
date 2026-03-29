<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $content['hero']['title'] ?? $pageable->title ?? 'Sales Page' }}</title>
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-white text-gray-800 font-sans">
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
<section class="bg-gradient-to-br from-[#1a1a2e] to-[#16213e] text-white py-20 px-6">
    <div class="max-w-3xl mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-bold leading-tight">{{ $content['hero']['title'] ?? $pageable->title }}</h1>
        @if(!empty($content['hero']['subtitle']))
        <p class="text-lg text-gray-300 mt-4">{{ $content['hero']['subtitle'] }}</p>
        @endif
        <div class="mt-8">
            <a href="{{ $productUrl }}" class="inline-block bg-[#e05a3a] hover:bg-[#c94d30] text-white font-bold py-3 px-8 rounded-xl text-lg transition-colors">
                {{ $content['hero']['cta_text'] ?? 'Get Started' }}
            </a>
        </div>
        @if($pageable->price ?? false)
        <p class="text-sm text-gray-400 mt-4">{{ $currencySymbol }}{{ number_format($pageable->price, 2) }}</p>
        @endif
    </div>
</section>

{{-- Features --}}
@if(!empty($content['features']))
<section class="py-16 px-6 bg-gray-50">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold text-center text-[#1a1a2e] mb-10">What You Get</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($content['features'] as $feature)
            @if(!empty($feature['title']))
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h3 class="text-base font-bold text-[#1a1a2e]">{{ $feature['title'] }}</h3>
                @if(!empty($feature['description']))
                <p class="text-sm text-gray-500 mt-2">{{ $feature['description'] }}</p>
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
<section class="py-16 px-6">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold text-center text-[#1a1a2e] mb-10">What People Say</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($content['testimonials'] as $testimonial)
            @if(!empty($testimonial['quote']))
            <div class="bg-gray-50 rounded-xl p-6">
                <p class="text-sm text-gray-600 italic">"{{ $testimonial['quote'] }}"</p>
                <div class="mt-4">
                    <p class="text-sm font-bold text-[#1a1a2e]">{{ $testimonial['name'] ?? '' }}</p>
                    @if(!empty($testimonial['role']))
                    <p class="text-xs text-gray-400">{{ $testimonial['role'] }}</p>
                    @endif
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- FAQ --}}
@if(!empty($content['faq']))
<section class="py-16 px-6 bg-gray-50" x-data="{ active: null }">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold text-center text-[#1a1a2e] mb-10">Frequently Asked Questions</h2>
        <div class="space-y-3">
            @foreach($content['faq'] as $i => $faq)
            @if(!empty($faq['question']))
            <div class="bg-white rounded-xl overflow-hidden">
                <button @click="active = active === {{ $i }} ? null : {{ $i }}" class="w-full px-6 py-4 text-left flex justify-between items-center">
                    <span class="text-sm font-semibold text-[#1a1a2e]">{{ $faq['question'] }}</span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform" :class="active === {{ $i }} && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="active === {{ $i }}" x-transition class="px-6 pb-4">
                    <p class="text-sm text-gray-500">{{ $faq['answer'] ?? '' }}</p>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- About Creator --}}
@if($creator)
<section class="py-16 px-6">
    <div class="max-w-2xl mx-auto text-center">
        <div class="w-16 h-16 rounded-full bg-[#e05a3a]/10 flex items-center justify-center mx-auto mb-4">
            @if($creator->avatar)
            <img src="{{ Storage::url($creator->avatar) }}" class="w-16 h-16 rounded-full object-cover" alt="">
            @else
            <span class="text-xl font-bold text-[#e05a3a]">{{ strtoupper(substr($creator->name, 0, 1)) }}</span>
            @endif
        </div>
        <h3 class="text-lg font-bold text-[#1a1a2e]">{{ $creator->name }}</h3>
        @if($creator->bio)
        <p class="text-sm text-gray-500 mt-2 max-w-md mx-auto">{{ $creator->bio }}</p>
        @endif
    </div>
</section>
@endif

{{-- Final CTA --}}
@if(!empty($content['cta']['title']))
<section class="bg-[#1a1a2e] text-white py-16 px-6">
    <div class="max-w-2xl mx-auto text-center">
        <h2 class="text-2xl font-bold">{{ $content['cta']['title'] }}</h2>
        @if(!empty($content['cta']['subtitle']))
        <p class="text-gray-400 mt-2">{{ $content['cta']['subtitle'] }}</p>
        @endif
        <a href="{{ $productUrl }}" class="inline-block mt-6 bg-[#e05a3a] hover:bg-[#c94d30] text-white font-bold py-3 px-8 rounded-xl transition-colors">
            {{ $content['cta']['button_text'] ?? 'Buy Now' }}
        </a>
    </div>
</section>
@endif

<footer class="py-6 text-center text-xs text-gray-400">
    Powered by {{ \App\Models\Setting::get('app_name', 'EarnRol') }}
</footer>
</body>
</html>
