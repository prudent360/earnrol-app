<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Apply SMTP settings from database on every request
        try {
            \App\Services\Mail\MailConfigService::apply();
        } catch (\Exception $e) {
            // Silently fail if DB not yet migrated
        }
    }
}
