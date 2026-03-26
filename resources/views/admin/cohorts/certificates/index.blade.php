@extends('layouts.app')

@section('title', 'Certificates — ' . $cohort->title)
@section('page_title', 'Certificates')
@section('page_subtitle', $cohort->title)

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <a href="{{ route('admin.cohorts.edit', $cohort) }}" class="text-sm text-gray-500 hover:text-[#e05a3a] flex items-center gap-1 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Cohort
    </a>

    @if($cohort->status === 'completed' && $cohort->certificate_enabled)
    @php $uncertified = $enrollments->whereNotIn('user_id', $certifiedUserIds)->count(); @endphp
    @if($uncertified > 0)
    <form method="POST" action="{{ route('admin.cohorts.certificates.issue', $cohort) }}">
        @csrf
        <button type="submit" class="btn-primary text-sm py-2" onclick="return confirm('Issue certificates to {{ $uncertified }} student(s)?')">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            Issue Certificates ({{ $uncertified }})
        </button>
    </form>
    @else
    <span class="text-sm text-green-600 font-medium">All students have certificates</span>
    @endif
    @elseif(!$cohort->certificate_enabled)
    <span class="text-sm text-gray-400">Certificates not enabled for this cohort</span>
    @else
    <span class="text-sm text-yellow-600">Cohort must be marked as "completed" to issue certificates</span>
    @endif
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon bg-blue-50 text-blue-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        </div>
        <div>
            <p class="text-lg font-bold text-[#1a1a2e]">{{ $enrollments->count() }}</p>
            <p class="text-[11px] text-gray-400">Enrolled</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-green-50 text-green-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
        </div>
        <div>
            <p class="text-lg font-bold text-[#1a1a2e]">{{ $certificates->count() }}</p>
            <p class="text-[11px] text-gray-400">Certified</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-yellow-50 text-yellow-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-lg font-bold text-[#1a1a2e]">{{ $enrollments->whereNotIn('user_id', $certifiedUserIds)->count() }}</p>
            <p class="text-[11px] text-gray-400">Pending</p>
        </div>
    </div>
</div>

{{-- Certificates Table --}}
<div class="card overflow-hidden !p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#f5f6fa] border-b border-[#e8eaf0]">
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Student</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Certificate No.</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Issued</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e8eaf0]">
                @foreach($enrollments as $enrollment)
                @php $cert = $certificates->firstWhere('user_id', $enrollment->user_id); @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#1a2535] flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr($enrollment->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-[#1a1a2e]">{{ $enrollment->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $enrollment->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($cert)
                        <span class="font-mono text-xs text-[#e05a3a] font-semibold">{{ $cert->certificate_number }}</span>
                        @else
                        <span class="text-xs text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $cert ? $cert->issued_at->format('M d, Y') : '—' }}
                    </td>
                    <td class="px-6 py-4">
                        @if($cert)
                        <span class="badge bg-green-100 text-green-700">Certified</span>
                        @else
                        <span class="badge bg-gray-100 text-gray-500">Pending</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
