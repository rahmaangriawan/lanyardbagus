<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
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
                $settings = Cache::remember('site_settings_global', 3600, function () {
                    return SiteSetting::with(['logoImage:id,url,file_name', 'faviconImage:id,url,file_name'])->first();
                });

                // Fallback: if site_settings verification is empty, try system settings
                if ($settings && empty($settings->google_site_verification)) {
                    if (Schema::hasTable('settings')) {
                        $settings->google_site_verification = Cache::remember('setting_google_search_console', 3600, fn () => Setting::get('google_search_console') ?: null);
                    }
                }
                if ($settings && empty($settings->bing_site_verification)) {
                    if (Schema::hasTable('settings')) {
                        $settings->bing_site_verification = Cache::remember('setting_bing_webmaster', 3600, fn () => Setting::get('bing_webmaster') ?: null);
                    }
                }

                View::share('settings', $settings);
            }
        } catch (\Exception $e) {
            // DB may not be ready during migration or early install
        }
    }
}
