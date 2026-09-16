<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectCity extends Model
{
    use \App\Traits\Translatable;

    protected $translatable = ['name'];

    protected $fillable = ['slug', 'order', 'status', 'name'];

    protected $casts = [
        'status' => 'boolean',
        'order' => 'integer',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'city', 'slug');
    }

    public static function optionsMap(?string $lang = null, bool $activeOnly = true): array
    {
        $lang = $lang ?: app()->getLocale();
        $query = static::query()->with('translations')->orderBy('order')->orderBy('id');
        if ($activeOnly) {
            $query->where('status', true);
        }

        $map = [];
        foreach ($query->get() as $item) {
            $map[$item->slug] = $item->translate($lang)?->name ?: $item->name ?: $item->slug;
        }

        return $map;
    }

    public static function filterOptions(?string $lang = null): array
    {
        $lang = $lang ?: app()->getLocale();
        $items = [];
        foreach (static::optionsMap($lang) as $id => $label) {
            $items[] = ['id' => $id, 'label' => $label];
        }

        return $items;
    }
}
