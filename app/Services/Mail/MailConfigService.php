<?php

namespace App\Services\Mail;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;

class MailConfigService
{
    public static function apply(): void
    {
        $settings = Setting::where('group', 'smtp')->get()->pluck('value', 'key')->toArray();

        if (empty($settings['mail_host'])) {
            return;
        }

        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.host', $settings['mail_host'] ?? config('mail.mailers.smtp.host'));
        Config::set('mail.mailers.smtp.port', $settings['mail_port'] ?? config('mail.mailers.smtp.port'));
        Config::set('mail.mailers.smtp.encryption', $settings['mail_encryption'] ?? config('mail.mailers.smtp.encryption'));
        Config::set('mail.mailers.smtp.username', $settings['mail_username'] ?? config('mail.mailers.smtp.username'));
        Config::set('mail.mailers.smtp.password', $settings['mail_password'] ?? config('mail.mailers.smtp.password'));

        Config::set('mail.from.address', $settings['mail_from_address'] ?? config('mail.from.address'));
        Config::set('mail.from.name', $settings['mail_from_name'] ?? config('mail.from.name'));
    }
}
