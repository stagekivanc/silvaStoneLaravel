<?php

namespace App\Support;

use App\Support\Concerns\HasLocalizedDefaults;

class SilvaStoresDefaults
{
    use HasLocalizedDefaults;

    protected static function baseData(): array
    {
        return [
            'intro' => [
                'kicker' => 'Showroom',
                'title' => 'Satış Noktaları',
                'aside' => 'Silva Stone’u Acarkon Store’larda görün, dokunun ve sipariş edin. Tüm noktalarda aynı telefon hattı geçerlidir.',
            ],
            'filter' => [
                'city_label' => 'Şehir',
                'all_cities' => 'Tüm şehirler',
                'near_label' => 'Bana en yakın',
            ],
            'phone' => '+90 850 346 02 26',
            'phone_raw' => '+908503460226',
            'items' => [
                [
                    'city' => 'konya',
                    'city_label' => 'Konya',
                    'lat' => '37.9218',
                    'lng' => '32.5084',
                    'name' => 'Acarkon Genel Merkez',
                    'address' => 'Horozluhan Mah. Gümüşlü Sk. No:48 Selçuklu / Konya',
                    'maps' => 'Horozluhan+Mah.+G%C3%BCm%C3%BC%C5%9Fl%C3%BC+Sk.+No%3A48+Sel%C3%A7uklu+%2F+Konya',
                ],
                [
                    'city' => 'istanbul',
                    'city_label' => 'İstanbul',
                    'lat' => '41.097',
                    'lng' => '28.802',
                    'name' => 'Acarkon Store — İstanbul',
                    'address' => 'Başak Mah. Enkoop 2. Sokak SOM Rezidans 1 No:1-I Başakşehir / İstanbul',
                    'maps' => 'Ba%C5%9Fak+Mah.+Enkoop+2.+Sokak+SOM+Rezidans+1+No%3A1-I+Ba%C5%9Fak%C5%9Fehir+%2F+%C4%B0stanbul',
                ],
                [
                    'city' => 'ankara',
                    'city_label' => 'Ankara',
                    'lat' => '39.9555',
                    'lng' => '32.8918',
                    'name' => 'Acarkon Store — Ankara',
                    'address' => 'Önder Mah. Sarıçam Cad. No:8-B Siteler-Altındağ / Ankara',
                    'maps' => '%C3%96nder+Mah.+Sar%C4%B1%C3%A7am+Cad.+No%3A8-B+Siteler-Alt%C4%B1nda%C4%9F+%2F+Ankara',
                ],
                [
                    'city' => 'antalya',
                    'city_label' => 'Antalya',
                    'lat' => '36.8969',
                    'lng' => '30.7133',
                    'name' => 'Acarkon Store — Antalya',
                    'address' => 'Mehmetçik Mah. 938 Sk. No:8 Murat Sit. A Blok No:1 Muratpaşa / Antalya',
                    'maps' => 'Mehmet%C3%A7ik+Mah.+938+Sk.+No%3A8+Murat+Sit.+A+Blok+No%3A1+Muratpa%C5%9Fa+%2F+Antalya',
                ],
                [
                    'city' => 'bursa',
                    'city_label' => 'Bursa',
                    'lat' => '40.2116',
                    'lng' => '28.9867',
                    'name' => 'Acarkon Store — Bursa',
                    'address' => 'Ataevler Mah. Ata Cad. Özgür Park Sit. No:H/144 Nilüfer / Bursa',
                    'maps' => 'Ataevler+Mah.+Ata+Cad.+%C3%96zg%C3%BCr+Park+Sit.+No%3AH%2F144+Nil%C3%BCfer+%2F+Bursa',
                ],
                [
                    'city' => 'tekirdag',
                    'city_label' => 'Tekirdağ',
                    'lat' => '40.9781',
                    'lng' => '27.5114',
                    'name' => 'Acarkon Store — Tekirdağ',
                    'address' => '100. Yıl Mah. Tevfik Kaptan Sok. No:52C Süleymanpaşa / Tekirdağ',
                    'maps' => '100.+Y%C4%B1l+Mah.+Tevfik+Kaptan+Sok.+No%3A52C+S%C3%BCleymanpa%C5%9Fa+%2F+Tekirda%C4%9F',
                ],
                [
                    'city' => 'edirne',
                    'city_label' => 'Edirne',
                    'lat' => '41.6771',
                    'lng' => '26.5556',
                    'name' => 'Acarkon Store — Edirne',
                    'address' => 'Cumhuriyet Mah. Tema Edirne Sitesi, Kuvayi Milliye Bul. Altı 81/B 37 Edirne',
                    'maps' => 'Cumhuriyet+Mah.+Tema+Edirne+Sitesi%2C+Kuvayi+Milliye+Bul.+Alt%C4%B1+81%2FB+37+Edirne',
                ],
                [
                    'city' => 'mardin',
                    'city_label' => 'Mardin',
                    'lat' => '37.3129',
                    'lng' => '40.7436',
                    'name' => 'Acarkon Store — Mardin',
                    'address' => 'Vali Ozan Cad. Nur Mah. Kanza Binaları Altı No:55 Artuklu / Mardin',
                    'maps' => 'Vali+Ozan+Cad.+Nur+Mah.+Kanza+Binalar%C4%B1+Alt%C4%B1+No%3A55+Artuklu+%2F+Mardin',
                ],
                [
                    'city' => 'eregli',
                    'city_label' => 'Konya Ereğli',
                    'lat' => '37.5063',
                    'lng' => '34.0517',
                    'name' => 'Acarkon Store — Ereğli',
                    'address' => 'Yunuslu Mah. Kazım Karabekir Cad. Hayat Sitesi Altı No:48/A Ereğli / Konya',
                    'maps' => 'Yunuslu+Mah.+Kaz%C4%B1m+Karabekir+Cad.+Hayat+Sitesi+Alt%C4%B1+No%3A48%2FA+Ere%C4%9Fli+%2F+Konya',
                ],
                [
                    'city' => 'eskisehir',
                    'city_label' => 'Eskişehir',
                    'lat' => '39.7767',
                    'lng' => '30.5206',
                    'name' => 'Acarkon Store — Eskişehir',
                    'address' => 'Yakında',
                    'maps' => '',
                ],
            ],
        ];
    }
}
