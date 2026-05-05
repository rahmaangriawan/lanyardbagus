<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAccessory extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'price',
        'image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'price' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
