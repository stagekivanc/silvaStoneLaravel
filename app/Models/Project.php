<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory, \App\Traits\Translatable;

    protected $translatable = [
        'title', 'slug', 'lead', 'body', 'feats', 'seo_title', 'seo_description',
    ];

    protected $fillable = [
        'slug', 'place', 'type', 'city', 'city_label', 'product_name',
        'year', 'area', 'main_image', 'gallery', 'order', 'status', 'home_status',
        'title', 'lead', 'body', 'feats', 'seo_title', 'seo_description',
    ];

    protected $casts = [
        'gallery' => 'array',
        'feats' => 'array',
        'status' => 'boolean',
        'home_status' => 'boolean',
        'order' => 'integer',
    ];

    public function typeRelation()
    {
        return $this->belongsTo(ProjectType::class, 'type', 'slug');
    }

    public function placeRelation()
    {
        return $this->belongsTo(ProjectPlace::class, 'place', 'slug');
    }

  public function cityLabel(): string
    {
        return ProjectCity::optionsMap()[$this->city]
            ?? (string) ($this->city_label ?: $this->city);
    }

    public function placeLabel(): string
    {
        return ProjectPlace::optionsMap()[$this->place]
            ?? ($this->placeRelation?->name ?: (string) $this->place);
    }

    public function typeLabel(): string
    {
        return ProjectType::optionsMap()[$this->type]
            ?? ($this->typeRelation?->name ?: (string) $this->type);
    }

    public function getImageUrlAttribute(): ?string
    {
        $url = self::mediaUrl($this->main_image);

        return $url !== '' ? $url : null;
    }

    public function galleryUrls(): array
    {
        $urls = [];

        if ($this->image_url) {
            $urls[] = $this->image_url;
        }

        foreach ((array) ($this->gallery ?? []) as $item) {
            $path = is_array($item)
                ? (string) ($item['path'] ?? $item['url'] ?? $item['image'] ?? $item['src'] ?? '')
                : (string) $item;
            $url = self::mediaUrl($path);
            if ($url !== '' && ! in_array($url, $urls, true)) {
                $urls[] = $url;
            }
        }

        return $urls;
    }

    public function featureList(): array
    {
        $feats = $this->feats;
        $items = [];

        if (! is_array($feats)) {
            return $items;
        }

        foreach ($feats as $item) {
            if (is_string($item) && trim($item) !== '') {
                $items[] = trim($item);
            } elseif (is_array($item)) {
                $text = trim((string) ($item['title'] ?? $item['text'] ?? $item['value'] ?? ''));
                if ($text !== '') {
                    $items[] = $text;
                }
            }
        }

        return array_values(array_unique($items));
    }

    public function detailUrl(?string $lang = null): string
    {
        $lang = $lang ?: app()->getLocale();
        $slug = $this->translate($lang)?->slug ?: $this->slug;

        return route('module.dispatcher', [
            'lang' => $lang,
            'module' => 'proje',
            'slug' => $slug,
        ]);
    }

    public function toFrontendArray(?string $lang = null): array
    {
        $lang = $lang ?: app()->getLocale();
        $translation = $this->translate($lang);
        $gallery = $this->galleryUrls();

        return [
            'id' => $this->slug,
            'slug' => $translation?->slug ?: $this->slug,
            'title' => $translation?->title ?: $this->title,
            'type' => $this->type,
            'place' => $this->place,
            'city' => $this->city,
            'cityLabel' => $this->cityLabel(),
            'product' => $this->product_name,
            'year' => $this->year,
            'area' => $this->area,
            'img' => $gallery[0] ?? ($this->image_url ?: ''),
            'imgs' => $gallery,
            'lead' => $translation?->lead ?: '',
            'body' => $translation?->body ?: '',
            'feats' => $this->featureList(),
            'url' => $this->detailUrl($lang),
        ];
    }

    public static function mediaUrl(?string $path): string
    {
        $path = trim((string) $path);
        if ($path === '') {
            return '';
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        $normalized = ltrim($path, '/');

        if (str_starts_with($normalized, 'assets/') || str_starts_with($normalized, 'css/') || str_starts_with($normalized, 'js/')) {
            return silva_asset($normalized);
        }

        if (str_starts_with($normalized, 'silvastone/')) {
            return homepage_media_url($normalized);
        }

        return homepage_media_url($normalized);
    }
}
