@extends('layouts.app')

@section('title', 'Bookings — ' . $coaching->title)
@section('page_title', $coaching->title)
@section('page_subtitle', 'Manage bookings')

@section('content')
<div class="mb-6">
    <a href="{{ route('creator.coaching.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to My Coaching
    </a>
</div>

<div class="card overflow-hidden !p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f5f6fa] border-b border-[#e8eaf0]">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date & Time</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Meeting Link</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e8eaf0]">
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="text-sm font-semibold text-[#1a1a2e]">{{ $booking->customer->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-400">{{ $booking->customer->email ?? '' }}</p>
                        @if($booking->notes)
                        <p class="text-xs text-gray-500 mt-1 italic">"{{ Str::limit($booking->notes, 60) }}"</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $booking->slot->start_time->format('M d, Y') }}<br>
                        <span class="text-xs text-gray-400">{{ $booking->slot->start_time->format('g:i A') }} — {{ $booking->slot->end_time->format('g:i A') }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($booking->meeting_link)
                        <a href="{{ $booking->meeting_link }}" target="_blank" class="text-sm text-[#e05a3a] hover:underline truncate block max-w-[200px]">{{ $booking->meeting_link }}</a>
                        @else
                        <form action="{{ route('creator.coaching.bookings.meeting-link', $booking) }}" method="POST" class="flex gap-2">
                            @csrf @method('PUT')
                            <input type="url" name="meeting_link" class="form-input text-xs py-1.5 flex-1" placeholder="https://meet.google.com/..." required>
                            <button type="submit" class="text-xs bg-[#e05a3a] text-white px-3 py-1.5 rounded-lg hover:bg-[#c94e31]">Set</button>
                        </form>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $booking->status === 'confirmed' ? 'bg-blue-100 text-blue-700' : ($booking->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($booking->status === 'confirmed')
                        <form action="{{ route('creator.coaching.bookings.complete', $booking) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-800 text-sm font-medium">Mark Complete</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">No bookings yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bookings->hasPages())
    <div class="px-6 py-4 border-t border-[#e8eaf0]">{{ $bookings->links() }}</div>
    @endif
</div>
@endsection
