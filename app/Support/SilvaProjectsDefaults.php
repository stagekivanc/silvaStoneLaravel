<?php

namespace App\Support;

use App\Support\Concerns\HasLocalizedDefaults;

class SilvaProjectsDefaults
{
    use HasLocalizedDefaults;

    public static function places(): array
    {
        return \App\Models\ProjectPlace::filterMap();
    }

    public static function types(): array
    {
        return \App\Models\ProjectType::filterMap();
    }

    protected static function baseData(): array
    {
        return [
            'intro' => [
                'kicker' => 'Uygulamalar',
                'title' => 'Projeler',
                'aside' => 'Otel lobisinden villa cephesine. İç mekân, dış mekân ve şehirle süzün.',
            ],
            'filter' => [
                'place_label' => 'Mekân',
                'type_label' => 'Tip',
                'all_cities' => 'Tüm şehirler',
                'count_suffix' => 'proje',
                'reset' => 'Sıfırla',
                'empty_title' => 'Bu seçime uygun proje yok.',
                'empty_reset' => 'Filtrelemeyi sıfırla',
            ],
            'detail' => [
                'notes_kicker' => 'Uygulama',
                'notes_title' => 'Notlar',
                'related_kicker' => 'Keşfet',
                'related_title' => 'Diğer uygulamalar',
                'story_kicker' => 'Hikâye',
                'surface_kicker' => 'Yüzey',
                'surface_hint' => 'Kullanılan panel',
                'cta' => 'Bu uygulamayı konuş',
                'type_projects' => 'projeleri',
                'facts' => [
                    'city' => 'Şehir',
                    'place' => 'Mekân',
                    'type' => 'Tip',
                    'product' => 'Yüzey',
                    'year' => 'Yıl',
                    'area' => 'Alan',
                ],
            ],
        ];
    }

    public static function seedItems(): array
    {
        return [
            [
                'slug' => 'lobi-feature-wall',
                'title' => 'Lobi feature wall',
                'type' => 'otel',
                'place' => 'indoor',
                'city' => 'istanbul',
                'city_label' => 'İstanbul',
                'product_name' => 'Slate Antrasit',
                'year' => '2025',
                'area' => '86 m²',
                'main_image' => 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1600&q=85',
                'gallery' => [
                    'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1600&q=85',
                    'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=1400&q=85',
                    'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=1400&q=85',
                    'silvastone/assets/hero/slate-antrasit.jpg',
                ],
                'lead' => 'İstanbul’da bir butik otel lobisinde kayrak dokulu antrasit yüzey, karşılama duvarını mimari odağa çevirir.',
                'body' => 'Resepsiyon arkası feature wall, sıcak ışık ve doğal taş karakteriyle lobi ölçeğini toparlar. Slate Antrasit paneller 600×1200 mm ebatta, iç mekân uygulamasında hızlı montaj ve düşük yük ile uygulandı. Dikey derz ritmi, otelin sakin ve koyu malzeme paletiyle uyumlu tutuldu.',
                'feats' => ['Feature wall odak', 'Kayrak doku', 'Akşam aydınlatmasına uygun', 'Hafif panel sistemi'],
                'home_status' => true,
                'order' => 1,
            ],
            [
                'slug' => 'restoran-duvari',
                'title' => 'Restoran duvarı',
                'type' => 'restoran',
                'place' => 'indoor',
                'city' => 'ankara',
                'city_label' => 'Ankara',
                'product_name' => 'Rammed Earth Antrasit',
                'year' => '2025',
                'area' => '42 m²',
                'main_image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1400&q=85',
                'gallery' => [
                    'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1400&q=85',
                    'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1400&q=85',
                    'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=1400&q=85',
                ],
                'lead' => 'Ankara’da bir restoran salonu, rammed earth dokusuyla akustik ve görsel derinlik kazandı.',
                'body' => 'Yemek alanı boyunca uzanan antrasit yüzey, masaların sıcak ahşabıyla kontrast kurar. Rammed Earth paneller nem ve lekeye karşı dirençli yapısıyla yoğun kullanıma uygundur. Mutfak açıklığına yakın bölümde kolay temizlenen, anti-kir yüzey tercih edildi.',
                'feats' => ['Yoğun kullanıma uygun', 'Anti-kir yüzey', 'Akustik derinlik', 'Hızlı uygulama'],
                'home_status' => true,
                'order' => 2,
            ],
            [
                'slug' => 'konut-accent-wall',
                'title' => 'Konut accent wall',
                'type' => 'konut',
                'place' => 'indoor',
                'city' => 'konya',
                'city_label' => 'Konya',
                'product_name' => 'Coarse Clothh Krem',
                'year' => '2024',
                'area' => '18 m²',
                'main_image' => 'silvastone/assets/hero/coarse-clothh-krem.jpg',
                'gallery' => [
                    'silvastone/assets/hero/coarse-clothh-krem.jpg',
                    'https://images.unsplash.com/photo-1600210492493-0946911123ea?auto=format&fit=crop&w=1400&q=85',
                    'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=1400&q=85',
                ],
                'lead' => 'Konya’da bir konut oturma odasında kumaş dokulu krem panel, yumuşak ve doğal bir accent wall oluşturur.',
                'body' => 'Televizyon nişi yerine seçilen dokulu yüzey, odayı boğmadan karakter katar. Coarse Clothh Krem, gün ışığında sıcak; akşam LED şeritte ise yumuşak bir rölyef verir. İnce panel yapısı mevcut duvarı kalınlaştırmadan uygulandı.',
                'feats' => ['Kumaş doku', 'Sıcak krem ton', 'İnce kesit', 'Konut ölçeğine uygun'],
                'home_status' => true,
                'order' => 3,
            ],
            [
                'slug' => 'ofis-odak',
                'title' => 'Ofis odak yüzeyi',
                'type' => 'ofis',
                'place' => 'indoor',
                'city' => 'bursa',
                'city_label' => 'Bursa',
                'product_name' => 'Sea Beyaz',
                'year' => '2025',
                'area' => '64 m²',
                'main_image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1400&q=85',
                'gallery' => [
                    'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1400&q=85',
                    'https://images.unsplash.com/photo-1497366811353-6870744d04b2?auto=format&fit=crop&w=1400&q=85',
                    'https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=1400&q=85',
                ],
                'lead' => 'Bursa’da bir ofis toplantı katında açık tonlu taş paneli, marka duvarını sakin tutar.',
                'body' => 'Sea Beyaz yüzey, cam bölmeler ve açık tavanla birlikte çalışır. Toplantı odasının arka duvarında logo ve projeksiyon için düzgün bir zemin; koridor sürekliliğinde ise aynı doku devam eder. Hafif paneller kat teslim programına uyacak hızda monte edildi.',
                'feats' => ['Açık ton', 'Ofis akustik duvarı', 'Hızlı teslim', 'Marka duvarı'],
                'home_status' => true,
                'order' => 4,
            ],
            [
                'slug' => 'villa-cephe',
                'title' => 'Villa cephesi',
                'type' => 'cephe',
                'place' => 'outdoor',
                'city' => 'antalya',
                'city_label' => 'Antalya',
                'product_name' => 'Tetris Antrasit Desing',
                'year' => '2025',
                'area' => '220 m²',
                'main_image' => 'silvastone/assets/hero/tetris-antrasit.jpg',
                'gallery' => [
                    'silvastone/assets/hero/tetris-antrasit.jpg',
                    'https://images.unsplash.com/photo-1600585154340-0efd65870007?auto=format&fit=crop&w=1400&q=85',
                    'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1400&q=85',
                    'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=1400&q=85',
                ],
                'lead' => 'Antalya’da bir villa bahçe duvarı, gece aydınlatmasında derin antrasit blok dokusuyla mimari gölge üretir.',
                'body' => 'Tetris Antrasit Desing paneller dış mekânda, duvar lambaları ve merdiven LED’leriyle birlikte kurgulandı. Dış cepheye uygun incelik ve su direnci, Akdeniz ikliminde uzun ömür için seçildi. Blok ritmi, bahçe peyzajındaki koyu taş zeminle aynı paleti paylaşır.',
                'feats' => ['Dış cephe', 'Gece aydınlatması', 'Su dayanımı', 'Blok doku'],
                'home_status' => true,
                'order' => 5,
            ],
            [
                'slug' => 'salon-tv-duvari',
                'title' => 'Salon TV duvarı',
                'type' => 'konut',
                'place' => 'indoor',
                'city' => 'istanbul',
                'city_label' => 'İstanbul',
                'product_name' => 'Slate Antrasit',
                'year' => '2025',
                'area' => '24 m²',
                'main_image' => 'silvastone/assets/hero/slate-antrasit.jpg',
                'gallery' => [
                    'silvastone/assets/hero/slate-antrasit.jpg',
                    'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&w=1400&q=85',
                    'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=1400&q=85',
                ],
                'lead' => 'İstanbul’da bir salon feature wall, TV ünitesini kayrak dokulu antrasit yüzeyle çerçeveler.',
                'body' => 'Asma tavan ışığı ve yan LED şerit, slate dokunun rölyefini öne çıkarır. Panel inceliği, gizli kablo geçişine ve soundbar nişine yer bırakır. İç mekân uygulaması, mevcut alçıpan üzerine yapıştırma sistemiyle tamamlandı.',
                'feats' => ['TV duvarı', 'Gizli kablo', 'LED uyumu', 'Kayrak doku'],
                'home_status' => true,
                'order' => 6,
            ],
            [
                'slug' => 'otel-spa',
                'title' => 'Spa ıslak hacim',
                'type' => 'otel',
                'place' => 'indoor',
                'city' => 'antalya',
                'city_label' => 'Antalya',
                'product_name' => 'Sea Antrasit',
                'year' => '2024',
                'area' => '38 m²',
                'main_image' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1400&q=85',
                'gallery' => [
                    'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1400&q=85',
                    'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=1400&q=85',
                    'https://images.unsplash.com/photo-1600334129128-685c5582fd35?auto=format&fit=crop&w=1400&q=85',
                ],
                'lead' => 'Antalya otel spa’sında ıslak hacim duvarları, nem kontrolü ve koyu taş karakteriyle çözüldü.',
                'body' => 'Sea Antrasit, nemli ortam ve temizlik rejimine uygun su geçirmez yüzey sunar. Soyunma ve dinlenme koridorunda aynı doku devam ederek spa deneyimini bütünler. Antibakteriyel yüzey, otel operasyonunun hijyen standardına uyar.',
                'feats' => ['Islak hacim', 'Su geçirmez', 'Anti-bakteriyel', 'Nem kontrolü'],
                'home_status' => false,
                'order' => 7,
            ],
            [
                'slug' => 'bahce-duvari',
                'title' => 'Bahçe duvarı',
                'type' => 'konut',
                'place' => 'outdoor',
                'city' => 'tekirdag',
                'city_label' => 'Tekirdağ',
                'product_name' => 'Tetris Krem Desing',
                'year' => '2025',
                'area' => '54 m²',
                'main_image' => 'https://images.unsplash.com/photo-1600585154340-0efd65870007?auto=format&fit=crop&w=1400&q=85',
                'gallery' => [
                    'https://images.unsplash.com/photo-1600585154340-0efd65870007?auto=format&fit=crop&w=1400&q=85',
                    'https://images.unsplash.com/photo-1600047509807-ba8b95e8e6c8?auto=format&fit=crop&w=1400&q=85',
                    'silvastone/assets/hero/tetris-antrasit.jpg',
                ],
                'lead' => 'Tekirdağ’da bir konut bahçesinde krem tetris bloklar, sınır duvarını peyzajın parçası yapar.',
                'body' => 'Dış mekân uygulamasında Tetris Krem Desing, bitki dokusu ve açık taş zeminle uyumlu tutuldu. Güneş ve yağmura karşı dirençli yüzey, bahçe duvarının uzun ömürlü olmasını sağlar. Blok deseni, mesafeden bakıldığında mimari bir doku okutur.',
                'feats' => ['Bahçe duvarı', 'Dış mekân', 'Krem blok doku', 'UV dayanımı'],
                'home_status' => false,
                'order' => 8,
            ],
            [
                'slug' => 'showroom-yuzey',
                'title' => 'Showroom duvarı',
                'type' => 'ofis',
                'place' => 'indoor',
                'city' => 'konya',
                'city_label' => 'Konya',
                'product_name' => 'Mosaic Beyaz',
                'year' => '2025',
                'area' => '48 m²',
                'main_image' => 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=1400&q=85',
                'gallery' => [
                    'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=1400&q=85',
                    'https://images.unsplash.com/photo-1600607687644-c7171b42498b?auto=format&fit=crop&w=1400&q=85',
                    'https://images.unsplash.com/photo-1600566752355-35792bedcfea?auto=format&fit=crop&w=1400&q=85',
                ],
                'lead' => 'Konya Acarkon Store’da mosaic beyaz yüzey, ürünün kendisini sergileyen bir duvar haline gelir.',
                'body' => 'Showroom sergi duvarı hem numune hem mekân kimliği olarak çalışır. Mosaic Beyaz, vitrin ışığında dokuyu okutur; ziyaretçi paneli yakından ve uzaktan deneyimler. Aynı yüzey, danışma arkasında da tekrarlanır.',
                'feats' => ['Showroom sergi', 'Mosaic doku', 'Vitrin ışığı', 'Marka yüzeyi'],
                'home_status' => false,
                'order' => 9,
            ],
            [
                'slug' => 'kafe-teras',
                'title' => 'Kafe teras duvarı',
                'type' => 'restoran',
                'place' => 'outdoor',
                'city' => 'izmir',
                'city_label' => 'İzmir',
                'product_name' => 'Felleving Water Antrasit',
                'year' => '2024',
                'area' => '31 m²',
                'main_image' => 'https://images.unsplash.com/photo-1559339352-11d035aa6de1?auto=format&fit=crop&w=1400&q=85',
                'gallery' => [
                    'https://images.unsplash.com/photo-1559339352-11d035aa6de1?auto=format&fit=crop&w=1400&q=85',
                    'https://images.unsplash.com/photo-1445116572660-236099ec97a0?auto=format&fit=crop&w=1400&q=85',
                    'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1400&q=85',
                ],
                'lead' => 'İzmir’de bir kafe teras duvarı, dış mekân oturma alanına antrasit su dokulu yüzey kazandırır.',
                'body' => 'Felleving Water Antrasit, açık terasın rüzgâr ve nemine uygun seçildi. Dikey ritim, saksı ve metal korkulukla birlikte sakin bir arka plan üretir. Akşam saatlerinde duvar yıkama ışığı dokuyu güçlendirir.',
                'feats' => ['Açık teras', 'Dış mekân', 'Su dokusu', 'Yıkama ışık'],
                'home_status' => false,
                'order' => 10,
            ],
            [
                'slug' => 'otel-cephe',
                'title' => 'Otel dış cephe',
                'type' => 'cephe',
                'place' => 'outdoor',
                'city' => 'mardin',
                'city_label' => 'Mardin',
                'product_name' => 'Tetris Antrasit Desing',
                'year' => '2025',
                'area' => '310 m²',
                'main_image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1400&q=85',
                'gallery' => [
                    'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1400&q=85',
                    'silvastone/assets/hero/tetris-antrasit.jpg',
                    'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1400&q=70',
                    'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1400&q=85',
                ],
                'lead' => 'Mardin’de bir otel cephesi, tetris blok ritmiyle kente yeni bir taş okuması ekler.',
                'body' => 'Dış cephe panelleri, güneş ve toza karşı dayanıklı antrasit yüzeyle uygulandı. Giriş saçağı ve üst kat ritmi aynı dokuyu paylaşır. Hafif sistem, mevcut strüktüre ek yük bindirmeden büyük alan kaplamayı mümkün kıldı.',
                'feats' => ['Büyük cephe', 'Dış iklim', 'Hafif sistem', 'Blok ritmi'],
                'home_status' => false,
                'order' => 11,
            ],
            [
                'slug' => 'residans-salon',
                'title' => 'Rezidans salonu',
                'type' => 'konut',
                'place' => 'indoor',
                'city' => 'ankara',
                'city_label' => 'Ankara',
                'product_name' => 'Banana Leaf Pattern Krem',
                'year' => '2024',
                'area' => '22 m²',
                'main_image' => 'https://images.unsplash.com/photo-1600210492493-0946911123ea?auto=format&fit=crop&w=1400&q=85',
                'gallery' => [
                    'https://images.unsplash.com/photo-1600210492493-0946911123ea?auto=format&fit=crop&w=1400&q=85',
                    'silvastone/assets/hero/coarse-clothh-krem.jpg',
                    'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1400&q=85',
                ],
                'lead' => 'Ankara’da bir rezidans salonunda yaprak desenli krem panel, doğal ve yumuşak bir iç mekân karakteri kurar.',
                'body' => 'Banana Leaf Pattern Krem, oturma grubunun arkasında tek bir duvar olarak kullanıldı. Desen ölçeği salona göre seçildi; fazla tekrar etmeden doğal bir ritim verir. İç mekân, gün ışığı ve abajur ışığında iki farklı okuma sunar.',
                'feats' => ['Desenli yüzey', 'Konut salonu', 'Krem palet', 'Tek duvar odak'],
                'home_status' => false,
                'order' => 12,
            ],
        ];
    }

    /**
     * Full English copy for project translations, keyed by slug.
     *
     * @return array<string, array{title:string,lead:string,body:string,feats:array<int,string>,product_name:string}>
     */
    public static function englishBySlug(): array
    {
        return [
            'lobi-feature-wall' => [
                'title' => 'Lobby feature wall',
                'product_name' => 'Slate Anthracite',
                'lead' => 'In a boutique hotel lobby in Istanbul, a slate-textured anthracite surface turns the welcome wall into an architectural focal point.',
                'body' => 'The feature wall behind reception gathers the lobby scale with warm light and natural stone character. Slate Anthracite panels in 600×1200 mm were installed indoors for fast mounting and low load. The vertical joint rhythm stays aligned with the hotel’s calm, dark material palette.',
                'feats' => ['Feature-wall focus', 'Slate texture', 'Suited to evening lighting', 'Lightweight panel system'],
            ],
            'restoran-duvari' => [
                'title' => 'Restaurant wall',
                'product_name' => 'Rammed Earth Anthracite',
                'lead' => 'A restaurant dining room in Ankara gained acoustic and visual depth with a rammed-earth texture.',
                'body' => 'The anthracite surface running along the dining area contrasts with the warm wood of the tables. Rammed Earth panels suit high traffic with stain-resistant performance. Near the kitchen opening, an easy-clean anti-dirt finish was preferred.',
                'feats' => ['Built for heavy use', 'Anti-dirt surface', 'Acoustic depth', 'Fast installation'],
            ],
            'konut-accent-wall' => [
                'title' => 'Residential accent wall',
                'product_name' => 'Coarse Clothh Cream',
                'lead' => 'In a living room in Konya, a cloth-textured cream panel creates a soft, natural accent wall.',
                'body' => 'The textured surface chosen instead of a TV niche adds character without overwhelming the room. Coarse Clothh Cream reads warm in daylight and gives a gentle relief under evening LED strips. The thin panel build was applied without thickening the existing wall.',
                'feats' => ['Cloth texture', 'Warm cream tone', 'Thin section', 'Scaled for homes'],
            ],
            'ofis-odak' => [
                'title' => 'Office feature surface',
                'product_name' => 'Sea White',
                'lead' => 'On an office meeting floor in Bursa, a light stone panel keeps the brand wall calm.',
                'body' => 'Sea White works with glass partitions and an open ceiling. Behind the meeting room it provides a clean plane for logo and projection; the same texture continues along the corridor. Lightweight panels were mounted to match the floor handover schedule.',
                'feats' => ['Light tone', 'Office acoustic wall', 'Fast delivery', 'Brand wall'],
            ],
            'villa-cephe' => [
                'title' => 'Villa facade',
                'product_name' => 'Tetris Anthracite Design',
                'lead' => 'A villa garden wall in Antalya produces architectural shadow with deep anthracite block texture under night lighting.',
                'body' => 'Tetris Anthracite Design panels were detailed outdoors with wall lights and stair LEDs. Thin build and water resistance suited to Mediterranean climate were selected for longevity. The block rhythm shares the same palette as the dark stone paving in the landscape.',
                'feats' => ['Exterior facade', 'Night lighting', 'Water resistance', 'Block texture'],
            ],
            'salon-tv-duvari' => [
                'title' => 'Living room TV wall',
                'product_name' => 'Slate Anthracite',
                'lead' => 'A living-room feature wall in Istanbul frames the TV unit with a slate-textured anthracite surface.',
                'body' => 'Suspended-ceiling light and side LED strips bring out the slate relief. Panel thinness leaves room for concealed cable runs and a soundbar niche. The indoor installation was completed with a bonding system over existing gypsum board.',
                'feats' => ['TV wall', 'Concealed cabling', 'LED-ready', 'Slate texture'],
            ],
            'otel-spa' => [
                'title' => 'Spa wet area',
                'product_name' => 'Sea Anthracite',
                'lead' => 'Wet-area walls in an Antalya hotel spa were resolved with moisture control and dark stone character.',
                'body' => 'Sea Anthracite offers a waterproof surface suited to humid environments and cleaning regimes. The same texture continues through changing and rest corridors to complete the spa experience. The antibacterial surface aligns with hotel hygiene standards.',
                'feats' => ['Wet area', 'Waterproof', 'Antibacterial', 'Moisture control'],
            ],
            'bahce-duvari' => [
                'title' => 'Garden wall',
                'product_name' => 'Tetris Cream Design',
                'lead' => 'Cream tetris blocks on a residential garden wall in Tekirdağ become part of the landscape.',
                'body' => 'Outdoors, Tetris Cream Design was kept in harmony with planting and light stone paving. A surface resistant to sun and rain supports long life on the garden wall. From a distance, the block pattern reads as architectural texture.',
                'feats' => ['Garden wall', 'Outdoor use', 'Cream block texture', 'UV resistance'],
            ],
            'showroom-yuzey' => [
                'title' => 'Showroom wall',
                'product_name' => 'Mosaic White',
                'lead' => 'At the Acarkon Store in Konya, a mosaic white surface becomes a wall that exhibits the product itself.',
                'body' => 'The showroom display wall works as both sample and spatial identity. Mosaic White reveals texture under vitrine lighting; visitors experience the panel from close and afar. The same surface is repeated behind the reception desk.',
                'feats' => ['Showroom display', 'Mosaic texture', 'Vitrine lighting', 'Brand surface'],
            ],
            'kafe-teras' => [
                'title' => 'Cafe terrace wall',
                'product_name' => 'Felleving Water Anthracite',
                'lead' => 'A cafe terrace wall in Izmir gives the outdoor seating area an anthracite water-textured surface.',
                'body' => 'Felleving Water Anthracite was chosen for wind and moisture on the open terrace. The vertical rhythm creates a calm backdrop with planters and metal railings. In the evening, wall-wash lighting strengthens the texture.',
                'feats' => ['Open terrace', 'Outdoor use', 'Water texture', 'Wash lighting'],
            ],
            'otel-cephe' => [
                'title' => 'Hotel exterior facade',
                'product_name' => 'Tetris Anthracite Design',
                'lead' => 'A hotel facade in Mardin adds a new stone reading to the city with a tetris block rhythm.',
                'body' => 'Exterior panels were applied with an anthracite surface resistant to sun and dust. The entrance canopy and upper-floor rhythm share the same texture. The lightweight system covered a large area without adding excess load to the existing structure.',
                'feats' => ['Large facade', 'Outdoor climate', 'Lightweight system', 'Block rhythm'],
            ],
            'residans-salon' => [
                'title' => 'Residence living room',
                'product_name' => 'Banana Leaf Pattern Cream',
                'lead' => 'In a residence living room in Ankara, a leaf-patterned cream panel builds a soft, natural interior character.',
                'body' => 'Banana Leaf Pattern Cream was used as a single wall behind the seating group. Pattern scale was chosen for the room so it gives a natural rhythm without excessive repetition. The interior reads differently in daylight and under lamp light.',
                'feats' => ['Patterned surface', 'Residential living room', 'Cream palette', 'Single-wall focus'],
            ],
        ];
    }

    public static function englishProductName(?string $name): string
    {
        $name = trim((string) $name);
        if ($name === '') {
            return '';
        }

        $map = [
            'Antrasit' => 'Anthracite',
            'Beyaz' => 'White',
            'Krem' => 'Cream',
            'Siyah' => 'Black',
            'Bej' => 'Beige',
            'Desing' => 'Design',
            'Banane' => 'Banana',
            'Traverten' => 'Travertine',
            'Duvar Paneli' => 'Wall Panel',
        ];

        return str_replace(array_keys($map), array_values($map), $name);
    }
}
