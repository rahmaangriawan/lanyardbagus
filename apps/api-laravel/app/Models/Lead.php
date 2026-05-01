<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'whatsapp',
        'address',
        'email',
        'size',
        'message',
        'design_url',
    ];

    protected function designUrl(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: function (?string $value) {
                if (!$value) return $value;
                
                // Jika URL lama masih nyangkut, otomatis replace dengan yang baru
                if (str_contains($value, '.r2.dev')) {
                    $newBase = rtrim(\App\Models\Setting::get('r2_public_url', 'https://img.devjog.my.id'), '/');
                    $path = parse_url($value, PHP_URL_PATH);
                    return $newBase . $path;
                }
                
                return $value;
            }
        );
    }
}
