<?php

namespace App\Support;

use App\Support\Concerns\HasLocalizedDefaults;

class SilvaProductsDefaults
{
    use HasLocalizedDefaults;

    protected static function baseData(): array
    {
        return [
            'intro' => [
                'kicker' => 'Koleksiyon',
                'title' => 'Yüzeyler',
                'aside' => 'Stonex traverten ve Stoneart desenli paneller. 600×1200 mm. İç ve dış mekân.',
            ],
            'filter' => [
                'all' => 'Tümü',
                'filter_label' => 'Filtre',
                'filter_title' => 'Filtrele',
                'color_label' => 'Renk',
                'feature_label' => 'Özellik',
                'reset' => 'Sıfırla',
                'apply' => 'Ürünleri gör',
                'empty' => 'Bu seçime uygun ürün yok.',
                'empty_reset' => 'Filtrelemeyi sıfırla',
                'count_suffix' => 'yüzey',
                'size_600x1200' => '600×1200',
                'thick_3_4' => '3–4 mm',
                'thick_3_6' => '3–6 mm',
                'indoor' => 'İç mekana uygun',
                'outdoor' => 'Dış mekana uygun',
                'depot' => 'Stokta',
            ],
            'seo' => [
                'kicker' => 'Rehber',
                'title' => 'Stonex ve Stoneart duvar panelleri',
                'more' => 'Devamını oku',
                'body' => [
                    'Silva Stone ürünleri iki koleksiyonda toplanır: Stonex ve Stoneart. Paneller 600×1200 mm ebatta üretilir; Stonex modelleri 3–4 mm, Stoneart modelleri 3–6 mm inceliktedir. İç ve dış mekâna uygundur. Depot stoklu modeller hemen teslim edilebilir.',
                    'Stonex koleksiyonu traverten ve doğal taş karakterli yüzeyleri; Stoneart koleksiyonu slate, mosaic, granite ve desenli panelleri kapsar. Renk filtresiyle beyaz, krem, bej, antrasit ve siyah tonları ayıklanır.',
                    'Stone duvar panelleri, iç ve dış mekânlarda doğal taş görünümü elde etmek için kullanılan dekoratif ve yüksek performanslı kaplamalardır. Gerçek taş dokusunu mekânına taşırken montaj kolaylığı ve hafiflik sunar; çevresel etkilere karşı dayanıklı yapılarıyla uzun ömürlü bir yüzey oluşturur.',
                    'Numune, metraj ve uygulama için Acarkon Store ağı veya iletişim formu üzerinden ekibe ulaşın. Özel sipariş ölçüleri 1200×2400 ve 1200×3000 mm olarak da talep edilebilir.',
                ],
            ],
            'detail' => [
                'related_kicker' => 'Koleksiyon',
                'related_title' => 'Benzer yüzeyler',
                'related_from' => ':cat koleksiyonundan',
                'spec_code' => 'Ürün kodu',
                'spec_collection' => 'Koleksiyon',
                'spec_color' => 'Renk',
                'spec_size' => 'Ölçü',
                'spec_thick' => 'İncelik',
                'spec_extra' => 'Özel sipariş',
                'mm' => 'mm',
                'indoor' => 'İç mekana uygun',
                'outdoor' => 'Dış mekana uygun',
                'depot' => 'Stokta',
                'add_cart' => 'Sepete ekle',
                'quote' => 'Teklif al',
                'qty' => 'Adet',
                'image_pending' => 'Ürün resmi hazırlanıyor',
                'collection_crumb' => 'Koleksiyon',
            ],
        ];
    }

    public static function formatSize(?string $size): string
    {
        $size = trim((string) $size);
        if ($size === '') {
            return '600×1200';
        }

        return str_replace(['x', 'X'], '×', $size);
    }

    public static function formatThick(?string $thick): string
    {
        return str_replace('-', '–', trim((string) $thick));
    }

    public static function normalizeMedia(?string $path): ?string
    {
        $path = trim((string) $path);
        if ($path === '') {
            return null;
        }
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        $normalized = ltrim($path, '/');
        if (str_starts_with($normalized, 'silvastone/')) {
            return $normalized;
        }
        if (str_starts_with($normalized, 'assets/')) {
            return 'silvastone/' . $normalized;
        }

        return $normalized;
    }
}
