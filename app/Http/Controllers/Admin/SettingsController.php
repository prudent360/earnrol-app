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
                'flutterwave_enabled'    => '0',
                'flutterwave_public_key' => '',
                'flutterwave_secret_key' => '',
                'flutterwave_enc_key'    => '',
                'currency'              => 'USD',
                'currency_symbol'       => '$',
                'currency_position'     => 'before',
            ],
            'templates' => [
                'tpl_welcome_subject'    => 'Welcome to EarnRol, {{name}}!',
                'tpl_welcome_body'       => "Hi {{name}},\n\nWelcome to EarnRol! We're thrilled to have you on board.\n\nYour account is ready. Start exploring courses, projects, and mentorship opportunities today.\n\nGet started: {{login_url}}\n\nBest,\nThe EarnRol Team",
                'tpl_reset_subject'     => 'Reset your EarnRol password',
                'tpl_reset_body'        => "Hi {{name}},\n\nWe received a request to reset the password for your EarnRol account.\n\nClick the link below to reset your password (valid for 60 minutes):\n{{reset_url}}\n\nIf you did not request this, please ignore this email.\n\nBest,\nThe EarnRol Team",
                'tpl_enroll_subject'    => 'You\'re enrolled in {{course_name}}!',
                'tpl_enroll_body'       => "Hi {{name}},\n\nGreat news! You are now enrolled in **{{course_name}}**.\n\nStart learning: {{course_url}}\n\nGood luck on your learning journey!\n\nBest,\nThe EarnRol Team",
                'tpl_job_applied_subject' => 'Application received — {{job_title}}',
                'tpl_job_applied_body'  => "Hi {{name}},\n\nYour application for **{{job_title}}** at {{company}} has been received.\n\nWe'll notify you as soon as the employer reviews your application.\n\nView your application: {{application_url}}\n\nBest,\nThe EarnRol Team",
                'tpl_mentor_subject'    => 'Mentorship session confirmed with {{mentor_name}}',
                'tpl_mentor_body'       => "Hi {{name}},\n\nYour mentorship session with **{{mentor_name}}** has been confirmed.\n\nDate & Time: {{session_datetime}}\nMeeting Link: {{meeting_url}}\n\nBest,\nThe EarnRol Team",
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
        $request->validate(['email' => 'required|email']);

        try {
            \Illuminate\Support\Facades\Mail::raw(
                'This is a test email to verify your SMTP configuration in EarnRol.',
                function ($message) use ($request) {
                    $message->to($request->email)->subject('SMTP Verification — EarnRol');
                }
            );

            return response()->json([
                'success' => true,
                'message' => 'Test email sent to ' . $request->email,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
