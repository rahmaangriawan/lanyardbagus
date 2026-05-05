<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SiteSettingController extends Controller
{
    public function show(): JsonResponse
    {
        if (! Schema::hasTable('site_settings')) {
            return response()->json($this->defaultPayload());
        }

        return response()->json($this->serialize($this->getSingleton()));
    }

    public function update(Request $request): JsonResponse
    {
        if (! Schema::hasTable('site_settings')) {
            return response()->json([
                'message' => 'Tabel site_settings belum tersedia. Jalankan migration terlebih dahulu.',
            ], 503);
        }

        $this->ensureHomepageDataColumn();

        $data = $request->validate([
            'site_title'                => ['nullable', 'string', 'max:255'],
            'whatsapp_number'           => ['nullable', 'string', 'max:255'],
            'homepage_seo_title'        => ['nullable', 'string', 'max:255'],
            'homepage_meta_description' => ['nullable', 'string', 'max:500'],
            'homepage_og_image_id'      => ['nullable', 'exists:media,id'],
            'logo_image_id'             => ['nullable', 'exists:media,id'],
            'favicon_image_id'          => ['nullable', 'exists:media,id'],
            'homepage_data'             => ['nullable', 'array'],
            'google_site_verification'  => ['nullable', 'string', 'max:255'],
            'bing_site_verification'    => ['nullable', 'string', 'max:255'],
        ]);

        $data['google_site_verification'] = $this->cleanVerification($data['google_site_verification'] ?? null);
        $data['bing_site_verification']   = $this->cleanVerification($data['bing_site_verification'] ?? null);

        $settings = $this->getSingleton();
        $settings->fill($data);
        $settings->save();
        $settings->load(['homepageOgImage:id,url,file_name', 'logoImage:id,url,file_name', 'faviconImage:id,url,file_name']);

        // Clear caches so verification meta tags appear immediately
        \Illuminate\Support\Facades\Cache::flush();

        return response()->json($this->serialize($settings));
    }

    private function ensureHomepageDataColumn(): void
    {
        if (Schema::hasTable('site_settings') && ! Schema::hasColumn('site_settings', 'homepage_data')) {
            Schema::table('site_settings', function ($table) {
                $table->json('homepage_data')->nullable()->after('favicon_image_id');
            });
        }
    }

    private function getSingleton(): SiteSetting
    {
        return SiteSetting::with(['homepageOgImage:id,url,file_name', 'logoImage:id,url,file_name', 'faviconImage:id,url,file_name'])->firstOrCreate(
            ['id' => 1],
            []
        );
    }

    private function serialize(SiteSetting $settings): array
    {
        return [
            'site_title'                => $settings->site_title,
            'whatsapp_number'           => $settings->whatsapp_number,
            'homepage_seo_title'        => $settings->homepage_seo_title,
            'homepage_meta_description' => $settings->homepage_meta_description,
            'homepage_og_image_id'      => $settings->homepage_og_image_id,
            'homepage_og_image'         => $settings->homepageOgImage ? [
                'id'        => $settings->homepageOgImage->id,
                'url'       => $settings->homepageOgImage->url,
                'file_name' => $settings->homepageOgImage->file_name,
            ] : null,
            'logo_image_id'             => $settings->logo_image_id,
            'logo_image'                => $settings->logoImage ? [
                'id'        => $settings->logoImage->id,
                'url'       => $settings->logoImage->url,
                'file_name' => $settings->logoImage->file_name,
            ] : null,
            'favicon_image_id'          => $settings->favicon_image_id,
            'favicon_image'             => $settings->faviconImage ? [
                'id'        => $settings->faviconImage->id,
                'url'       => $settings->faviconImage->url,
                'file_name' => $settings->faviconImage->file_name,
            ] : null,
            'homepage_data'             => $settings->homepage_data ?? null,
            'google_site_verification'  => $settings->google_site_verification,
            'bing_site_verification'    => $settings->bing_site_verification,
        ];
    }

    private function cleanVerification(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        // Strip full HTML meta tag if user pasted the whole thing
        if (str_starts_with($value, '<meta')) {
            if (preg_match('/content=["\']([^"\']+)["\']/', $value, $matches)) {
                $value = $matches[1];
            }
        }

        // Strip common prefixes like google-site-verification=... or msvalidate.01=...
        $value = preg_replace('/^(google-site-verification|msvalidate\.01)=/i', '', $value);

        // Strip quotes and extra whitespace
        $value = trim($value, " \t\n\r\0\x0B\"'");

        return $value === '' ? null : $value;
    }

    private function defaultPayload(): array
    {
        return [
            'site_title'                => null,
            'whatsapp_number'           => null,
            'homepage_seo_title'        => null,
            'homepage_meta_description' => null,
            'homepage_og_image_id'      => null,
            'homepage_og_image'         => null,
            'logo_image_id'             => null,
            'logo_image'                => null,
            'favicon_image_id'          => null,
            'favicon_image'             => null,
            'homepage_data'             => null,
            'google_site_verification'  => null,
            'bing_site_verification'    => null,
        ];
    }
}
