<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TemplateMail;
use App\Models\Setting;
use App\Services\Mail\TemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index(string $tab = 'general')
    {
        $settings = Setting::where('group', $tab)->get()->pluck('value', 'key')->toArray();

        // Also load template-enabled flags if on templates tab
        if ($tab === 'templates') {
            $tplSettings = Setting::where('group', 'templates')->get()->pluck('value', 'key')->toArray();
            $settings = array_merge($settings, $tplSettings);
        }

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
                'bank_transfer_enabled'  => '0',
                'bank_name'              => '',
                'bank_account_name'      => '',
                'bank_sort_code'         => '',
                'bank_account_number'    => '',
                'bank_iban'              => '',
                'bank_reference_note'    => 'Please use your full name as the payment reference.',
                'currency'              => 'GBP',
                'currency_symbol'       => '£',
                'currency_position'     => 'before',
            ],
            'templates' => [],  // defaults handled by TemplateService
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
                'creator_enabled'         => '0',
                'creator_commission'      => '80',
                'creator_min_withdrawal'  => '1000',
                'affiliate_enabled'       => '0',
                'affiliate_admin_fee'     => '5',
                'affiliate_cookie_days'   => '30',
            ],
            default => [],
        };

        $settings = array_merge($defaults, $settings);

        // Pass template definitions if on templates tab
        $emailTemplates = ($tab === 'templates') ? TemplateService::all() : [];

        return view('admin.settings.index', compact('tab', 'settings', 'emailTemplates'));
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

    /**
     * Toggle a template enabled/disabled.
     */
    public function toggleTemplate(Request $request)
    {
        $request->validate(['key' => 'required|string']);
        $key = $request->key;
        $settingKey = "tpl_{$key}_enabled";
        $current = Setting::get($settingKey, '1');
        $new = $current === '1' ? '0' : '1';
        Setting::set($settingKey, $new, 'string', 'templates');

        return response()->json([
            'success' => true,
            'enabled' => $new === '1',
            'message' => ($new === '1' ? 'Enabled' : 'Disabled') . ' successfully.',
        ]);
    }

    /**
     * Get preview data for a template.
     */
    public function previewTemplate(Request $request)
    {
        $request->validate(['key' => 'required|string']);
        [$subject, $body] = TemplateService::preview($request->key);

        return response()->json([
            'subject' => $subject,
            'body'    => nl2br(e($body)),
        ]);
    }

    /**
     * Send test email using a specific template.
     */
    public function sendTestEmail(Request $request)
    {
        try {
            $request->validate([
                'email'    => 'required|email',
                'template' => 'nullable|string',
            ]);

            \App\Services\Mail\MailConfigService::apply();

            $templateKey = $request->get('template');

            if ($templateKey && isset(TemplateService::all()[$templateKey])) {
                Mail::to($request->email)->send(new TemplateMail($templateKey, [
                    'name'            => 'Test User',
                    'verify_url'      => url('/email/verify/1/sample-hash'),
                    'reset_url'       => url('/reset-password/sample-token'),
                    'cohort_name'     => 'Full-Stack Web Development',
                    'dashboard_url'   => url('/dashboard'),
                    'amount'          => Setting::get('currency_symbol', '£') . '5,000.00',
                    'reason'          => 'Receipt image was unclear.',
                    'referred_name'   => 'Jane Smith',
                    'wallet_balance'  => Setting::get('currency_symbol', '£') . '12,500.00',
                    'referrals_url'   => url('/referrals'),
                    'material_title'  => 'Week 3: Building REST APIs',
                    'materials_url'   => url('/cohorts/1/materials'),
                ]));
            } else {
                Mail::to($request->email)->send(new TemplateMail('welcome', [
                    'name' => 'Test User',
                ]));
            }

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
