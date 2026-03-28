@extends('layouts.app')

@section('title', 'Slots — ' . $coaching->title)
@section('page_title', $coaching->title)
@section('page_subtitle', 'Manage available time slots')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('creator.coaching.index') }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to My Coaching
    </a>
    <a href="{{ route('creator.coaching.slots.create', $coaching) }}" class="btn-primary text-sm py-2">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Slot
    </a>
</div>

<div class="card overflow-hidden !p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f5f6fa] border-b border-[#e8eaf0]">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Time</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Duration</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e8eaf0]">
                @forelse($slots as $slot)
                <tr class="hover:bg-gray-50 transition-colors {{ $slot->start_time->isPast() ? 'opacity-50' : '' }}">
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $slot->start_time->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $slot->start_time->format('g:i A') }} — {{ $slot->end_time->format('g:i A') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $coaching->duration_minutes }} min</td>
                    <td class="px-6 py-4">
                        @if($slot->is_booked)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Booked</span>
                        @elseif($slot->start_time->isPast())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Expired</span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Available</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if(!$slot->is_booked)
                        <form action="{{ route('creator.coaching.slots.destroy', [$coaching, $slot]) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium" onclick="return confirm('Remove this slot?')">Remove</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                        No slots added yet. <a href="{{ route('creator.coaching.slots.create', $coaching) }}" class="text-[#e05a3a] hover:underline">Add your first slot</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
