<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageTranslation;
use App\Models\Project;
use App\Models\ProjectTranslation;
use App\Support\SilvaProjectsDefaults;
use Illuminate\Database\Seeder;

class SilvaProjectsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = SilvaProjectsDefaults::data();

        $page = Page::updateOrCreate(
            ['type' => 'projects'],
            [
                'slug' => 'projeler',
                'name' => 'Projeler',
                'title' => 'Projeler',
                'seo_title' => 'Projeler | Silva Stone Uygulamaları',
                'seo_description' => 'Silva Stone dekoratif taş panellerinin otel, konut, restoran ve cephe uygulamaları.',
                'seo_keywords' => 'Silva Stone projeler, taş panel uygulama, feature wall, dış cephe',
                'extras' => $defaults,
            ]
        );

        PageTranslation::updateOrCreate(
            ['page_id' => $page->id, 'lang_key' => 'tr'],
            [
                'slug' => 'projeler',
                'name' => 'Projeler',
                'title' => 'Projeler',
                'seo_title' => 'Projeler | Silva Stone Uygulamaları',
                'seo_description' => 'Silva Stone dekoratif taş panellerinin otel, konut, restoran ve cephe uygulamaları.',
                'seo_keywords' => 'Silva Stone projeler, taş panel uygulama',
                'extras' => $defaults,
            ]
        );

        PageTranslation::updateOrCreate(
            ['page_id' => $page->id, 'lang_key' => 'en'],
            [
                'slug' => 'projects',
                'name' => 'Projects',
                'title' => 'Projects',
                'seo_title' => 'Projects | Silva Stone Applications',
                'seo_description' => 'Hotel, home, restaurant and facade applications of Silva Stone decorative panels.',
                'seo_keywords' => 'Silva Stone projects, wall panel applications',
                'extras' => $defaults,
            ]
        );

        foreach (SilvaProjectsDefaults::seedItems() as $item) {
            $project = Project::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'place' => $item['place'],
                    'type' => $item['type'],
                    'city' => $item['city'],
                    'city_label' => $item['city_label'],
                    'product_name' => $item['product_name'],
                    'year' => $item['year'],
                    'area' => $item['area'],
                    'main_image' => $item['main_image'],
                    'gallery' => $item['gallery'],
                    'order' => $item['order'],
                    'status' => true,
                    'home_status' => (bool) ($item['home_status'] ?? false),
                ]
            );

            ProjectTranslation::updateOrCreate(
                ['project_id' => $project->id, 'lang_key' => 'tr'],
                [
                    'title' => $item['title'],
                    'slug' => $item['slug'],
                    'lead' => $item['lead'],
                    'body' => $item['body'],
                    'feats' => $item['feats'],
                    'seo_title' => $item['title'] . ' | Silva Stone',
                    'seo_description' => $item['lead'],
                ]
            );

            ProjectTranslation::updateOrCreate(
                ['project_id' => $project->id, 'lang_key' => 'en'],
                [
                    'title' => $item['title'],
                    'slug' => $item['slug'],
                    'lead' => $item['lead'],
                    'body' => $item['body'],
                    'feats' => $item['feats'],
                    'seo_title' => $item['title'] . ' | Silva Stone',
                    'seo_description' => $item['lead'],
                ]
            );
        }
    }
}
