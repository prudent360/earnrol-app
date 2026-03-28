@extends('layouts.app')

@section('title', 'Create Membership Plan')
@section('page_title', 'Create Membership Plan')
@section('page_subtitle', 'Set up a recurring membership for your subscribers')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('creator.memberships.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to My Memberships
        </a>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-6 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-sm text-blue-700">Your membership plan will be submitted for review. It will become visible to subscribers once approved by an admin.</p>
    </div>

    <div class="card">
        <form action="{{ route('creator.memberships.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="border-b border-gray-100 pb-4">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Plan Details</h3>
            </div>

            <div>
                <label for="title" class="form-label">Plan Title</label>
                <input type="text" name="title" id="title" class="form-input @error('title') border-red-500 @enderror" value="{{ old('title') }}" required placeholder="e.g. Premium Membership">
                @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="4" class="form-input @error('description') border-red-500 @enderror" placeholder="Describe what subscribers get with this membership...">{{ old('description') }}</textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="price" class="form-label">Price ({{ \App\Models\Setting::get('currency_symbol', '£') }})</label>
                    <input type="number" name="price" id="price" step="0.01" min="0.50" class="form-input @error('price') border-red-500 @enderror" value="{{ old('price', '9.99') }}" required>
                    @error('price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="billing_interval" class="form-label">Billing Interval</label>
                    <select name="billing_interval" id="billing_interval" class="form-input @error('billing_interval') border-red-500 @enderror" required>
                        @foreach(\App\Models\MembershipPlan::BILLING_INTERVALS as $key => $label)
                        <option value="{{ $key }}" {{ old('billing_interval', 'monthly') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('billing_interval') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="features" class="form-label">Features (one per line)</label>
                <textarea name="features" id="features" rows="5" class="form-input @error('features') border-red-500 @enderror" placeholder="Access to exclusive content&#10;Monthly live Q&A sessions&#10;Community access&#10;Early access to new products">{{ old('features') }}</textarea>
                @error('features') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">Each line becomes a feature bullet point.</p>
            </div>

            <div>
                <label for="max_subscribers" class="form-label">Max Subscribers (optional)</label>
                <input type="number" name="max_subscribers" id="max_subscribers" min="1" class="form-input @error('max_subscribers') border-red-500 @enderror" value="{{ old('max_subscribers') }}" placeholder="Leave empty for unlimited">
                @error('max_subscribers') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="welcome_message" class="form-label">Welcome Message (optional)</label>
                <textarea name="welcome_message" id="welcome_message" rows="3" class="form-input @error('welcome_message') border-red-500 @enderror" placeholder="Shown to subscribers after they join...">{{ old('welcome_message') }}</textarea>
                @error('welcome_message') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="border-b border-gray-100 pb-4 pt-2">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Cover Image</h3>
            </div>

            <div>
                <label for="cover_image" class="form-label">Cover Image (optional)</label>
                <input type="file" name="cover_image" id="cover_image" accept="image/*" class="form-input @error('cover_image') border-red-500 @enderror">
                @error('cover_image') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">Recommended: 800x600px. Shown on the membership page.</p>
            </div>

            @if(\App\Models\Setting::get('affiliate_enabled'))
            <div class="border-b border-gray-100 pb-4 pt-2">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Affiliate Promotion</h3>
            </div>

            <div class="flex items-center justify-between bg-gray-50 rounded-xl p-4">
                <div>
                    <p class="text-sm font-medium text-[#1a1a2e]">Allow Affiliate Promotion</p>
                    <p class="text-xs text-gray-400 mt-0.5">Let affiliates promote this item and earn commission per sale</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="affiliate_enabled" value="0">
                    <input type="checkbox" name="affiliate_enabled" value="1" class="sr-only peer" {{ old('affiliate_enabled', '') ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-[#e05a3a]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#e05a3a]"></div>
                </label>
            </div>

            <div>
                <label for="affiliate_commission" class="form-label">Affiliate Commission (%)</label>
                <input type="number" name="affiliate_commission" id="affiliate_commission" step="0.1" min="0" max="90" class="form-input" value="{{ old('affiliate_commission', '') }}" placeholder="e.g. 20">
                <p class="text-xs text-gray-400 mt-1">Platform fee on affiliate sales: {{ \App\Models\Setting::get('affiliate_admin_fee', '5') }}% (deducted from your share after affiliate commission)</p>
            </div>
            @endif

            <div class="pt-4 border-t border-[#e8eaf0] flex justify-end">
                <button type="submit" class="btn-primary">Submit for Review</button>
            </div>
        </form>
    </div>
</div>
@endsection
