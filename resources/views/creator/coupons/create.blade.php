@extends('layouts.app')

@section('title', 'Create Discount Code')
@section('page_title', 'Create Discount Code')
@section('page_subtitle', 'Add a discount code for your items')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('creator.coupons.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Discount Codes
        </a>
    </div>

    <div class="card" x-data="{
        discountType: '{{ old('discount_type', 'percentage') }}',
        appliesTo: '{{ old('applies_to', 'cohort') }}'
    }">
        <form action="{{ route('creator.coupons.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="border-b border-gray-100 pb-4">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Code Details</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="code" class="form-label">Discount Code</label>
                    <input type="text" name="code" id="code" class="form-input uppercase @error('code') border-red-500 @enderror" value="{{ old('code') }}" required placeholder="e.g. SAVE20">
                    @error('code') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="description" class="form-label">Description (optional)</label>
                    <input type="text" name="description" id="description" class="form-input @error('description') border-red-500 @enderror" value="{{ old('description') }}" placeholder="e.g. Launch week special">
                    @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="border-b border-gray-100 pb-4 pt-2">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Discount</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="discount_type" class="form-label">Discount Type</label>
                    <select name="discount_type" id="discount_type" x-model="discountType" class="form-input" required>
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed">Fixed Amount</option>
                    </select>
                </div>
                <div>
                    <label for="discount_value" class="form-label">
                        <span x-text="discountType === 'percentage' ? 'Discount (%)' : 'Discount Amount'"></span>
                    </label>
                    <input type="number" name="discount_value" id="discount_value" step="0.01" min="0.01" class="form-input @error('discount_value') border-red-500 @enderror" value="{{ old('discount_value') }}" required>
                    @error('discount_value') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div x-show="discountType === 'percentage'" x-transition>
                    <label for="max_discount" class="form-label">Max Discount Cap (optional)</label>
                    <input type="number" name="max_discount" id="max_discount" step="0.01" min="0" class="form-input" value="{{ old('max_discount') }}" placeholder="No cap">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="min_purchase" class="form-label">Minimum Purchase (optional)</label>
                    <input type="number" name="min_purchase" id="min_purchase" step="0.01" min="0" class="form-input" value="{{ old('min_purchase') }}" placeholder="No minimum">
                </div>
                <div>
                    <label for="usage_limit" class="form-label">Usage Limit (optional)</label>
                    <input type="number" name="usage_limit" id="usage_limit" min="1" class="form-input" value="{{ old('usage_limit') }}" placeholder="Unlimited">
                </div>
            </div>

            <div class="border-b border-gray-100 pb-4 pt-2">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Applies To</h3>
            </div>

            <div>
                <label for="applies_to" class="form-label">Item Type</label>
                <select name="applies_to" id="applies_to" x-model="appliesTo" class="form-input" required>
                    <option value="cohort">Specific Cohort</option>
                    <option value="product">Specific Product</option>
                    <option value="membership">Specific Membership</option>
                </select>
            </div>

            <div x-show="appliesTo === 'cohort'" x-transition>
                <label class="form-label">Select Cohort</label>
                <select name="applicable_id" class="form-input" :disabled="appliesTo !== 'cohort'">
                    <option value="">— Select —</option>
                    @foreach($cohorts as $cohort)
                    <option value="{{ $cohort->id }}" {{ old('applicable_id') == $cohort->id ? 'selected' : '' }}>{{ $cohort->title }}</option>
                    @endforeach
                </select>
            </div>

            <div x-show="appliesTo === 'product'" x-transition>
                <label class="form-label">Select Product</label>
                <select name="applicable_id" class="form-input" :disabled="appliesTo !== 'product'">
                    <option value="">— Select —</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ old('applicable_id') == $product->id ? 'selected' : '' }}>{{ $product->title }}</option>
                    @endforeach
                </select>
            </div>

            <div x-show="appliesTo === 'membership'" x-transition>
                <label class="form-label">Select Membership</label>
                <select name="applicable_id" class="form-input" :disabled="appliesTo !== 'membership'">
                    <option value="">— Select —</option>
                    @foreach($memberships as $membership)
                    <option value="{{ $membership->id }}" {{ old('applicable_id') == $membership->id ? 'selected' : '' }}>{{ $membership->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="border-b border-gray-100 pb-4 pt-2">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Schedule</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="starts_at" class="form-label">Start Date (optional)</label>
                    <input type="date" name="starts_at" id="starts_at" class="form-input" value="{{ old('starts_at') }}">
                </div>
                <div>
                    <label for="expires_at" class="form-label">Expiry Date (optional)</label>
                    <input type="date" name="expires_at" id="expires_at" class="form-input" value="{{ old('expires_at') }}">
                </div>
            </div>

            <div class="flex items-center justify-between bg-gray-50 rounded-xl p-4">
                <div>
                    <p class="text-sm font-medium text-[#1a1a2e]">Active</p>
                    <p class="text-xs text-gray-400 mt-0.5">Enable this code immediately</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', '1') ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-[#e05a3a]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#e05a3a]"></div>
                </label>
            </div>

            <div class="pt-4 border-t border-[#e8eaf0] flex justify-end">
                <button type="submit" class="btn-primary">Create Discount Code</button>
            </div>
        </form>
    </div>
</div>
@endsection
