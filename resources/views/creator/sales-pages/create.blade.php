@extends('layouts.app')

@section('title', 'Create Sales Page')
@section('page_title', 'Create Sales Page')
@section('page_subtitle', 'Build a landing page for your product')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('creator.sales-pages.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Sales Pages
        </a>
    </div>

    <div class="card" x-data="salesPageForm()">
        <form action="{{ route('creator.sales-pages.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Step 1: Choose Item --}}
            <div>
                <label class="form-label">What is this sales page for?</label>
                <select name="pageable_type" x-model="itemType" class="form-input" required>
                    <option value="">Select type...</option>
                    @if($products->count()) <option value="product">Digital Product</option> @endif
                    @if($cohorts->count()) <option value="cohort">Cohort / Course</option> @endif
                    @if($memberships->count()) <option value="membership">Membership Plan</option> @endif
                    @if($coaching->count()) <option value="coaching">Coaching Service</option> @endif
                </select>
            </div>

            <div x-show="itemType">
                <label class="form-label">Select Item</label>
                <select name="pageable_id" class="form-input" required>
                    <option value="">Choose...</option>
                    <template x-if="itemType === 'product'">
                        <template x-for="(text, val) in productOptions">
                            <option :value="val" x-text="text"></option>
                        </template>
                    </template>
                    <template x-if="itemType === 'cohort'">
                        <template x-for="(text, val) in cohortOptions">
                            <option :value="val" x-text="text"></option>
                        </template>
                    </template>
                    <template x-if="itemType === 'membership'">
                        <template x-for="(text, val) in membershipOptions">
                            <option :value="val" x-text="text"></option>
                        </template>
                    </template>
                    <template x-if="itemType === 'coaching'">
                        <template x-for="(text, val) in coachingOptions">
                            <option :value="val" x-text="text"></option>
                        </template>
                    </template>
                </select>
            </div>

            {{-- Step 2: Template --}}
            <div>
                <label class="form-label">Template</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($templates as $key => $label)
                    <label class="cursor-pointer">
                        <input type="radio" name="template" value="{{ $key }}" class="sr-only peer" {{ $loop->first ? 'checked' : '' }}>
                        <div class="border-2 rounded-xl p-4 text-center transition-colors peer-checked:border-[#e05a3a] peer-checked:bg-[#e05a3a]/5 border-gray-200 hover:border-gray-300">
                            <p class="text-sm font-bold text-[#1a1a2e]">{{ $label }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Step 3: Content Sections --}}
            <div class="space-y-5">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Page Content</p>

                {{-- Hero --}}
                <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                    <h4 class="text-sm font-bold text-[#1a1a2e]">Hero Section</h4>
                    <input type="text" name="content[hero][title]" class="form-input" placeholder="Headline — e.g. Master Digital Marketing">
                    <input type="text" name="content[hero][subtitle]" class="form-input" placeholder="Subtitle — e.g. The complete guide to growing your business online">
                    <input type="text" name="content[hero][cta_text]" class="form-input" placeholder="Button text — e.g. Get Started Now" value="Get Started">
                </div>

                {{-- Features --}}
                <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-bold text-[#1a1a2e]">Features</h4>
                        <button type="button" @click="addFeature()" class="text-xs text-[#e05a3a] font-semibold">+ Add</button>
                    </div>
                    <template x-for="(f, i) in features" :key="i">
                        <div class="flex gap-2">
                            <input type="text" :name="'content[features]['+i+'][title]'" x-model="f.title" class="form-input flex-1" placeholder="Feature title">
                            <input type="text" :name="'content[features]['+i+'][description]'" x-model="f.description" class="form-input flex-1" placeholder="Short description">
                            <button type="button" @click="features.splice(i, 1)" class="text-red-400 hover:text-red-600 px-2">&times;</button>
                        </div>
                    </template>
                </div>

                {{-- Testimonials --}}
                <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-bold text-[#1a1a2e]">Testimonials</h4>
                        <button type="button" @click="addTestimonial()" class="text-xs text-[#e05a3a] font-semibold">+ Add</button>
                    </div>
                    <template x-for="(t, i) in testimonials" :key="i">
                        <div class="space-y-2 border-b border-gray-200 pb-3">
                            <div class="flex gap-2">
                                <input type="text" :name="'content[testimonials]['+i+'][name]'" x-model="t.name" class="form-input flex-1" placeholder="Name">
                                <input type="text" :name="'content[testimonials]['+i+'][role]'" x-model="t.role" class="form-input flex-1" placeholder="Title/Role">
                                <button type="button" @click="testimonials.splice(i, 1)" class="text-red-400 hover:text-red-600 px-2">&times;</button>
                            </div>
                            <textarea :name="'content[testimonials]['+i+'][quote]'" x-model="t.quote" class="form-input" rows="2" placeholder="What they said..."></textarea>
                        </div>
                    </template>
                </div>

                {{-- FAQ --}}
                <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-bold text-[#1a1a2e]">FAQ</h4>
                        <button type="button" @click="addFaq()" class="text-xs text-[#e05a3a] font-semibold">+ Add</button>
                    </div>
                    <template x-for="(q, i) in faqs" :key="i">
                        <div class="flex gap-2">
                            <input type="text" :name="'content[faq]['+i+'][question]'" x-model="q.question" class="form-input flex-1" placeholder="Question">
                            <input type="text" :name="'content[faq]['+i+'][answer]'" x-model="q.answer" class="form-input flex-1" placeholder="Answer">
                            <button type="button" @click="faqs.splice(i, 1)" class="text-red-400 hover:text-red-600 px-2">&times;</button>
                        </div>
                    </template>
                </div>

                {{-- CTA --}}
                <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                    <h4 class="text-sm font-bold text-[#1a1a2e]">Final Call to Action</h4>
                    <input type="text" name="content[cta][title]" class="form-input" placeholder="e.g. Ready to get started?">
                    <input type="text" name="content[cta][subtitle]" class="form-input" placeholder="e.g. Join hundreds of happy students today">
                    <input type="text" name="content[cta][button_text]" class="form-input" placeholder="Button text" value="Buy Now">
                </div>
            </div>

            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" class="rounded border-gray-300 text-[#e05a3a] focus:ring-[#e05a3a]">
                    <span class="text-sm font-medium text-[#1a1a2e]">Publish immediately</span>
                </label>
            </div>

            <div class="pt-4 border-t border-[#e8eaf0] flex justify-end">
                <button type="submit" class="btn-primary">Create Sales Page</button>
            </div>
        </form>
    </div>
</div>

<script>
function salesPageForm() {
    return {
        itemType: '',
        features: [{ title: '', description: '' }],
        testimonials: [],
        faqs: [],
        productOptions: {!! $products->pluck('title', 'id')->toJson() !!},
        cohortOptions: {!! $cohorts->pluck('title', 'id')->toJson() !!},
        membershipOptions: {!! $memberships->pluck('title', 'id')->toJson() !!},
        coachingOptions: {!! $coaching->pluck('title', 'id')->toJson() !!},
        addFeature() { this.features.push({ title: '', description: '' }); },
        addTestimonial() { this.testimonials.push({ name: '', role: '', quote: '' }); },
        addFaq() { this.faqs.push({ question: '', answer: '' }); },
    }
}
</script>
@endsection
