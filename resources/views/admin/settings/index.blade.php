@extends('layouts.app')

@section('title', 'Settings')
@section('page_title', 'Settings')
@section('page_subtitle', 'Configure your platform')

@section('content')
<div class="mb-8">
    {{-- Tab Navigation --}}
    <div class="flex items-center gap-1 border-b border-gray-200 overflow-x-auto pb-px">
        @php
        $tabs = [
            'general'   => ['label' => 'General',          'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            'payment'   => ['label' => 'Payment Gateways', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
            'smtp'      => ['label' => 'Email / SMTP',     'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
            'templates' => ['label' => 'Email Templates',  'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            'branding'  => ['label' => 'Branding',         'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ];
        @endphp

        @foreach($tabs as $key => $meta)
        <a href="{{ route('admin.settings.index', ['tab' => $key]) }}"
           class="flex items-center gap-2 px-5 py-4 text-sm font-medium transition-colors border-b-2 whitespace-nowrap
                  {{ $tab === $key ? 'border-[#e05a3a] text-[#e05a3a]' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $meta['icon'] }}"/>
            </svg>
            {{ $meta['label'] }}
        </a>
        @endforeach
    </div>
</div>

@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl px-5 py-4 flex items-center gap-3">
    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
    </svg>
    {{ session('success') }}
</div>
@endif

<form action="{{ route('admin.settings.update', ['tab' => $tab]) }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- =========================================================
         GENERAL TAB
    ========================================================= --}}
    @if($tab === 'general')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- General Settings --}}
        <div class="card space-y-5">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <h3 class="text-base font-bold text-[#1a1a2e]">General Settings</h3>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Site Name</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'EarnRol' }}" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                </div>
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Site URL</label>
                    <input type="url" name="site_url" value="{{ $settings['site_url'] ?? config('app.url') }}" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                </div>
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Contact Email</label>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? '' }}" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all" placeholder="support@earnrol.com">
                </div>
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Timezone</label>
                    <select name="timezone" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                        @foreach(\DateTimeZone::listIdentifiers() as $tz)
                        <option value="{{ $tz }}" {{ ($settings['timezone'] ?? 'UTC') === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center justify-between pt-1">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Maintenance Mode</p>
                        <p class="text-xs text-gray-400 mt-0.5">Show a maintenance page to visitors</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="maintenance_mode" value="0">
                        <input type="checkbox" name="maintenance_mode" value="1" class="sr-only peer" {{ ($settings['maintenance_mode'] ?? '0') === '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#e05a3a] after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                    </label>
                </div>
            </div>
        </div>

        {{-- Referral Program --}}
        <div class="card space-y-5">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                </svg>
                <h3 class="text-base font-bold text-[#1a1a2e]">Referral Program</h3>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between pt-1">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Enable Referrals</p>
                        <p class="text-xs text-gray-400 mt-0.5">Allow users to earn commission by referring others</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="referral_enabled" value="0">
                        <input type="checkbox" name="referral_enabled" value="1" class="sr-only peer" {{ ($settings['referral_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#e05a3a] after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                    </label>
                </div>
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Referral Commission (%)</label>
                    <input type="number" name="referral_commission" value="{{ $settings['referral_commission'] ?? '10' }}" min="0" max="100" step="0.1" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all" placeholder="10">
                    <p class="text-[11px] text-gray-400 mt-1.5">Percentage awarded to the referrer when their friend makes a first purchase</p>
                </div>
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Min Withdrawal (₦)</label>
                    <input type="number" name="referral_min_withdrawal" value="{{ $settings['referral_min_withdrawal'] ?? '1000' }}" min="0" step="100" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all" placeholder="1000">
                    <p class="text-[11px] text-gray-400 mt-1.5">Minimum balance required before a referral credit withdrawal can be requested</p>
                </div>
            </div>
        </div>

        {{-- Announcement --}}
        <div class="card space-y-5">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                <h3 class="text-base font-bold text-[#1a1a2e]">Announcement</h3>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between pt-1">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Enable Announcement</p>
                        <p class="text-xs text-gray-400 mt-0.5">Show an announcement banner on the user dashboard</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="announcement_enabled" value="0">
                        <input type="checkbox" name="announcement_enabled" value="1" class="sr-only peer" {{ ($settings['announcement_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#e05a3a] after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                    </label>
                </div>
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Announcement Message</label>
                    <textarea name="announcement_message" rows="4" maxlength="1000"
                              class="form-input bg-gray-50 border-transparent focus:bg-white transition-all resize-none"
                              placeholder="Type your announcement here..."
                              oninput="document.getElementById('ann_count').textContent = this.value.length">{{ $settings['announcement_message'] ?? '' }}</textarea>
                    <p class="text-[11px] text-gray-400 mt-1.5">
                        <span id="ann_count">{{ strlen($settings['announcement_message'] ?? '') }}</span>/1000 characters
                    </p>
                </div>
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Auto-Dismiss Timer (seconds)</label>
                    <input type="number" name="announcement_timer" value="{{ $settings['announcement_timer'] ?? '0' }}" min="0" step="1" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all" placeholder="0">
                    <p class="text-[11px] text-gray-400 mt-1.5">Set to 0 to keep the banner visible until the user closes it manually</p>
                </div>
            </div>
        </div>

        {{-- VAT Settings --}}
        <div class="card space-y-5">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-base font-bold text-[#1a1a2e]">VAT / Tax Settings</h3>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between pt-1">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Enable VAT</p>
                        <p class="text-xs text-gray-400 mt-0.5">When enabled, VAT will be added to the price at checkout</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="vat_enabled" value="0">
                        <input type="checkbox" name="vat_enabled" value="1" class="sr-only peer" {{ ($settings['vat_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#e05a3a] after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                    </label>
                </div>
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">VAT Percentage (%)</label>
                    <input type="number" name="vat_percentage" value="{{ $settings['vat_percentage'] ?? '7.5' }}" min="0" max="100" step="0.1" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all" placeholder="7.5">
                    <p class="text-[11px] text-gray-400 mt-1.5">e.g. enter 7.5 for 7.5% VAT</p>
                </div>
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Tax Label</label>
                    <input type="text" name="vat_label" value="{{ $settings['vat_label'] ?? 'VAT' }}" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all" placeholder="VAT">
                    <p class="text-[11px] text-gray-400 mt-1.5">Label shown at checkout — e.g. VAT, GST, Sales Tax</p>
                </div>
            </div>
        </div>

    </div>


    {{-- =========================================================
         PAYMENT GATEWAYS TAB
    ========================================================= --}}
    @elseif($tab === 'payment')

    {{-- Currency Settings --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <div class="card space-y-5 lg:col-span-1">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-base font-bold text-[#1a1a2e]">Currency</h3>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Default Currency</label>
                    <select name="currency" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                        @foreach(['USD' => 'US Dollar (USD)', 'EUR' => 'Euro (EUR)', 'GBP' => 'British Pound (GBP)', 'NGN' => 'Nigerian Naira (NGN)', 'GHS' => 'Ghanaian Cedi (GHS)', 'KES' => 'Kenyan Shilling (KES)', 'ZAR' => 'South African Rand (ZAR)', 'CAD' => 'Canadian Dollar (CAD)', 'AUD' => 'Australian Dollar (AUD)'] as $code => $name)
                        <option value="{{ $code }}" {{ ($settings['currency'] ?? 'USD') === $code ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Currency Symbol</label>
                    <input type="text" name="currency_symbol" value="{{ $settings['currency_symbol'] ?? '$' }}" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all" placeholder="$">
                </div>
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Symbol Position</label>
                    <select name="currency_position" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                        <option value="before" {{ ($settings['currency_position'] ?? 'before') === 'before' ? 'selected' : '' }}>Before amount ($50)</option>
                        <option value="after" {{ ($settings['currency_position'] ?? 'before') === 'after' ? 'selected' : '' }}>After amount (50$)</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Gateway Status --}}
        <div class="card lg:col-span-2 space-y-4">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-base font-bold text-[#1a1a2e]">Gateway Status</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex items-center justify-between p-4 rounded-xl border {{ ($settings['stripe_enabled'] ?? '0') === '1' ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-7 rounded flex items-center justify-center text-white text-[9px] font-black" style="background:#635BFF">STRIPE</div>
                        <span class="text-sm font-medium text-gray-700">Stripe</span>
                    </div>
                    <span class="text-xs font-bold {{ ($settings['stripe_enabled'] ?? '0') === '1' ? 'text-green-600' : 'text-gray-400' }}">
                        {{ ($settings['stripe_enabled'] ?? '0') === '1' ? 'Active' : 'Off' }}
                    </span>
                </div>
                <div class="flex items-center justify-between p-4 rounded-xl border {{ ($settings['paypal_enabled'] ?? '0') === '1' ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-7 rounded flex items-center justify-center text-white text-[9px] font-black" style="background:#003087">PAYPAL</div>
                        <span class="text-sm font-medium text-gray-700">PayPal</span>
                    </div>
                    <span class="text-xs font-bold {{ ($settings['paypal_enabled'] ?? '0') === '1' ? 'text-green-600' : 'text-gray-400' }}">
                        {{ ($settings['paypal_enabled'] ?? '0') === '1' ? 'Active' : 'Off' }}
                    </span>
                </div>
            </div>
            <p class="text-xs text-gray-400">When all gateways are disabled, students can enrol in cohorts for free.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- Stripe --}}
        <div class="card space-y-5">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-8 bg-[#635BFF] rounded flex items-center justify-center text-white text-[10px] font-black tracking-wider">STRIPE</div>
                    <h3 class="text-base font-bold text-[#1a1a2e]">Stripe</h3>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="stripe_enabled" value="0">
                    <input type="checkbox" name="stripe_enabled" value="1" class="sr-only peer" {{ ($settings['stripe_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#635BFF] after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                </label>
            </div>

            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                <span class="text-xs font-medium text-yellow-800">Mode</span>
                <div class="flex rounded-lg overflow-hidden border border-yellow-200">
                    <label class="cursor-pointer">
                        <input type="radio" name="stripe_test_mode" value="1" class="sr-only peer" {{ ($settings['stripe_test_mode'] ?? '1') === '1' ? 'checked' : '' }}>
                        <span class="block px-3 py-1 text-xs font-medium peer-checked:bg-yellow-500 peer-checked:text-white text-yellow-700 transition-colors">Test</span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="stripe_test_mode" value="0" class="sr-only peer" {{ ($settings['stripe_test_mode'] ?? '1') === '0' ? 'checked' : '' }}>
                        <span class="block px-3 py-1 text-xs font-medium peer-checked:bg-green-500 peer-checked:text-white text-yellow-700 transition-colors">Live</span>
                    </label>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Publishable Key</label>
                    <input type="text" name="stripe_public_key" value="{{ $settings['stripe_public_key'] ?? '' }}" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all font-mono text-sm" placeholder="pk_test_...">
                </div>
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Secret Key</label>
                    <div class="relative">
                        <input type="password" name="stripe_secret_key" id="stripe_secret_key" value="{{ $settings['stripe_secret_key'] ?? '' }}" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all font-mono text-sm pr-12" placeholder="sk_test_...">
                        <button type="button" onclick="togglePassword('stripe_secret_key')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Webhook Secret</label>
                    <div class="relative">
                        <input type="password" name="stripe_webhook_secret" id="stripe_webhook_secret" value="{{ $settings['stripe_webhook_secret'] ?? '' }}" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all font-mono text-sm pr-12" placeholder="whsec_...">
                        <button type="button" onclick="togglePassword('stripe_webhook_secret')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">Get this from your Stripe dashboard → Webhooks</p>
                </div>
            </div>
        </div>

        {{-- PayPal --}}
        <div class="card space-y-5">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-8 bg-[#003087] rounded flex items-center justify-center text-white text-[10px] font-black tracking-wider">PAYPAL</div>
                    <h3 class="text-base font-bold text-[#1a1a2e]">PayPal</h3>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="paypal_enabled" value="0">
                    <input type="checkbox" name="paypal_enabled" value="1" class="sr-only peer" {{ ($settings['paypal_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#003087] after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                </label>
            </div>

            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                <span class="text-xs font-medium text-blue-800">Mode</span>
                <div class="flex rounded-lg overflow-hidden border border-blue-200">
                    <label class="cursor-pointer">
                        <input type="radio" name="paypal_sandbox" value="1" class="sr-only peer" {{ ($settings['paypal_sandbox'] ?? '1') === '1' ? 'checked' : '' }}>
                        <span class="block px-3 py-1 text-xs font-medium peer-checked:bg-yellow-500 peer-checked:text-white text-blue-700 transition-colors">Sandbox</span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="paypal_sandbox" value="0" class="sr-only peer" {{ ($settings['paypal_sandbox'] ?? '1') === '0' ? 'checked' : '' }}>
                        <span class="block px-3 py-1 text-xs font-medium peer-checked:bg-green-500 peer-checked:text-white text-blue-700 transition-colors">Live</span>
                    </label>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Client ID</label>
                    <input type="text" name="paypal_client_id" value="{{ $settings['paypal_client_id'] ?? '' }}" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all font-mono text-sm" placeholder="AX...">
                </div>
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Client Secret</label>
                    <div class="relative">
                        <input type="password" name="paypal_client_secret" id="paypal_client_secret" value="{{ $settings['paypal_client_secret'] ?? '' }}" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all font-mono text-sm pr-12" placeholder="EH...">
                        <button type="button" onclick="togglePassword('paypal_client_secret')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>
                <div class="bg-blue-50 rounded-lg p-3">
                    <p class="text-[11px] text-blue-700">Get your credentials from <span class="font-semibold">developer.paypal.com</span> → My Apps & Credentials</p>
                </div>
            </div>
        </div>

    </div>


    {{-- =========================================================
         EMAIL / SMTP TAB
    ========================================================= --}}
    @elseif($tab === 'smtp')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- SMTP Configuration --}}
        <div class="card space-y-6">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <h3 class="text-lg font-bold text-[#1a1a2e]">SMTP Configuration</h3>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Mail Driver</label>
                    <select name="mail_driver" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                        <option value="smtp"     {{ $settings['mail_driver'] === 'smtp'     ? 'selected' : '' }}>SMTP</option>
                        <option value="mailgun"  {{ $settings['mail_driver'] === 'mailgun'  ? 'selected' : '' }}>Mailgun</option>
                        <option value="postmark" {{ $settings['mail_driver'] === 'postmark' ? 'selected' : '' }}>Postmark</option>
                    </select>
                </div>
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">SMTP Host</label>
                    <input type="text" name="mail_host" value="{{ $settings['mail_host'] }}" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all" placeholder="e.g. smtp.hostinger.com">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Port</label>
                        <input type="text" name="mail_port" value="{{ $settings['mail_port'] }}" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all" placeholder="587">
                    </div>
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Encryption</label>
                        <select name="mail_encryption" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                            <option value="tls"  {{ $settings['mail_encryption'] === 'tls'  ? 'selected' : '' }}>TLS</option>
                            <option value="ssl"  {{ $settings['mail_encryption'] === 'ssl'  ? 'selected' : '' }}>SSL</option>
                            <option value="none" {{ $settings['mail_encryption'] === 'none' ? 'selected' : '' }}>None</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Username</label>
                    <input type="text" name="mail_username" value="{{ $settings['mail_username'] }}" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all" placeholder="hello@earnrol.com">
                </div>
                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Password</label>
                    <div class="relative">
                        <input type="password" name="mail_password" id="mail_password" value="{{ $settings['mail_password'] }}" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all pr-12" placeholder="••••••••••••">
                        <button type="button" onclick="togglePassword('mail_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2">For Gmail, use an App Password</p>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            {{-- Sender Information --}}
            <div class="card space-y-6">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <h3 class="text-lg font-bold text-[#1a1a2e]">Sender Information</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">From Name</label>
                        <input type="text" name="mail_from_name" value="{{ $settings['mail_from_name'] }}" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                        <p class="text-[10px] text-gray-400 mt-2">Name that appears in emails</p>
                    </div>
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">From Email</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <input type="email" name="mail_from_address" value="{{ $settings['mail_from_address'] }}" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all pl-10">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-100 rounded-xl p-5">
                <h4 class="text-sm font-bold text-blue-900 mb-3">Common SMTP Settings</h4>
                <ul class="space-y-1.5 text-xs text-blue-800">
                    <li><span class="font-bold">Gmail:</span> smtp.gmail.com · Port 587 · TLS · App Password</li>
                    <li><span class="font-bold">Mailtrap:</span> smtp.mailtrap.io · Port 587 · TLS</li>
                    <li><span class="font-bold">SendGrid:</span> smtp.sendgrid.net · Port 587 · TLS</li>
                    <li><span class="font-bold">Mailgun:</span> smtp.mailgun.org · Port 587 · TLS</li>
                    <li><span class="font-bold">Hostinger:</span> smtp.hostinger.com · Port 587 · TLS</li>
                </ul>
            </div>

            <div class="bg-gray-50 rounded-xl p-5">
                <h4 class="text-sm font-bold text-gray-900 mb-3">Send Test Email</h4>
                <div class="flex gap-2">
                    <input type="email" id="test_email_address" placeholder="test@example.com" class="form-input bg-white flex-1">
                    <button type="button" id="send_test_btn" onclick="sendTestEmail()" class="btn-primary text-xs py-2 px-4 whitespace-nowrap bg-gray-200 !text-gray-700 border-none hover:bg-gray-300 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <span>Send Test</span>
                        <svg class="animate-spin h-4 w-4 hidden" id="test_spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                </div>
                <p class="text-[10px] text-gray-400 mt-2">Save settings first, then send a test email to verify configuration</p>
                <div id="test_result" class="mt-3 text-xs hidden"></div>
            </div>
        </div>
    </div>


    {{-- =========================================================
         EMAIL TEMPLATES TAB
    ========================================================= --}}
    @elseif($tab === 'templates')
    @php
    $templates = [
        'welcome'    => ['label' => 'Welcome Email',       'desc' => 'Sent when a new user registers', 'vars' => ['{{name}}', '{{login_url}}', '{{app_name}}']],
        'reset'      => ['label' => 'Password Reset',      'desc' => 'Sent when a user requests a password reset', 'vars' => ['{{name}}', '{{reset_url}}', '{{app_name}}']],
        'enroll'     => ['label' => 'Cohort Enrollment',   'desc' => 'Sent after successful cohort enrollment', 'vars' => ['{{name}}', '{{cohort_name}}', '{{dashboard_url}}', '{{app_name}}']],
    ];
    $activeTemplate = request('template', 'welcome');
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        {{-- Template List --}}
        <div class="card p-0 overflow-hidden h-fit">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-bold text-[#1a1a2e]">Templates</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ count($templates) }} templates</p>
            </div>
            <nav class="divide-y divide-gray-50">
                @foreach($templates as $key => $tpl)
                <a href="{{ route('admin.settings.index', ['tab' => 'templates', 'template' => $key]) }}"
                   class="flex items-start gap-3 px-5 py-4 transition-colors {{ $activeTemplate === $key ? 'bg-[#e05a3a]/5 border-l-4 border-[#e05a3a]' : 'hover:bg-gray-50 border-l-4 border-transparent' }}">
                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0 {{ $activeTemplate === $key ? 'text-[#e05a3a]' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium {{ $activeTemplate === $key ? 'text-[#e05a3a]' : 'text-gray-700' }}">{{ $tpl['label'] }}</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">{{ $tpl['desc'] }}</p>
                    </div>
                </a>
                @endforeach
            </nav>
        </div>

        {{-- Template Editor --}}
        <div class="lg:col-span-3 space-y-6">
            @if(isset($templates[$activeTemplate]))
            @php $tpl = $templates[$activeTemplate]; @endphp
            <input type="hidden" name="active_template" value="{{ $activeTemplate }}">

            <div class="card space-y-5">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                    <div>
                        <h3 class="text-base font-bold text-[#1a1a2e]">{{ $tpl['label'] }}</h3>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $tpl['desc'] }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs bg-blue-100 text-blue-700 font-medium px-3 py-1 rounded-full">Editing</span>
                        <button type="button" onclick="testCurrentTemplate('{{ $activeTemplate }}')" class="btn-primary !bg-gray-100 !text-gray-700 !border-none text-xs py-1 px-3 hover:!bg-gray-200 transition-all flex items-center gap-2">
                             <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                             Test this Template
                        </button>
                    </div>
                </div>

                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Subject Line</label>
                    <input type="text" name="tpl_{{ $activeTemplate }}_subject"
                           value="{{ $settings['tpl_' . $activeTemplate . '_subject'] ?? '' }}"
                           class="form-input bg-gray-50 border-transparent focus:bg-white transition-all"
                           placeholder="Email subject...">
                </div>

                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Body</label>
                    <textarea name="tpl_{{ $activeTemplate }}_body" rows="14"
                              class="form-input bg-gray-50 border-transparent focus:bg-white transition-all font-mono text-sm leading-relaxed resize-y"
                              placeholder="Email body...">{{ $settings['tpl_' . $activeTemplate . '_body'] ?? '' }}</textarea>
                    <p class="text-[10px] text-gray-400 mt-2">Plain text format. Use the variables below to personalise each email.</p>
                </div>
            </div>

            {{-- Variables Reference --}}
            <div class="bg-gray-50 rounded-xl p-5">
                <h4 class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-3">Available Variables</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach($tpl['vars'] as $var)
                    <button type="button" onclick="insertVar('{{ $var }}')"
                            class="font-mono text-xs bg-white border border-gray-200 text-[#e05a3a] px-3 py-1.5 rounded-lg hover:bg-[#e05a3a] hover:text-white hover:border-[#e05a3a] transition-colors cursor-pointer">
                        {{ $var }}
                    </button>
                    @endforeach
                </div>
                <p class="text-[10px] text-gray-400 mt-3">Click a variable to copy it, or type it directly into the body above.</p>
            </div>
            @endif
        </div>
    </div>


    {{-- =========================================================
         BRANDING TAB
    ========================================================= --}}
    @elseif($tab === 'branding')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- Logo Uploads --}}
        <div class="card space-y-6">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-lg font-bold text-[#1a1a2e]">Logo &amp; Favicon</h3>
            </div>

            {{-- Main Logo --}}
            <div>
                <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Main Logo <span class="normal-case text-gray-400 font-normal">(light background)</span></label>
                <div class="mt-2 border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-[#e05a3a] transition-colors group cursor-pointer relative" onclick="document.getElementById('logo_input').click()">
                    @if(!empty($settings['logo_path']))
                    <div id="logo_preview_wrap" class="mb-3">
                        <img src="{{ Storage::url($settings['logo_path']) }}" alt="Logo" class="h-16 mx-auto object-contain">
                    </div>
                    @else
                    <div id="logo_preview_wrap" class="mb-3 hidden">
                        <img id="logo_preview_img" src="" alt="Logo preview" class="h-16 mx-auto object-contain">
                    </div>
                    @endif
                    <svg class="w-8 h-8 mx-auto text-gray-300 group-hover:text-[#e05a3a] transition-colors mb-2 {{ !empty($settings['logo_path']) ? 'hidden' : '' }}" id="logo_upload_icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="text-sm text-gray-400 group-hover:text-[#e05a3a] transition-colors">
                        {{ !empty($settings['logo_path']) ? 'Click to replace logo' : 'Click to upload logo' }}
                    </p>
                    <p class="text-[11px] text-gray-300 mt-1">PNG, SVG, or WebP · Max 2MB · Recommended: 300×80px</p>
                    <input type="file" id="logo_input" name="logo" accept="image/png,image/svg+xml,image/webp,image/jpeg" class="sr-only" onchange="previewImage(this, 'logo_preview_img', 'logo_preview_wrap', 'logo_upload_icon')">
                </div>
            </div>

            {{-- Dark Logo --}}
            <div>
                <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Dark Logo <span class="normal-case text-gray-400 font-normal">(dark background / sidebar)</span></label>
                <div class="mt-2 border-2 border-dashed border-gray-200 rounded-xl p-5 text-center hover:border-[#e05a3a] transition-colors group cursor-pointer bg-[#1a2535] relative" onclick="document.getElementById('logo_dark_input').click()">
                    @if(!empty($settings['logo_dark_path']))
                    <div id="logo_dark_preview_wrap" class="mb-2">
                        <img src="{{ Storage::url($settings['logo_dark_path']) }}" alt="Dark Logo" class="h-12 mx-auto object-contain">
                    </div>
                    @else
                    <div id="logo_dark_preview_wrap" class="mb-2 hidden">
                        <img id="logo_dark_preview_img" src="" alt="Dark logo preview" class="h-12 mx-auto object-contain">
                    </div>
                    @endif
                    <svg class="w-7 h-7 mx-auto text-white/30 group-hover:text-white/60 transition-colors mb-1 {{ !empty($settings['logo_dark_path']) ? 'hidden' : '' }}" id="logo_dark_upload_icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="text-sm text-white/50 group-hover:text-white/80 transition-colors">
                        {{ !empty($settings['logo_dark_path']) ? 'Click to replace dark logo' : 'Upload dark / white version' }}
                    </p>
                    <input type="file" id="logo_dark_input" name="logo_dark" accept="image/png,image/svg+xml,image/webp,image/jpeg" class="sr-only" onchange="previewImage(this, 'logo_dark_preview_img', 'logo_dark_preview_wrap', 'logo_dark_upload_icon')">
                </div>
            </div>

            {{-- Favicon --}}
            <div>
                <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Favicon <span class="normal-case text-gray-400 font-normal">(browser tab icon)</span></label>
                <div class="mt-2 flex items-center gap-5 border border-gray-200 rounded-xl p-4">
                    <div class="w-16 h-16 bg-gray-50 rounded-xl border border-gray-200 flex items-center justify-center flex-shrink-0 overflow-hidden">
                        @if(!empty($settings['favicon_path']))
                        <img id="favicon_preview_img" src="{{ Storage::url($settings['favicon_path']) }}" alt="Favicon" class="w-10 h-10 object-contain">
                        @else
                        <img id="favicon_preview_img" src="" alt="" class="w-10 h-10 object-contain hidden">
                        <svg class="w-8 h-8 text-gray-300" id="favicon_placeholder" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-700">Favicon Image</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">ICO, PNG, or SVG · Recommended: 32×32px or 64×64px</p>
                        <label class="mt-3 inline-flex items-center gap-2 text-xs font-medium text-[#e05a3a] cursor-pointer hover:underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            Choose file
                            <input type="file" name="favicon" accept="image/x-icon,image/png,image/svg+xml" class="sr-only" onchange="previewFavicon(this)">
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- Brand Identity --}}
        <div class="space-y-6">
            <div class="card space-y-5">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <h3 class="text-lg font-bold text-[#1a1a2e]">Brand Identity</h3>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">App Name</label>
                        <input type="text" name="app_name" value="{{ $settings['app_name'] ?? 'EarnRol' }}" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Tagline</label>
                        <input type="text" name="app_tagline" value="{{ $settings['app_tagline'] ?? 'Learn. Build. Earn.' }}" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all" placeholder="Your platform tagline">
                    </div>
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Footer Copyright Text</label>
                        <input type="text" name="footer_text" value="{{ $settings['footer_text'] ?? '© ' . date('Y') . ' EarnRol. All rights reserved.' }}" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all">
                    </div>
                </div>
            </div>

            {{-- Color Palette --}}
            <div class="card space-y-5">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-4">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                    </svg>
                    <h3 class="text-lg font-bold text-[#1a1a2e]">Brand Colors</h3>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Primary / Accent Color</label>
                        <div class="flex items-center gap-3 mt-1">
                            <input type="color" name="brand_color" value="{{ $settings['brand_color'] ?? '#e05a3a' }}" id="brand_color_input"
                                   class="w-12 h-12 rounded-xl border border-gray-200 cursor-pointer bg-transparent p-1"
                                   oninput="document.getElementById('brand_color_hex').value = this.value">
                            <div class="flex-1">
                                <input type="text" id="brand_color_hex" value="{{ $settings['brand_color'] ?? '#e05a3a' }}"
                                       class="form-input bg-gray-50 border-transparent focus:bg-white transition-all font-mono uppercase text-sm"
                                       oninput="document.getElementById('brand_color_input').value = this.value"
                                       placeholder="#e05a3a">
                            </div>
                            <div class="w-10 h-10 rounded-xl flex-shrink-0" id="brand_color_swatch" style="background: {{ $settings['brand_color'] ?? '#e05a3a' }}"></div>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2">Used for buttons, links, and active states across the platform</p>
                    </div>

                    <div>
                        <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Sidebar / Dark Color</label>
                        <div class="flex items-center gap-3 mt-1">
                            <input type="color" name="accent_color" value="{{ $settings['accent_color'] ?? '#1a2535' }}" id="accent_color_input"
                                   class="w-12 h-12 rounded-xl border border-gray-200 cursor-pointer bg-transparent p-1"
                                   oninput="document.getElementById('accent_color_hex').value = this.value">
                            <div class="flex-1">
                                <input type="text" id="accent_color_hex" value="{{ $settings['accent_color'] ?? '#1a2535' }}"
                                       class="form-input bg-gray-50 border-transparent focus:bg-white transition-all font-mono uppercase text-sm"
                                       oninput="document.getElementById('accent_color_input').value = this.value"
                                       placeholder="#1a2535">
                            </div>
                            <div class="w-10 h-10 rounded-xl flex-shrink-0" id="accent_color_swatch" style="background: {{ $settings['accent_color'] ?? '#1a2535' }}"></div>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2">Used for the sidebar, navbar, and dark UI elements</p>
                    </div>
                </div>
            </div>

            {{-- Live Preview --}}
            <div class="rounded-xl overflow-hidden border border-gray-200">
                <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                    <div class="flex gap-1.5"><div class="w-3 h-3 rounded-full bg-red-400"></div><div class="w-3 h-3 rounded-full bg-yellow-400"></div><div class="w-3 h-3 rounded-full bg-green-400"></div></div>
                    <span class="text-xs text-gray-400 ml-2">Preview</span>
                </div>
                <div class="flex h-28">
                    <div class="w-16 flex-shrink-0" id="preview_sidebar" style="background: {{ $settings['accent_color'] ?? '#1a2535' }}"></div>
                    <div class="flex-1 bg-white flex flex-col justify-center px-5 gap-3">
                        <div class="h-6 w-24 rounded-lg" id="preview_btn" style="background: {{ $settings['brand_color'] ?? '#e05a3a' }}"></div>
                        <div class="flex gap-2">
                            <div class="h-3 w-32 rounded bg-gray-100"></div>
                            <div class="h-3 w-3 rounded" id="preview_dot" style="background: {{ $settings['brand_color'] ?? '#e05a3a' }}"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @else
    <div class="card bg-gray-50 border-dashed border-2 border-gray-200 h-64 flex flex-col items-center justify-center text-gray-400">
        <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        <p class="text-sm font-medium">{{ ucfirst($tab) }} settings coming soon.</p>
    </div>
    @endif

    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
        <button type="submit" class="btn-primary px-8">Save Settings</button>
    </div>
</form>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}

function previewImage(input, imgId, wrapId, iconId) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById(imgId);
        const wrap = document.getElementById(wrapId);
        const icon = document.getElementById(iconId);
        img.src = e.target.result;
        img.classList.remove('hidden');
        if (wrap) wrap.classList.remove('hidden');
        if (icon) icon.classList.add('hidden');
    };
    reader.readAsDataURL(input.files[0]);
}

function previewFavicon(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = document.getElementById('favicon_preview_img');
        const placeholder = document.getElementById('favicon_placeholder');
        img.src = e.target.result;
        img.classList.remove('hidden');
        if (placeholder) placeholder.classList.add('hidden');
    };
    reader.readAsDataURL(input.files[0]);
}

function insertVar(variable) {
    const textarea = document.querySelector('textarea[name^="tpl_"]');
    if (!textarea) return;
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    textarea.value = text.substring(0, start) + variable + text.substring(end);
    textarea.focus();
    textarea.setSelectionRange(start + variable.length, start + variable.length);
    // Copy to clipboard as well
    navigator.clipboard.writeText(variable).catch(() => {});
}

// Live color swatch updates
['brand_color', 'accent_color'].forEach(id => {
    const input = document.getElementById(id + '_input');
    const hex   = document.getElementById(id + '_hex');
    const swatch = document.getElementById(id + '_swatch');
    if (!input) return;
    function sync(val) {
        if (swatch) swatch.style.background = val;
        if (id === 'brand_color') {
            const btn = document.getElementById('preview_btn');
            const dot = document.getElementById('preview_dot');
            if (btn) btn.style.background = val;
            if (dot) dot.style.background = val;
        } else {
            const sidebar = document.getElementById('preview_sidebar');
            if (sidebar) sidebar.style.background = val;
        }
    }
    input.addEventListener('input', () => { hex.value = input.value; sync(input.value); });
    hex.addEventListener('input', () => { input.value = hex.value; sync(hex.value); });
});

async function sendTestEmail() {
    const email = document.getElementById('test_email_address').value;
    const btn = document.getElementById('send_test_btn');
    const spinner = document.getElementById('test_spinner');
    const resultDiv = document.getElementById('test_result');
    if (!email) { alert('Please enter an email address first.'); return; }
    btn.disabled = true;
    spinner.classList.remove('hidden');
    resultDiv.classList.add('hidden');
    resultDiv.className = 'mt-3 text-xs';
    try {
        console.log('Sending test email to:', email);
        const response = await fetch('{{ route("admin.settings.test-email") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email: email })
        });

        console.log('Response status:', response.status);
        
        let data;
        const contentType = response.headers.get("content-type");
        if (contentType && contentType.indexOf("application/json") !== -1) {
            data = await response.json();
        } else {
            const text = await response.text();
            console.error('Non-JSON response snippet:', text.substring(0, 500));
            
            // Try to extract title from HTML
            const doc = new DOMParser().parseFromString(text, "text/html");
            const title = doc.querySelector('title')?.innerText || 'Unknown Page';
            
            throw new Error('Server returned HTML: "' + title + '" (Status ' + response.status + '). This usually means a redirect to Login or Dashboard occurred.');
        }

        resultDiv.classList.remove('hidden');
        if (response.ok && data.success) {
            resultDiv.classList.add('text-green-600');
            resultDiv.innerText = '✓ ' + data.message;
        } else {
            resultDiv.classList.add('text-red-600');
            resultDiv.innerText = '✗ ' + (data.message || 'Error ' + response.status);
        }
    } catch (error) {
        console.error('Fetch error:', error);
        resultDiv.classList.remove('hidden');
        resultDiv.classList.add('text-red-600');
        resultDiv.innerText = '✗ Connection Error: ' + error.message;
    } finally {
        btn.disabled = false;
        spinner.classList.add('hidden');
    }
}

async function testCurrentTemplate(key) {
    const email = prompt("Enter email address to send test template to:", "{{ Auth::user()->email }}");
    if (!email) return;

    try {
        const response = await fetch('{{ route("admin.settings.test-email") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                email: email,
                template: key
            })
        });

        const data = await response.json();
        if (data.success) {
            alert("✓ Success: " + data.message);
        } else {
            alert("✗ Error: " + data.message);
        }
    } catch (error) {
        alert("✗ Connection Error: " + error.message);
    }
}
</script>
@endsection
