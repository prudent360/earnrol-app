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

{{-- Hero with gradient --}}
<section class="relative overflow-hidden py-24 px-6" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    <div class="max-w-3xl mx-auto text-center relative z-10">
        <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight">{{ $content['hero']['title'] ?? $pageable->title }}</h1>
        @if(!empty($content['hero']['subtitle']))
        <p class="text-lg text-white/80 mt-4 max-w-xl mx-auto">{{ $content['hero']['subtitle'] }}</p>
        @endif
        <div class="mt-8 flex items-center justify-center gap-4">
            <a href="{{ $productUrl }}" class="inline-block bg-white text-purple-700 font-bold py-3 px-8 rounded-xl text-lg hover:bg-gray-100 transition-colors">
                {{ $content['hero']['cta_text'] ?? 'Get Started' }}
            </a>
            @if($pageable->price ?? false)
            <span class="text-white font-bold text-lg">{{ $currencySymbol }}{{ number_format($pageable->price, 2) }}</span>
            @endif
        </div>
    </div>
</section>

{{-- Features --}}
@if(!empty($content['features']))
<section class="py-20 px-6">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Everything Included</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($content['features'] as $feature)
            @if(!empty($feature['title']))
            <div class="text-center">
                <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="text-base font-bold text-gray-900">{{ $feature['title'] }}</h3>
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
<section class="py-20 px-6 bg-gray-50">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Trusted by Many</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($content['testimonials'] as $testimonial)
            @if(!empty($testimonial['quote']))
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                <div class="flex gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)
                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-gray-600">"{{ $testimonial['quote'] }}"</p>
                <div class="mt-6 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-sm">{{ strtoupper(substr($testimonial['name'] ?? 'A', 0, 1)) }}</div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">{{ $testimonial['name'] ?? '' }}</p>
                        <p class="text-xs text-gray-400">{{ $testimonial['role'] ?? '' }}</p>
                    </div>
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
<section class="py-20 px-6" x-data="{ active: null }">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">FAQ</h2>
        <div class="space-y-4">
            @foreach($content['faq'] as $i => $faq)
            @if(!empty($faq['question']))
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <button @click="active = active === {{ $i }} ? null : {{ $i }}" class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50">
                    <span class="font-semibold text-gray-900">{{ $faq['question'] }}</span>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" :class="active === {{ $i }} && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="active === {{ $i }}" x-transition class="px-6 pb-4">
                    <p class="text-gray-500">{{ $faq['answer'] ?? '' }}</p>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Creator --}}
@if($creator)
<section class="py-16 px-6 bg-gray-50">
    <div class="max-w-md mx-auto text-center">
        @if($creator->avatar)
        <img src="{{ Storage::url($creator->avatar) }}" class="w-20 h-20 rounded-full object-cover mx-auto mb-4" alt="">
        @else
        <div class="w-20 h-20 rounded-full bg-purple-100 flex items-center justify-center mx-auto mb-4">
            <span class="text-2xl font-bold text-purple-600">{{ strtoupper(substr($creator->name, 0, 1)) }}</span>
        </div>
        @endif
        <h3 class="text-lg font-bold text-gray-900">{{ $creator->name }}</h3>
        @if($creator->bio)
        <p class="text-sm text-gray-500 mt-2">{{ $creator->bio }}</p>
        @endif
    </div>
</section>
@endif

{{-- Final CTA --}}
@if(!empty($content['cta']['title']))
<section class="py-20 px-6" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="max-w-2xl mx-auto text-center">
        <h2 class="text-3xl font-bold text-white">{{ $content['cta']['title'] }}</h2>
        @if(!empty($content['cta']['subtitle']))
        <p class="text-white/70 mt-3">{{ $content['cta']['subtitle'] }}</p>
        @endif
        <a href="{{ $productUrl }}" class="inline-block mt-8 bg-white text-purple-700 font-bold py-3 px-10 rounded-xl text-lg hover:bg-gray-100 transition-colors">
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
