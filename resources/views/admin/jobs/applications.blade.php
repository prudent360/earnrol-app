@extends('layouts.admin')

@section('title', 'Applications for ' . $job->title)

@section('admin_content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-[#1a1a2e]">Applications</h2>
            <p class="text-gray-500 text-sm">Reviewing candidates for <span class="font-bold text-[#e05a3a]">{{ $job->title }}</span> at {{ $job->company }}</p>
        </div>
        <a href="{{ route('admin.jobs.index') }}" class="btn-secondary px-4 py-2 text-sm">
            Back to Jobs
        </a>
    </div>

    <div class="card p-0 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Candidate</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Applied Date</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($applications as $app)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-[#e05a3a] font-bold">
                                    {{ strtoupper(substr($app->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-[#1a1a2e]">{{ $app->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $app->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $app->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusClasses = [
                                    'pending'  => 'bg-yellow-50 text-yellow-700',
                                    'reviewed' => 'bg-blue-50 text-blue-700',
                                    'accepted' => 'bg-green-50 text-green-700',
                                    'rejected' => 'bg-red-50 text-red-700',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $statusClasses[$app->status] ?? 'bg-gray-50 text-gray-500' }}">
                                {{ $app->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.job-applications.show', $app) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#e05a3a] hover:underline">
                                View Profile
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="text-sm">No applications yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($applications->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $applications->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
