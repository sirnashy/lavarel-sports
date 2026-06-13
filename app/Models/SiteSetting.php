<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'label'];

    public static function get(string $key, $default = null)
    {
        try {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        } catch (\Throwable) {
            return $default;
        }
    }

    public static function set(string $key, $value): void
    {
        try {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        } catch (\Throwable) {
            // Table may not exist yet
        }
    }
}