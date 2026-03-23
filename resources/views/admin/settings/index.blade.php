@extends('layouts.app')

@section('title', 'Settings')
@section('page_title', 'Settings')
@section('page_subtitle', 'Configure your booking system')

@section('content')
<div class="mb-8">
    {{-- Tab Navigation --}}
    <div class="flex items-center gap-2 border-b border-gray-200 overflow-x-auto pb-px">
        <a href="{{ route('admin.settings.index', ['tab' => 'general']) }}" class="flex items-center gap-2 px-6 py-4 text-sm font-medium transition-colors border-b-2 {{ $tab === 'general' ? 'border-[#e05a3a] text-[#e05a3a]' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            General
        </a>
        <a href="{{ route('admin.settings.index', ['tab' => 'payment']) }}" class="flex items-center gap-2 px-6 py-4 text-sm font-medium transition-colors border-b-2 {{ $tab === 'payment' ? 'border-[#e05a3a] text-[#e05a3a]' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            Payment Gateways
        </a>
        <a href="{{ route('admin.settings.index', ['tab' => 'smtp']) }}" class="flex items-center gap-2 px-6 py-4 text-sm font-medium transition-colors border-b-2 {{ $tab === 'smtp' ? 'border-[#e05a3a] text-[#e05a3a]' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Email / SMTP
        </a>
        <a href="{{ route('admin.settings.index', ['tab' => 'templates']) }}" class="flex items-center gap-2 px-6 py-4 text-sm font-medium transition-colors border-b-2 {{ $tab === 'templates' ? 'border-[#e05a3a] text-[#e05a3a]' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Email Templates
        </a>
        <a href="{{ route('admin.settings.index', ['tab' => 'sms']) }}" class="flex items-center gap-2 px-6 py-4 text-sm font-medium transition-colors border-b-2 {{ $tab === 'sms' ? 'border-[#e05a3a] text-[#e05a3a]' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            SMS
        </a>
        <a href="{{ route('admin.settings.index', ['tab' => 'branding']) }}" class="flex items-center gap-2 px-6 py-4 text-sm font-medium transition-colors border-b-2 {{ $tab === 'branding' ? 'border-[#e05a3a] text-[#e05a3a]' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Branding
        </a>
    </div>
</div>

<form action="{{ route('admin.settings.update', ['tab' => $tab]) }}" method="POST">
    @csrf

    {{-- SMTP Settings Tab --}}
    @if($tab === 'smtp')
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
                        <option value="smtp" {{ $settings['mail_driver'] === 'smtp' ? 'selected' : '' }}>SMTP</option>
                        <option value="mailgun" {{ $settings['mail_driver'] === 'mailgun' ? 'selected' : '' }}>Mailgun</option>
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
                            <option value="tls" {{ $settings['mail_encryption'] === 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ $settings['mail_encryption'] === 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="none" {{ $settings['mail_encryption'] === 'none' ? 'selected' : '' }}>None</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Username</label>
                    <input type="text" name="mail_username" value="{{ $settings['mail_username'] }}" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all" placeholder="hello@reenite.com">
                </div>

                <div>
                    <label class="form-label uppercase text-[10px] tracking-widest text-gray-400">Password <span class="text-[10px] text-green-500 font-bold ml-2">✓ CONFIGURED</span></label>
                    <div class="relative">
                        <input type="password" name="mail_password" id="mail_password" value="{{ $settings['mail_password'] }}" class="form-input bg-gray-50 border-transparent focus:bg-white transition-all pr-12" placeholder="••••••••••••">
                        <button type="button" onclick="togglePassword('mail_password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5 visibility-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2">For Gmail, use an App Password</p>
                </div>
            </div>
        </div>

        {{-- Sender Information --}}
        <div class="space-y-6">
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

            <div class="bg-blue-50 border border-blue-100 rounded-xl p-6">
                <h4 class="text-sm font-bold text-blue-900 mb-4">Common SMTP Settings</h4>
                <ul class="space-y-2 text-xs text-blue-800">
                    <li><span class="font-bold">Gmail:</span> smtp.gmail.com, Port 587, TLS, App Password</li>
                    <li><span class="font-bold">Mailtrap:</span> smtp.mailtrap.io, Port 587, TLS</li>
                    <li><span class="font-bold">SendGrid:</span> smtp.sendgrid.net, Port 587, TLS</li>
                    <li><span class="font-bold">Mailgun:</span> smtp.mailgun.org, Port 587, TLS</li>
                </ul>
            </div>

            <div class="bg-gray-50 rounded-xl p-6">
                <h4 class="text-sm font-bold text-gray-900 mb-4">Send Test Email</h4>
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
    @else
    <div class="card bg-gray-50 border-dashed border-2 border-gray-200 h-64 flex flex-col items-center justify-center text-gray-400">
        <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        <p class="text-sm font-medium">{{ ucfirst($tab) }} settings implementation coming soon.</p>
    </div>
    @endif

    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
        <button type="submit" class="btn-primary px-8">
            Save Settings
        </button>
    </div>
</form>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
}

async function sendTestEmail() {
    const email = document.getElementById('test_email_address').value;
    const btn = document.getElementById('send_test_btn');
    const spinner = document.getElementById('test_spinner');
    const resultDiv = document.getElementById('test_result');
    
    if (!email) {
        alert('Please enter an email address first.');
        return;
    }

    // Reset and show loading
    btn.disabled = true;
    spinner.classList.remove('hidden');
    resultDiv.classList.add('hidden');
    resultDiv.className = 'mt-3 text-xs';

    try {
        const response = await fetch('{{ route("admin.settings.test-email") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email: email })
        });

        const data = await response.json();

        resultDiv.classList.remove('hidden');
        if (data.success) {
            resultDiv.classList.add('text-green-600');
            resultDiv.innerText = '✓ ' + data.message;
        } else {
            resultDiv.classList.add('text-red-600');
            resultDiv.innerText = '✗ ' + data.message;
        }
    } catch (error) {
        resultDiv.classList.remove('hidden');
        resultDiv.classList.add('text-red-600');
        resultDiv.innerText = '✗ Error: Could not connect to the server.';
    } finally {
        btn.disabled = false;
        spinner.classList.add('hidden');
    }
}
</script>
@endsection
