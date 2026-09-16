<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Ayarı anahtarına göre getirir.
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Aktif dile göre ayar getirir (ör. homepage_prod_title_en).
     */
    public static function getLocalized($key, $default = null)
    {
        $locale = app()->getLocale();

        if ($locale && $locale !== 'tr') {
            $localized = self::get($key . '_' . $locale);
            if ($localized !== null && $localized !== '') {
                return $localized;
            }
        }

        return self::get($key, $default);
    }

    /**
     * Ayarı günceller veya oluşturur.
     */
    public static function set($key, $value)
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
