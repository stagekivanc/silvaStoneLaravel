<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageTranslation;
use App\Models\Product;
use App\Models\ProductColorTranslation;
use App\Models\ProductTranslation;
use App\Models\Project;
use App\Models\ProjectCityTranslation;
use App\Models\ProjectPlaceTranslation;
use App\Models\ProjectTranslation;
use App\Models\ProjectTypeTranslation;
use App\Models\StaticTranslation;
use App\Support\SilvaContactDefaults;
use App\Support\SilvaContractsDefaults;
use App\Support\SilvaHomepageDefaults;
use App\Support\SilvaLegalDefaults;
use App\Support\SilvaProductsDefaults;
use App\Support\SilvaProjectsDefaults;
use App\Support\SilvaStoresDefaults;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SilvaEnglishContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedUiStrings();
        $this->seedRouteStrings();
        $this->seedPageExtras();
        $this->seedColors();
        $this->seedProjectTaxonomies();
        $this->seedProducts();
        $this->seedProjects();
    }

    private function seedUiStrings(): void
    {
        $rows = [
            'nav_features' => ['tr' => 'Özellikler', 'en' => 'Features'],
            'nav_collection' => ['tr' => 'Koleksiyon', 'en' => 'Collection'],
            'ui_showroom_cities' => ['tr' => 'Showroom şehirleri', 'en' => 'Showroom cities'],
            'nav_showroom' => ['tr' => 'Showroom', 'en' => 'Showroom'],
            'nav_projects' => ['tr' => 'Projeler', 'en' => 'Projects'],
            'nav_contact' => ['tr' => 'İletişim', 'en' => 'Contact'],
            'nav_catalog' => ['tr' => 'Online katalog', 'en' => 'Online catalog'],
            'nav_quote' => ['tr' => 'Teklif Al', 'en' => 'Get a quote'],
            'nav_menu' => ['tr' => 'Menu', 'en' => 'Menu'],
            'nav_close' => ['tr' => 'Kapat', 'en' => 'Close'],
            'nav_lang' => ['tr' => 'Dil', 'en' => 'Language'],
            'nav_spaces' => ['tr' => 'Mekânlar', 'en' => 'Spaces'],
            'footer_sales' => ['tr' => 'Satış', 'en' => 'Sales'],
            'footer_corporate' => ['tr' => 'Kurumsal', 'en' => 'Company'],
            'footer_about' => ['tr' => 'Hakkımızda', 'en' => 'About us'],
            'footer_brands' => ['tr' => 'Markalar', 'en' => 'Brands'],
            'footer_contracts' => ['tr' => 'Sözleşmeler', 'en' => 'Contracts'],
            'footer_dealer' => ['tr' => 'Bayi Ol', 'en' => 'Become a dealer'],
            'footer_stores_acarkon' => ['tr' => 'Acarkon Store', 'en' => 'Acarkon Store'],
            'footer_privacy' => ['tr' => 'Gizlilik', 'en' => 'Privacy'],
            'footer_cookies' => ['tr' => 'Çerezler', 'en' => 'Cookies'],
            'footer_kvkk' => ['tr' => 'KVKK', 'en' => 'KVKK'],
            'footer_cta_title' => ['tr' => 'Mekânınız için doğru yüzeyi seçin', 'en' => 'Choose the right surface for your space'],
            'footer_cta_text' => ['tr' => 'Katalogu inceleyin veya en yakın Acarkon Store’dan numune alın.', 'en' => 'Browse the catalog or get a sample from your nearest Acarkon Store.'],
            'footer_brand_text' => ['tr' => 'Silva Stone, Acarkon Orman Ürünleri ürün ailesinin dekoratif taş duvar paneli markasıdır.', 'en' => 'Silva Stone is the decorative stone wall panel brand of the Acarkon Forest Products family.'],
            'footer_contact' => ['tr' => 'İletişim', 'en' => 'Contact'],
            'ui_all' => ['tr' => 'Tümü', 'en' => 'All'],
            'ui_prev' => ['tr' => 'Önceki', 'en' => 'Previous'],
            'ui_next' => ['tr' => 'Sonraki', 'en' => 'Next'],
            'ui_prev_product' => ['tr' => 'Önceki ürün', 'en' => 'Previous product'],
            'ui_next_product' => ['tr' => 'Sonraki ürün', 'en' => 'Next product'],
            'ui_close' => ['tr' => 'Kapat', 'en' => 'Close'],
            'ui_directions' => ['tr' => 'Yol tarifi al', 'en' => 'Get directions'],
            'ui_enlarge_image' => ['tr' => 'Görseli büyüt', 'en' => 'Enlarge image'],
            'ui_projects' => ['tr' => 'Projeler', 'en' => 'Projects'],
            'ui_city' => ['tr' => 'Şehir', 'en' => 'City'],
            'ui_collection' => ['tr' => 'Koleksiyon', 'en' => 'Collection'],
            'ui_list_view' => ['tr' => 'Liste', 'en' => 'List'],
            'ui_grid_view' => ['tr' => 'Izgara', 'en' => 'Grid'],
            'ui_pages' => ['tr' => 'Sayfalar', 'en' => 'Pages'],
            'ui_display' => ['tr' => 'Gösterim', 'en' => 'Display'],
            'ui_decrease' => ['tr' => 'Azalt', 'en' => 'Decrease'],
            'ui_increase' => ['tr' => 'Artır', 'en' => 'Increase'],
            'ui_map_label' => ['tr' => 'Showroom konum haritası', 'en' => 'Showroom location map'],
            'ui_legal_nav' => ['tr' => 'Sözleşme sayfaları', 'en' => 'Contract pages'],
            'error_404_meta_title' => ['tr' => '404 - Sayfa Bulunamadı', 'en' => '404 - Page Not Found'],
            'error_404_badge' => ['tr' => 'Hata 404', 'en' => 'Error 404'],
            'error_404_heading' => ['tr' => 'Sayfa bulunamadı', 'en' => 'Page not found'],
            'error_404_description' => [
                'tr' => 'Aradığınız sayfa taşınmış, silinmiş veya hiç var olmamış olabilir. Ana sayfaya dönerek gezintiye devam edebilirsiniz.',
                'en' => 'The page you are looking for may have been moved, deleted, or never existed. Return home to continue browsing.',
            ],
            'error_404_home' => ['tr' => 'Ana Sayfa', 'en' => 'Home'],
            'error_404_products' => ['tr' => 'Ürünleri İncele', 'en' => 'Browse products'],
            'error_404_popular_pages' => ['tr' => 'Popüler Sayfalar', 'en' => 'Popular pages'],
            'error_404_link_products' => ['tr' => 'Tüm Ürünler', 'en' => 'All products'],
            'error_404_link_references' => ['tr' => 'Referanslar', 'en' => 'Projects'],
            'error_404_link_contact' => ['tr' => 'İletişim', 'en' => 'Contact'],
            'error_404_link_login' => ['tr' => 'Giriş Yap', 'en' => 'Log in'],
            'search_meta_title' => ['tr' => 'Arama Sonuçları | Silva Stone', 'en' => 'Search Results | Silva Stone'],
            'search_breadcrumb' => ['tr' => 'Arama', 'en' => 'Search'],
            'search_title' => ['tr' => 'Arama Sonuçları', 'en' => 'Search results'],
            'search_result_meta' => ['tr' => '“:query” için :count sonuç bulundu.', 'en' => ':count results found for “:query”.'],
            'search_hint' => [
                'tr' => 'Ürün adı, kategori veya ürün açıklamasıyla arama yapabilirsiniz.',
                'en' => 'Search by product name, collection or description.',
            ],
            'search_placeholder' => ['tr' => 'Ne aramıştınız?', 'en' => 'What are you looking for?'],
            'search_submit' => ['tr' => 'Ara', 'en' => 'Search'],
            'search_empty_prompt_title' => ['tr' => 'Arama yapın', 'en' => 'Start a search'],
            'search_empty_prompt_text' => [
                'tr' => 'Ürün adı, koleksiyon veya yüzey açıklaması yazarak arama yapabilirsiniz.',
                'en' => 'Type a product name, collection or surface description to search.',
            ],
            'search_empty_title' => ['tr' => 'Sonuç bulunamadı', 'en' => 'No results found'],
            'search_empty_description' => [
                'tr' => '“:query” araması için eşleşen sonuç yok. Farklı bir kelime deneyin.',
                'en' => 'No matches for “:query”. Try a different word.',
            ],
            'search_view_products' => ['tr' => 'Tüm Ürünleri Gör', 'en' => 'View all products'],
            'search_type_product' => ['tr' => 'Ürün', 'en' => 'Product'],
            'search_type_category' => ['tr' => 'Koleksiyon', 'en' => 'Collection'],
            'breadcrumb_aria_label' => ['tr' => 'İçerik yolu', 'en' => 'Breadcrumb'],
        ];

        foreach ($rows as $key => $values) {
            foreach ($values as $lang => $value) {
                StaticTranslation::updateOrCreate(
                    ['lang_key' => $lang, 'group' => 'frontend', 'key' => $key],
                    ['value' => $value]
                );
            }
        }
    }

    private function seedRouteStrings(): void
    {
        $routes = [
            'route_product' => ['tr' => 'urun', 'en' => 'product'],
            'route_products' => ['tr' => 'urunler', 'en' => 'products'],
            'route_products_all' => ['tr' => 'tum-urunler', 'en' => 'all-products'],
            'route_projects' => ['tr' => 'projeler', 'en' => 'projects'],
            'route_project' => ['tr' => 'proje', 'en' => 'project'],
            'route_contact' => ['tr' => 'iletisim', 'en' => 'contact'],
            'route_search' => ['tr' => 'arama', 'en' => 'search'],
            'route_stores' => ['tr' => 'magazalar', 'en' => 'stores'],
            'route_contracts' => ['tr' => 'sozlesmeler', 'en' => 'contracts'],
        ];

        foreach ($routes as $key => $values) {
            foreach ($values as $lang => $value) {
                StaticTranslation::updateOrCreate(
                    ['lang_key' => $lang, 'group' => 'routes', 'key' => $key],
                    ['value' => $value]
                );
            }
        }
    }

    private function seedPageExtras(): void
    {
        $map = [
            'index' => fn () => SilvaHomepageDefaults::data('en'),
            'contact' => fn () => SilvaContactDefaults::data('en'),
            'products' => fn () => SilvaProductsDefaults::data('en'),
            'projects' => fn () => SilvaProjectsDefaults::data('en'),
            'stores' => fn () => SilvaStoresDefaults::data('en'),
            'contracts' => fn () => SilvaContractsDefaults::data('en'),
        ];

        foreach ($map as $type => $resolver) {
            $page = Page::where('type', $type)->first();
            if (! $page) {
                continue;
            }
            $extras = $resolver();
            if ($type === 'stores') {
                $extras = $this->localizeStoreExtras($extras);
            }

            PageTranslation::updateOrCreate(
                ['page_id' => $page->id, 'lang_key' => 'en'],
                array_filter([
                    'extras' => $extras,
                    'name' => match ($type) {
                        'index' => 'Home',
                        'contact' => 'Contact',
                        'products' => 'Products',
                        'projects' => 'Projects',
                        'stores' => 'Showrooms',
                        'contracts' => 'Contracts',
                        default => null,
                    },
                    'title' => $type === 'index' ? 'Silva Stone' : match ($type) {
                        'contact' => 'Contact',
                        'products' => 'Products',
                        'projects' => 'Projects',
                        'stores' => 'Showrooms',
                        'contracts' => 'Contracts',
                        default => null,
                    },
                    'seo_title' => match ($type) {
                        'index' => 'Silva Stone | Acarkon Decorative Stone Wall Panels',
                        'contact' => 'Contact | Silva Stone',
                        'products' => 'Products | Silva Stone',
                        'projects' => 'Projects | Silva Stone',
                        'stores' => 'Showrooms | Silva Stone',
                        'contracts' => 'Contracts | Silva Stone',
                        default => null,
                    },
                ], fn ($v) => $v !== null)
            );
        }

        foreach (SilvaLegalDefaults::types() as $type => $meta) {
            $page = Page::where('type', $type)->first();
            if (! $page) {
                continue;
            }
            $extras = SilvaLegalDefaults::data($type, 'en');
            PageTranslation::updateOrCreate(
                ['page_id' => $page->id, 'lang_key' => 'en'],
                [
                    'slug' => $meta['slug_en'] ?? $meta['slug'],
                    'name' => $meta['name_en'] ?? $meta['name'],
                    'title' => $meta['name_en'] ?? $meta['name'],
                    'seo_title' => ($meta['name_en'] ?? $meta['name']) . ' | Silva Stone',
                    'seo_description' => 'Silva Stone ' . strtolower($meta['name_en'] ?? $meta['name']) . '.',
                    'body_content' => $extras['body_html'] ?? '',
                    'extras' => $extras,
                ]
            );
        }
    }

    private function localizeStoreExtras(array $extras): array
    {
        $items = collect($extras['items'] ?? [])
            ->map(function ($item) {
                if (! is_array($item)) {
                    return $item;
                }
                if (trim((string) ($item['address'] ?? '')) === 'Yakında') {
                    $item['address'] = 'Coming soon';
                }

                return $item;
            })
            ->values()
            ->all();

        $extras['items'] = $items;

        return $extras;
    }

    private function seedColors(): void
    {
        $names = [
            'antrasit' => 'Anthracite',
            'bej' => 'Beige',
            'beyaz' => 'White',
            'krem' => 'Cream',
            'siyah' => 'Black',
        ];
        foreach ($names as $slug => $en) {
            $color = \App\Models\ProductColor::where('slug', $slug)->first();
            if (! $color) {
                continue;
            }
            ProductColorTranslation::updateOrCreate(
                ['product_color_id' => $color->id, 'lang_key' => 'en'],
                ['name' => $en]
            );
        }
    }

    private function seedProjectTaxonomies(): void
    {
        $types = [
            'cephe' => 'Facade',
            'konut' => 'Residential',
            'ofis' => 'Office',
            'otel' => 'Hotel',
            'restoran' => 'Restaurant',
        ];
        foreach ($types as $slug => $en) {
            $item = \App\Models\ProjectType::where('slug', $slug)->first();
            if ($item) {
                ProjectTypeTranslation::updateOrCreate(
                    ['project_type_id' => $item->id, 'lang_key' => 'en'],
                    ['name' => $en]
                );
            }
        }

        foreach (['indoor' => 'Indoor', 'outdoor' => 'Outdoor'] as $slug => $en) {
            $item = \App\Models\ProjectPlace::where('slug', $slug)->first();
            if ($item) {
                ProjectPlaceTranslation::updateOrCreate(
                    ['project_place_id' => $item->id, 'lang_key' => 'en'],
                    ['name' => $en]
                );
            }
        }

        foreach (\App\Models\ProjectCity::with('translations')->get() as $city) {
            $tr = $city->translations->firstWhere('lang_key', 'tr')?->name ?: $city->slug;
            ProjectCityTranslation::updateOrCreate(
                ['project_city_id' => $city->id, 'lang_key' => 'en'],
                ['name' => $tr]
            );
        }
    }

    private function seedProducts(): void
    {
        foreach (Product::with('translations')->get() as $product) {
            $tr = $product->translations->firstWhere('lang_key', 'tr');
            $baseName = (string) ($tr?->name ?: $product->name);
            $nameEn = $this->translateProductName($baseName);
            $shortEn = $nameEn . ' decorative stone wall panel by Silva Stone. 600×1200 mm.';

            ProductTranslation::updateOrCreate(
                ['product_id' => $product->id, 'lang_key' => 'en'],
                [
                    'name' => $nameEn,
                    'title' => $nameEn,
                    'slug' => $tr?->slug ?: Str::slug($nameEn),
                    'short_description' => $shortEn,
                    'description' => $shortEn,
                    'seo_title' => $nameEn . ' | Silva Stone',
                    'seo_description' => $nameEn . ' — Silva Stone decorative stone wall panel.',
                ]
            );
        }
    }

    private function seedProjects(): void
    {
        $enMap = SilvaProjectsDefaults::englishBySlug();

        foreach (SilvaProjectsDefaults::seedItems() as $item) {
            $project = Project::where('slug', $item['slug'])->first();
            if (! $project) {
                continue;
            }

            $en = $enMap[$item['slug']] ?? null;
            if (! $en) {
                continue;
            }

            ProjectTranslation::updateOrCreate(
                ['project_id' => $project->id, 'lang_key' => 'en'],
                [
                    'title' => $en['title'],
                    'slug' => $item['slug'],
                    'lead' => $en['lead'],
                    'body' => $en['body'],
                    'feats' => $en['feats'],
                    'seo_title' => $en['title'] . ' | Silva Stone',
                    'seo_description' => $en['lead'],
                ]
            );
        }
    }

    private function translateProductName(string $name): string
    {
        $map = [
            'Traverten Duvar Paneli' => 'Travertine Wall Panel',
            'Stonart Duvar Paneli' => 'Stoneart Wall Panel',
            'Stoneart Duvar Paneli' => 'Stoneart Wall Panel',
            'Duvar Paneli' => 'Wall Panel',
            'Traverten' => 'Travertine',
            'Stonart' => 'Stoneart',
            'Beyaz' => 'White',
            'Antrasit' => 'Anthracite',
            'Anthracite' => 'Anthracite',
            'Krem' => 'Cream',
            'Cream' => 'Cream',
            'Siyah' => 'Black',
            'Bej' => 'Beige',
            'Desing' => 'Design',
            'Banane' => 'Banana',
            'Banana' => 'Banana',
        ];

        return str_replace(array_keys($map), array_values($map), $name);
    }
}
