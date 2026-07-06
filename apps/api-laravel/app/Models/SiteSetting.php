<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected static function booted(): void
    {
        static::saved(fn () => self::clearCachedPayloads());
        static::deleted(fn () => self::clearCachedPayloads());
    }

    public static function clearCachedPayloads(): void
    {
        Cache::forget('site_settings_global');
        Cache::forget('setting_google_search_console');
        Cache::forget('setting_bing_webmaster');
        Cache::forget('homepage_data');
    }

    protected $fillable = [
        'site_title',
        'whatsapp_number',
        'homepage_seo_title',
        'homepage_meta_description',
        'homepage_og_image_id',
        'logo_image_id',
        'favicon_image_id',
        'homepage_data',
        'faq_data',
        'google_site_verification',
        'bing_site_verification',
    ];

    protected $casts = [
        'homepage_data' => 'array',
        'faq_data' => 'array',
    ];

    public function homepageOgImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'homepage_og_image_id');
    }

    public function logoImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'logo_image_id');
    }

    public function faviconImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'favicon_image_id');
    }
}
