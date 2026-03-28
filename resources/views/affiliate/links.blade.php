@extends('layouts.app')

@section('title', 'My Affiliate Links')
@section('page_title', 'My Affiliate Links')
@section('page_subtitle', 'Share these links to earn commission')

@section('content')
<div class="mb-6">
    <a href="{{ route('affiliate.dashboard') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Dashboard
    </a>
</div>

<div class="space-y-4">
    @forelse($links as $link)
    <div class="card">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="flex-1 min-w-0">
                <h3 class="text-sm font-bold text-[#1a1a2e]">{{ $link->affiliable->title ?? 'Unknown Item' }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ class_basename($link->affiliable_type) }}</p>

                <div class="flex items-center gap-2 mt-2">
                    <input type="text" value="{{ $link->url }}" readonly class="form-input text-xs py-1.5 flex-1 bg-gray-50 font-mono" id="link-{{ $link->id }}">
                    <button onclick="navigator.clipboard.writeText(document.getElementById('link-{{ $link->id }}').value); this.textContent='Copied!'; setTimeout(() => this.textContent='Copy', 2000)" class="text-xs bg-[#e05a3a] text-white px-3 py-1.5 rounded-lg hover:bg-[#c94e31] flex-shrink-0">Copy</button>
                </div>
            </div>
            <div class="flex gap-4 text-center">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Clicks</p>
                    <p class="text-lg font-extrabold text-[#1a1a2e]">{{ $link->clicks }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Sales</p>
                    <p class="text-lg font-extrabold text-[#1a1a2e]">{{ $link->sales_count }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Earned</p>
                    <p class="text-lg font-extrabold text-emerald-600">{{ $currencySymbol }}{{ number_format($link->sales_sum_affiliate_commission ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="card text-center text-gray-400 py-12">
        No affiliate links yet. <a href="{{ route('affiliate.products') }}" class="text-[#e05a3a] hover:underline">Browse products to promote</a>
    </div>
    @endforelse
</div>
@endsection
