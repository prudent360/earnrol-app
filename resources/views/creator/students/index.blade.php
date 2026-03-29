@extends('layouts.app')

@section('title', 'My Students')
@section('page_title', 'My Students')
@section('page_subtitle', 'People who purchased your products or joined your cohorts')

@section('content')
{{-- Filter Tabs --}}
<div class="flex flex-wrap gap-2 mb-6">
    @php
        $tabs = [
            'all' => 'All Students',
            'products' => 'Product Buyers',
            'cohorts' => 'Cohort Students',
            'memberships' => 'Members',
            'coaching' => 'Coaching Clients',
        ];
    @endphp
    @foreach($tabs as $key => $label)
    <a href="{{ route('creator.students.index', array_merge(request()->only('search'), ['filter' => $key])) }}"
       class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $filter === $key ? 'bg-[#e05a3a] text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
        {{ $label }}
        <span class="ml-1 {{ $filter === $key ? 'text-white/80' : 'text-gray-400' }}">({{ $counts[$key] }})</span>
    </a>
    @endforeach
</div>

{{-- Search --}}
<div class="mb-6">
    <form action="{{ route('creator.students.index') }}" method="GET" class="flex gap-3">
        <input type="hidden" name="filter" value="{{ $filter }}">
        <div class="relative flex-1 max-w-md">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or email..." class="form-input pl-10">
        </div>
        <button type="submit" class="btn-primary text-sm">Search</button>
        @if($search)
        <a href="{{ route('creator.students.index', ['filter' => $filter]) }}" class="btn-outline text-sm">Clear</a>
        @endif
    </form>
</div>

{{-- Students Table --}}
<div class="card overflow-hidden !p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f5f6fa] border-b border-[#e8eaf0]">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Student</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Products</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Cohorts</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Memberships</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Coaching</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e8eaf0]">
                @forelse($students as $student)
                <tr class="hover:bg-gray-50 transition-colors" x-data="{ expanded: false }">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#e05a3a]/10 flex items-center justify-center flex-shrink-0">
                                @if($student->avatar)
                                <img src="{{ Storage::url($student->avatar) }}" class="w-8 h-8 rounded-full object-cover" alt="">
                                @else
                                <span class="text-xs font-bold text-[#e05a3a]">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <p class="text-sm font-semibold text-[#1a1a2e]">{{ $student->name }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <a href="mailto:{{ $student->email }}" class="text-sm text-[#e05a3a] hover:underline">{{ $student->email }}</a>
                    </td>
                    <td class="px-6 py-4">
                        @if($student->purchased_products->count() > 0)
                        <div x-data="{ show: false }" class="relative">
                            <button @click="show = !show" type="button" class="inline-flex items-center gap-1 px-2 py-1 rounded bg-blue-50 text-blue-700 text-xs font-medium hover:bg-blue-100 transition-colors">
                                {{ $student->purchased_products->count() }} {{ Str::plural('product', $student->purchased_products->count()) }}
                                <svg class="w-3 h-3" :class="show && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="show" @click.away="show = false" x-transition class="absolute z-10 mt-1 w-56 bg-white border border-gray-200 rounded-lg shadow-lg p-2">
                                @foreach($student->purchased_products as $pp)
                                <p class="text-xs text-gray-600 py-1 px-2">{{ $pp->product->title ?? 'Deleted product' }}</p>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <span class="text-xs text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($student->enrolled_cohorts->count() > 0)
                        <div x-data="{ show: false }" class="relative">
                            <button @click="show = !show" type="button" class="inline-flex items-center gap-1 px-2 py-1 rounded bg-purple-50 text-purple-700 text-xs font-medium hover:bg-purple-100 transition-colors">
                                {{ $student->enrolled_cohorts->count() }} {{ Str::plural('cohort', $student->enrolled_cohorts->count()) }}
                                <svg class="w-3 h-3" :class="show && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="show" @click.away="show = false" x-transition class="absolute z-10 mt-1 w-56 bg-white border border-gray-200 rounded-lg shadow-lg p-2">
                                @foreach($student->enrolled_cohorts as $ec)
                                <p class="text-xs text-gray-600 py-1 px-2">{{ $ec->cohort->title ?? 'Deleted cohort' }}</p>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <span class="text-xs text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($student->membership_subs->count() > 0)
                        <div x-data="{ show: false }" class="relative">
                            <button @click="show = !show" type="button" class="inline-flex items-center gap-1 px-2 py-1 rounded bg-green-50 text-green-700 text-xs font-medium hover:bg-green-100 transition-colors">
                                {{ $student->membership_subs->count() }} {{ Str::plural('plan', $student->membership_subs->count()) }}
                                <svg class="w-3 h-3" :class="show && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="show" @click.away="show = false" x-transition class="absolute z-10 mt-1 w-56 bg-white border border-gray-200 rounded-lg shadow-lg p-2">
                                @foreach($student->membership_subs as $ms)
                                <p class="text-xs text-gray-600 py-1 px-2">{{ $ms->membershipPlan->title ?? 'Deleted plan' }}</p>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <span class="text-xs text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($student->coaching_bookings->count() > 0)
                        <div x-data="{ show: false }" class="relative">
                            <button @click="show = !show" type="button" class="inline-flex items-center gap-1 px-2 py-1 rounded bg-amber-50 text-amber-700 text-xs font-medium hover:bg-amber-100 transition-colors">
                                {{ $student->coaching_bookings->count() }} {{ Str::plural('session', $student->coaching_bookings->count()) }}
                                <svg class="w-3 h-3" :class="show && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="show" @click.away="show = false" x-transition class="absolute z-10 mt-1 w-56 bg-white border border-gray-200 rounded-lg shadow-lg p-2">
                                @foreach($student->coaching_bookings as $cb)
                                <p class="text-xs text-gray-600 py-1 px-2">{{ $cb->service->title ?? 'Deleted service' }}</p>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <span class="text-xs text-gray-300">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <p class="text-sm font-semibold text-[#1a1a2e]">No students yet</p>
                        <p class="text-xs text-gray-400 mt-1">Students will appear here once they purchase your products or join your cohorts.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($students->hasPages())
    <div class="px-6 py-4 border-t border-[#e8eaf0]">
        {{ $students->links() }}
    </div>
    @endif
</div>
@endsection
