@extends('frontend.layouts.app')

@section('title', ($product->seo_title ?: $product->name) . ' | Silva Stone')
@section('body_class', 'productDetailPage')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css">
<style>
    .gallery-thumb-scroll::-webkit-scrollbar { height: 4px; width: 4px; }
    .gallery-thumb-scroll::-webkit-scrollbar-thumb { background: #ddd; border-radius: 2px; }
    #mainImage.fade-out { opacity: 0; }
    @keyframes modalSlideUp { from { opacity: 0; transform: translateY(16px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
    .modal-animate { animation: modalSlideUp 0.25s ease-out; }
    @keyframes toastIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
    .toast-show { animation: toastIn 0.3s ease-out; }
    .detail-tab.active { color: #111; }
    .detail-tab-indicator { position: absolute; bottom: 0; left: 0; height: 2px; background: #E50914; transition: left 0.3s ease, width 0.3s ease; pointer-events: none; z-index: 2; }
    .detail-panel { display: none; }
    .detail-panel.active { display: block; }
    .faq-item.open .faq-icon { transform: rotate(45deg); }
    .faq-item.open .faq-body { grid-template-rows: 1fr; }
    .faq-body { display: grid; grid-template-rows: 0fr; transition: grid-template-rows 0.3s ease; }
    .faq-body > div { overflow: hidden; }
</style>
@endpush

@section('content')
@php
    $lang = app()->getLocale();
    $category = $product->category;
    $productsUrl = route('module.dispatcher', ['lang' => $lang, 'module' => 'urunler']);
    $categoryUrl = $category
        ? route('module.dispatcher', ['lang' => $lang, 'module' => 'urunler', 'slug' => $category->slug])
        : $productsUrl;
    $b2bUrl = route('module.dispatcher', ['lang' => $lang, 'module' => 'teklif-al']);
    $shippingUrl = route('module.dispatcher', ['lang' => $lang, 'module' => 'kargo-teslimat']);
    $returnUrl = route('module.dispatcher', ['lang' => $lang, 'module' => 'iade-kosullari']);

    $gallery = collect($product->galleryUrls());
    if ($gallery->isEmpty()) {
        $gallery = collect([front_asset('img/images.jpeg')]);
    }

    $badge = $product->badgeKey();
    $badgeLabel = $product->badgeLabel();
    $sku = $product->skuCode();
    $sizes = $product->sizeOptions();
    $colors = $product->colorOptions();
    $colorHex = $product->colorHexMap();
    $material = $product->materialLabel();
    $features = $product->featureList();
    $description = trim(strip_tags((string) ($product->short_description ?: $product->description)));
    $isAyakkabi = $product->isFootwear();
    $firstSize = $sizes[0] ?? 'M';
    $firstColor = $colors[0] ?? 'siyah';
    $whatsapp = preg_replace('/\D+/', '', (string) \App\Models\Setting::get('contact_whatsapp', '905555555555')) ?: '905555555555';
    $waText = 'Merhaba, ' . $product->name . ' (' . $sku . ') — Beden: ' . $firstSize . ', Adet: 1 hakkında teklif almak istiyorum.';
    $shareText = $product->name . ' — ' . url()->current();
    $altKategori = $category?->name ?: 'Ürün';
    $anaKategori = $category?->name ?: 'Tüm Ürünler';

    $sss = collect(setting_json('product_detail_faqs'))
        ->map(function ($item) {
            return [
                's' => trim((string) ($item['question'] ?? $item['s'] ?? '')),
                'c' => trim((string) ($item['answer'] ?? $item['c'] ?? '')),
            ];
        })
        ->filter(fn ($item) => $item['s'] !== '' || $item['c'] !== '')
        ->values()
        ->all();

    $shoeChart = setting_json('product_size_chart_shoes');
    $apparelChart = setting_json('product_size_chart_apparel');
    $ayakkabiBedenler = array_values($shoeChart['rows'] ?? []);
    $giyimBedenler = array_values($apparelChart['rows'] ?? []);
    $shoeHeaders = array_values($shoeChart['headers'] ?? []);
    $apparelHeaders = array_values($apparelChart['headers'] ?? []);
    $shoeNote = (string) ($shoeChart['note'] ?? '');
    $apparelNote = (string) ($apparelChart['note'] ?? '');
    $shoeFooter = (string) ($shoeChart['footer'] ?? '');
    $apparelFooter = (string) ($apparelChart['footer'] ?? '');

    $shippingCargoTitle = (string) \App\Models\Setting::get('product_shipping_cargo_title', '');
    $shippingCargoText = (string) \App\Models\Setting::get('product_shipping_cargo_text', '');
    $shippingDeliveryTitle = (string) \App\Models\Setting::get('product_shipping_delivery_title', '');
    $shippingDeliveryText = (string) \App\Models\Setting::get('product_shipping_delivery_text', '');
    $shippingReturnTitle = (string) \App\Models\Setting::get('product_shipping_return_title', '');
    $shippingReturnText = (string) \App\Models\Setting::get('product_shipping_return_text', '');

    $promoEyebrow = (string) \App\Models\Setting::get('product_promo_eyebrow', '');
    $promoTitle = (string) \App\Models\Setting::get('product_promo_title', '');
    $promoText = (string) \App\Models\Setting::get('product_promo_text', '');
    $promoCta = (string) \App\Models\Setting::get('product_promo_cta', '');
    $promoItems = [
        [
            'title' => (string) \App\Models\Setting::get('product_promo_item1_title', ''),
            'text' => (string) \App\Models\Setting::get('product_promo_item1_text', ''),
            'icon' => 'bx-palette',
        ],
        [
            'title' => (string) \App\Models\Setting::get('product_promo_item2_title', ''),
            'text' => (string) \App\Models\Setting::get('product_promo_item2_text', ''),
            'icon' => 'bx-package',
        ],
        [
            'title' => (string) \App\Models\Setting::get('product_promo_item3_title', ''),
            'text' => (string) \App\Models\Setting::get('product_promo_item3_text', ''),
            'icon' => 'bx-time-five',
        ],
    ];
@endphp

<div class="w-full bg-white min-h-screen pb-24 lg:pb-0">
    <section class="w-full border-b border-gray-100 bg-brand-gray">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-8 py-6 md:py-8">
            <nav class="text-xs text-gray-400 uppercase tracking-[0.15em]">
                <a href="{{ route('home', ['lang' => $lang]) }}" class="hover:text-brand-red transition-colors">Ana Sayfa</a>
                <span class="mx-2">/</span>
                <a href="{{ $productsUrl }}" class="hover:text-brand-red transition-colors">Tüm Ürünler</a>
                @if($category)
                    <span class="mx-2">/</span>
                    <a href="{{ $categoryUrl }}" class="hover:text-brand-red transition-colors">{{ $anaKategori }}</a>
                @endif
                <span class="mx-2">/</span>
                <span class="text-gray-600">{{ $product->name }}</span>
            </nav>
        </div>
    </section>

    <section class="w-full py-10 md:py-16">
        <div class="max-w-[1600px] mx-auto max-md:px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 xl:gap-20">
                <div class="flex flex-col-reverse md:flex-row gap-3 md:gap-4 lg:gap-5">
                    @if($gallery->count() > 1)
                    <div class="flex md:flex-col gap-2 md:w-[68px] lg:w-[76px] shrink-0 overflow-x-auto md:overflow-y-auto md:max-h-[min(680px,70vh)] gallery-thumb-scroll pb-1 md:pb-0">
                        @foreach($gallery as $i => $img)
                        <button type="button" class="thumb-btn relative w-[52px] md:w-full aspect-[3/4] bg-[#f5f5f5] overflow-hidden border-2 transition-all duration-300 shrink-0 {{ $i === 0 ? 'border-brand-dark opacity-100' : 'border-transparent opacity-60 hover:opacity-100 hover:border-gray-300' }}" data-index="{{ $i }}" data-image="{{ $img }}" aria-label="Görsel {{ $i + 1 }}">
                            <img src="{{ $img }}" alt="" class="w-full h-full object-cover mix-blend-multiply">
                        </button>
                        @endforeach
                    </div>
                    @endif

                    <div class="flex-1 min-w-0">
                        <div id="mainGallery" class="relative group/main w-full aspect-[4/5] bg-[#f5f5f5] overflow-hidden touch-pan-y">
                            <a href="{{ $gallery->first() }}" data-fancybox="product-gallery" data-caption="{{ $product->name }}" id="mainImageLink" class="block w-full h-full cursor-zoom-in">
                                <img id="mainImage" src="{{ $gallery->first() }}" alt="{{ $product->name }}" class="w-full h-full object-cover mix-blend-multiply transition-opacity duration-300 select-none">
                            </a>
                            @foreach($gallery as $i => $img)
                                @if($i > 0)
                                    <a href="{{ $img }}" data-fancybox="product-gallery" data-caption="{{ $product->name }}" class="hidden gallery-link"></a>
                                @endif
                            @endforeach

                            @if($badge !== '')
                                <span class="absolute top-4 left-4 z-10 px-3 py-1.5 {{ $badge === 'yeni' ? 'bg-brand-dark' : 'bg-brand-red' }} text-white text-[9px] font-bold tracking-widest uppercase pointer-events-none">{{ $badgeLabel }}</span>
                            @endif

                            @if($gallery->count() > 1)
                                <span id="imageCounter" class="absolute top-4 right-4 z-10 px-2.5 py-1 bg-white/90 backdrop-blur text-[10px] font-bold tracking-widest text-gray-700 pointer-events-none">1 / {{ $gallery->count() }}</span>
                            @endif

                            <span class="absolute bottom-4 left-1/2 -translate-x-1/2 z-10 px-3 py-1.5 bg-white/90 backdrop-blur text-[9px] font-bold tracking-[0.2em] text-gray-500 uppercase pointer-events-none opacity-0 group-hover/main:opacity-100 transition-opacity duration-300 hidden md:flex items-center gap-1.5">
                                <i class="bx bx-expand-alt text-sm"></i> Büyütmek için tıklayın
                            </span>

                            @if($gallery->count() > 1)
                            <button type="button" id="imgPrev" class="gallery-nav absolute left-3 top-1/2 -translate-y-1/2 z-10 w-10 h-10 md:w-11 md:h-11 rounded-full bg-white/95 backdrop-blur flex items-center justify-center text-gray-900 hover:bg-brand-dark hover:text-white transition-all opacity-100 md:opacity-0 md:group-hover/main:opacity-100 shadow-sm border border-white/50" aria-label="Önceki görsel">
                                <i class="bx bx-chevron-left text-2xl"></i>
                            </button>
                            <button type="button" id="imgNext" class="gallery-nav absolute right-3 top-1/2 -translate-y-1/2 z-10 w-10 h-10 md:w-11 md:h-11 rounded-full bg-white/95 backdrop-blur flex items-center justify-center text-gray-900 hover:bg-brand-dark hover:text-white transition-all opacity-100 md:opacity-0 md:group-hover/main:opacity-100 shadow-sm border border-white/50" aria-label="Sonraki görsel">
                                <i class="bx bx-chevron-right text-2xl"></i>
                            </button>
                            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-10 flex gap-1.5 md:hidden">
                                @foreach($gallery as $i => $img)
                                    <span class="dot-indicator w-1.5 h-1.5 rounded-full transition-all duration-300 {{ $i === 0 ? 'bg-brand-dark w-4' : 'bg-gray-300' }}" data-index="{{ $i }}"></span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex flex-col lg:sticky lg:top-[4.5rem] lg:self-start" id="productInfo">
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        @if($badge !== '')
                            <span class="px-2.5 py-1 {{ $badge === 'yeni' ? 'bg-brand-dark' : 'bg-brand-red' }} text-white text-[9px] font-bold tracking-widest uppercase">{{ $badgeLabel }}</span>
                        @endif
                        <span class="px-2.5 py-1 bg-brand-gray text-gray-600 text-[9px] font-bold tracking-widest uppercase">{{ $altKategori }}</span>
                        <span class="px-2.5 py-1 border border-emerald-200 bg-emerald-50 text-emerald-700 text-[9px] font-bold tracking-widest uppercase flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Stokta
                        </span>
                    </div>

                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-light text-gray-900 tracking-tight leading-tight mb-4">{{ $product->name }}</h1>
                    @if($description !== '')
                        <p class="text-sm text-gray-500 font-light leading-relaxed mb-6">{{ $description }}</p>
                    @endif

                    <div class="flex flex-wrap items-center gap-3 mb-6 pb-6 border-b border-gray-100">
                        <span class="inline-flex items-center gap-2 px-3 py-2 bg-brand-gray text-xs text-gray-600">
                            <i class="bx bx-barcode text-brand-red"></i>
                            <span>SKU: <strong class="text-gray-800">{{ $sku }}</strong></span>
                        </span>
                        <span class="inline-flex items-center gap-2 px-3 py-2 bg-brand-gray text-xs text-gray-600">
                            <i class="bx bx-layer text-brand-red"></i>
                            {{ $material }}
                        </span>
                        <a href="{{ $categoryUrl }}" class="inline-flex items-center gap-1.5 px-3 py-2 border border-gray-200 text-xs text-gray-500 hover:border-brand-red hover:text-brand-red transition-colors">
                            <i class="bx bx-category text-sm"></i>
                            {{ $anaKategori }}
                        </a>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-xs font-bold text-gray-900 uppercase tracking-[0.2em] mb-4">Renk</h3>
                        <div class="flex flex-wrap gap-3">
                            @foreach($colors as $i => $renk)
                                @php $hex = $colorHex[$renk] ?? '#ccc'; @endphp
                                <button type="button" class="color-btn flex items-center gap-2 px-3 py-2 border transition-colors {{ $i === 0 ? 'border-brand-dark bg-gray-50' : 'border-gray-200 hover:border-gray-400' }}" data-color="{{ $renk }}">
                                    <span class="w-5 h-5 rounded-full border border-gray-200 shrink-0" style="background-color: {{ $hex }}"></span>
                                    <span class="text-xs text-gray-600 capitalize">{{ $renk }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-10">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-[0.2em]">Beden</h3>
                            <div class="flex items-center gap-4">
                                <span id="selectedSizeLabel" class="text-[10px] text-gray-400">{{ $firstSize }}</span>
                                <button type="button" id="sizeChartBtn" class="text-[10px] font-bold tracking-widest text-gray-400 hover:text-brand-red uppercase transition-colors flex items-center gap-1">
                                    <i class="bx bx-ruler text-sm"></i> Beden Tablosu
                                </button>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($sizes as $i => $beden)
                                <button type="button" class="size-btn min-w-[48px] h-12 px-4 border text-xs font-medium transition-colors {{ $i === 0 ? 'bg-brand-dark text-white border-brand-dark' : 'border-gray-200 text-gray-600 hover:border-gray-900' }}" data-size="{{ $beden }}">
                                    {{ $beden }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-xs font-bold text-gray-900 uppercase tracking-[0.2em] mb-4">Adet</h3>
                        <div class="inline-flex items-center border border-gray-200">
                            <button type="button" id="qtyMinus" class="w-12 h-12 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors text-lg">−</button>
                            <span id="qtyValue" class="w-14 h-12 flex items-center justify-center text-sm font-medium text-gray-900 border-x border-gray-200">1</span>
                            <button type="button" id="qtyPlus" class="w-12 h-12 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-colors text-lg">+</button>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2">Toptan siparişlerde özel fiyatlandırma uygulanır.</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 mb-6">
                        <button type="button" id="addToCartBtn" class="flex-1 py-4 bg-brand-dark text-white text-xs font-bold tracking-[0.2em] uppercase hover:bg-brand-red transition-colors flex items-center justify-center gap-2">
                            <i class="bx bx-cart text-lg"></i> Sepete Ekle
                        </button>
                        <a id="whatsappBtn" href="https://wa.me/{{ $whatsapp }}?text={{ urlencode($waText) }}" target="_blank" class="flex-1 py-4 bg-[#25D366] text-white text-xs font-bold tracking-[0.2em] uppercase hover:bg-[#1da851] transition-colors flex items-center justify-center gap-2">
                            <i class="bx bxl-whatsapp text-lg"></i> Teklif Al
                        </a>
                    </div>

                    <div class="flex items-center gap-3 mb-8 pb-8 border-b border-gray-100">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Paylaş</span>
                        <button type="button" id="copyLinkBtn" class="w-9 h-9 border border-gray-200 flex items-center justify-center text-gray-500 hover:border-brand-red hover:text-brand-red transition-colors" title="Linki kopyala">
                            <i class="bx bx-link text-lg"></i>
                        </button>
                        <a href="https://wa.me/?text={{ urlencode($shareText) }}" target="_blank" class="w-9 h-9 border border-gray-200 flex items-center justify-center text-gray-500 hover:border-[#25D366] hover:text-[#25D366] transition-colors" title="WhatsApp'ta paylaş">
                            <i class="bx bxl-whatsapp text-lg"></i>
                        </a>
                        <button type="button" id="printBtn" class="w-9 h-9 border border-gray-200 flex items-center justify-center text-gray-500 hover:border-gray-900 hover:text-gray-900 transition-colors" title="Yazdır">
                            <i class="bx bx-printer text-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="w-full border-y border-gray-100">
        <div class="max-w-[1600px] mx-auto max-md:px-4">
            <div class="grid grid-cols-1 lg:grid-cols-12 items-stretch min-h-[140px] lg:min-h-0">
                <div class="lg:col-span-4 px-6 md:px-10 py-8 md:py-10 flex flex-col justify-center" style="background: linear-gradient(135deg, #111111 0%, #1a1a1a 100%);">
                    @if($promoEyebrow !== '')
                    <span class="text-[10px] font-bold tracking-[0.3em] text-brand-red uppercase mb-2">{{ $promoEyebrow }}</span>
                    @endif
                    @if($promoTitle !== '')
                    <h2 class="text-xl md:text-2xl font-light text-white tracking-tight mb-3">{{ $promoTitle }}</h2>
                    @endif
                    @if($promoText !== '')
                    <p class="text-xs text-gray-400 font-light leading-relaxed max-w-sm mb-6">{{ $promoText }}</p>
                    @endif
                    @if($promoCta !== '')
                    <a href="{{ $b2bUrl }}" class="inline-flex items-center justify-center gap-2 self-start px-6 py-3 bg-brand-red text-white text-[10px] font-bold tracking-[0.2em] uppercase hover:bg-white hover:text-gray-900 transition-colors">
                        {{ $promoCta }}
                        <i class="bx bx-right-arrow-alt text-base"></i>
                    </a>
                    @endif
                </div>
                <div class="lg:col-span-8 pl-6 md:pl-10 pr-6 md:pr-10 py-8 md:py-10 bg-white flex items-center border-t lg:border-t-0 lg:border-l border-gray-100">
                    <div class="grid grid-cols-1 sm:grid-cols-3 w-full">
                        @foreach($promoItems as $idx => $promoItem)
                        @if(($promoItem['title'] ?? '') !== '' || ($promoItem['text'] ?? '') !== '')
                        <div class="flex items-start gap-3 {{ $idx === 0 ? 'sm:pr-6 lg:pr-10 border-b sm:border-b-0 sm:border-r border-gray-200 pb-6 sm:pb-0' : ($idx === 1 ? 'sm:px-6 lg:px-10 border-b sm:border-b-0 sm:border-r border-gray-200 py-6 sm:py-0' : 'sm:pl-6 lg:pl-10 pt-6 sm:pt-0') }}">
                            <div class="w-10 h-10 shrink-0 flex items-center justify-center border-2 border-gray-400 rounded-md text-gray-400 mt-0.5">
                                <i class="bx {{ $promoItem['icon'] }} text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                @if(($promoItem['title'] ?? '') !== '')
                                <p class="text-[10px] font-bold text-gray-900 uppercase tracking-widest mb-1.5">{{ $promoItem['title'] }}</p>
                                @endif
                                @if(($promoItem['text'] ?? '') !== '')
                                <p class="text-xs text-gray-500 font-light leading-relaxed pr-1 sm:pr-0">{{ $promoItem['text'] }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="w-full border-t border-gray-100 py-14 md:py-20 bg-white">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10 lg:mb-12">
                <div>
                    <span class="text-xs font-semibold tracking-[0.3em] text-gray-400 uppercase mb-3 block">DETAYLAR</span>
                    <h2 class="text-2xl lg:text-3xl font-light text-gray-900 tracking-tight">Ürün Hakkında</h2>
                </div>
                <p class="text-xs text-gray-400 uppercase tracking-widest">{{ $sku }}</p>
            </div>

            <div class="border-b border-gray-200 mb-10">
                <div class="relative flex overflow-x-auto no-scrollbar" role="tablist" aria-label="Ürün detay sekmeleri">
                    <button type="button" role="tab" aria-selected="true" aria-controls="panel-aciklama" id="tab-aciklama" class="detail-tab active shrink-0 px-0 mr-8 md:mr-12 pb-4 text-xs font-bold uppercase tracking-[0.15em] text-gray-400 hover:text-gray-900 transition-colors border-b-2 border-transparent" data-tab="aciklama">Açıklama</button>
                    <button type="button" role="tab" aria-selected="false" aria-controls="panel-ozellikler" id="tab-ozellikler" class="detail-tab shrink-0 px-0 mr-8 md:mr-12 pb-4 text-xs font-bold uppercase tracking-[0.15em] text-gray-400 hover:text-gray-900 transition-colors border-b-2 border-transparent" data-tab="ozellikler">Özellikler</button>
                    <button type="button" role="tab" aria-selected="false" aria-controls="panel-kargo" id="tab-kargo" class="detail-tab shrink-0 px-0 mr-8 md:mr-12 pb-4 text-xs font-bold uppercase tracking-[0.15em] text-gray-400 hover:text-gray-900 transition-colors border-b-2 border-transparent" data-tab="kargo">Kargo & İade</button>
                    @if(count($sss) > 0)
                    <button type="button" role="tab" aria-selected="false" aria-controls="panel-sss" id="tab-sss" class="detail-tab shrink-0 px-0 pb-4 text-xs font-bold uppercase tracking-[0.15em] text-gray-400 hover:text-gray-900 transition-colors border-b-2 border-transparent" data-tab="sss">SSS</button>
                    @endif
                    <div id="tabIndicator" class="detail-tab-indicator" style="width: 0;"></div>
                </div>
            </div>

            <div id="panel-aciklama" role="tabpanel" aria-labelledby="tab-aciklama" class="detail-panel active">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-start">
                    <div class="space-y-5">
                        <p class="text-sm md:text-base text-gray-600 font-light leading-relaxed">{{ $description !== '' ? $description : $product->name }}</p>
                        <p class="text-sm text-gray-500 font-light leading-relaxed">Silva Stone kalitesiyle üretilen bu ürün, kurumsal logo baskı ve nakış uygulamaları ile kişiselleştirilebilir. Toptan siparişlerde özel fiyatlandırma sunulmaktadır.</p>
                    </div>
                    <div class="border border-gray-100 p-6 md:p-8 bg-white">
                        <h4 class="text-[10px] font-bold text-gray-900 uppercase tracking-[0.25em] mb-6">Ürün Bilgileri</h4>
                        <dl class="space-y-0 text-sm divide-y divide-gray-100">
                            <div class="flex justify-between gap-6 py-3.5">
                                <dt class="text-gray-400 shrink-0">SKU</dt>
                                <dd class="text-gray-900 font-medium text-right">{{ $sku }}</dd>
                            </div>
                            <div class="flex justify-between gap-6 py-3.5">
                                <dt class="text-gray-400 shrink-0">Kumaş</dt>
                                <dd class="text-gray-900 font-medium text-right">{{ $material }}</dd>
                            </div>
                            <div class="flex justify-between gap-6 py-3.5">
                                <dt class="text-gray-400 shrink-0">Kategori</dt>
                                <dd class="text-gray-900 font-medium text-right">{{ $anaKategori }}</dd>
                            </div>
                            <div class="flex justify-between gap-6 py-3.5">
                                <dt class="text-gray-400 shrink-0">Bedenler</dt>
                                <dd class="text-gray-900 font-medium text-right">{{ implode(', ', $sizes) }}</dd>
                            </div>
                            <div class="flex justify-between gap-6 py-3.5 items-center">
                                <dt class="text-gray-400 shrink-0">Renkler</dt>
                                <dd class="flex flex-wrap gap-1.5 justify-end">
                                    @foreach($colors as $renk)
                                        <span class="w-4 h-4 rounded-full border border-gray-200" style="background:{{ $colorHex[$renk] ?? '#ccc' }}" title="{{ ucfirst($renk) }}"></span>
                                    @endforeach
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <div id="panel-ozellikler" role="tabpanel" aria-labelledby="tab-ozellikler" class="detail-panel" hidden>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-start">
                    <ul class="space-y-4">
                        @foreach($features as $ozellik)
                        <li class="flex items-start gap-3 text-sm text-gray-600">
                            <i class="bx bx-check text-brand-red text-lg shrink-0 mt-0.5"></i>
                            {{ $ozellik }}
                        </li>
                        @endforeach
                    </ul>
                    <div class="border border-gray-100 p-6 md:p-8">
                        <h4 class="text-[10px] font-bold text-gray-900 uppercase tracking-[0.25em] mb-6">Teknik Detaylar</h4>
                        <dl class="space-y-0 text-sm divide-y divide-gray-100">
                            <div class="flex justify-between gap-6 py-3.5">
                                <dt class="text-gray-400">Kumaş</dt>
                                <dd class="text-gray-900 font-medium text-right">{{ $material }}</dd>
                            </div>
                            <div class="flex justify-between gap-6 py-3.5">
                                <dt class="text-gray-400">Alt Kategori</dt>
                                <dd class="text-gray-900 font-medium text-right">{{ $altKategori }}</dd>
                            </div>
                            <div class="flex justify-between gap-6 py-3.5">
                                <dt class="text-gray-400">SKU</dt>
                                <dd class="text-gray-900 font-medium">{{ $sku }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <div id="panel-kargo" role="tabpanel" aria-labelledby="tab-kargo" class="detail-panel" hidden>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <i class="bx bxs-truck text-brand-red text-lg"></i>
                            <span class="text-[10px] font-bold text-gray-900 uppercase tracking-widest">{{ $shippingCargoTitle }}</span>
                        </div>
                        <p class="text-sm text-gray-500 font-light leading-relaxed">{{ $shippingCargoText }}</p>
                        @if($shippingUrl)
                        <a href="{{ $shippingUrl }}" class="inline-block mt-3 text-[10px] font-bold uppercase tracking-widest text-brand-red hover:text-gray-900">Kargo detayları</a>
                        @endif
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <i class="bx bx-time-five text-brand-red text-lg"></i>
                            <span class="text-[10px] font-bold text-gray-900 uppercase tracking-widest">{{ $shippingDeliveryTitle }}</span>
                        </div>
                        <p class="text-sm text-gray-500 font-light leading-relaxed">{{ $shippingDeliveryText }}</p>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <i class="bx bx-refresh text-brand-red text-lg"></i>
                            <span class="text-[10px] font-bold text-gray-900 uppercase tracking-widest">{{ $shippingReturnTitle }}</span>
                        </div>
                        <p class="text-sm text-gray-500 font-light leading-relaxed">{{ $shippingReturnText }}</p>
                        @if($returnUrl)
                        <a href="{{ $returnUrl }}" class="inline-block mt-3 text-[10px] font-bold uppercase tracking-widest text-brand-red hover:text-gray-900">İade koşulları</a>
                        @endif
                    </div>
                </div>
            </div>

            @if(count($sss) > 0)
            <div id="panel-sss" role="tabpanel" aria-labelledby="tab-sss" class="detail-panel" hidden>
                <div class="max-w-2xl divide-y divide-gray-100">
                    @foreach($sss as $i => $item)
                    <div class="faq-item {{ $i === 0 ? 'open' : '' }}">
                        <button type="button" class="faq-toggle w-full flex items-center justify-between gap-4 py-5 text-left">
                            <span class="text-sm font-medium text-gray-900">{{ $item['s'] }}</span>
                            <i class="faq-icon bx bx-plus text-xl text-gray-400 shrink-0 transition-transform duration-300"></i>
                        </button>
                        <div class="faq-body">
                            <div>
                                <p class="pb-5 text-sm text-gray-500 font-light leading-relaxed">{{ $item['c'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </section>

    @if(($relatedProducts ?? collect())->isNotEmpty())
    <section class="w-full py-12 md:py-16 border-t border-gray-100 overflow-hidden">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-6 lg:mb-10 gap-4">
                <div>
                    <h2 class="text-xs lg:text-sm font-semibold tracking-[0.3em] text-gray-400 uppercase mb-3">İLGİNİZİ ÇEKEBİLİR</h2>
                    <h3 class="text-2xl lg:text-3xl font-light text-gray-900 tracking-tight">Benzer Ürünler</h3>
                </div>
                <div class="hidden md:flex items-center gap-6">
                    <button type="button" id="relatedPrev" class="text-[11px] font-medium tracking-widest text-gray-400 hover:text-gray-900 uppercase border-b border-transparent hover:border-gray-900 pb-1 transition-all disabled:opacity-30 disabled:pointer-events-none">ÖNCEKİ</button>
                    <button type="button" id="relatedNext" class="text-[11px] font-medium tracking-widest text-gray-400 hover:text-gray-900 uppercase border-b border-transparent hover:border-gray-900 pb-1 transition-all disabled:opacity-30 disabled:pointer-events-none">SONRAKİ</button>
                </div>
            </div>

            <div id="relatedSlider" class="flex overflow-x-auto snap-x snap-mandatory no-scrollbar scroll-smooth gap-3 md:gap-4 lg:gap-5 pb-2 -mx-4 px-4 sm:mx-0 sm:px-0 lg:overflow-hidden">
                @foreach($relatedProducts as $urun)
                    @include('frontend.partials.product-card', [
                        'product' => $urun,
                        'extraClass' => 'snap-start shrink-0 w-[48vw] sm:w-[32vw] md:w-[22vw] lg:w-[calc(20%-1rem)]',
                    ])
                @endforeach
            </div>

            <div class="flex md:hidden items-center justify-center gap-8 mt-6">
                <button type="button" id="relatedPrevMobile" class="w-10 h-10 border border-gray-200 flex items-center justify-center text-gray-600 hover:border-gray-900 transition-colors">
                    <i class="bx bx-chevron-left text-xl"></i>
                </button>
                <button type="button" id="relatedNextMobile" class="w-10 h-10 border border-gray-200 flex items-center justify-center text-gray-600 hover:border-gray-900 transition-colors">
                    <i class="bx bx-chevron-right text-xl"></i>
                </button>
            </div>
        </div>
    </section>
    @endif

    <div id="sizeModal" class="fixed inset-0 z-[200] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" id="sizeModalOverlay"></div>
        <div id="sizeModalContent" class="relative bg-white w-full max-w-lg max-h-[90vh] overflow-hidden shadow-[0_30px_60px_-15px_rgba(0,0,0,0.2)] modal-animate">
            <div class="w-full h-[2px] bg-brand-red"></div>
            <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-5 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-[0.15em]">Beden Tablosu</h3>
                    <p class="text-[10px] text-gray-400 mt-1">{{ $isAyakkabi ? 'Ayakkabı ölçüleri' : 'Giyim beden ölçüleri' }}</p>
                </div>
                <button type="button" id="sizeModalClose" class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-gray-900 hover:bg-gray-50 rounded-full transition-colors" aria-label="Kapat">
                    <i class="bx bx-x text-2xl"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-80px)]">
                @if($isAyakkabi)
                    @if($shoeNote !== '')
                    <p class="text-xs text-gray-500 mb-4 leading-relaxed">{{ $shoeNote }}</p>
                    @endif
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    @foreach($shoeHeaders as $hi => $header)
                                    <th class="py-3 {{ $hi === 0 ? 'pr-4' : ($hi === count($shoeHeaders) - 1 ? 'pl-4' : 'px-4') }} font-bold text-gray-900 uppercase tracking-wider">{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="text-gray-600">
                                @foreach($ayakkabiBedenler as $j => $row)
                                <tr class="{{ $j < count($ayakkabiBedenler) - 1 ? 'border-b border-gray-100' : '' }} hover:bg-brand-gray transition-colors">
                                    @foreach($row as $ci => $cell)
                                        <td class="py-3 {{ $ci === 0 ? 'pr-4 font-medium text-gray-900' : ($ci === count($row) - 1 ? 'pl-4' : 'px-4') }}">{{ $cell }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($shoeFooter !== '')
                    <p class="text-[10px] text-gray-400 mt-5 leading-relaxed">{{ $shoeFooter }}</p>
                    @endif
                @else
                    @if($apparelNote !== '')
                    <p class="text-xs text-gray-500 mb-4 leading-relaxed">{{ $apparelNote }}</p>
                    @endif
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    @foreach($apparelHeaders as $hi => $header)
                                    <th class="py-3 {{ $hi === 0 ? 'pr-4' : ($hi === count($apparelHeaders) - 1 ? 'pl-4' : 'px-4') }} font-bold text-gray-900 uppercase tracking-wider">{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="text-gray-600">
                                @foreach($giyimBedenler as $j => $row)
                                <tr class="{{ $j < count($giyimBedenler) - 1 ? 'border-b border-gray-100' : '' }} hover:bg-brand-gray transition-colors">
                                    @foreach($row as $ci => $cell)
                                        <td class="py-3 {{ $ci === 0 ? 'pr-4 font-medium text-gray-900' : ($ci === count($row) - 1 ? 'pl-4' : 'px-4') }}">{{ $cell }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($apparelFooter !== '')
                    <p class="text-[10px] text-gray-400 mt-5 leading-relaxed">{{ $apparelFooter }}</p>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <div id="cartAddedModal" class="fixed inset-0 z-[200] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" id="cartAddedModalOverlay"></div>
        <div id="cartAddedModalContent" class="relative bg-white w-full max-w-md overflow-hidden shadow-[0_30px_60px_-15px_rgba(0,0,0,0.2)] modal-animate">
            <div class="w-full h-[2px] bg-brand-red"></div>
            <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-5 flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 shrink-0 flex items-center justify-center bg-emerald-50 text-emerald-600 rounded-full">
                        <i class="bx bx-check text-xl"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-[0.15em]">Sepete Eklendi</h3>
                        <p class="text-[10px] text-gray-400 mt-1">Ürün sepetinize başarıyla eklendi</p>
                    </div>
                </div>
                <button type="button" id="cartAddedModalClose" class="w-9 h-9 shrink-0 flex items-center justify-center text-gray-400 hover:text-gray-900 hover:bg-gray-50 rounded-full transition-colors" aria-label="Kapat">
                    <i class="bx bx-x text-2xl"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="flex gap-4 mb-6 pb-6 border-b border-gray-100">
                    <img id="cartAddedImage" src="{{ $gallery->first() }}" alt="{{ $product->name }}" class="w-20 h-24 object-cover bg-gray-50 shrink-0">
                    <div class="flex-1 min-w-0">
                        <p id="cartAddedName" class="text-sm font-medium text-gray-900 leading-snug mb-2">{{ $product->name }}</p>
                        <p id="cartAddedMeta" class="text-xs text-gray-500 leading-relaxed"></p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" id="cartContinueBtn" class="flex-1 py-3.5 border border-gray-200 text-xs font-bold tracking-[0.2em] uppercase text-gray-700 hover:border-gray-900 hover:text-gray-900 transition-colors">Alışverişe Devam Et</button>
                    <button type="button" id="cartGoBtn" class="flex-1 py-3.5 bg-brand-dark text-white text-xs font-bold tracking-[0.2em] uppercase hover:bg-brand-red transition-colors">Sepete Git</button>
                </div>
            </div>
        </div>
    </div>

    <div id="mobileActionBar" class="fixed bottom-0 left-0 right-0 z-[70] lg:hidden translate-y-full transition-transform duration-300 bg-white border-t border-gray-200 shadow-[0_-8px_30px_-10px_rgba(0,0,0,0.12)] px-4 py-3">
        <div class="flex items-center gap-3">
            <div class="flex-1 min-w-0">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest truncate">{{ $product->name }}</p>
                <p class="text-xs text-gray-600 truncate"><span id="mobileSummary">Beden {{ $firstSize }} · 1 adet</span></p>
            </div>
            <button type="button" id="mobileCartBtn" class="shrink-0 px-5 py-3 bg-brand-dark text-white text-[10px] font-bold tracking-widest uppercase">Sepete Ekle</button>
            <a id="mobileWhatsappBtn" href="https://wa.me/{{ $whatsapp }}?text={{ urlencode($waText) }}" target="_blank" class="shrink-0 w-11 h-11 bg-[#25D366] text-white flex items-center justify-center">
                <i class="bx bxl-whatsapp text-xl"></i>
            </a>
        </div>
    </div>

    <div id="toast" class="fixed bottom-24 lg:bottom-8 left-1/2 -translate-x-1/2 z-[80] px-6 py-3 bg-brand-dark text-white text-xs font-bold tracking-widest uppercase opacity-0 pointer-events-none transition-opacity duration-300 flex items-center gap-2 shadow-lg">
        <i class="bx bx-check-circle text-lg text-emerald-400"></i>
        <span id="toastMessage">Sepete eklendi</span>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const images = @json($gallery->values());
    const whatsappNumber = @json($whatsapp);
    let currentIndex = 0;
    const mainImage = document.getElementById('mainImage');
    const mainImageLink = document.getElementById('mainImageLink');
    const imageCounter = document.getElementById('imageCounter');
    const mainGallery = document.getElementById('mainGallery');

    function setActiveImage(index, animate = true) {
        if (!images.length) return;
        if (index === currentIndex && animate) return;
        currentIndex = index;

        const updateImage = () => {
            mainImage.src = images[index];
            mainImageLink.href = images[index];
            if (imageCounter) imageCounter.textContent = `${index + 1} / ${images.length}`;

            document.querySelectorAll('.thumb-btn').forEach(btn => {
                const isActive = parseInt(btn.dataset.index) === index;
                btn.classList.toggle('border-brand-dark', isActive);
                btn.classList.toggle('opacity-100', isActive);
                btn.classList.toggle('border-transparent', !isActive);
                btn.classList.toggle('opacity-60', !isActive);
            });

            document.querySelectorAll('.dot-indicator').forEach(dot => {
                const isActive = parseInt(dot.dataset.index) === index;
                dot.classList.toggle('bg-brand-dark', isActive);
                dot.classList.toggle('w-4', isActive);
                dot.classList.toggle('bg-gray-300', !isActive);
                dot.classList.toggle('w-1.5', !isActive);
            });
        };

        if (animate) {
            mainImage.classList.add('fade-out');
            setTimeout(() => {
                updateImage();
                mainImage.classList.remove('fade-out');
            }, 150);
        } else {
            updateImage();
        }
    }

    document.querySelectorAll('.thumb-btn').forEach(btn => {
        btn.addEventListener('click', () => setActiveImage(parseInt(btn.dataset.index)));
    });

    const imgPrev = document.getElementById('imgPrev');
    const imgNext = document.getElementById('imgNext');
    if (imgPrev && imgNext && images.length > 1) {
        imgPrev.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            setActiveImage((currentIndex - 1 + images.length) % images.length);
        });
        imgNext.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            setActiveImage((currentIndex + 1) % images.length);
        });
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft' && images.length > 1) setActiveImage((currentIndex - 1 + images.length) % images.length);
        if (e.key === 'ArrowRight' && images.length > 1) setActiveImage((currentIndex + 1) % images.length);
    });

    if (mainGallery && images.length > 1) {
        let touchStartX = 0;
        mainGallery.addEventListener('touchstart', (e) => { touchStartX = e.changedTouches[0].screenX; }, { passive: true });
        mainGallery.addEventListener('touchend', (e) => {
            const diff = touchStartX - e.changedTouches[0].screenX;
            if (Math.abs(diff) > 50) {
                setActiveImage(diff > 0 ? (currentIndex + 1) % images.length : (currentIndex - 1 + images.length) % images.length);
            }
        }, { passive: true });
    }

    if (typeof Fancybox !== 'undefined') {
        Fancybox.bind('[data-fancybox="product-gallery"]', {
            Carousel: { infinite: true },
            Images: { zoom: true },
        });
        mainImageLink?.addEventListener('click', (e) => {
            e.preventDefault();
            const allLinks = document.querySelectorAll('[data-fancybox="product-gallery"]');
            Fancybox.show(Array.from(allLinks).map(el => ({ src: el.href, caption: el.dataset.caption })), { startIndex: currentIndex });
        });
    }

    const sizeModal = document.getElementById('sizeModal');
    const sizeChartBtn = document.getElementById('sizeChartBtn');
    const sizeModalClose = document.getElementById('sizeModalClose');
    const sizeModalOverlay = document.getElementById('sizeModalOverlay');
    const sizeModalContent = document.getElementById('sizeModalContent');
    const cartAddedModal = document.getElementById('cartAddedModal');
    const cartAddedModalClose = document.getElementById('cartAddedModalClose');
    const cartAddedModalOverlay = document.getElementById('cartAddedModalOverlay');
    const cartAddedModalContent = document.getElementById('cartAddedModalContent');
    const cartAddedMeta = document.getElementById('cartAddedMeta');
    const cartAddedImage = document.getElementById('cartAddedImage');
    const productId = @json($product->id);
    const productName = @json($product->name);
    const productSku = @json($sku);
    let qty = 1;
    let selectedSize = @json($firstSize);
    let selectedColor = @json(ucfirst($firstColor));
    const qtyValue = document.getElementById('qtyValue');
    const mobileSummary = document.getElementById('mobileSummary');
    const whatsappBtn = document.getElementById('whatsappBtn');
    const mobileWhatsappBtn = document.getElementById('mobileWhatsappBtn');

    function openSizeModal() {
        sizeModal.classList.remove('hidden');
        sizeModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        sizeModalContent?.classList.remove('modal-animate');
        void sizeModalContent?.offsetWidth;
        sizeModalContent?.classList.add('modal-animate');
    }

    function closeSizeModal() {
        sizeModal.classList.add('hidden');
        sizeModal.classList.remove('flex');
        if (cartAddedModal?.classList.contains('hidden')) document.body.style.overflow = '';
    }

    sizeChartBtn?.addEventListener('click', openSizeModal);
    sizeModalClose?.addEventListener('click', closeSizeModal);
    sizeModalOverlay?.addEventListener('click', closeSizeModal);

    function openCartAddedModal() {
        if (cartAddedMeta) cartAddedMeta.textContent = `Renk: ${selectedColor} · Beden: ${selectedSize} · Adet: ${qty}`;
        if (cartAddedImage && images[currentIndex]) cartAddedImage.src = images[currentIndex];

        try {
            if (window.SilvaCart && typeof window.SilvaCart.add === 'function') {
                window.SilvaCart.add({
                    id: productId,
                    name: productName,
                    sku: productSku,
                    image: images[currentIndex] || '',
                    size: selectedSize,
                    color: selectedColor,
                    qty: qty,
                    url: window.location.href,
                });
                window.SilvaCart.render();
            }
        } catch (err) {
            console.error('Sepete ekleme hatası:', err);
        }

        cartAddedModal?.classList.remove('hidden');
        cartAddedModal?.classList.add('flex');
        document.body.style.overflow = 'hidden';
        cartAddedModalContent?.classList.remove('modal-animate');
        void cartAddedModalContent?.offsetWidth;
        cartAddedModalContent?.classList.add('modal-animate');
    }

    function closeCartAddedModal() {
        cartAddedModal?.classList.add('hidden');
        cartAddedModal?.classList.remove('flex');
        if (sizeModal?.classList.contains('hidden')) document.body.style.overflow = '';
    }

    function goToCartFromModal() {
        closeCartAddedModal();
        setTimeout(function () {
            if (typeof window.openCartDrawer === 'function') {
                window.openCartDrawer();
            } else if (typeof window.toggleCartDrawer === 'function') {
                window.toggleCartDrawer();
            }
        }, 60);
    }

    cartAddedModalClose?.addEventListener('click', closeCartAddedModal);
    cartAddedModalOverlay?.addEventListener('click', closeCartAddedModal);
    document.getElementById('cartContinueBtn')?.addEventListener('click', closeCartAddedModal);
    document.getElementById('cartGoBtn')?.addEventListener('click', goToCartFromModal);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (cartAddedModal && !cartAddedModal.classList.contains('hidden')) closeCartAddedModal();
            else if (sizeModal && !sizeModal.classList.contains('hidden')) closeSizeModal();
        }
    });

    function updateSelectionSummary() {
        if (mobileSummary) mobileSummary.textContent = `Beden ${selectedSize} · ${qty} adet`;
        const waText = encodeURIComponent(`Merhaba, ${productName} (${productSku}) — Renk: ${selectedColor}, Beden: ${selectedSize}, Adet: ${qty} hakkında teklif almak istiyorum.`);
        const waUrl = `https://wa.me/${whatsappNumber}?text=${waText}`;
        if (whatsappBtn) whatsappBtn.href = waUrl;
        if (mobileWhatsappBtn) mobileWhatsappBtn.href = waUrl;
    }

    document.querySelectorAll('.size-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.size-btn').forEach(b => {
                b.classList.remove('bg-brand-dark', 'text-white', 'border-brand-dark');
                b.classList.add('border-gray-200', 'text-gray-600');
            });
            btn.classList.add('bg-brand-dark', 'text-white', 'border-brand-dark');
            btn.classList.remove('border-gray-200', 'text-gray-600');
            selectedSize = btn.dataset.size;
            const label = document.getElementById('selectedSizeLabel');
            if (label) label.textContent = selectedSize;
            updateSelectionSummary();
        });
    });

    document.querySelectorAll('.color-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.color-btn').forEach(b => {
                b.classList.remove('border-brand-dark', 'bg-gray-50');
                b.classList.add('border-gray-200');
            });
            btn.classList.add('border-brand-dark', 'bg-gray-50');
            btn.classList.remove('border-gray-200');
            selectedColor = btn.dataset.color.charAt(0).toUpperCase() + btn.dataset.color.slice(1);
            updateSelectionSummary();
        });
    });

    document.getElementById('qtyMinus')?.addEventListener('click', () => {
        if (qty > 1) { qty--; qtyValue.textContent = qty; updateSelectionSummary(); }
    });
    document.getElementById('qtyPlus')?.addEventListener('click', () => {
        qty++; qtyValue.textContent = qty; updateSelectionSummary();
    });

    updateSelectionSummary();

    function showToast(message) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toastMessage');
        if (!toast) return;
        toastMessage.textContent = message;
        toast.classList.remove('opacity-0', 'pointer-events-none');
        toast.classList.add('toast-show');
        setTimeout(() => {
            toast.classList.add('opacity-0', 'pointer-events-none');
            toast.classList.remove('toast-show');
        }, 2500);
    }

    document.getElementById('addToCartBtn')?.addEventListener('click', openCartAddedModal);
    document.getElementById('mobileCartBtn')?.addEventListener('click', openCartAddedModal);
    document.getElementById('copyLinkBtn')?.addEventListener('click', () => {
        navigator.clipboard.writeText(window.location.href).then(() => showToast('Link kopyalandı'));
    });
    document.getElementById('printBtn')?.addEventListener('click', () => window.print());

    function moveTabIndicator(activeTab) {
        const indicator = document.getElementById('tabIndicator');
        if (!indicator || !activeTab) return;
        indicator.style.width = activeTab.offsetWidth + 'px';
        indicator.style.left = activeTab.offsetLeft + 'px';
    }

    function switchDetailTab(tabId) {
        document.querySelectorAll('.detail-tab').forEach(t => {
            const isActive = t.dataset.tab === tabId;
            t.classList.toggle('active', isActive);
            t.setAttribute('aria-selected', isActive ? 'true' : 'false');
            if (isActive) moveTabIndicator(t);
        });
        document.querySelectorAll('.detail-panel').forEach(p => {
            const isActive = p.id === 'panel-' + tabId;
            p.classList.toggle('active', isActive);
            p.hidden = !isActive;
        });
    }

    const activeTabEl = document.querySelector('.detail-tab.active');
    if (activeTabEl) moveTabIndicator(activeTabEl);
    window.addEventListener('resize', () => {
        const current = document.querySelector('.detail-tab.active');
        if (current) moveTabIndicator(current);
    });

    document.querySelectorAll('.detail-tab').forEach(tab => {
        tab.addEventListener('click', () => switchDetailTab(tab.dataset.tab));
        tab.addEventListener('keydown', (e) => {
            const tabs = Array.from(document.querySelectorAll('.detail-tab'));
            const index = tabs.indexOf(tab);
            if (e.key === 'ArrowRight') {
                e.preventDefault();
                const next = tabs[(index + 1) % tabs.length];
                next.focus();
                switchDetailTab(next.dataset.tab);
            }
            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                const prev = tabs[(index - 1 + tabs.length) % tabs.length];
                prev.focus();
                switchDetailTab(prev.dataset.tab);
            }
        });
    });

    document.querySelectorAll('.faq-toggle').forEach(btn => {
        btn.addEventListener('click', () => {
            const item = btn.closest('.faq-item');
            const isOpen = item.classList.contains('open');
            document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
            if (!isOpen) item.classList.add('open');
        });
    });

    const mobileActionBar = document.getElementById('mobileActionBar');
    const productInfo = document.getElementById('productInfo');
    if (mobileActionBar && productInfo) {
        const observer = new IntersectionObserver(([entry]) => {
            mobileActionBar.classList.toggle('translate-y-full', entry.isIntersecting);
            mobileActionBar.classList.toggle('translate-y-0', !entry.isIntersecting);
        }, { threshold: 0, rootMargin: '0px 0px -60px 0px' });
        observer.observe(productInfo);
    }

    const relatedSlider = document.getElementById('relatedSlider');
    if (relatedSlider) {
        const btnPrev = document.getElementById('relatedPrev');
        const btnNext = document.getElementById('relatedNext');
        const btnPrevMobile = document.getElementById('relatedPrevMobile');
        const btnNextMobile = document.getElementById('relatedNextMobile');
        const scrollAmount = () => {
            const first = relatedSlider.firstElementChild;
            if (!first) return 300;
            return first.offsetWidth + parseFloat(window.getComputedStyle(relatedSlider).gap || 24);
        };
        const scrollPrev = () => relatedSlider.scrollBy({ left: -scrollAmount(), behavior: 'smooth' });
        const scrollNext = () => relatedSlider.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
        btnPrev?.addEventListener('click', scrollPrev);
        btnNext?.addEventListener('click', scrollNext);
        btnPrevMobile?.addEventListener('click', scrollPrev);
        btnNextMobile?.addEventListener('click', scrollNext);

        const updateArrows = () => {
            const atStart = relatedSlider.scrollLeft <= 10;
            const atEnd = relatedSlider.scrollLeft >= relatedSlider.scrollWidth - relatedSlider.clientWidth - 10;
            [btnPrev, btnNext].forEach(btn => {
                if (!btn) return;
                if (btn.id.includes('Prev')) {
                    btn.disabled = atStart;
                    btn.classList.toggle('border-gray-900', !atStart);
                    btn.classList.toggle('border-transparent', atStart);
                } else {
                    btn.disabled = atEnd;
                    btn.classList.toggle('border-gray-900', !atEnd);
                    btn.classList.toggle('border-transparent', atEnd);
                }
            });
        };

        relatedSlider.addEventListener('scroll', updateArrows);
        updateArrows();
        relatedSlider.style.cursor = 'grab';
        relatedSlider.querySelectorAll('img').forEach(img => img.addEventListener('dragstart', e => e.preventDefault()));

        let isDown = false, startX = 0, scrollLeft = 0;
        relatedSlider.addEventListener('mousedown', (e) => {
            isDown = true;
            relatedSlider.style.cursor = 'grabbing';
            relatedSlider.style.scrollSnapType = 'none';
            startX = e.pageX - relatedSlider.offsetLeft;
            scrollLeft = relatedSlider.scrollLeft;
        });
        relatedSlider.addEventListener('mouseleave', () => {
            isDown = false;
            relatedSlider.style.cursor = 'grab';
            relatedSlider.style.scrollSnapType = 'x mandatory';
        });
        relatedSlider.addEventListener('mouseup', () => {
            isDown = false;
            relatedSlider.style.cursor = 'grab';
            relatedSlider.style.scrollSnapType = 'x mandatory';
        });
        relatedSlider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            relatedSlider.scrollLeft = scrollLeft - (e.pageX - relatedSlider.offsetLeft - startX) * 1.5;
        });
    }
});
</script>
@endpush
