<?php

namespace App\Support;

class SilvaLegalDefaults
{
    public static function types(): array
    {
        return [
            'privacy-policy' => [
                'slug' => 'gizlilik-politikasi',
                'slug_en' => 'privacy-policy',
                'name' => 'Gizlilik politikası',
                'name_en' => 'Privacy policy',
                'number' => '01',
                'seo_title' => 'Gizlilik politikası | Silva Stone',
                'seo_description' => 'Silva Stone gizlilik politikası.',
                'file' => 'privacy-policy.html',
            ],
            'kvkk' => [
                'slug' => 'aydinlatma-metni',
                'slug_en' => 'privacy-notice',
                'name' => 'Aydınlatma metni',
                'name_en' => 'Privacy notice',
                'number' => '02',
                'seo_title' => 'Aydınlatma metni | Silva Stone',
                'seo_description' => 'Silva Stone KVKK aydınlatma metni.',
                'file' => 'kvkk.html',
            ],
            'cookie-policy' => [
                'slug' => 'cerez-politikasi',
                'slug_en' => 'cookie-policy',
                'name' => 'Çerez politikası',
                'name_en' => 'Cookie policy',
                'number' => '03',
                'seo_title' => 'Çerez politikası | Silva Stone',
                'seo_description' => 'Silva Stone çerez politikası.',
                'file' => 'cookie-policy.html',
            ],
            'terms' => [
                'slug' => 'guvenlik-politikasi',
                'slug_en' => 'security-policy',
                'name' => 'Güvenlik politikası',
                'name_en' => 'Security policy',
                'number' => '04',
                'seo_title' => 'Güvenlik politikası | Silva Stone',
                'seo_description' => 'Silva Stone güvenlik politikası.',
                'file' => 'terms.html',
            ],
            'kvkk-law' => [
                'slug' => 'kvkk-kanunu',
                'slug_en' => 'kvkk-law',
                'name' => 'KVKK kanunu metni',
                'name_en' => 'KVKK law text',
                'number' => '05',
                'seo_title' => 'KVKK kanunu metni | Silva Stone',
                'seo_description' => 'Silva Stone KVKK kanununa ilişkin metin.',
                'file' => 'kvkk-law.html',
            ],
            'personal-data' => [
                'slug' => 'kisisel-veri-koruma',
                'slug_en' => 'personal-data-protection',
                'name' => 'Kişisel veri koruma',
                'name_en' => 'Personal data protection',
                'number' => '06',
                'seo_title' => 'Kişisel veri koruma | Silva Stone',
                'seo_description' => 'Silva Stone kişisel veri koruma metni.',
                'file' => 'personal-data.html',
            ],
        ];
    }

    public static function silvaTypes(): array
    {
        return array_keys(self::types());
    }

    public static function data(?string $type = 'privacy-policy', ?string $lang = null): array
    {
        $lang = $lang ?? app()->getLocale() ?? 'tr';
        $isEn = str_starts_with(strtolower((string) $lang), 'en');
        $meta = self::types()[$type] ?? self::types()['privacy-policy'];
        $name = $isEn
            ? ($meta['name_en'] ?? $meta['name'])
            : $meta['name'];

        return [
            'intro' => [
                'kicker' => $isEn ? 'Contracts' : 'Sözleşmeler',
                'title' => $name,
                'aside' => $isEn
                    ? 'Legal text published by Acarkon Entegre Ağaç San. ve Tic. A.Ş. in its capacity as data controller.'
                    : 'Acarkon Entegre Ağaç San. ve Tic. A.Ş. veri sorumlusu sıfatıyla yayımlanan yasal metin.',
            ],
            'doc' => [
                'number' => $meta['number'],
                'title' => $name,
            ],
            'body_html' => self::loadBody($meta['file'], $isEn),
            'nav' => self::navItems($isEn),
        ];
    }

    public static function navItems(bool $english = false): array
    {
        $items = [];
        foreach (self::types() as $type => $meta) {
            $items[] = [
                'type' => $type,
                'number' => $meta['number'],
                'label' => $english
                    ? ($meta['name_en'] ?? $meta['name'])
                    : $meta['name'],
            ];
        }

        return $items;
    }

    public static function resolveBodyHtml(string $html): string
    {
        $map = [
            '__PRIVACY__' => m_url('privacy-policy'),
            '__KVKK__' => m_url('kvkk'),
            '__COOKIE__' => m_url('cookie-policy'),
            '__TERMS__' => m_url('terms'),
            '__KVKK_LAW__' => m_url('kvkk-law'),
            '__PERSONAL_DATA__' => m_url('personal-data'),
        ];

        return str_replace(array_keys($map), array_values($map), $html);
    }

    protected static function loadBody(string $file, bool $english = false): string
    {
        if ($english) {
            $enPath = resource_path('content/legal/en/' . $file);
            if (is_file($enPath)) {
                return (string) file_get_contents($enPath);
            }
        }

        $path = resource_path('content/legal/' . $file);
        if (! is_file($path)) {
            return $english
                ? '<p>Content is not available yet.</p>'
                : '<p>İçerik henüz eklenmedi.</p>';
        }

        return (string) file_get_contents($path);
    }
}
