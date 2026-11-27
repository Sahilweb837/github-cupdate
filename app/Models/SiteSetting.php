<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Throwable;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value', 'group'];

    public static function getValue(string $key, ?string $default = null): ?string
    {
        try {
            if (! Schema::hasTable('site_settings')) {
                return $default;
            }

            return static::query()->where('key', $key)->value('value') ?? $default;
        } catch (Throwable) {
            return $default;
        }
    }
}
