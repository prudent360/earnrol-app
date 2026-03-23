<?php
7: 
8: namespace App\Services\Mail;
9: 
10: use App\Models\Setting;
11: use Illuminate\Support\Facades\Config;
12: 
13: class MailConfigService
14: {
15:     /**
16:      * Apply SMTP settings from the database to the Laravel configuration.
17:      */
18:     public static function apply(): void
19:     {
20:         $settings = Setting::where('group', 'smtp')->get()->pluck('value', 'key')->toArray();
21: 
22:         if (empty($settings['mail_host'])) {
23:             return;
24:         }
25: 
26:         Config::set('mail.mailers.smtp.host', $settings['mail_host'] ?? config('mail.mailers.smtp.host'));
27:         Config::set('mail.mailers.smtp.port', $settings['mail_port'] ?? config('mail.mailers.smtp.port'));
28:         Config::set('mail.mailers.smtp.encryption', $settings['mail_encryption'] ?? config('mail.mailers.smtp.encryption'));
29:         Config::set('mail.mailers.smtp.username', $settings['mail_username'] ?? config('mail.mailers.smtp.username'));
30:         Config::set('mail.mailers.smtp.password', $settings['mail_password'] ?? config('mail.mailers.smtp.password'));
31:         
32:         Config::set('mail.from.address', $settings['mail_from_address'] ?? config('mail.from.address'));
33:         Config::set('mail.from.name', $settings['mail_from_name'] ?? config('mail.from.name'));
34:     }
35: }
36: 
