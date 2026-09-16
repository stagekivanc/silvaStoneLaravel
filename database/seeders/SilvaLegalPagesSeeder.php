<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageTranslation;
use App\Support\SilvaLegalDefaults;
use Illuminate\Database\Seeder;

class SilvaLegalPagesSeeder extends Seeder
{
    public function run(): void
    {
        foreach (SilvaLegalDefaults::types() as $type => $meta) {
            $defaultsTr = SilvaLegalDefaults::data($type, 'tr');
            $defaultsEn = SilvaLegalDefaults::data($type, 'en');

            $page = Page::updateOrCreate(
                ['type' => $type],
                [
                    'slug' => $meta['slug'],
                    'name' => $meta['name'],
                    'title' => $meta['name'],
                    'seo_title' => $meta['seo_title'],
                    'seo_description' => $meta['seo_description'],
                    'body_content' => $defaultsTr['body_html'],
                    'extras' => $defaultsTr,
                ]
            );

            PageTranslation::updateOrCreate(
                ['page_id' => $page->id, 'lang_key' => 'tr'],
                [
                    'slug' => $meta['slug'],
                    'name' => $meta['name'],
                    'title' => $meta['name'],
                    'seo_title' => $meta['seo_title'],
                    'seo_description' => $meta['seo_description'],
                    'body_content' => $defaultsTr['body_html'],
                    'extras' => $defaultsTr,
                ]
            );

            PageTranslation::updateOrCreate(
                ['page_id' => $page->id, 'lang_key' => 'en'],
                [
                    'slug' => $meta['slug_en'],
                    'name' => $meta['name_en'],
                    'title' => $meta['name_en'],
                    'seo_title' => $meta['name_en'] . ' | Silva Stone',
                    'seo_description' => 'Silva Stone ' . strtolower($meta['name_en']) . '.',
                    'body_content' => $defaultsEn['body_html'],
                    'extras' => $defaultsEn,
                ]
            );
        }
    }
}
