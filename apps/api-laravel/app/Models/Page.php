<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = [
        'title', 'slug', 'content', 'is_published', 'is_indexed',
        'parent_id', 'seo_title', 'meta_description', 'featured_image_id',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'is_indexed'   => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Page::class, 'parent_id');
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
