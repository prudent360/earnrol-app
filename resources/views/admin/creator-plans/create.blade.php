@extends('layouts.app')

@section('title', 'Create Creator Plan')
@section('page_title', 'Create Creator Plan')
@section('page_subtitle', 'Add a new subscription tier for creators')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.creator-plans.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Plans
        </a>
    </div>

    <div class="card">
        <form action="{{ route('admin.creator-plans.store') }}" method="POST" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="title" class="form-label">Plan Title</label>
                    <input type="text" name="title" id="title" class="form-input @error('title') border-red-500 @enderror" value="{{ old('title') }}" required placeholder="e.g. Starter">
                    @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="price" class="form-label">Price</label>
                    <input type="number" name="price" id="price" class="form-input @error('price') border-red-500 @enderror" value="{{ old('price') }}" required step="0.01" min="0.01" placeholder="19.99">
                    @error('price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="billing_interval" class="form-label">Billing Interval</label>
                    <select name="billing_interval" id="billing_interval" class="form-input">
                        <option value="monthly" {{ old('billing_interval') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="yearly" {{ old('billing_interval') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                    </select>
                </div>
                <div>
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-input">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div>
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="2" class="form-input" placeholder="Brief plan description">{{ old('description') }}</textarea>
            </div>
            <div>
                <label for="features" class="form-label">Features (one per line)</label>
                <textarea name="features" id="features" rows="5" class="form-input" placeholder="Unlimited products&#10;Priority support&#10;Analytics dashboard">{{ old('features') }}</textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label for="max_products" class="form-label">Max Products</label>
                    <input type="number" name="max_products" id="max_products" class="form-input" value="{{ old('max_products') }}" min="1" placeholder="Unlimited">
                    <p class="text-xs text-gray-400 mt-1">Leave empty for unlimited</p>
                </div>
                <div>
                    <label for="max_cohorts" class="form-label">Max Cohorts</label>
                    <input type="number" name="max_cohorts" id="max_cohorts" class="form-input" value="{{ old('max_cohorts') }}" min="1" placeholder="Unlimited">
                </div>
                <div>
                    <label for="sort_order" class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" class="form-input" value="{{ old('sort_order', 0) }}" min="0">
                </div>
            </div>
            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 text-[#e05a3a] focus:ring-[#e05a3a]" {{ old('is_featured') ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-[#1a1a2e]">Mark as featured plan</span>
                </label>
            </div>
            <div class="pt-4 border-t border-[#e8eaf0] flex justify-end">
                <button type="submit" class="btn-primary">Create Plan</button>
            </div>
        </form>
    </div>
</div>
@endsection
