<?php

namespace App\Support;

class EnglishDefaults
{
    public static function for(string $class): array
    {
        return match ($class) {
            SilvaProductsDefaults::class => self::silvaProducts(),
            SilvaContactDefaults::class => self::silvaContact(),
            default => [],
        };
    }

    private static function silvaContact(): array
    {
        return [
            'intro' => [
                'kicker' => 'Contact',
                'title' => "Let's talk about your project",
                'text' => 'Fill in the form for samples, quantities or a showroom visit. Our team will get back to you shortly.',
                'hours' => 'Weekdays 09:00 – 18:00',
            ],
            'channels' => [
                [
                    'type' => 'phone',
                    'icon' => 'bx-phone',
                    'label' => 'Phone',
                    'value' => '+90 850 346 02 26',
                    'url' => 'tel:+908503460226',
                ],
                [
                    'type' => 'email',
                    'icon' => 'bx-envelope',
                    'label' => 'Email',
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
                    'label' => 'Working hours',
                    'value' => 'Weekdays 09:00 – 18:00',
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
                'name_label' => 'Full name',
                'name_placeholder' => 'Your first and last name',
                'phone_label' => 'Phone',
                'phone_placeholder' => '05xx xxx xx xx',
                'email_label' => 'Email',
                'email_placeholder' => 'example@mail.com',
                'city_label' => 'City',
                'city_placeholder' => 'Project city',
                'interest_label' => 'Topic of interest',
                'message_label' => 'Project note',
                'message_placeholder' => 'Space type, approximate area or a short note...',
                'consent_html' => 'I accept the processing of personal data under the <a href="__KVKK__">privacy notice</a> and <a href="__PRIVACY__">privacy policy</a>.',
                'submit_label' => 'Send request',
                'success_message' => 'Thank you — we will get back to you shortly.',
                'interests' => [
                    ['value' => 'catalog', 'label' => 'Collection / sample'],
                    ['value' => 'project', 'label' => 'Project & quantity'],
                    ['value' => 'store', 'label' => 'Showroom visit'],
                    ['value' => 'other', 'label' => 'Other'],
                ],
            ],
            'map' => [
                'title' => 'Headquarters location',
                'iframe_title' => 'Acarkon Showroom Konya',
            ],
        ];
    }

    private static function silvaProducts(): array
    {
        return [
            'intro' => [
                'kicker' => 'Collection',
                'title' => 'Surfaces',
                'aside' => 'Stonex travertine and Stoneart patterned panels. 600×1200 mm. Indoor and outdoor.',
            ],
            'filter' => [
                'all' => 'All',
                'filter_label' => 'Filter',
                'filter_title' => 'Filter',
                'color_label' => 'Color',
                'feature_label' => 'Features',
                'reset' => 'Reset',
                'apply' => 'View products',
                'empty' => 'No products match this selection.',
                'empty_reset' => 'Clear filters',
                'count_suffix' => 'surfaces',
                'size_600x1200' => '600×1200',
                'thick_3_4' => '3–4 mm',
                'thick_3_6' => '3–6 mm',
                'indoor' => 'Suitable for indoor use',
                'outdoor' => 'Suitable for outdoor use',
                'depot' => 'In stock',
            ],
            'seo' => [
                'kicker' => 'Guide',
                'title' => 'Stonex and Stoneart wall panels',
                'more' => 'Read more',
                'body' => [
                    'Silva Stone products are grouped in two collections: Stonex and Stoneart. Panels are produced in 600×1200 mm; Stonex models are 3–4 mm and Stoneart models are 3–6 mm thick. Suitable for indoor and outdoor use. Depot-stocked models can ship immediately.',
                    'Stonex covers travertine and natural-stone surfaces; Stoneart covers slate, mosaic, granite and patterned panels. Filter by white, cream, beige, anthracite and black tones.',
                    'Stone wall panels deliver a natural stone look with light weight and easy installation for interior and exterior spaces.',
                    'For samples, quantity and installation, contact the Acarkon Store network or use the contact form. Custom sizes 1200×2400 and 1200×3000 mm are also available.',
                ],
            ],
            'detail' => [
                'related_kicker' => 'Collection',
                'related_title' => 'Similar surfaces',
                'related_from' => 'From the :cat collection',
                'spec_code' => 'Product code',
                'spec_collection' => 'Collection',
                'spec_color' => 'Color',
                'spec_size' => 'Size',
                'spec_thick' => 'Thickness',
                'spec_extra' => 'Custom order',
                'mm' => 'mm',
                'indoor' => 'Suitable for indoor use',
                'outdoor' => 'Suitable for outdoor use',
                'depot' => 'In stock',
                'add_cart' => 'Add to cart',
                'quote' => 'Get a quote',
                'qty' => 'Qty',
                'image_pending' => 'Product image coming soon',
                'collection_crumb' => 'Collection',
            ],
        ];
    }
}
