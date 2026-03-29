@extends('layouts.app')

@section('title', 'Sales Pages')
@section('page_title', 'Sales Pages')
@section('page_subtitle', 'Create landing pages for your products')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div></div>
    <a href="{{ route('creator.sales-pages.create') }}" class="btn-primary text-sm">+ Create Sales Page</a>
</div>

<div class="card overflow-hidden !p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f5f6fa] border-b border-[#e8eaf0]">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Page</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">For</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Template</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e8eaf0]">
                @forelse($salesPages as $page)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="text-sm font-semibold text-[#1a1a2e]">{{ $page->pageable->title ?? 'Untitled' }}</p>
                        <p class="text-xs text-gray-400 font-mono">/s/{{ $page->custom_slug }}</p>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $typeLabel = match(class_basename($page->pageable_type)) {
                                'DigitalProduct' => 'Product',
                                'Cohort' => 'Cohort',
                                'MembershipPlan' => 'Membership',
                                'CoachingService' => 'Coaching',
                                default => 'Item',
                            };
                        @endphp
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ $typeLabel }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ \App\Models\SalesPage::TEMPLATES[$page->template] ?? $page->template }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $page->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $page->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('creator.sales-pages.edit', $page) }}" class="text-xs text-[#e05a3a] hover:underline">Edit</a>
                            <a href="{{ route('creator.sales-pages.preview', $page) }}" target="_blank" class="text-xs text-blue-600 hover:underline">Preview</a>
                            @if($page->is_published)
                            <a href="{{ route('sales-page.show', $page->custom_slug) }}" target="_blank" class="text-xs text-green-600 hover:underline">View</a>
                            @endif
                            <form method="POST" action="{{ route('creator.sales-pages.destroy', $page) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:underline" onclick="return confirm('Delete this sales page?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        <p class="text-sm font-semibold text-[#1a1a2e]">No sales pages yet</p>
                        <p class="text-xs text-gray-400 mt-1">Create customized landing pages to boost conversions.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($salesPages->hasPages())
    <div class="px-6 py-4 border-t border-[#e8eaf0]">{{ $salesPages->links() }}</div>
    @endif
</div>
@endsection
