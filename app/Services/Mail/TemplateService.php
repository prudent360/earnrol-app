<?php

namespace App\Services\Mail;

use App\Models\Setting;

class TemplateService
{
    /**
     * Default templates used when nothing is saved in the database.
     */
    protected static array $defaults = [
        'welcome' => [
            'subject' => 'Welcome to EarnRol, {{name}}!',
            'body'    => "Hi {{name}},\n\nWelcome to EarnRol! We're thrilled to have you on board.\n\nYour account is ready. Browse available cohorts and enrol in a live class to start learning.\n\nGet started: {{login_url}}\n\nBest,\nThe EarnRol Team",
        ],
        'reset' => [
            'subject' => 'Reset your EarnRol password',
            'body'    => "Hi {{name}},\n\nWe received a request to reset the password for your EarnRol account.\n\nClick the link below to reset your password (valid for 60 minutes):\n{{reset_url}}\n\nIf you did not request this, please ignore this email.\n\nBest,\nThe EarnRol Team",
        ],
        'enroll' => [
            'subject' => "You're enrolled in {{cohort_name}}!",
            'body'    => "Hi {{name}},\n\nGreat news! You are now enrolled in {{cohort_name}}.\n\nHead to your dashboard to join your live class: {{dashboard_url}}\n\nBest,\nThe EarnRol Team",
        ],
    ];

    /**
     * Parse a template with the given data.
     *
     * @return array [subject, body]
     */
    public static function parse(string $templateKey, array $data = []): array
    {
        $subjectTemplate = Setting::get("tpl_{$templateKey}_subject")
            ?: (self::$defaults[$templateKey]['subject'] ?? '');
        $bodyTemplate = Setting::get("tpl_{$templateKey}_body")
            ?: (self::$defaults[$templateKey]['body'] ?? '');

        $data['app_name']  = Setting::get('app_name', 'EarnRol');
        $data['login_url'] = $data['login_url'] ?? route('login');

        $subject = self::replaceVariables($subjectTemplate, $data);
        $body    = self::replaceVariables($bodyTemplate, $data);

        return [$subject, $body];
    }

    protected static function replaceVariables(string $content, array $data): string
    {
        foreach ($data as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value ?? '', $content);
        }
        return $content;
    }
}
