@extends('layouts.app')

@section('title', 'Manage Cohorts')
@section('page_title', 'Manage Cohorts')
@section('page_subtitle', 'Create and manage training cohorts')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-xl font-bold text-[#1a1a2e]">All Cohorts</h3>
    <a href="{{ route('admin.cohorts.create') }}" class="btn-primary text-sm py-2">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        New Cohort
    </a>
</div>

<div class="card overflow-hidden !p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f5f6fa] border-b border-[#e8eaf0]">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Cohort</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Approval</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Students</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Start Date</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e8eaf0]">
                @forelse($cohorts as $cohort)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="text-sm font-semibold text-[#1a1a2e]">{{ $cohort->title }}</p>
                        <p class="text-xs text-gray-400 truncate max-w-xs">{{ Str::limit($cohort->description, 50) }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="badge {{ $cohort->status === 'active' ? 'bg-green-100 text-green-700' : ($cohort->status === 'upcoming' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500') }}">
                            {{ ucfirst($cohort->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($cohort->approval_status === 'approved')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Approved</span>
                        @elseif($cohort->approval_status === 'pending')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Pending</span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Rejected</span>
                        @endif
                        @if($cohort->creator)
                        <p class="text-[10px] text-gray-400 mt-1">by {{ $cohort->creator->name }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                        {{ \App\Models\Setting::get('currency_symbol', '£') }}{{ number_format($cohort->price, 2) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $cohort->enrollments_count }}{{ $cohort->max_students ? '/' . $cohort->max_students : '' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $cohort->start_date->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        @if($cohort->approval_status === 'pending')
                        <form action="{{ route('admin.cohorts.approve', $cohort) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-800 text-sm font-medium">Approve</button>
                        </form>
                        <div x-data="{ open: false }" class="inline relative">
                            <button @click="open = !open" class="text-orange-600 hover:text-orange-800 text-sm font-medium">Reject</button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-lg border border-gray-200 p-4 z-50">
                                <form action="{{ route('admin.cohorts.reject', $cohort) }}" method="POST">
                                    @csrf
                                    <textarea name="rejection_reason" rows="3" class="form-input text-sm w-full" placeholder="Reason for rejection..." required></textarea>
                                    <button type="submit" class="btn-primary text-xs py-1.5 mt-2 w-full justify-center">Confirm Reject</button>
                                </form>
                            </div>
                        </div>
                        @endif
                        <a href="{{ route('admin.cohorts.materials.index', $cohort) }}" class="text-green-600 hover:text-green-800 text-sm font-medium">Materials</a>
                        <a href="{{ route('admin.cohorts.edit', $cohort) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Edit</a>
                        <form action="{{ route('admin.cohorts.destroy', $cohort) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium" onclick="return confirm('Delete this cohort?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                        No cohorts created yet. <a href="{{ route('admin.cohorts.create') }}" class="text-[#e05a3a] hover:underline">Create your first cohort</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($cohorts->hasPages())
    <div class="px-6 py-4 border-t border-[#e8eaf0]">
        {{ $cohorts->links() }}
    </div>
    @endif
</div>
@endsection
