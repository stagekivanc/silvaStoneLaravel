<?php

namespace App\Support\Concerns;

use App\Support\EnglishDefaults;

trait HasLocalizedDefaults
{
    public static function data(?string $lang = null): array
    {
        $lang = $lang ?? app()->getLocale() ?? 'tr';
        $data = static::baseData();

        if (str_starts_with($lang, 'en')) {
            $overrides = EnglishDefaults::for(static::class);

            if (!empty($overrides)) {
                $data = array_replace_recursive($data, $overrides);
            }
        }

        return $data;
    }

    protected static function baseData(): array
    {
        return [];
    }
}
