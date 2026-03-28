@extends('layouts.app')

@section('title', 'Affiliate Products')
@section('page_title', 'Available Products')
@section('page_subtitle', 'Browse products you can promote')

@section('content')
<div class="mb-6">
    <a href="{{ route('affiliate.dashboard') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Dashboard
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($affiliateProducts as $ap)
    @php $item = $ap->affiliable; @endphp
    <div class="card !p-0 overflow-hidden">
        @if($item->cover_image)
        <img src="{{ Storage::url($item->cover_image) }}" alt="{{ $item->title }}" class="w-full h-36 object-cover">
        @else
        <div class="w-full h-36 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
            <svg class="w-10 h-10 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
        </div>
        @endif
        <div class="p-5">
            <h3 class="text-sm font-bold text-[#1a1a2e]">{{ $item->title }}</h3>
            <p class="text-xs text-gray-400 mt-1">{{ class_basename($ap->affiliable_type) }}</p>

            <div class="flex items-center justify-between mt-3">
                <div>
                    <p class="text-lg font-extrabold text-[#1a1a2e]">{{ $currencySymbol }}{{ number_format($item->price, 2) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400">Commission</p>
                    <p class="text-sm font-bold text-emerald-600">{{ $ap->commission_percentage }}%</p>
                </div>
            </div>

            <form action="{{ route('affiliate.generate-link') }}" method="POST" class="mt-4">
                @csrf
                <input type="hidden" name="affiliable_type" value="{{ $ap->affiliable_type }}">
                <input type="hidden" name="affiliable_id" value="{{ $ap->affiliable_id }}">
                <button type="submit" class="btn-primary w-full justify-center text-sm py-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    Get Affiliate Link
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-16 text-gray-400">No products available for affiliate promotion yet.</div>
    @endforelse
</div>
@endsection
