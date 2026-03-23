<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            if (!app()->runningInConsole() && Schema::hasTable('settings')) {
                // Load SMTP settings into config
                $smtpSettings = Setting::where('group', 'smtp')->get();
                
                foreach ($smtpSettings as $setting) {
                    $configKey = $this->mapSettingToConfig($setting->key);
                    if ($configKey) {
                        Config::set($configKey, $setting->value);
                    }
                }
            }
        } catch (\Exception $e) {
            // Table might not exist yet during migration
        }
    }

    /**
     * Map our settings keys to Laravel config keys.
     */
    protected function mapSettingToConfig($key)
    {
        $map = [
            'mail_driver' => 'mail.default',
            'mail_host' => 'mail.mailers.smtp.host',
            'mail_port' => 'mail.mailers.smtp.port',
            'mail_encryption' => 'mail.mailers.smtp.encryption',
            'mail_username' => 'mail.mailers.smtp.username',
            'mail_password' => 'mail.mailers.smtp.password',
            'mail_from_address' => 'mail.from.address',
            'mail_from_name' => 'mail.from.name',
        ];

        return $map[$key] ?? null;
    }
}
