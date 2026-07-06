<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Banner extends Model
{
    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('homepage_active_banners'));
        static::deleted(fn () => Cache::forget('homepage_active_banners'));
    }

    protected $fillable = [
        'title',
        'description',
        'badge',
        'image_url',
        'card_image_url',
        'link_url',
        'button_text',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];
}
