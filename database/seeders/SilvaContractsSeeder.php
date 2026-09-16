<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageTranslation;
use App\Support\SilvaContractsDefaults;
use Illuminate\Database\Seeder;

class SilvaContractsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = SilvaContractsDefaults::data();

        $page = Page::updateOrCreate(
            ['type' => 'contracts'],
            [
                'slug' => 'sozlesmeler',
                'name' => 'Sözleşmeler',
                'title' => 'Sözleşmeler',
                'seo_title' => 'Sözleşmeler | Silva Stone',
                'seo_description' => 'Silva Stone gizlilik, aydınlatma, çerez, güvenlik ve KVKK metinleri.',
                'extras' => $defaults,
            ]
        );

        PageTranslation::updateOrCreate(
            ['page_id' => $page->id, 'lang_key' => 'tr'],
            [
                'slug' => 'sozlesmeler',
                'name' => 'Sözleşmeler',
                'title' => 'Sözleşmeler',
                'seo_title' => 'Sözleşmeler | Silva Stone',
                'seo_description' => 'Silva Stone gizlilik, aydınlatma, çerez, güvenlik ve KVKK metinleri.',
                'extras' => $defaults,
            ]
        );

        PageTranslation::updateOrCreate(
            ['page_id' => $page->id, 'lang_key' => 'en'],
            [
                'slug' => 'contracts',
                'name' => 'Contracts',
                'title' => 'Contracts',
                'seo_title' => 'Contracts | Silva Stone',
                'seo_description' => 'Silva Stone privacy, notice, cookie, security and KVKK documents.',
                'extras' => $defaults,
            ]
        );
    }
}
