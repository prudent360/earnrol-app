<?php

namespace App\Services\Mail;

use App\Models\Setting;

class TemplateService
{
    /**
     * Parse a template with the given data.
     *
     * @return array [subject, body]
     */
    public static function parse(string $templateKey, array $data = []): array
    {
        $subjectTemplate = Setting::get("tpl_{$templateKey}_subject", '');
        $bodyTemplate    = Setting::get("tpl_{$templateKey}_body", '');

        $data['app_name'] = Setting::get('app_name', 'EarnRol');
        $data['login_url'] = route('login');

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
