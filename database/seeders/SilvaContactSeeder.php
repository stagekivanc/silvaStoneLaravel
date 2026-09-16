<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageTranslation;
use App\Support\SilvaContactDefaults;
use Illuminate\Database\Seeder;

class SilvaContactSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = SilvaContactDefaults::data('tr');

        $page = Page::updateOrCreate(
            ['type' => 'contact'],
            [
                'slug' => 'iletisim',
                'name' => 'İletişim',
                'title' => 'İletişim',
                'seo_title' => 'İletişim | Silva Stone',
                'seo_description' => 'Silva Stone proje, numune ve showroom talepleri için Acarkon ekibiyle iletişime geçin.',
                'seo_keywords' => 'Silva Stone iletişim, Acarkon, showroom, numune, dekoratif taş panel, Konya',
                'extras' => $defaults,
            ]
        );

        PageTranslation::updateOrCreate(
            ['page_id' => $page->id, 'lang_key' => 'tr'],
            [
                'slug' => 'iletisim',
                'name' => 'İletişim',
                'title' => 'İletişim',
                'seo_title' => 'İletişim | Silva Stone',
                'seo_description' => 'Silva Stone proje, numune ve showroom talepleri için Acarkon ekibiyle iletişime geçin.',
                'seo_keywords' => 'Silva Stone iletişim, Acarkon, showroom, numune, dekoratif taş panel, Konya',
                'extras' => $defaults,
            ]
        );

        PageTranslation::updateOrCreate(
            ['page_id' => $page->id, 'lang_key' => 'en'],
            [
                'slug' => 'contact',
                'name' => 'Contact',
                'title' => 'Contact',
                'seo_title' => 'Contact | Silva Stone',
                'seo_description' => 'Contact the Acarkon team for Silva Stone project, sample and showroom requests.',
                'seo_keywords' => 'Silva Stone contact, Acarkon, showroom, sample, decorative stone panel',
                'extras' => $defaults,
            ]
        );
    }
}
