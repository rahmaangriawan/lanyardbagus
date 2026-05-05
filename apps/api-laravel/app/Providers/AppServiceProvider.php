<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        try {
            if (Schema::hasTable('site_settings')) {
                $settings = SiteSetting::with(['logoImage', 'faviconImage'])->first();

                // Fallback: if site_settings verification is empty, try system settings
                if ($settings && empty($settings->google_site_verification)) {
                    if (Schema::hasTable('settings')) {
                        $settings->google_site_verification = Setting::get('google_search_console') ?: null;
                    }
                }
                if ($settings && empty($settings->bing_site_verification)) {
                    if (Schema::hasTable('settings')) {
                        $settings->bing_site_verification = Setting::get('bing_webmaster') ?: null;
                    }
                }

                View::share('settings', $settings);
            }
        } catch (\Exception $e) {
            // DB may not be ready during migration or early install
        }
    }
}
