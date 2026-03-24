<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index(string $tab = 'general')
    {
        $settings = Setting::where('group', $tab)->get()->pluck('value', 'key')->toArray();

        $defaults = match($tab) {
            'smtp' => [
                'mail_driver'       => 'smtp',
                'mail_host'         => '',
                'mail_port'         => '587',
                'mail_encryption'   => 'tls',
                'mail_username'     => '',
                'mail_password'     => '',
                'mail_from_name'    => 'EarnRol',
                'mail_from_address' => 'hello@earnrol.com',
            ],
            'payment' => [
                'stripe_enabled'         => '0',
                'stripe_test_mode'       => '1',
                'stripe_public_key'      => '',
                'stripe_secret_key'      => '',
                'stripe_webhook_secret'  => '',
                'paypal_enabled'         => '0',
                'paypal_sandbox'         => '1',
                'paypal_client_id'       => '',
                'paypal_client_secret'   => '',
                'currency'              => 'GBP',
                'currency_symbol'       => '£',
                'currency_position'     => 'before',
            ],
            'templates' => [
                'tpl_welcome_subject'    => 'Welcome to EarnRol, {{name}}!',
                'tpl_welcome_body'       => "Hi {{name}},\n\nWelcome to EarnRol! We're thrilled to have you on board.\n\nYour account is ready. Browse available cohorts and enrol in a live class to start learning.\n\nGet started: {{login_url}}\n\nBest,\nThe EarnRol Team",
                'tpl_reset_subject'     => 'Reset your EarnRol password',
                'tpl_reset_body'        => "Hi {{name}},\n\nWe received a request to reset the password for your EarnRol account.\n\nClick the link below to reset your password (valid for 60 minutes):\n{{reset_url}}\n\nIf you did not request this, please ignore this email.\n\nBest,\nThe EarnRol Team",
                'tpl_enroll_subject'    => 'You\'re enrolled in {{cohort_name}}!',
                'tpl_enroll_body'       => "Hi {{name}},\n\nGreat news! You are now enrolled in **{{cohort_name}}**.\n\nHead to your dashboard to join your live class: {{dashboard_url}}\n\nBest,\nThe EarnRol Team",
            ],
            'branding' => [
                'app_name'        => 'EarnRol',
                'app_tagline'     => 'Learn. Build. Earn.',
                'logo_path'       => '',
                'logo_dark_path'  => '',
                'favicon_path'    => '',
                'brand_color'     => '#e05a3a',
                'accent_color'    => '#1a2535',
                'footer_text'     => '© ' . date('Y') . ' EarnRol. All rights reserved.',
            ],
            'general' => [
                'site_name'               => 'EarnRol',
                'site_url'                => config('app.url'),
                'contact_email'           => '',
                'timezone'                => 'UTC',
                'maintenance_mode'        => '0',
                'referral_enabled'        => '0',
                'referral_commission'     => '10',
                'referral_min_withdrawal' => '1000',
                'announcement_enabled'    => '0',
                'announcement_message'    => '',
                'announcement_timer'      => '0',
                'vat_enabled'             => '0',
                'vat_percentage'          => '7.5',
                'vat_label'               => 'VAT',
            ],
            default => [],
        };

        $settings = array_merge($defaults, $settings);

        return view('admin.settings.index', compact('tab', 'settings'));
    }

    public function update(Request $request, string $tab)
    {
        // Handle file uploads for branding tab
        if ($tab === 'branding') {
            $fileFields = ['logo', 'logo_dark', 'favicon'];

            foreach ($fileFields as $field) {
                if ($request->hasFile($field) && $request->file($field)->isValid()) {
                    // Delete old file if it exists
                    $existingPath = Setting::get("{$field}_path");
                    if ($existingPath && Storage::disk('public')->exists($existingPath)) {
                        Storage::disk('public')->delete($existingPath);
                    }

                    $path = $request->file($field)->store('branding', 'public');
                    Setting::set("{$field}_path", $path, 'string', 'branding');
                }
            }
        }

        $data = $request->except(['_token', '_method', 'logo', 'logo_dark', 'favicon']);

        foreach ($data as $key => $value) {
            $type = 'string';
            if (is_array($value)) $type = 'json';

            Setting::set($key, $value, $type, $tab);
        }

        return redirect()->route('admin.settings.index', ['tab' => $tab])
            ->with('success', ucfirst($tab) . ' settings updated successfully.');
    }

    public function sendTestEmail(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);
            
            // Apply current DB settings (user should save before testing)
            \App\Services\Mail\MailConfigService::apply();

            $templateKey = $request->get('template');
            $subject = 'Test Email from ' . config('app.name');
            $body = 'This is a test email from ' . config('app.name') . '. If you received this, your SMTP settings are working correctly!';

            if ($templateKey) {
                // Parse the requested template with dummy data
                [$subject, $body] = \App\Services\Mail\TemplateService::parse($templateKey, [
                    'name'              => 'Test User',
                    'course_name'       => 'Mastering Laravel',
                    'course_url'        => url('/learning/1'),
                    'job_title'         => 'Full Stack Developer',
                    'company'           => 'EarnRol Tech',
                    'application_url'   => url('/applications/1'),
                    'mentor_name'       => 'John Doe',
                    'session_datetime'  => now()->addDays(2)->format('M d, Y H:i A'),
                    'meeting_url'       => 'https://meet.google.com/test-session',
                    'reset_url'         => url('/password/reset/token'),
                ]);
            }

            \Illuminate\Support\Facades\Mail::raw($body, function ($message) use ($request, $subject) {
                $message->to($request->email)
                        ->subject($subject);
            });

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully to ' . $request->email
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage()
            ], 500);
        }
    }
}
