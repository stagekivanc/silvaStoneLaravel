<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageTranslation;
use App\Models\Setting;
use App\Support\SilvaHomepageDefaults;
use Illuminate\Database\Seeder;

class SilvaHomepageSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = SilvaHomepageDefaults::data('tr');

        $page = Page::updateOrCreate(
            ['type' => 'index'],
            [
                'slug' => 'anasayfa',
                'name' => 'Anasayfa',
                'title' => 'Silva Stone',
                'seo_title' => 'Silva Stone | Acarkon Dekoratif Taş Duvar Panelleri',
                'seo_description' => 'Silva Stone by Acarkon — modern iç mekânlar için dekoratif taş duvar panelleri. Traverten, kayrak, tuğla ve beton görünüm; hızlı montaj, Acarkon Store ağı.',
                'seo_keywords' => 'Silva Stone, Acarkon, dekoratif taş, duvar paneli, traverten panel, taş kaplama',
                'extras' => $defaults,
            ]
        );

        PageTranslation::updateOrCreate(
            ['page_id' => $page->id, 'lang_key' => 'tr'],
            [
                'slug' => 'anasayfa',
                'name' => 'Anasayfa',
                'title' => 'Silva Stone',
                'seo_title' => 'Silva Stone | Acarkon Dekoratif Taş Duvar Panelleri',
                'seo_description' => 'Silva Stone by Acarkon — modern iç mekânlar için dekoratif taş duvar panelleri. Traverten, kayrak, tuğla ve beton görünüm; hızlı montaj, Acarkon Store ağı.',
                'seo_keywords' => 'Silva Stone, Acarkon, dekoratif taş, duvar paneli, traverten panel, taş kaplama',
                'extras' => $defaults,
            ]
        );

        PageTranslation::updateOrCreate(
            ['page_id' => $page->id, 'lang_key' => 'en'],
            [
                'slug' => 'home',
                'name' => 'Home',
                'title' => 'Silva Stone',
                'seo_title' => 'Silva Stone | Acarkon Decorative Stone Wall Panels',
                'seo_description' => 'Silva Stone by Acarkon — decorative stone wall panels for modern interiors. Travertine, slate, brick and concrete looks with fast install.',
                'seo_keywords' => 'Silva Stone, Acarkon, decorative stone, wall panel, travertine panel',
                'extras' => SilvaHomepageDefaults::data('en'),
            ]
        );

        $settings = [
            'site_name' => 'Silva Stone',
            'site_tagline' => 'Acarkon dekoratif taş duvar panelleri',
            'company_name' => 'Acarkon Orman Ürünleri',
            'phone' => '+90 850 346 02 26',
            'phone_raw' => '+908503460226',
            'email' => 'bilgi@acarkon.com',
            'address' => 'Horozluhan Mahallesi Hotamış Sk. No:49 Selçuklu / Konya',
            'whatsapp' => '908503460226',
            'instagram' => 'https://www.instagram.com/acarkon/',
            'facebook' => 'https://www.facebook.com/',
            'youtube' => 'https://www.youtube.com/',
            'catalog_url' => 'silvastone/assets/silva-stone-2026-katalog.pdf',
            'footer_cta_title' => 'Mekânınız için doğru yüzeyi seçin',
            'footer_cta_text' => 'Katalogu inceleyin veya en yakın Acarkon Store’dan numune alın.',
            'footer_brand_text' => 'Silva Stone, Acarkon Orman Ürünleri ürün ailesinin dekoratif taş duvar paneli markasıdır.',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
