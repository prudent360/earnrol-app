@extends('layouts.app')

@section('title', 'Edit Sales Page')
@section('page_title', 'Edit Sales Page')
@section('page_subtitle', $salesPage->pageable->title ?? 'Sales Page')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('creator.sales-pages.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back
        </a>
        <a href="{{ route('creator.sales-pages.preview', $salesPage) }}" target="_blank" class="btn-outline text-sm py-2">Preview</a>
    </div>

    <div class="card" x-data="salesPageEdit()">
        <form action="{{ route('creator.sales-pages.update', $salesPage) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')

            <div>
                <label class="form-label">Template</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($templates as $key => $label)
                    <label class="cursor-pointer">
                        <input type="radio" name="template" value="{{ $key }}" class="sr-only peer" {{ $salesPage->template === $key ? 'checked' : '' }}>
                        <div class="border-2 rounded-xl p-4 text-center transition-colors peer-checked:border-[#e05a3a] peer-checked:bg-[#e05a3a]/5 border-gray-200 hover:border-gray-300">
                            <p class="text-sm font-bold text-[#1a1a2e]">{{ $label }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="space-y-5">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Page Content</p>

                @php $c = $salesPage->content ?? []; @endphp

                <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                    <h4 class="text-sm font-bold text-[#1a1a2e]">Hero Section</h4>
                    <input type="text" name="content[hero][title]" class="form-input" value="{{ $c['hero']['title'] ?? '' }}" placeholder="Headline">
                    <input type="text" name="content[hero][subtitle]" class="form-input" value="{{ $c['hero']['subtitle'] ?? '' }}" placeholder="Subtitle">
                    <input type="text" name="content[hero][cta_text]" class="form-input" value="{{ $c['hero']['cta_text'] ?? 'Get Started' }}" placeholder="Button text">
                </div>

                <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-bold text-[#1a1a2e]">Features</h4>
                        <button type="button" @click="addFeature()" class="text-xs text-[#e05a3a] font-semibold">+ Add</button>
                    </div>
                    <template x-for="(f, i) in features" :key="i">
                        <div class="flex gap-2">
                            <input type="text" :name="'content[features]['+i+'][title]'" x-model="f.title" class="form-input flex-1" placeholder="Title">
                            <input type="text" :name="'content[features]['+i+'][description]'" x-model="f.description" class="form-input flex-1" placeholder="Description">
                            <button type="button" @click="features.splice(i, 1)" class="text-red-400 hover:text-red-600 px-2">&times;</button>
                        </div>
                    </template>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-bold text-[#1a1a2e]">Testimonials</h4>
                        <button type="button" @click="addTestimonial()" class="text-xs text-[#e05a3a] font-semibold">+ Add</button>
                    </div>
                    <template x-for="(t, i) in testimonials" :key="i">
                        <div class="space-y-2 border-b border-gray-200 pb-3">
                            <div class="flex gap-2">
                                <input type="text" :name="'content[testimonials]['+i+'][name]'" x-model="t.name" class="form-input flex-1" placeholder="Name">
                                <input type="text" :name="'content[testimonials]['+i+'][role]'" x-model="t.role" class="form-input flex-1" placeholder="Role">
                                <button type="button" @click="testimonials.splice(i, 1)" class="text-red-400 hover:text-red-600 px-2">&times;</button>
                            </div>
                            <textarea :name="'content[testimonials]['+i+'][quote]'" x-model="t.quote" class="form-input" rows="2" placeholder="Quote"></textarea>
                        </div>
                    </template>
                </div>

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

                <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                    <h4 class="text-sm font-bold text-[#1a1a2e]">Final Call to Action</h4>
                    <input type="text" name="content[cta][title]" class="form-input" value="{{ $c['cta']['title'] ?? '' }}" placeholder="e.g. Ready to get started?">
                    <input type="text" name="content[cta][subtitle]" class="form-input" value="{{ $c['cta']['subtitle'] ?? '' }}" placeholder="Subtitle">
                    <input type="text" name="content[cta][button_text]" class="form-input" value="{{ $c['cta']['button_text'] ?? 'Buy Now' }}" placeholder="Button text">
                </div>
            </div>

            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" class="rounded border-gray-300 text-[#e05a3a] focus:ring-[#e05a3a]" {{ $salesPage->is_published ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-[#1a1a2e]">Published</span>
                </label>
                @if($salesPage->is_published)
                <p class="text-xs text-gray-400 mt-1">Live at: <a href="{{ route('sales-page.show', $salesPage->custom_slug) }}" target="_blank" class="text-[#e05a3a] hover:underline">{{ url('/s/' . $salesPage->custom_slug) }}</a></p>
                @endif
            </div>

            <div class="pt-4 border-t border-[#e8eaf0] flex justify-end">
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
function salesPageEdit() {
    return {
        features: {!! json_encode($c['features'] ?? [['title' => '', 'description' => '']]) !!},
        testimonials: {!! json_encode($c['testimonials'] ?? []) !!},
        faqs: {!! json_encode($c['faq'] ?? []) !!},
        addFeature() { this.features.push({ title: '', description: '' }); },
        addTestimonial() { this.testimonials.push({ name: '', role: '', quote: '' }); },
        addFaq() { this.faqs.push({ question: '', answer: '' }); },
    }
}
</script>
@endsection
