<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, \App\Traits\Translatable;

    protected $translatable = [
        'name', 'slug', 'title', 'short_description', 'description',
        'why_title', 'certificates_intro', 'advantages_intro',
        'cta_title', 'cta_text', 'features', 'technical_specs', 'certificates',
        'advantages', 'seo_title', 'seo_description',
    ];

    protected $fillable = [
        'category_id', 'name', 'slug', 'sku', 'color', 'panel_size', 'size_extra', 'thick',
        'indoor', 'outdoor', 'depot', 'title', 'short_description', 'description',
        'main_image', 'hover_image', 'source_url', 'gallery', 'documents', 'features',
        'technical_specs', 'certificates', 'advantages', 'order', 'status', 'home_status',
        'badge', 'menu_order', 'seo_title', 'seo_description',
    ];

    protected $casts = [
        'gallery' => 'array',
        'documents' => 'array',
        'features' => 'array',
        'technical_specs' => 'array',
        'certificates' => 'array',
        'advantages' => 'array',
        'status' => 'boolean',
        'home_status' => 'boolean',
        'indoor' => 'boolean',
        'outdoor' => 'boolean',
        'depot' => 'boolean',
        'menu_order' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function colorRelation()
    {
        return $this->belongsTo(ProductColor::class, 'color', 'slug');
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

    public function displayName(): string
    {
        $name = trim((string) ($this->name ?? ''));

        return preg_replace('/\s+Duvar Paneli$/u', '', $name) ?: $name;
    }

    public function detailUrl(?string $lang = null): string
    {
        $lang = $lang ?: app()->getLocale();
        $slug = $this->translate($lang)?->slug ?: $this->slug;

        return route('module.dispatcher', [
            'lang' => $lang,
            'module' => 'urun',
            'slug' => $slug,
        ]);
    }

    public function toFrontendArray(?string $lang = null): array
    {
        $lang = $lang ?: app()->getLocale();
        $translation = $this->translate($lang);
        $gallery = $this->galleryUrls();
        $main = $gallery[0] ?? (self::mediaUrl($this->main_image) ?: '');
        $hover = self::mediaUrl($this->hover_image);
        $catSlug = $this->category?->slug ?: '';

        return [
            'id' => $this->id,
            'cat' => $catSlug,
            'name' => $translation?->name ?: $this->name,
            'title' => $this->displayName(),
            'code' => $this->sku ?: ($translation?->slug ?: $this->slug),
            'slug' => $translation?->slug ?: $this->slug,
            'color' => $this->color,
            'img' => $main,
            'imgs' => $gallery !== [] ? $gallery : array_filter([$main]),
            'imgHover' => $hover ?: $main,
            'url' => $this->source_url,
            'size' => $this->panel_size,
            'sizeExtra' => $this->size_extra,
            'thick' => $this->thick,
            'indoor' => (bool) $this->indoor,
            'outdoor' => (bool) $this->outdoor,
            'depot' => (bool) $this->depot,
            'href' => $this->detailUrl($lang),
            'lead' => $translation?->short_description ?: '',
            'body' => $translation?->description ?: '',
        ];
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

    public function skuCode(): string
    {
        $sku = trim((string) ($this->getAttributes()['sku'] ?? $this->getAttribute('sku') ?? ''));
        if ($sku !== '') {
            return $sku;
        }

        return 'TRK-' . str_pad((string) $this->id, 3, '0', STR_PAD_LEFT);
    }

    public function badgeKey(): string
    {
        $badge = trim((string) ($this->getAttributes()['badge'] ?? $this->getAttribute('badge') ?? ''));
        if ($badge === '') {
            return '';
        }

        static $valid = null;
        if ($valid === null) {
            try {
                $valid = ProductBadge::query()->where('status', true)->pluck('slug')->all();
            } catch (\Throwable $e) {
                $valid = ['yeni', 'cok-satan', 'indirim'];
            }
        }

        return in_array($badge, $valid, true) ? $badge : '';
    }

    public function badgeLabel(?string $lang = null): string
    {
        $key = $this->badgeKey();
        if ($key === '') {
            return '';
        }

        return ProductBadge::optionsMap($lang)[$key] ?? $key;
    }

    public function isFootwear(): bool
    {
        $category = $this->category;
        if ($category && $category->parent_id && ! $category->relationLoaded('parent')) {
            $category->load('parent');
        }

        $haystack = mb_strtolower(trim(implode(' ', [
            (string) ($category?->slug ?? ''),
            (string) ($category?->name ?? ''),
            (string) ($category?->parent?->slug ?? ''),
            (string) ($category?->parent?->name ?? ''),
            (string) ($this->slug ?? ''),
        ])));

        return str_contains($haystack, 'ayakkabi')
            || str_contains($haystack, 'ayakkabı')
            || str_contains($haystack, 'shoe')
            || str_contains($haystack, 'terlik');
    }

    public function sizeOptions(): array
    {
        return $this->isFootwear()
            ? ['40', '41', '42', '43', '44', '45']
            : ['S', 'M', 'L', 'XL', 'XXL'];
    }

    public function colorOptions(): array
    {
        $color = trim((string) ($this->getAttributes()['color'] ?? ''));
        if ($color !== '') {
            return [$this->normalizeColorSlug($color) ?: $color];
        }

        return ['siyah', 'lacivert', 'gri'];
    }

    public function colorHexMap(): array
    {
        return array_merge([
            'siyah' => '#111111',
            'beyaz' => '#ffffff',
            'lacivert' => '#1e3a5f',
            'gri' => '#9ca3af',
            'haki' => '#8b8b5e',
            'turuncu' => '#f97316',
            'bordo' => '#7f1d1d',
            'kirmizi' => '#dc2626',
            'kırmızı' => '#dc2626',
            'turkuaz' => '#14b8a6',
            'sari' => '#d4ff00',
            'sarı' => '#d4ff00',
        ], ProductColor::hexMap(false));
    }

    public function materialLabel(): string
    {
        $specs = is_array($this->technical_specs) ? $this->technical_specs : [];
        foreach ((array) ($specs['rows'] ?? []) as $row) {
            $label = mb_strtolower((string) ($row['label'] ?? ''));
            $value = trim((string) (is_array($row['values'] ?? null) ? ($row['values'][0] ?? '') : ($row['value'] ?? '')));
            if ($value !== '' && (str_contains($label, 'malzeme') || str_contains($label, 'kumaş') || str_contains($label, 'kumas') || str_contains($label, 'material'))) {
                return $value;
            }
        }

        return '%65 Polyester, %35 Pamuk';
    }

    public function featureList(): array
    {
        $features = $this->features;
        $items = [];

        if (is_array($features) && isset($features['items']) && is_array($features['items'])) {
            foreach ($features['items'] as $item) {
                $text = is_array($item) ? trim((string) ($item['title'] ?? $item['text'] ?? '')) : trim((string) $item);
                if ($text !== '') {
                    $items[] = $text;
                }
            }
        } elseif (is_array($features)) {
            foreach ($features as $item) {
                if (is_string($item) && trim($item) !== '') {
                    $items[] = trim($item);
                } elseif (is_array($item)) {
                    $text = trim((string) ($item['title'] ?? $item['text'] ?? $item['value'] ?? ''));
                    if ($text !== '') {
                        $items[] = $text;
                    }
                }
            }
        }

        if ($items !== []) {
            return array_values(array_unique($items));
        }

        return [
            'Kurumsal logo baskı ve nakış uygulamalarına uygun',
            'Dayanıklı dikiş ve uzun ömürlü kumaş',
            'Toptan siparişlerde özel fiyatlandırma',
            'Stoktan hızlı teslimat imkânı',
        ];
    }

    private function normalizeColorSlug(string $value): string
    {
        $slug = mb_strtolower(trim($value));
        $slug = strtr($slug, ['ı' => 'i', 'İ' => 'i', 'ş' => 's', 'Ş' => 's', 'ğ' => 'g', 'Ğ' => 'g', 'ü' => 'u', 'Ü' => 'u', 'ö' => 'o', 'Ö' => 'o', 'ç' => 'c', 'Ç' => 'c']);

        return preg_replace('/[^a-z0-9]+/', '', $slug) ?: $slug;
    }
}
