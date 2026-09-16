<?php

namespace App\Support;

use App\Support\Concerns\HasLocalizedDefaults;

class SilvaHomepageDefaults
{
    use HasLocalizedDefaults;

    protected static function baseData(): array
    {
        return [
            'hero' => [
                'primary_label' => 'Koleksiyonu incele',
                'primary_url' => '#katalog',
                'secondary_label' => 'Showroom',
                'secondary_url' => '/tr/magazalar',
                'spotlight_label' => 'Yeni ürünler',
                'slides' => [
                    [
                        'image' => 'silvastone/assets/hero/tetris-antrasit.jpg',
                        'kicker' => 'Silva Stone · Dış cephe',
                        'title' => 'Mimari cephe',
                        'lead' => 'Villa ve bahçe duvarlarında derin antrasit blok dokusu. Gece ışığında taşın gölgesi mimariyi tamamlar.',
                        'tone' => 'dark',
                    ],
                    [
                        'image' => 'silvastone/assets/hero/slate-antrasit.jpg',
                        'kicker' => 'Silva Stone · İç mimari',
                        'title' => 'Salon odağı',
                        'lead' => 'TV duvarı ve feature wall için sakin antrasit yüzey. Oturma alanlarında kayrak dokuyla net bir mimari merkez.',
                        'tone' => 'dark',
                    ],
                    [
                        'image' => 'silvastone/assets/hero/coarse-clothh-krem.jpg',
                        'kicker' => 'Silva Stone · Konut & otel',
                        'title' => 'Dokulu yüzey',
                        'lead' => 'Kumaş dokulu krem panel; lobilerden konut iç mekânına kadar sıcak, doğal ve yumuşak bir duvar karakteri.',
                        'tone' => 'light',
                    ],
                ],
            ],
            'intro' => [
                'eyebrow' => 'Acarkon ürün ailesi',
                'title' => 'Doğal taşın karakterini, hafif ve uygulanabilir bir yüzeyle iç mekâna taşıyoruz.',
                'text' => 'Silva Stone; otel lobilerinden konut feature wall’larına kadar, mimari odağı güçlendiren dekoratif taş paneller sunar. Numune ve sipariş için Türkiye genelindeki Acarkon Store’ları ziyaret edin.',
            ],
            'features' => [
                'title' => 'Ürün özellikleri',
                'subtitle' => 'Tasarım, uygulama ve günlük kullanım için panellerin avantajları',
                'catalog_label' => 'Panel özelliklerini inceleyin',
                'catalog_url' => 'silvastone/assets/silva-stone-2026-katalog.pdf',
                'items' => [
                    ['title' => 'Yangın güvenliği', 'text' => 'A2 sınıfı yangın dayanıklılığı.'],
                    ['title' => 'Dayanıklılık', 'text' => 'Dış mekân koşullarına dirençli, uzun ömürlü yüzey.'],
                    ['title' => 'Esneklik', 'text' => 'Bükülebilir, kavisli yüzeylere uyum sağlar.'],
                    ['title' => 'Uygulama kolaylığı', 'text' => 'İnce ve hafif; kesilir, taşınır, yapıştırılır.'],
                    ['title' => 'Çevre dostu', 'text' => 'Yüksek oranda geri dönüştürülebilir malzeme.'],
                    ['title' => 'Nefes alabilirlik', 'text' => 'İç mekân hava kalitesini destekler.'],
                    ['title' => 'Anti-kir', 'text' => 'Temizliği kolay, antibakteriyel yüzey.'],
                    ['title' => 'Su geçirmezlik', 'text' => 'Nemli hacimler ve dış cephe için uygun.'],
                    ['title' => 'Sıcaklık kontrolü', 'text' => 'Isı dengesine katkı, enerji tasarrufu.'],
                    ['title' => 'Nem kontrolü', 'text' => 'Küf ve nem riskini azaltır.'],
                    ['title' => 'İç ve dış mekân', 'text' => 'Aynı panel konut, lobi ve cephede kullanılır.'],
                    ['title' => 'Özel ölçü', 'text' => '600×1200 mm standart; 1200×2400 / 3000 mm üretim.'],
                ],
            ],
            'products' => [
                'eyebrow' => 'Koleksiyon',
                'title' => 'Ürünler',
                'cta_label' => 'Tüm ürünleri keşfet',
                'cta_url' => '/tr/urunler',
            ],
            'spaces' => [
                'title' => 'Mekânlara karakter',
                'subtitle' => 'Feature wall, lobi ve ticari yüzeylerde doğal derinlik.',
                'items' => [
                    [
                        'icon' => 'bx-home-alt-2',
                        'title' => 'Konutlar',
                        'text' => 'TV duvarı, şömine nişi ve oturma odasında sakin accent yüzey.',
                        'url' => '/silvastone/projeler.html?type=konut',
                    ],
                    [
                        'icon' => 'bx-hotel',
                        'title' => 'Oteller',
                        'text' => 'Lobi, resepsiyon ve oda koridorlarında mimari odak duvarı.',
                        'url' => '/silvastone/projeler.html?type=otel',
                    ],
                    [
                        'icon' => 'bx-briefcase-alt-2',
                        'title' => 'Ofis mekânları',
                        'text' => 'Toplantı odası ve marka duvarında sakin, bakımı kolay yüzey.',
                        'url' => '/silvastone/projeler.html?type=ofis',
                    ],
                    [
                        'icon' => 'bx-restaurant',
                        'title' => 'Restoranlar',
                        'text' => 'Salon ve bar duvarında doku, leke direnci ve hızlı temizlik.',
                        'url' => '/silvastone/projeler.html?type=restoran',
                    ],
                    [
                        'icon' => 'bx-spa',
                        'title' => 'Klinik & spa',
                        'text' => 'Neme dayanıklı, hijyenik ve sessiz bir iç mekân yüzeyi.',
                        'url' => '/tr/iletisim',
                    ],
                    [
                        'icon' => 'bx-store-alt',
                        'title' => 'Ticari alanlar',
                        'text' => 'Mağaza, showroom ve giriş cephelerinde doğal taş karakteri.',
                        'url' => '/silvastone/projeler.html?type=cephe',
                    ],
                ],
            ],
            'stores' => [
                'eyebrow' => 'Showroom',
                'title' => 'Showroom & satış noktaları',
                'subtitle' => 'Silva Stone’u Acarkon Store’larda görün, dokunun ve sipariş edin.',
                'cta_label' => 'Tüm showroom’ları gör',
                'cta_url' => '/tr/magazalar',
            ],
            'projects' => [
                'title' => 'Seçili uygulamalar',
                'subtitle' => 'Silva Stone panellerinin otel, restoran ve konut projelerindeki gerçek yüzey hikâyeleri.',
                'cta_label' => 'Tüm projeler',
                'cta_url' => '/tr/projeler',
                'hint' => 'Yana kaydırarak daha fazla uygulama görün',
            ],
        ];
    }
}
