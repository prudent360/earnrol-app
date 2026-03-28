@extends('layouts.app')

@section('title', 'Coaching Services')
@section('page_title', 'Coaching Services')
@section('page_subtitle', 'Manage coaching services from creators')

@section('content')
<div class="card overflow-hidden !p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f5f6fa] border-b border-[#e8eaf0]">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Service</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Creator</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Bookings</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e8eaf0]">
                @forelse($services as $service)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="text-sm font-semibold text-[#1a1a2e]">{{ $service->title }}</p>
                        <p class="text-xs text-gray-400">{{ $service->duration_minutes }} min &middot; {{ $service->platform_label }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $service->creator->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-700">{{ \App\Models\Setting::get('currency_symbol', '£') }}{{ number_format($service->price, 2) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $service->bookings_count }}</td>
                    <td class="px-6 py-4">
                        @if($service->approval_status === 'approved')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Approved</span>
                        @elseif($service->approval_status === 'pending')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Pending</span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Rejected</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right space-x-1">
                        @if($service->approval_status === 'pending')
                        <form action="{{ route('admin.coaching.approve', $service) }}" method="POST" class="inline">@csrf
                            <button type="submit" class="text-green-600 hover:text-green-800 text-sm font-medium">Approve</button>
                        </form>
                        <button type="button" onclick="document.getElementById('reject-{{ $service->id }}').classList.toggle('hidden')" class="text-red-600 hover:text-red-800 text-sm font-medium">Reject</button>
                        @endif
                    </td>
                </tr>
                @if($service->approval_status === 'pending')
                <tr id="reject-{{ $service->id }}" class="hidden">
                    <td colspan="6" class="px-6 py-4 bg-red-50">
                        <form action="{{ route('admin.coaching.reject', $service) }}" method="POST" class="flex items-center gap-3">@csrf
                            <input type="text" name="rejection_reason" class="form-input flex-1 text-sm" placeholder="Reason for rejection..." required>
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700">Confirm Reject</button>
                        </form>
                    </td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">No coaching services yet.</td>
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
