@extends('layouts.app')

@section('title', 'Projects')
@section('page_title', 'Projects')
@section('page_subtitle', 'Build real-world projects to grow your portfolio and skills')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background-color:#e05a3a20;"><svg class="w-6 h-6 text-[#e05a3a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg></div>
        <div><p class="text-2xl font-bold text-[#1a1a2e]">2</p><p class="text-sm text-[#6b7280]">Completed</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background-color:#f59e0b20;"><svg class="w-6 h-6 text-[#f59e0b]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
        <div><p class="text-2xl font-bold text-[#1a1a2e]">1</p><p class="text-sm text-[#6b7280]">In Progress</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background-color:#3b82f620;"><svg class="w-6 h-6 text-[#3b82f6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg></div>
        <div><p class="text-2xl font-bold text-[#1a1a2e]">24</p><p class="text-sm text-[#6b7280]">Points Earned</p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background-color:#22c55e20;"><svg class="w-6 h-6 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg></div>
        <div><p class="text-2xl font-bold text-[#1a1a2e]">1</p><p class="text-sm text-[#6b7280]">Certifications</p></div>
    </div>
</div>

{{-- Filter tabs --}}
<div class="flex gap-2 mb-6">
    <button class="px-4 py-2 rounded-lg text-sm font-medium bg-[#e05a3a] text-white">All Projects</button>
    <button class="px-4 py-2 rounded-lg text-sm font-medium bg-white border border-[#e8eaf0] text-[#6b7280] hover:border-[#e05a3a] hover:text-[#e05a3a] transition-colors">My Projects</button>
    <button class="px-4 py-2 rounded-lg text-sm font-medium bg-white border border-[#e8eaf0] text-[#6b7280] hover:border-[#e05a3a] hover:text-[#e05a3a] transition-colors">Available</button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @php
    $projects = [
        ['title' => 'Deploy a 3-Tier Web App on AWS', 'desc' => 'Design and deploy a scalable 3-tier architecture using EC2, RDS, S3, CloudFront, and Route53.', 'status' => 'completed', 'difficulty' => 'Intermediate', 'points' => 15, 'tags' => ['AWS', 'EC2', 'RDS', 'S3'], 'track' => 'Cloud Computing', 'color' => '#22c55e'],
        ['title' => 'CI/CD Pipeline with Jenkins & Docker', 'desc' => 'Build an end-to-end CI/CD pipeline with Jenkins, Docker, and GitHub Actions for automated deployments.', 'status' => 'in_progress', 'difficulty' => 'Intermediate', 'points' => 20, 'tags' => ['Jenkins', 'Docker', 'GitHub Actions'], 'track' => 'DevOps', 'color' => '#f59e0b'],
        ['title' => 'Kubernetes Cluster Setup & Management', 'desc' => 'Set up a production-grade Kubernetes cluster, configure deployments, services, and ingress controllers.', 'status' => 'available', 'difficulty' => 'Advanced', 'points' => 30, 'tags' => ['Kubernetes', 'K8s', 'Helm'], 'track' => 'DevOps', 'color' => '#3b82f6'],
        ['title' => 'Linux Hardening & Security Audit', 'desc' => 'Perform a comprehensive security audit and harden a Linux server following CIS benchmarks.', 'status' => 'available', 'difficulty' => 'Intermediate', 'points' => 18, 'tags' => ['Linux', 'Security', 'CIS'], 'track' => 'Cybersecurity', 'color' => '#8b5cf6'],
        ['title' => 'Terraform Multi-Cloud Infrastructure', 'desc' => 'Use Terraform to provision and manage infrastructure across AWS and GCP using Infrastructure as Code.', 'status' => 'available', 'difficulty' => 'Advanced', 'points' => 25, 'tags' => ['Terraform', 'AWS', 'GCP'], 'track' => 'DevOps', 'color' => '#e05a3a'],
        ['title' => 'Data Pipeline with Apache Kafka', 'desc' => 'Build a real-time data streaming pipeline using Apache Kafka, Zookeeper, and Python consumers.', 'status' => 'available', 'difficulty' => 'Advanced', 'points' => 28, 'tags' => ['Kafka', 'Python', 'Zookeeper'], 'track' => 'Data Engineering', 'color' => '#f59e0b'],
    ];
    $statusConfig = [
        'completed' => ['label' => 'Completed', 'bg' => 'bg-[#22c55e]/10', 'text' => 'text-[#22c55e]'],
        'in_progress' => ['label' => 'In Progress', 'bg' => 'bg-[#f59e0b]/10', 'text' => 'text-[#f59e0b]'],
        'available' => ['label' => 'Available', 'bg' => 'bg-[#3b82f6]/10', 'text' => 'text-[#3b82f6]'],
    ];
    @endphp

    @foreach($projects as $project)
    @php $sc = $statusConfig[$project['status']]; @endphp
    <div class="card hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
        <div class="flex items-start justify-between mb-3">
            <span class="badge {{ $sc['bg'] }} {{ $sc['text'] }}">{{ $sc['label'] }}</span>
            <div class="flex items-center gap-1 text-[#f59e0b] text-sm font-bold">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                {{ $project['points'] }} pts
            </div>
        </div>
        <div class="w-10 h-1 rounded-full mb-3" style="background-color: {{ $project['color'] }};"></div>
        <p class="text-xs text-[#6b7280] mb-1">{{ $project['track'] }} · {{ $project['difficulty'] }}</p>
        <h3 class="font-bold text-[#1a1a2e] mb-2 leading-snug">{{ $project['title'] }}</h3>
        <p class="text-sm text-[#6b7280] leading-relaxed mb-4">{{ $project['desc'] }}</p>
        <div class="flex flex-wrap gap-1 mb-4">
            @foreach($project['tags'] as $tag)
            <span class="tag">{{ $tag }}</span>
            @endforeach
        </div>
        @if($project['status'] === 'completed')
        <button class="w-full flex items-center justify-center gap-2 py-2.5 rounded-lg border-2 border-[#22c55e] text-[#22c55e] text-sm font-semibold">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            View Certificate
        </button>
        @elseif($project['status'] === 'in_progress')
        <button class="btn-primary w-full justify-center py-2.5 text-sm">Continue Project</button>
        @else
        <button class="btn-outline w-full justify-center py-2.5 text-sm">Start Project</button>
        @endif
    </div>
    @endforeach
</div>

@endsection
