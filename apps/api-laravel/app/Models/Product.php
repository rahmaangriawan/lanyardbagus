<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'slug',
        'name',
        'description',
        'price',
        'min_order',
        'specs',
        'images',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'specs' => 'array',
        'images' => 'array',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id')->orderBy('sort_order');
    }

    public function accessories()
    {
        return $this->hasMany(ProductAccessory::class, 'product_id')->orderBy('sort_order');
    }

    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
}
