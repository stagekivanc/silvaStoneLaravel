<?php

namespace App\Services;

use App\Support\SilvaContactDefaults;
use App\Support\SilvaHomepageDefaults;

class PageTemplateService
{
    public static function formatBannerTitle(?string $title): array
    {
        $html = preg_replace('/<img[^>]*\/?>/i', '', trim($title ?? ''));
        $words = preg_split('/\s+/u', trim(strip_tags($html)), -1, PREG_SPLIT_NO_EMPTY);
        $lead = e(implode(' ', array_slice($words, 0, 3)));
        $rest = preg_match('/<b>(.*?)<\/b>/is', $html, $matches)
            ? '<b>' . $matches[1] . '</b>'
            : e(implode(' ', array_slice($words, 3)));

        return compact('lead', 'rest');
    }

    public static function getFields(string $type): array
    {
        if (in_array($type, ['kvkk', 'cookie-policy', 'terms', 'privacy-policy', 'return-policy', 'shipping-policy', 'kvkk-law', 'personal-data'], true)) {
            return self::legalFields($type);
        }

        if ($type === 'contact') {
            return self::contactFields();
        }

        if ($type === 'contracts') {
            return self::contractsFields();
        }

        if ($type === 'stores') {
            return self::storesFields();
        }

        if ($type === 'projects') {
            return self::projectsFields();
        }

        if ($type === 'products') {
            return self::productsPageFields();
        }

        if (in_array($type, ['application', 'faq', 'login', 'search', 'solution', 'sustainability', 'corporate'], true)) {
            return [];
        }

        if ($type !== 'index') {
            return [];
        }

        $defaults = SilvaHomepageDefaults::data();

        return [
            self::group('Hero Butonları', [
                self::field('Birinci Buton', 'hero.primary_label', 'text', $defaults['hero']['primary_label']),
                self::field('Birinci Buton Linki', 'hero.primary_url', 'text', $defaults['hero']['primary_url']),
                self::field('İkinci Buton', 'hero.secondary_label', 'text', $defaults['hero']['secondary_label']),
                self::field('İkinci Buton Linki', 'hero.secondary_url', 'text', $defaults['hero']['secondary_url']),
                self::field('Spotlight Etiketi', 'hero.spotlight_label', 'text', $defaults['hero']['spotlight_label']),
            ]),
            self::repeater('Hero Slaytları', 'hero.slides', $defaults['hero']['slides'], [
                self::field('Görsel', 'image', 'image'),
                self::field('Üst Metin', 'kicker'),
                self::field('Başlık', 'title'),
                self::field('Açıklama', 'lead', 'textarea'),
                self::field('Ton (dark/light)', 'tone'),
            ]),
            self::group('Intro', [
                self::field('Etiket', 'intro.eyebrow', 'text', $defaults['intro']['eyebrow']),
                self::field('Başlık', 'intro.title', 'textarea', $defaults['intro']['title']),
                self::field('Metin', 'intro.text', 'textarea', $defaults['intro']['text']),
            ]),
            self::group('Özellikler Başlığı', [
                self::field('Başlık', 'features.title', 'text', $defaults['features']['title']),
                self::field('Alt Başlık', 'features.subtitle', 'text', $defaults['features']['subtitle']),
                self::field('Katalog Metni', 'features.catalog_label', 'text', $defaults['features']['catalog_label']),
                self::field('Katalog Linki', 'features.catalog_url', 'text', $defaults['features']['catalog_url']),
            ]),
            self::repeater('Özellik Kartları', 'features.items', $defaults['features']['items'], [
                self::field('Başlık', 'title'),
                self::field('Açıklama', 'text', 'textarea'),
            ]),
            self::group('Ürünler Bölümü', [
                self::field('Etiket', 'products.eyebrow', 'text', $defaults['products']['eyebrow']),
                self::field('Başlık', 'products.title', 'text', $defaults['products']['title']),
                self::field('Buton Metni', 'products.cta_label', 'text', $defaults['products']['cta_label']),
                self::field('Buton Linki', 'products.cta_url', 'text', $defaults['products']['cta_url']),
            ]),
            self::group('Mekânlar Başlığı', [
                self::field('Başlık', 'spaces.title', 'text', $defaults['spaces']['title']),
                self::field('Alt Başlık', 'spaces.subtitle', 'text', $defaults['spaces']['subtitle']),
            ]),
            self::repeater('Mekân Kartları', 'spaces.items', $defaults['spaces']['items'], [
                self::field('İkon (boxicons class)', 'icon'),
                self::field('Başlık', 'title'),
                self::field('Açıklama', 'text', 'textarea'),
                self::field('Link', 'url'),
            ]),
            self::group('Showroom', [
                self::field('Etiket', 'stores.eyebrow', 'text', $defaults['stores']['eyebrow']),
                self::field('Başlık', 'stores.title', 'text', $defaults['stores']['title']),
                self::field('Alt Başlık', 'stores.subtitle', 'text', $defaults['stores']['subtitle']),
                self::field('Buton Metni', 'stores.cta_label', 'text', $defaults['stores']['cta_label']),
                self::field('Buton Linki', 'stores.cta_url', 'text', $defaults['stores']['cta_url']),
            ]),
            self::group('Projeler', [
                self::field('Başlık', 'projects.title', 'text', $defaults['projects']['title']),
                self::field('Alt Başlık', 'projects.subtitle', 'text', $defaults['projects']['subtitle']),
                self::field('Buton Metni', 'projects.cta_label', 'text', $defaults['projects']['cta_label']),
                self::field('Buton Linki', 'projects.cta_url', 'text', $defaults['projects']['cta_url']),
                self::field('İpucu', 'projects.hint', 'text', $defaults['projects']['hint']),
            ]),
        ];
    }

    private static function legalFields(string $type): array
    {
        $defaults = \App\Support\SilvaLegalDefaults::data($type);

        return [
            self::group('Intro', [
                self::field('Üst Metin', 'intro.kicker', 'text', $defaults['intro']['kicker']),
                self::field('Başlık', 'intro.title', 'text', $defaults['intro']['title']),
                self::field('Yan Metin', 'intro.aside', 'textarea', $defaults['intro']['aside']),
            ]),
            self::group('Doküman', [
                self::field('Numara', 'doc.number', 'text', $defaults['doc']['number']),
                self::field('Başlık', 'doc.title', 'text', $defaults['doc']['title']),
            ]),
            self::group('İçerik', [
                self::field('Metin (HTML)', 'body_html', 'html', $defaults['body_html']),
            ]),
        ];
    }

    private static function contactFields(): array
    {
        $defaults = SilvaContactDefaults::data();

        return [
            self::group('Intro', [
                self::field('Üst Metin', 'intro.kicker', 'text', $defaults['intro']['kicker']),
                self::field('Başlık', 'intro.title', 'text', $defaults['intro']['title']),
                self::field('Açıklama', 'intro.text', 'textarea', $defaults['intro']['text']),
                self::field('Çalışma Saati Notu', 'intro.hours', 'text', $defaults['intro']['hours']),
            ]),
            self::repeater('İletişim Kanalları', 'channels', $defaults['channels'], [
                self::field('Tip (phone/email/whatsapp/hours/address)', 'type'),
                self::field('İkon (boxicons)', 'icon'),
                self::field('Etiket', 'label'),
                self::field('Değer', 'value', 'textarea'),
                self::field('Link', 'url'),
            ]),
            self::group('Form Metinleri', [
                self::field('Ad Soyad', 'form.name_label', 'text', $defaults['form']['name_label']),
                self::field('Ad Placeholder', 'form.name_placeholder', 'text', $defaults['form']['name_placeholder']),
                self::field('Telefon', 'form.phone_label', 'text', $defaults['form']['phone_label']),
                self::field('Telefon Placeholder', 'form.phone_placeholder', 'text', $defaults['form']['phone_placeholder']),
                self::field('E-posta', 'form.email_label', 'text', $defaults['form']['email_label']),
                self::field('E-posta Placeholder', 'form.email_placeholder', 'text', $defaults['form']['email_placeholder']),
                self::field('Şehir', 'form.city_label', 'text', $defaults['form']['city_label']),
                self::field('Şehir Placeholder', 'form.city_placeholder', 'text', $defaults['form']['city_placeholder']),
                self::field('Konu', 'form.interest_label', 'text', $defaults['form']['interest_label']),
                self::field('Mesaj', 'form.message_label', 'text', $defaults['form']['message_label']),
                self::field('Mesaj Placeholder', 'form.message_placeholder', 'text', $defaults['form']['message_placeholder']),
                self::field('Onay Metni (HTML)', 'form.consent_html', 'html', $defaults['form']['consent_html']),
                self::field('Gönder Butonu', 'form.submit_label', 'text', $defaults['form']['submit_label']),
                self::field('Başarı Mesajı', 'form.success_message', 'text', $defaults['form']['success_message']),
            ]),
            self::repeater('Konu Seçenekleri', 'form.interests', $defaults['form']['interests'], [
                self::field('Değer', 'value'),
                self::field('Etiket', 'label'),
            ]),
            self::group('Harita', [
                self::field('Başlık', 'map.title', 'text', $defaults['map']['title']),
                self::field('iframe Başlığı', 'map.iframe_title', 'text', $defaults['map']['iframe_title']),
                self::field('Embed URL', 'map.embed_url', 'textarea', $defaults['map']['embed_url']),
            ]),
        ];
    }

    private static function contractsFields(): array
    {
        $defaults = \App\Support\SilvaContractsDefaults::data();

        return [
            self::group('Intro', [
                self::field('Üst Metin', 'intro.kicker', 'text', $defaults['intro']['kicker']),
                self::field('Başlık', 'intro.title', 'text', $defaults['intro']['title']),
                self::field('Yan Metin', 'intro.aside', 'textarea', $defaults['intro']['aside']),
            ]),
            self::repeater('Sözleşme Listesi', 'items', $defaults['items'], [
                self::field('Sayfa Tipi', 'type'),
                self::field('Numara', 'number'),
                self::field('Başlık', 'label'),
            ]),
        ];
    }

    private static function storesFields(): array
    {
        $defaults = \App\Support\SilvaStoresDefaults::data();

        return [
            self::group('Intro', [
                self::field('Üst Metin', 'intro.kicker', 'text', $defaults['intro']['kicker']),
                self::field('Başlık', 'intro.title', 'text', $defaults['intro']['title']),
                self::field('Yan Metin', 'intro.aside', 'textarea', $defaults['intro']['aside']),
            ]),
            self::group('Filtre', [
                self::field('Şehir Etiketi', 'filter.city_label', 'text', $defaults['filter']['city_label']),
                self::field('Tüm Şehirler', 'filter.all_cities', 'text', $defaults['filter']['all_cities']),
                self::field('Yakınımda Butonu', 'filter.near_label', 'text', $defaults['filter']['near_label']),
            ]),
            self::group('Telefon', [
                self::field('Telefon', 'phone', 'text', $defaults['phone']),
                self::field('Telefon (raw)', 'phone_raw', 'text', $defaults['phone_raw']),
            ]),
            self::repeater('Mağazalar', 'items', $defaults['items'], [
                self::field('Şehir Kodu', 'city'),
                self::field('Şehir Adı', 'city_label'),
                self::field('Enlem', 'lat'),
                self::field('Boylam', 'lng'),
                self::field('İsim', 'name'),
                self::field('Adres', 'address', 'textarea'),
                self::field('Maps Query', 'maps', 'textarea'),
            ]),
        ];
    }

    private static function projectsFields(): array
    {
        $defaults = \App\Support\SilvaProjectsDefaults::data();

        return [
            self::group('Intro', [
                self::field('Üst Metin', 'intro.kicker', 'text', $defaults['intro']['kicker']),
                self::field('Başlık', 'intro.title', 'text', $defaults['intro']['title']),
                self::field('Yan Metin', 'intro.aside', 'textarea', $defaults['intro']['aside']),
            ]),
            self::group('Filtre Metinleri', [
                self::field('Mekân Etiketi', 'filter.place_label', 'text', $defaults['filter']['place_label']),
                self::field('Tip Etiketi', 'filter.type_label', 'text', $defaults['filter']['type_label']),
                self::field('Tüm Şehirler', 'filter.all_cities', 'text', $defaults['filter']['all_cities']),
                self::field('Sayım Eki', 'filter.count_suffix', 'text', $defaults['filter']['count_suffix']),
                self::field('Sıfırla', 'filter.reset', 'text', $defaults['filter']['reset']),
                self::field('Boş Sonuç', 'filter.empty_title', 'text', $defaults['filter']['empty_title']),
                self::field('Boş Sonuç Butonu', 'filter.empty_reset', 'text', $defaults['filter']['empty_reset']),
            ]),
            self::group('Detay Sayfası', [
                self::field('Notlar Üst Metin', 'detail.notes_kicker', 'text', $defaults['detail']['notes_kicker']),
                self::field('Notlar Başlık', 'detail.notes_title', 'text', $defaults['detail']['notes_title']),
                self::field('İlgili Üst Metin', 'detail.related_kicker', 'text', $defaults['detail']['related_kicker']),
                self::field('İlgili Başlık', 'detail.related_title', 'text', $defaults['detail']['related_title']),
                self::field('Hikâye Üst Metin', 'detail.story_kicker', 'text', $defaults['detail']['story_kicker']),
                self::field('Yüzey Üst Metin', 'detail.surface_kicker', 'text', $defaults['detail']['surface_kicker']),
                self::field('Yüzey İpucu', 'detail.surface_hint', 'text', $defaults['detail']['surface_hint']),
                self::field('CTA', 'detail.cta', 'text', $defaults['detail']['cta']),
            ]),
        ];
    }

    private static function productsPageFields(): array
    {
        $defaults = \App\Support\SilvaProductsDefaults::data();

        return [
            self::group('Intro', [
                self::field('Üst Metin', 'intro.kicker', 'text', $defaults['intro']['kicker']),
                self::field('Başlık', 'intro.title', 'text', $defaults['intro']['title']),
                self::field('Yan Metin', 'intro.aside', 'textarea', $defaults['intro']['aside']),
            ]),
            self::group('SEO Bloğu', [
                self::field('Üst Metin', 'seo.kicker', 'text', $defaults['seo']['kicker']),
                self::field('Başlık', 'seo.title', 'text', $defaults['seo']['title']),
                self::field('Devam Butonu', 'seo.more', 'text', $defaults['seo']['more']),
            ]),
        ];
    }

    private static function group(string $name, array $fields): array
    {
        return compact('name', 'fields') + ['type' => 'group'];
    }

    private static function repeater(string $name, string $key, array $defaults, array $fields): array
    {
        return compact('name', 'key', 'defaults', 'fields') + [
            'type' => 'repeater',
            'count' => count($defaults),
        ];
    }

    private static function field(string $label, string $key, string $type = 'text', mixed $default = null): array
    {
        return compact('label', 'key', 'type', 'default');
    }

    public static function galleryFieldKeys(string $type): array
    {
        return [];
    }
}
