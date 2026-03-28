@extends('layouts.app')

@section('title', 'My Coaching')
@section('page_title', 'My Coaching Services')
@section('page_subtitle', 'Manage your 1-on-1 coaching offerings')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-xl font-bold text-[#1a1a2e]">Your Coaching Services</h3>
    <a href="{{ route('creator.coaching.create') }}" class="btn-primary text-sm py-2">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Service
    </a>
</div>

<div class="card overflow-hidden !p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f5f6fa] border-b border-[#e8eaf0]">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Service</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Approval</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Bookings</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e8eaf0]">
                @forelse($services as $service)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-teal-500 to-emerald-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-[#1a1a2e]">{{ $service->title }}</p>
                                <p class="text-xs text-gray-400">{{ $service->duration_minutes }} min &middot; {{ $service->platform_label }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($service->approval_status === 'approved')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Approved</span>
                        @elseif($service->approval_status === 'pending')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Pending</span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Rejected</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-700">
                        {{ \App\Models\Setting::get('currency_symbol', '£') }}{{ number_format($service->price, 2) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <a href="{{ route('creator.coaching.bookings', $service) }}" class="text-[#e05a3a] hover:underline font-medium">{{ $service->bookings_count }}</a>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('creator.coaching.slots.index', $service) }}" class="text-teal-600 hover:text-teal-800 text-sm font-medium">Slots</a>
                        <a href="{{ route('creator.coaching.edit', $service) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</a>
                        <form action="{{ route('creator.coaching.destroy', $service) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium" onclick="return confirm('Delete this coaching service?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                        No coaching services yet. <a href="{{ route('creator.coaching.create') }}" class="text-[#e05a3a] hover:underline">Create your first service</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($services->hasPages())
    <div class="px-6 py-4 border-t border-[#e8eaf0]">{{ $services->links() }}</div>
    @endif
</div>
@endsection
