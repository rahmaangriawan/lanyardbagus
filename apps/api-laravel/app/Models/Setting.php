<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group'];

    public static function get(string $key, $default = null)
    {
        return static::where('key', $key)->first()?->value ?? $default;
    }

    public static function set(string $key, $value, string $group = 'general')
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }

    public static function getByGroup(string $group)
    {
        return static::where('group', $group)->pluck('value', 'key')->toArray();
    }
}
