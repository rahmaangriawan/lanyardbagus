<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteSetting extends Model
{
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
