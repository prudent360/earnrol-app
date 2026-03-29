<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
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

        // Register permission gates dynamically
        try {
            \App\Models\Permission::all()->each(function ($permission) {
                Gate::define($permission->slug, fn ($user) => $user->hasPermissionTo($permission->slug));
            });
        } catch (\Exception $e) {
            // DB not migrated yet
        }
    }
}
