<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFeature extends Model
{
    use \App\Traits\Translatable;

    protected $translatable = ['name'];

    protected $fillable = ['slug', 'filter_key', 'filter_value', 'order', 'status', 'name'];

    protected $casts = [
        'status' => 'boolean',
        'order' => 'integer',
    ];

    public const FILTER_KEYS = [
        'size' => 'Ebat',
        'thick' => 'Kalınlık',
        'indoor' => 'İç mekân',
        'outdoor' => 'Dış mekân',
        'depot' => 'Stok',
    ];

    public static function activeOrdered(?string $lang = null)
    {
        $lang = $lang ?: app()->getLocale();

        return static::query()
            ->with('translations')
            ->where('status', true)
            ->orderBy('order')
            ->orderBy('id')
            ->get()
            ->each(function (self $item) use ($lang) {
                $item->setRelation('translations', $item->translations);
                $item->resolved_name = $item->translate($lang)?->name ?: $item->name ?: $item->slug;
            });
    }

    public static function labelMap(?string $lang = null, bool $activeOnly = true): array
    {
        $lang = $lang ?: app()->getLocale();
        $query = static::query()->with('translations')->orderBy('order')->orderBy('id');
        if ($activeOnly) {
            $query->where('status', true);
        }

        $map = [];
        foreach ($query->get() as $item) {
            $map[$item->slug] = $item->translate($lang)?->name ?: $item->name ?: $item->slug;
            $map[$item->filter_key] = $map[$item->filter_key] ?? ($item->translate($lang)?->name ?: $item->name);
            if (in_array($item->filter_key, ['indoor', 'outdoor', 'depot'], true)) {
                $map[$item->filter_key] = $item->translate($lang)?->name ?: $item->name ?: $item->slug;
            }
        }

        return $map;
    }

    public static function labelFor(string $slugOrKey, ?string $lang = null, string $fallback = ''): string
    {
        $map = self::labelMap($lang);

        return $map[$slugOrKey] ?? $fallback;
    }

    public function filterKeyLabel(): string
    {
        return self::FILTER_KEYS[$this->filter_key] ?? $this->filter_key;
    }
}
