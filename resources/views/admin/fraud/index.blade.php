@extends('layouts.app')

@section('title', 'Fraud Detection')
@section('page_title', 'Fraud Detection')
@section('page_subtitle', 'Monitor suspicious affiliate activity')

@section('content')
{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon bg-red-50 text-red-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
        </div>
        <div>
            <p class="text-lg font-bold text-[#1a1a2e]">{{ number_format($stats['total_suspicious']) }}</p>
            <p class="text-[11px] text-gray-400">Total Suspicious</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-orange-50 text-orange-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-lg font-bold text-[#1a1a2e]">{{ number_format($stats['today_suspicious']) }}</p>
            <p class="text-[11px] text-gray-400">Today</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-purple-50 text-purple-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </div>
        <div>
            <p class="text-lg font-bold text-[#1a1a2e]">{{ number_format($stats['self_clicks']) }}</p>
            <p class="text-[11px] text-gray-400">Self-Clicks</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-amber-50 text-amber-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <div>
            <p class="text-lg font-bold text-[#1a1a2e]">{{ number_format($stats['rapid_clicks']) }}</p>
            <p class="text-[11px] text-gray-400">Rapid Clicks</p>
        </div>
    </div>
</div>

{{-- Suspicious Activity Log --}}
<div class="card overflow-hidden !p-0">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-sm font-bold text-[#1a1a2e]">Suspicious Activity Log</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f5f6fa] border-b border-[#e8eaf0]">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Affiliate</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Reason</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">IP Address</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Clicked By</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e8eaf0]">
                @forelse($suspiciousClicks as $click)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        @if($click->affiliateLink && $click->affiliateLink->user)
                        <a href="{{ route('admin.users.show', $click->affiliateLink->user) }}" class="text-sm font-semibold text-[#e05a3a] hover:underline">{{ $click->affiliateLink->user->name }}</a>
                        <p class="text-xs text-gray-400">{{ $click->affiliateLink->code }}</p>
                        @else
                        <span class="text-xs text-gray-400">Deleted</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                            {{ $click->suspicious_reason }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-mono text-gray-500">{{ $click->ip_address }}</td>
                    <td class="px-6 py-4">
                        @if($click->user)
                        <a href="{{ route('admin.users.show', $click->user) }}" class="text-sm text-[#e05a3a] hover:underline">{{ $click->user->name }}</a>
                        @else
                        <span class="text-xs text-gray-400">Guest</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $click->created_at->format('M d, Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">No suspicious activity detected.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($suspiciousClicks->hasPages())
    <div class="px-6 py-4 border-t border-[#e8eaf0]">
        {{ $suspiciousClicks->links() }}
    </div>
    @endif
</div>
@endsection
