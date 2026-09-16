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
            $languages = Language::active();
            $seedValue = $default !== '' ? $default : $key;
            foreach ($languages as $l) {
                self::firstOrCreate(
                    ['lang_key' => $l->code, 'group' => $group, 'key' => $key],
                    ['value' => $seedValue]
                );
            }
            return $seedValue;
        }

        $value = trim((string) ($translation->value ?? ''));
        if ($value !== '') {
            return $value;
        }

        return $default !== '' ? $default : $key;
    }
}
