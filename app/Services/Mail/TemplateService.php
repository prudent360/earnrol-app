<?php

namespace App\Services\Mail;

use App\Models\Setting;

class TemplateService
{
    /**
     * All template definitions with defaults: subject, body, description, variables.
     */
    protected static array $templates = [
        'welcome' => [
            'label'   => 'Welcome Email',
            'desc'    => 'Sent when a new user registers',
            'subject' => 'Welcome to {{app_name}}, {{name}}!',
            'body'    => "Hi {{name}},\n\nWelcome to {{app_name}}! We're thrilled to have you on board.\n\nYour account is ready. Browse available cohorts and enrol in a live class to start learning.\n\nGet started: {{login_url}}\n\nBest,\nThe {{app_name}} Team",
            'vars'    => ['{{name}}', '{{login_url}}', '{{app_name}}'],
        ],
        'verify' => [
            'label'   => 'Email Verification',
            'desc'    => 'Sent to verify the user\'s email address',
            'subject' => 'Verify your email — {{app_name}}',
            'body'    => "Hi {{name}},\n\nPlease verify your email address by clicking the link below:\n\n{{verify_url}}\n\nThis link will expire in 60 minutes.\n\nIf you did not create an account, no action is required.\n\nBest,\nThe {{app_name}} Team",
            'vars'    => ['{{name}}', '{{verify_url}}', '{{app_name}}'],
        ],
        'reset' => [
            'label'   => 'Password Reset',
            'desc'    => 'Sent when a user requests a password reset',
            'subject' => 'Reset your {{app_name}} password',
            'body'    => "Hi {{name}},\n\nWe received a request to reset the password for your {{app_name}} account.\n\nClick the link below to reset your password (valid for 60 minutes):\n{{reset_url}}\n\nIf you did not request this, please ignore this email.\n\nBest,\nThe {{app_name}} Team",
            'vars'    => ['{{name}}', '{{reset_url}}', '{{app_name}}'],
        ],
        'enroll' => [
            'label'   => 'Cohort Enrollment',
            'desc'    => 'Sent after successful cohort enrollment',
            'subject' => "You're enrolled in {{cohort_name}}!",
            'body'    => "Hi {{name}},\n\nGreat news! You are now enrolled in {{cohort_name}}.\n\nHead to your dashboard to join your live class: {{dashboard_url}}\n\nBest,\nThe {{app_name}} Team",
            'vars'    => ['{{name}}', '{{cohort_name}}', '{{dashboard_url}}', '{{app_name}}'],
        ],
        'payment_approved' => [
            'label'   => 'Payment Approved',
            'desc'    => 'Sent when admin approves a bank transfer',
            'subject' => 'Payment Approved — {{cohort_name}}',
            'body'    => "Hi {{name}},\n\nGreat news! Your bank transfer payment of {{amount}} for {{cohort_name}} has been approved.\n\nYou are now enrolled. Head to your dashboard to get started: {{dashboard_url}}\n\nBest,\nThe {{app_name}} Team",
            'vars'    => ['{{name}}', '{{cohort_name}}', '{{amount}}', '{{dashboard_url}}', '{{app_name}}'],
        ],
        'payment_rejected' => [
            'label'   => 'Payment Rejected',
            'desc'    => 'Sent when admin rejects a bank transfer',
            'subject' => 'Payment Not Approved — {{cohort_name}}',
            'body'    => "Hi {{name}},\n\nUnfortunately, your bank transfer for {{cohort_name}} could not be verified.\n\nReason: {{reason}}\n\nPlease contact support or try submitting your payment again.\n\nBest,\nThe {{app_name}} Team",
            'vars'    => ['{{name}}', '{{cohort_name}}', '{{reason}}', '{{app_name}}'],
        ],
        'referral_earned' => [
            'label'   => 'Referral Commission',
            'desc'    => 'Sent when a referred user makes a payment',
            'subject' => 'You earned {{amount}} — Referral Commission!',
            'body'    => "Hi {{name}},\n\n{{referred_name}} just made a payment and you earned a referral commission of {{amount}}!\n\nYour updated wallet balance: {{wallet_balance}}\n\nView your referrals: {{referrals_url}}\n\nBest,\nThe {{app_name}} Team",
            'vars'    => ['{{name}}', '{{referred_name}}', '{{amount}}', '{{wallet_balance}}', '{{referrals_url}}', '{{app_name}}'],
        ],
        'withdrawal_approved' => [
            'label'   => 'Withdrawal Approved',
            'desc'    => 'Sent when admin approves a withdrawal request',
            'subject' => 'Withdrawal of {{amount}} Approved',
            'body'    => "Hi {{name}},\n\nYour withdrawal request of {{amount}} has been approved. The funds will be transferred to your registered bank account.\n\nBest,\nThe {{app_name}} Team",
            'vars'    => ['{{name}}', '{{amount}}', '{{app_name}}'],
        ],
        'withdrawal_rejected' => [
            'label'   => 'Withdrawal Rejected',
            'desc'    => 'Sent when admin rejects a withdrawal request',
            'subject' => 'Withdrawal Request Not Approved',
            'body'    => "Hi {{name}},\n\nYour withdrawal request of {{amount}} was not approved.\n\nReason: {{reason}}\n\nIf you believe this is an error, please contact support.\n\nBest,\nThe {{app_name}} Team",
            'vars'    => ['{{name}}', '{{amount}}', '{{reason}}', '{{app_name}}'],
        ],
        'new_material' => [
            'label'   => 'New Material Uploaded',
            'desc'    => 'Sent when new lesson or assignment is added to a cohort',
            'subject' => 'New material in {{cohort_name}} — {{material_title}}',
            'body'    => "Hi {{name}},\n\nNew content has been added to {{cohort_name}}:\n\n{{material_title}}\n\nView it now: {{materials_url}}\n\nBest,\nThe {{app_name}} Team",
            'vars'    => ['{{name}}', '{{material_title}}', '{{cohort_name}}', '{{materials_url}}', '{{app_name}}'],
        ],
    ];

    /**
     * Get all template definitions (for admin UI).
     */
    public static function all(): array
    {
        return self::$templates;
    }

    /**
     * Check if a template is enabled (default: enabled).
     */
    public static function isEnabled(string $templateKey): bool
    {
        return Setting::get("tpl_{$templateKey}_enabled", '1') === '1';
    }

    /**
     * Parse a template with the given data.
     *
     * @return array [subject, body]
     */
    public static function parse(string $templateKey, array $data = []): array
    {
        $defaults = self::$templates[$templateKey] ?? ['subject' => '', 'body' => ''];

        $subjectTemplate = Setting::get("tpl_{$templateKey}_subject")
            ?: ($defaults['subject'] ?? '');
        $bodyTemplate = Setting::get("tpl_{$templateKey}_body")
            ?: ($defaults['body'] ?? '');

        $data['app_name']  = Setting::get('app_name', 'EarnRol');
        $data['login_url'] = $data['login_url'] ?? route('login');

        $subject = self::replaceVariables($subjectTemplate, $data);
        $body    = self::replaceVariables($bodyTemplate, $data);

        return [$subject, $body];
    }

    /**
     * Parse with sample/dummy data for preview purposes.
     *
     * @return array [subject, body]
     */
    public static function preview(string $templateKey): array
    {
        $symbol = Setting::get('currency_symbol', '£');

        return self::parse($templateKey, [
            'name'            => 'John Doe',
            'login_url'       => url('/login'),
            'verify_url'      => url('/email/verify/1/sample-hash'),
            'reset_url'       => url('/reset-password/sample-token'),
            'cohort_name'     => 'Full-Stack Web Development',
            'dashboard_url'   => url('/dashboard'),
            'amount'          => $symbol . '5,000.00',
            'reason'          => 'Receipt image was unclear — please re-upload.',
            'referred_name'   => 'Jane Smith',
            'wallet_balance'  => $symbol . '12,500.00',
            'referrals_url'   => url('/referrals'),
            'material_title'  => 'Week 3: Building REST APIs',
            'materials_url'   => url('/cohorts/1/materials'),
        ]);
    }

    protected static function replaceVariables(string $content, array $data): string
    {
        foreach ($data as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value ?? '', $content);
        }
        return $content;
    }
}
