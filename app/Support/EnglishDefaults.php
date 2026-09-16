<?php

namespace App\Support;

class EnglishDefaults
{
    public static function for(string $class): array
    {
        return match ($class) {
            SilvaProductsDefaults::class => self::silvaProducts(),
            SilvaContactDefaults::class => self::silvaContact(),
            SilvaHomepageDefaults::class => self::silvaHomepage(),
            SilvaStoresDefaults::class => self::silvaStores(),
            SilvaProjectsDefaults::class => self::silvaProjects(),
            SilvaContractsDefaults::class => self::silvaContracts(),
            default => [],
        };
    }

    private static function silvaHomepage(): array
    {
        return [
            'hero' => [
                'primary_label' => 'Explore the collection',
                'primary_url' => '#katalog',
                'secondary_label' => 'Showroom',
                'secondary_url' => '/en/stores',
                'spotlight_label' => 'New products',
                'slides' => [
                    [
                        'image' => 'silvastone/assets/hero/tetris-antrasit.jpg',
                        'kicker' => 'Silva Stone · Exterior',
                        'title' => 'Architectural facade',
                        'lead' => 'Deep anthracite block texture for villa and garden walls. Night lighting completes the stone’s shadow play.',
                        'tone' => 'dark',
                    ],
                    [
                        'image' => 'silvastone/assets/hero/slate-antrasit.jpg',
                        'kicker' => 'Silva Stone · Interiors',
                        'title' => 'Living room focus',
                        'lead' => 'Calm anthracite for TV walls and feature walls. A clear architectural centre with slate texture.',
                        'tone' => 'dark',
                    ],
                    [
                        'image' => 'silvastone/assets/hero/coarse-clothh-krem.jpg',
                        'kicker' => 'Silva Stone · Home & hotel',
                        'title' => 'Textured surface',
                        'lead' => 'Cloth-textured cream panel — warm, natural and soft from lobbies to residential interiors.',
                        'tone' => 'light',
                    ],
                ],
            ],
            'intro' => [
                'eyebrow' => 'Acarkon product family',
                'title' => 'We bring the character of natural stone indoors as a light, easy-to-apply surface.',
                'text' => 'Silva Stone offers decorative stone panels that strengthen architectural focus — from hotel lobbies to residential feature walls. Visit Acarkon Stores across Türkiye for samples and orders.',
            ],
            'features' => [
                'title' => 'Product features',
                'subtitle' => 'Advantages for design, installation and everyday use',
                'catalog_label' => 'Browse all features',
                'catalog_url' => 'silvastone/assets/silva-stone-2026-katalog.pdf',
                'items' => [
                    ['title' => 'Fire safety', 'text' => 'A2-class fire resistance.'],
                    ['title' => 'Durability', 'text' => 'Long-lasting surface resistant to outdoor conditions.'],
                    ['title' => 'Flexibility', 'text' => 'Bendable — adapts to curved surfaces.'],
                    ['title' => 'Easy install', 'text' => 'Thin and light; cut, carry and glue.'],
                    ['title' => 'Eco-friendly', 'text' => 'Highly recyclable material content.'],
                    ['title' => 'Breathable', 'text' => 'Supports indoor air quality.'],
                    ['title' => 'Anti-dirt', 'text' => 'Easy to clean, antibacterial surface.'],
                    ['title' => 'Waterproof', 'text' => 'Suitable for wet areas and facades.'],
                    ['title' => 'Thermal control', 'text' => 'Helps temperature balance and energy savings.'],
                    ['title' => 'Moisture control', 'text' => 'Reduces mould and humidity risk.'],
                    ['title' => 'Indoor & outdoor', 'text' => 'Same panel for home, lobby and facade.'],
                    ['title' => 'Custom size', 'text' => '600×1200 mm standard; 1200×2400 / 3000 mm available.'],
                ],
            ],
            'products' => [
                'eyebrow' => 'Collection',
                'title' => 'Products',
                'cta_label' => 'Explore all products',
                'cta_url' => '/en/products',
            ],
            'spaces' => [
                'title' => 'Character for every space',
                'subtitle' => 'Natural depth for feature walls, lobbies and commercial surfaces.',
                'items' => [
                    ['icon' => 'bx-home-alt-2', 'title' => 'Homes', 'text' => 'Calm accent surfaces for TV walls, fireplace niches and living rooms.', 'url' => '/en/projects?type=konut'],
                    ['icon' => 'bx-hotel', 'title' => 'Hotels', 'text' => 'Architectural focus walls for lobbies, reception and corridors.', 'url' => '/en/projects?type=otel'],
                    ['icon' => 'bx-briefcase-alt-2', 'title' => 'Offices', 'text' => 'Calm, easy-care surfaces for meeting rooms and brand walls.', 'url' => '/en/projects?type=ofis'],
                    ['icon' => 'bx-restaurant', 'title' => 'Restaurants', 'text' => 'Texture, stain resistance and quick cleaning for dining and bar walls.', 'url' => '/en/projects?type=restoran'],
                    ['icon' => 'bx-spa', 'title' => 'Clinic & spa', 'text' => 'Moisture-resistant, hygienic and quiet interior surface.', 'url' => '/en/contact'],
                    ['icon' => 'bx-store-alt', 'title' => 'Retail', 'text' => 'Natural stone character for stores, showrooms and entrance facades.', 'url' => '/en/projects?type=cephe'],
                ],
            ],
            'stores' => [
                'eyebrow' => 'Showroom',
                'title' => 'Showrooms & sales points',
                'subtitle' => 'See, touch and order Silva Stone at Acarkon Stores.',
                'cta_label' => 'View all showrooms',
                'cta_url' => '/en/stores',
            ],
            'projects' => [
                'title' => 'Selected applications',
                'subtitle' => 'Real surface stories with Silva Stone panels in hotels, restaurants and homes.',
                'cta_label' => 'All projects',
                'cta_url' => '/en/projects',
                'hint' => 'Swipe to see more applications',
            ],
        ];
    }

    private static function silvaStores(): array
    {
        $items = collect(SilvaStoresDefaults::data('tr')['items'] ?? [])
            ->map(function (array $item) {
                if (trim((string) ($item['address'] ?? '')) === 'Yakında') {
                    $item['address'] = 'Coming soon';
                }

                return $item;
            })
            ->values()
            ->all();

        return [
            'intro' => [
                'kicker' => 'Showroom',
                'title' => 'Sales points',
                'aside' => 'See, touch and order Silva Stone at Acarkon Stores. The same phone line applies at every location.',
            ],
            'filter' => [
                'city_label' => 'City',
                'all_cities' => 'All cities',
                'near_label' => 'Nearest to me',
            ],
            'items' => $items,
        ];
    }

    private static function silvaProjects(): array
    {
        return [
            'intro' => [
                'kicker' => 'Applications',
                'title' => 'Projects',
                'aside' => 'From hotel lobbies to villa facades. Filter by indoor, outdoor and city.',
            ],
            'filter' => [
                'place_label' => 'Place',
                'type_label' => 'Type',
                'all_cities' => 'All cities',
                'count_suffix' => 'projects',
                'reset' => 'Reset',
                'empty_title' => 'No projects match this selection.',
                'empty_reset' => 'Clear filters',
            ],
            'detail' => [
                'notes_kicker' => 'Application',
                'notes_title' => 'Notes',
                'related_kicker' => 'Explore',
                'related_title' => 'Other applications',
                'story_kicker' => 'Story',
                'surface_kicker' => 'Surface',
                'surface_hint' => 'Panel used',
                'cta' => 'Talk about this application',
                'type_projects' => 'projects',
                'facts' => [
                    'city' => 'City',
                    'place' => 'Place',
                    'type' => 'Type',
                    'product' => 'Surface',
                    'year' => 'Year',
                    'area' => 'Area',
                ],
            ],
        ];
    }

    private static function silvaContracts(): array
    {
        return [
            'intro' => [
                'kicker' => 'Legal',
                'title' => 'Contracts',
                'aside' => 'Privacy, notice, cookies, security and KVKK texts. Each contract has its own page.',
            ],
            'items' => collect(SilvaLegalDefaults::types())
                ->map(fn (array $meta, string $type) => [
                    'type' => $type,
                    'number' => $meta['number'],
                    'label' => $meta['name_en'] ?? $meta['name'],
                ])
                ->values()
                ->all(),
        ];
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
                ['type' => 'phone', 'icon' => 'bx-phone', 'label' => 'Phone', 'value' => '+90 850 346 02 26', 'url' => 'tel:+908503460226'],
                ['type' => 'email', 'icon' => 'bx-envelope', 'label' => 'Email', 'value' => 'bilgi@acarkon.com', 'url' => 'mailto:bilgi@acarkon.com'],
                ['type' => 'whatsapp', 'icon' => 'bxl-whatsapp', 'label' => 'WhatsApp', 'value' => '+90 850 346 02 26', 'url' => 'https://wa.me/908503460226'],
                ['type' => 'hours', 'icon' => 'bx-time-five', 'label' => 'Working hours', 'value' => 'Weekdays 09:00 – 18:00', 'url' => ''],
                ['type' => 'address', 'icon' => 'bx-map', 'label' => 'Showroom', 'value' => 'Horozluhan Mahallesi Hotamış Sk. No:49 Selçuklu / Konya', 'url' => ''],
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
