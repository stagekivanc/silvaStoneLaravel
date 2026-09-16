<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageTranslation;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductCategoryTranslation;
use App\Models\ProductTranslation;
use App\Support\SilvaProductsDefaults;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SilvaProductsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = SilvaProductsDefaults::data();

        $page = Page::updateOrCreate(
            ['type' => 'products'],
            [
                'slug' => 'urunler',
                'name' => 'Ürünler',
                'title' => 'Yüzeyler',
                'seo_title' => 'Ürünler | Silva Stone Dekoratif Taş Duvar Panelleri',
                'seo_description' => 'Silva Stone Stonex ve Stoneart dekoratif taş duvar panelleri. 600×1200 mm, 3–6 mm, iç ve dış mekân.',
                'seo_keywords' => 'Silva Stone, dekoratif taş duvar paneli, Stonex, Stoneart',
                'extras' => $defaults,
            ]
        );

        PageTranslation::updateOrCreate(
            ['page_id' => $page->id, 'lang_key' => 'tr'],
            [
                'slug' => 'urunler',
                'name' => 'Ürünler',
                'title' => 'Yüzeyler',
                'seo_title' => 'Ürünler | Silva Stone Dekoratif Taş Duvar Panelleri',
                'seo_description' => 'Silva Stone Stonex ve Stoneart dekoratif taş duvar panelleri.',
                'seo_keywords' => 'Silva Stone, Stonex, Stoneart',
                'extras' => $defaults,
            ]
        );

        PageTranslation::updateOrCreate(
            ['page_id' => $page->id, 'lang_key' => 'en'],
            [
                'slug' => 'products',
                'name' => 'Products',
                'title' => 'Surfaces',
                'seo_title' => 'Products | Silva Stone Decorative Wall Panels',
                'seo_description' => 'Silva Stone Stonex and Stoneart decorative stone wall panels.',
                'seo_keywords' => 'Silva Stone, Stonex, Stoneart',
                'extras' => $defaults,
            ]
        );

        $categories = [
            ['slug' => 'stonex', 'name' => 'Stonex', 'order' => 1],
            ['slug' => 'stoneart', 'name' => 'Stoneart', 'order' => 2],
        ];

        $categoryIds = [];
        foreach ($categories as $cat) {
            $model = ProductCategory::updateOrCreate(
                ['slug' => $cat['slug']],
                [
                    'name' => $cat['name'],
                    'order' => $cat['order'],
                    'status' => true,
                    'home_status' => true,
                ]
            );
            foreach (['tr', 'en'] as $lang) {
                ProductCategoryTranslation::updateOrCreate(
                    ['product_category_id' => $model->id, 'lang_key' => $lang],
                    [
                        'name' => $cat['name'],
                        'slug' => $cat['slug'],
                    ]
                );
            }
            $categoryIds[$cat['slug']] = $model->id;
        }

        $payloadPath = database_path('data/silva_products.json');
        if (! is_file($payloadPath)) {
            $this->command?->warn('silva_products.json bulunamadı, ürün seed atlandı.');

            return;
        }

        $payload = json_decode(file_get_contents($payloadPath), true) ?: [];
        $products = $payload['products'] ?? [];
        $featured = array_flip($payload['featured'] ?? []);

        foreach ($products as $index => $item) {
            $catSlug = $item['cat'] ?? 'stonex';
            $categoryId = $categoryIds[$catSlug] ?? reset($categoryIds);
            $code = trim((string) ($item['code'] ?? ''));
            $name = trim((string) ($item['name'] ?? $code));
            $slugBase = Str::slug($code ?: $name) ?: ('urun-' . ($index + 1));

            $main = SilvaProductsDefaults::normalizeMedia($item['img'] ?? null);
            $hover = SilvaProductsDefaults::normalizeMedia($item['imgHover'] ?? null);
            $gallery = [];
            foreach ((array) ($item['imgs'] ?? []) as $img) {
                $normalized = SilvaProductsDefaults::normalizeMedia($img);
                if ($normalized) {
                    $gallery[] = $normalized;
                }
            }

            $product = Product::updateOrCreate(
                ['sku' => $code !== '' ? $code : $slugBase],
                [
                    'category_id' => $categoryId,
                    'name' => $name,
                    'slug' => $slugBase,
                    'color' => $item['color'] ?? null,
                    'panel_size' => $item['size'] ?? '600x1200',
                    'size_extra' => $item['sizeExtra'] ?? null,
                    'thick' => $item['thick'] ?? null,
                    'indoor' => (bool) ($item['indoor'] ?? true),
                    'outdoor' => (bool) ($item['outdoor'] ?? true),
                    'depot' => (bool) ($item['depot'] ?? true),
                    'main_image' => $main,
                    'hover_image' => $hover,
                    'source_url' => $item['url'] ?? null,
                    'gallery' => $gallery,
                    'order' => $index + 1,
                    'status' => true,
                    'home_status' => isset($featured[$code]),
                    'short_description' => null,
                    'description' => null,
                ]
            );

            foreach (['tr', 'en'] as $lang) {
                ProductTranslation::updateOrCreate(
                    ['product_id' => $product->id, 'lang_key' => $lang],
                    [
                        'name' => $name,
                        'slug' => $slugBase,
                        'seo_title' => preg_replace('/\s+Duvar Paneli$/u', '', $name) . ' | Silva Stone',
                        'seo_description' => $name . ' — Silva Stone dekoratif taş duvar paneli.',
                    ]
                );
            }
        }
    }
}
