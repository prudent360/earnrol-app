@extends('layouts.app')

@section('title', 'Mentorship')
@section('page_title', 'Mentorship')
@section('page_subtitle', 'Book 1-on-1 sessions with industry experts')

@section('content')

{{-- Session status banner --}}
<div class="bg-[#e05a3a]/5 border border-[#e05a3a]/20 rounded-2xl p-5 mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-4">
    <div class="w-12 h-12 rounded-xl bg-[#e05a3a]/10 flex items-center justify-center flex-shrink-0">
        <svg class="w-6 h-6 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    </div>
    <div class="flex-1">
        <p class="font-semibold text-[#1a1a2e]">Upcoming session with James Kofi</p>
        <p class="text-sm text-[#6b7280]">Tomorrow · 2:00 PM WAT · AWS Career Strategy · 45 minutes</p>
    </div>
    <div class="flex gap-3">
        <button class="btn-primary text-sm py-2.5">Join Session</button>
        <button class="text-sm text-[#6b7280] hover:text-[#1a1a2e] font-medium">Reschedule</button>
    </div>
</div>

{{-- Filter --}}
<div class="flex flex-wrap gap-2 mb-6">
    @foreach(['All Mentors', 'Cloud Computing', 'DevOps', 'Cybersecurity', 'Data Engineering', 'Career Coaching'] as $i => $f)
    <button class="px-4 py-2 rounded-full text-sm font-medium transition-all {{ $i === 0 ? 'bg-[#e05a3a] text-white' : 'bg-white border border-[#e8eaf0] text-[#6b7280] hover:border-[#e05a3a] hover:text-[#e05a3a]' }}">
        {{ $f }}
    </button>
    @endforeach
</div>

{{-- Mentor grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @php
    $mentors = [
        ['name' => 'James Kofi', 'role' => 'Senior Cloud Architect', 'company' => 'Amazon Web Services', 'avatar' => 'JK', 'rating' => 5.0, 'sessions' => 247, 'expertise' => ['AWS', 'Cloud Architecture', 'DevOps'], 'price' => 'Free (1/mo)', 'available' => true, 'bio' => '10+ years building cloud-native systems. AWS certified architect helping engineers land their dream cloud roles.', 'color' => '#e05a3a'],
        ['name' => 'Sarah Mensah', 'role' => 'Principal DevOps Engineer', 'company' => 'Google', 'avatar' => 'SM', 'rating' => 4.9, 'sessions' => 189, 'expertise' => ['Kubernetes', 'CI/CD', 'SRE'], 'price' => '$30/session', 'available' => true, 'bio' => 'SRE at Google for 7 years. Passionate about building reliable distributed systems and mentoring the next wave of DevOps engineers.', 'color' => '#3b82f6'],
        ['name' => 'Emmanuel Okafor', 'role' => 'Cybersecurity Lead', 'company' => 'Microsoft', 'avatar' => 'EO', 'rating' => 4.8, 'sessions' => 143, 'expertise' => ['Security', 'Pen Testing', 'SOC'], 'price' => '$25/session', 'available' => false, 'bio' => 'CISSP certified security professional. Former CISO with hands-on expertise in enterprise security architecture.', 'color' => '#22c55e'],
        ['name' => 'Amina Traoré', 'role' => 'Data Engineering Manager', 'company' => 'Spotify', 'avatar' => 'AT', 'rating' => 4.9, 'sessions' => 98, 'expertise' => ['Kafka', 'Spark', 'Python'], 'price' => '$35/session', 'available' => true, 'bio' => 'Building data infrastructure at scale. Ex-Airbnb data engineer who loves helping others navigate the data engineering landscape.', 'color' => '#8b5cf6'],
        ['name' => 'David Asante', 'role' => 'Cloud Solutions Architect', 'company' => 'Flutterwave', 'avatar' => 'DA', 'rating' => 4.7, 'sessions' => 211, 'expertise' => ['AWS', 'Terraform', 'FinOps'], 'price' => 'Free (1/mo)', 'available' => true, 'bio' => 'Fintech cloud architect focused on cost-optimized, secure cloud infrastructure for financial services companies.', 'color' => '#f59e0b'],
        ['name' => 'Ngozi Williams', 'role' => 'VP Engineering', 'company' => 'Andela', 'avatar' => 'NW', 'rating' => 5.0, 'sessions' => 76, 'expertise' => ['Leadership', 'Career Growth', 'Engineering'], 'price' => '$50/session', 'available' => true, 'bio' => 'Engineering leader who has hired 200+ tech professionals. Expert at career strategy, salary negotiation, and engineering leadership.', 'color' => '#e05a3a'],
    ];
    @endphp

    @foreach($mentors as $mentor)
    <div class="card hover:shadow-lg transition-shadow">
        <div class="flex items-start gap-4 mb-4">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white font-bold text-lg flex-shrink-0" style="background-color: {{ $mentor['color'] }};">
                {{ $mentor['avatar'] }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-[#1a1a2e]">{{ $mentor['name'] }}</h3>
                    <div class="flex items-center gap-1 text-sm">
                        <svg class="w-4 h-4 text-[#f59e0b]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span class="font-bold text-[#1a1a2e]">{{ $mentor['rating'] }}</span>
                    </div>
                </div>
                <p class="text-sm text-[#6b7280]">{{ $mentor['role'] }}</p>
                <p class="text-xs text-[#e05a3a] font-medium">@ {{ $mentor['company'] }}</p>
            </div>
        </div>

        <p class="text-sm text-[#6b7280] leading-relaxed mb-4">{{ $mentor['bio'] }}</p>

        <div class="flex flex-wrap gap-1 mb-4">
            @foreach($mentor['expertise'] as $skill)
            <span class="tag">{{ $skill }}</span>
            @endforeach
        </div>

        <div class="flex items-center justify-between pt-3 border-t border-[#e8eaf0]">
            <div>
                <p class="text-sm font-bold text-[#1a1a2e]">{{ $mentor['price'] }}</p>
                <p class="text-xs text-[#6b7280]">{{ $mentor['sessions'] }} sessions done</p>
            </div>
            <div class="flex items-center gap-2">
                @if($mentor['available'])
                <span class="flex items-center gap-1 text-xs text-[#22c55e] font-medium">
                    <span class="w-2 h-2 bg-[#22c55e] rounded-full"></span> Available
                </span>
                @else
                <span class="flex items-center gap-1 text-xs text-[#6b7280] font-medium">
                    <span class="w-2 h-2 bg-gray-300 rounded-full"></span> Booked
                </span>
                @endif
                <button class="btn-primary text-xs py-2 px-4 {{ !$mentor['available'] ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$mentor['available'] ? 'disabled' : '' }}>
                    Book Session
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection
