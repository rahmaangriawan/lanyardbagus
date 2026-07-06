<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

class Post extends Model
{
    protected static function booted(): void
    {
        static::updating(function (Post $post) {
            if ($post->isDirty('slug')) {
                Cache::forget("post_show_{$post->getOriginal('slug')}");
            }
        });

        static::saved(fn (Post $post) => self::clearCachedPayloads($post));
        static::deleted(fn (Post $post) => self::clearCachedPayloads($post));
    }

    public static function clearCachedPayloads(?Post $post = null): void
    {
        Cache::forget('posts_public_list');
        Cache::forget('homepage_latest_posts');

        if ($post?->slug) {
            Cache::forget("post_show_{$post->slug}");
        }
    }

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content',
        'status', 'published_at', 'author_id', 'category_id',
        'seo_title', 'meta_description', 'featured_image_id',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'PUBLISHED');
    }

}
