<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaticTranslation extends Model
{
    protected $fillable = ['lang_key', 'group', 'key', 'value'];

    public static function get($key, $default = '', $group = 'general', $lang = null)
    {
        $lang = $lang ?? app()->getLocale();
        $translation = self::where('lang_key', $lang)
            ->where('group', $group)
            ->where('key', $key)
            ->first();

        if (!$translation) {
            return self::firstOrCreate(
                ['lang_key' => $lang, 'group' => $group, 'key' => $key],
                ['value' => $default !== '' ? $default : $key]
            )->value ?: ($default !== '' ? $default : $key);
        }

        $value = trim((string) ($translation->value ?? ''));
        if ($value !== '') {
            return $value;
        }

        return $default !== '' ? $default : $key;
    }
}
