<?php

namespace App\Traits;

use Illuminate\Support\Facades\App;

trait Translatable
{
    public function translations()
    {
        $translationModel = $this->getTranslationModelName();
        return $this->hasMany($translationModel, $this->getForeignKey());
    }

    public function translate($lang = null)
    {
        $lang = $lang ?? App::getLocale();
        return $this->translations()->where('lang_key', $lang)->first();
    }

    public function getAttribute($key)
    {
        // Check if the attribute is translatable
        if (in_array($key, $this->translatable ?? [])) {
            $translation = $this->translate();
            if ($translation && !empty($translation->$key)) {
                return $translation->$key;
            }
        }

        return parent::getAttribute($key);
    }

    public function scopeWhereTranslation($query, $column, $value, $lang = null)
    {
        $lang = $lang ?? App::getLocale();
        return $query->whereHas('translations', function ($q) use ($column, $value, $lang) {
            $q->where('lang_key', $lang)->where($column, $value);
        });
    }

    protected function getTranslationModelName()
    {
        return property_exists($this, 'translationModel') 
            ? $this->translationModel 
            : get_class($this) . 'Translation';
    }
}
