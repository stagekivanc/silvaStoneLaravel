<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageTranslation;
use App\Support\SilvaStoresDefaults;
use Illuminate\Database\Seeder;

class SilvaStoresSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = SilvaStoresDefaults::data();

        $page = Page::updateOrCreate(
            ['type' => 'stores'],
            [
                'slug' => 'magazalar',
                'name' => 'Showroom',
                'title' => 'Satış Noktaları',
                'seo_title' => 'Showroom | Silva Stone',
                'seo_description' => 'Türkiye genelindeki Acarkon Store’larda Silva Stone panelleri görün, dokunun ve sipariş edin.',
                'seo_keywords' => 'Silva Stone showroom, Acarkon Store, satış noktaları, Konya, İstanbul, Ankara',
                'extras' => $defaults,
            ]
        );

        PageTranslation::updateOrCreate(
            ['page_id' => $page->id, 'lang_key' => 'tr'],
            [
                'slug' => 'magazalar',
                'name' => 'Showroom',
                'title' => 'Satış Noktaları',
                'seo_title' => 'Showroom | Silva Stone',
                'seo_description' => 'Türkiye genelindeki Acarkon Store’larda Silva Stone panelleri görün, dokunun ve sipariş edin.',
                'seo_keywords' => 'Silva Stone showroom, Acarkon Store, satış noktaları',
                'extras' => $defaults,
            ]
        );

        PageTranslation::updateOrCreate(
            ['page_id' => $page->id, 'lang_key' => 'en'],
            [
                'slug' => 'stores',
                'name' => 'Showrooms',
                'title' => 'Sales Points',
                'seo_title' => 'Showrooms | Silva Stone',
                'seo_description' => 'See, feel and order Silva Stone panels at Acarkon Stores across Turkey.',
                'seo_keywords' => 'Silva Stone showroom, Acarkon Store',
                'extras' => $defaults,
            ]
        );
    }
}
