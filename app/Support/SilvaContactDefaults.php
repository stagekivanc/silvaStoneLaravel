<?php

namespace App\Support;

use App\Support\Concerns\HasLocalizedDefaults;

class SilvaContactDefaults
{
    use HasLocalizedDefaults;

    protected static function baseData(): array
    {
        return [
            'intro' => [
                'kicker' => 'İletişim',
                'title' => 'Projenizi konuşalım',
                'text' => 'Numune, metraj veya showroom ziyareti için formu doldurun. Ekibimiz en kısa sürede dönüş yapar.',
                'hours' => 'Hafta içi 09:00 – 18:00',
            ],
            'channels' => [
                [
                    'type' => 'phone',
                    'icon' => 'bx-phone',
                    'label' => 'Telefon',
                    'value' => '+90 850 346 02 26',
                    'url' => 'tel:+908503460226',
                ],
                [
                    'type' => 'email',
                    'icon' => 'bx-envelope',
                    'label' => 'E-posta',
                    'value' => 'bilgi@acarkon.com',
                    'url' => 'mailto:bilgi@acarkon.com',
                ],
                [
                    'type' => 'whatsapp',
                    'icon' => 'bxl-whatsapp',
                    'label' => 'WhatsApp',
                    'value' => '+90 850 346 02 26',
                    'url' => 'https://wa.me/908503460226',
                ],
                [
                    'type' => 'hours',
                    'icon' => 'bx-time-five',
                    'label' => 'Çalışma saatleri',
                    'value' => 'Hafta içi 09:00 – 18:00',
                    'url' => '',
                ],
                [
                    'type' => 'address',
                    'icon' => 'bx-map',
                    'label' => 'Showroom',
                    'value' => 'Horozluhan Mahallesi Hotamış Sk. No:49 Selçuklu / Konya',
                    'url' => '',
                ],
            ],
            'form' => [
                'name_label' => 'Ad Soyad',
                'name_placeholder' => 'Adınız ve soyadınız',
                'phone_label' => 'Telefon',
                'phone_placeholder' => '05xx xxx xx xx',
                'email_label' => 'E-posta',
                'email_placeholder' => 'ornek@mail.com',
                'city_label' => 'Şehir',
                'city_placeholder' => 'Proje şehri',
                'interest_label' => 'İlgilendiğiniz konu',
                'message_label' => 'Proje notu',
                'message_placeholder' => 'Mekân tipi, yaklaşık metraj veya kısa notunuz...',
                'consent_html' => 'Kişisel verilerin <a href="__KVKK__">aydınlatma metni</a> ve <a href="__PRIVACY__">gizlilik politikası</a> kapsamında işlenmesini kabul ediyorum.',
                'submit_label' => 'Talebi gönder',
                'success_message' => 'Teşekkürler — en kısa sürede size dönüş yapacağız.',
                'interests' => [
                    ['value' => 'catalog', 'label' => 'Koleksiyon / numune'],
                    ['value' => 'project', 'label' => 'Proje & metraj'],
                    ['value' => 'store', 'label' => 'Showroom ziyareti'],
                    ['value' => 'other', 'label' => 'Diğer'],
                ],
            ],
            'map' => [
                'title' => 'Genel Merkez Konumu',
                'iframe_title' => 'Acarkon Showroom Konya',
                'embed_url' => 'https://maps.google.com/maps?q=Horozluhan%20Mahallesi%20Hotam%C4%B1%C5%9F%20Sk.%20No%3A49%20Sel%C3%A7uklu%20Konya&z=16&output=embed',
            ],
        ];
    }
}
