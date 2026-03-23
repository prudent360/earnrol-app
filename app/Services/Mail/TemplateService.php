<?php
7: 
8: namespace App\Services\Mail;
9: 
10: use App\Models\Setting;
11: 
12: class TemplateService
13: {
14:     /**
15:      * Parse a template with the given data.
16:      *
17:      * @param string $templateKey Simple key like 'welcome', 'enroll'
18:      * @param array $data Variables to replace
19:      * @return array [subject, body]
20:      */
21:     public static function parse(string $templateKey, array $data = []): array
22:     {
23:         $subjectTemplate = Setting::get("tpl_{$templateKey}_subject", '');
24:         $bodyTemplate    = Setting::get("tpl_{$templateKey}_body", '');
25: 
26:         // Add global variables
27:         $data['app_name'] = Setting::get('app_name', 'EarnRol');
28:         $data['login_url'] = route('login');
29: 
30:         $subject = self::replaceVariables($subjectTemplate, $data);
31:         $body    = self::replaceVariables($bodyTemplate, $data);
32: 
33:         return [$subject, $body];
34:     }
35: 
36:     /**
37:      * Replace {{variable}} with actual data.
38:      */
39:     protected static function replaceVariables(string $content, array $data): string
40:     {
41:         foreach ($data as $key => $value) {
42:             $content = str_replace('{{' . $key . '}}', $value ?? '', $content);
43:         }
44:         return $content;
45:     }
46: }
47: 1: 
