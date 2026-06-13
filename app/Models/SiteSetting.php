<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group', 'label'];

    protected static function tableExists(): bool
    {
        try {
            return Schema::hasTable((new static)->getTable());
        } catch (\Throwable) {
            return false;
        }
    }

    public static function get(string $key, $default = null)
    {
        if (! static::tableExists()) {
            return $default;
        }

        try {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        } catch (\Throwable) {
            return $default;
        }
    }

    public static function set(string $key, $value): void
    {
        if (! static::tableExists()) {
            return;
        }

        try {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        } catch (\Throwable) {
            // Table may not exist yet
        }
    }
}