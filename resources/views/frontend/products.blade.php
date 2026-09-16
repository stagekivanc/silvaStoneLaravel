@extends('frontend.layouts.app')

@section('title', data_get($page, 'seo_title') ?: 'Ürünler | Silva Stone')
@section('meta_description', data_get($page, 'seo_description') ?: 'Silva Stone Stonex ve Stoneart dekoratif taş duvar panelleri.')
@section('body_attrs', 'data-page="products"')

@php
  $intro = data_get($productsPage, 'intro', []);
  $filter = data_get($productsPage, 'filter', []);
  $seo = data_get($productsPage, 'seo', []);
  $cats = array_merge(['all' => data_get($filter, 'all', 'Tümü')], $categoryMap);
  $colors = array_merge(['all' => data_get($filter, 'all', 'Tümü')], $colorMap);
  $features = $productFeatures ?? collect();
  $featuresForJs = $features->map(function ($f) {
      return [
          'key' => $f->filter_key,
          'value' => $f->filter_value,
          'label' => $f->resolved_name ?? $f->name,
      ];
  })->values();
  $silvaLabels = [
      'count_suffix' => data_get($filter, 'count_suffix', 'yüzey'),
      'empty' => data_get($filter, 'empty', 'Bu seçime uygun ürün yok.'),
      'empty_reset' => data_get($filter, 'empty_reset', 'Filtrelemeyi sıfırla'),
      'mm' => 'mm',
      'image_pending' => 'Ürün resmi hazırlanıyor',
  ];
@endphp

@section('content')
  <section class="page-intro page-intro--plain page-intro--white">
    <div class="mx-auto max-w-[1440px] px-5 md:px-8">
      <div class="page-intro-top">
        <div>
          <p class="page-intro-kicker font-display italic">{{ data_get($intro, 'kicker', 'Koleksiyon') }}</p>
          <h1>{{ data_get($intro, 'title', 'Yüzeyler') }}</h1>
        </div>
        <div class="page-intro-aside">
          <p>{{ data_get($intro, 'aside') }}</p>
        </div>
      </div>
    </div>
  </section>

  <section class="plp" id="product-list">
    <div class="plp-bar-wrap">
      <div class="mx-auto max-w-[1440px] px-5 md:px-8">
        <div class="plp-toolbar" id="product-filters">
          <div class="plp-seg" id="product-cats" role="tablist" aria-label="{{ data_get($filter, 'feature_label', 'Özellik') }}">
            @foreach ($cats as $value => $label)
              <button type="button" data-filter="cat" data-value="{{ $value }}">{{ $label }}</button>
            @endforeach
          </div>
          <button type="button" class="plp-filter-btn" id="product-filter-toggle" aria-expanded="false" aria-controls="product-filter-sheet">
            <i class="bx bx-slider-alt" aria-hidden="true"></i>
            <span>{{ data_get($filter, 'filter_label', 'Filtre') }}</span>
            <em class="plp-filter-badge" id="product-filter-badge" hidden>0</em>
          </button>
          <span class="plp-rule" aria-hidden="true"></span>
          <div class="plp-filter-sheet" id="product-filter-sheet" aria-hidden="true">
            <div class="plp-filter-sheet-bar">
              <p>{{ data_get($filter, 'filter_title', 'Filtrele') }}</p>
              <button type="button" class="plp-filter-close" id="product-filter-close" aria-label="{{ __t('ui_close', 'Kapat', 'frontend') }}">
                <i class="bx bx-x"></i>
              </button>
            </div>
            <p class="plp-filter-label">{{ data_get($filter, 'color_label', 'Renk') }}</p>
            <div class="plp-dots" role="group" aria-label="{{ data_get($filter, 'color_label', 'Renk') }}">
              <button type="button" class="plp-dot" data-filter="color" data-value="all" aria-label="{{ data_get($filter, 'all', 'Tümü') }}" title="{{ data_get($filter, 'all', 'Tümü') }}"><span></span></button>
              @foreach ($colorHex as $slug => $hex)
                <button type="button" class="plp-dot" data-filter="color" data-value="{{ $slug }}" aria-label="{{ $colors[$slug] ?? $slug }}" title="{{ $colors[$slug] ?? $slug }}">
                  <span style="background:{{ $hex }}"></span>
                </button>
              @endforeach
            </div>
            <p class="plp-filter-label">{{ data_get($filter, 'feature_label', 'Özellik') }}</p>
            <div class="plp-chips" role="group" aria-label="{{ data_get($filter, 'feature_label', 'Özellik') }}">
              @foreach ($features as $feature)
                <button type="button" class="plp-chip" data-filter="{{ $feature->filter_key }}" data-value="{{ $feature->filter_value }}">{{ $feature->resolved_name ?? $feature->name }}</button>
              @endforeach
            </div>
            <div class="plp-filter-sheet-actions">
              <button type="button" class="plp-reset" id="product-sheet-reset">{{ data_get($filter, 'reset', 'Sıfırla') }}</button>
              <button type="button" class="plp-filter-apply" id="product-filter-apply">{{ data_get($filter, 'apply', 'Ürünleri gör') }}</button>
            </div>
          </div>
          <div class="plp-toolbar-end">
            <p id="product-count" class="plp-count"></p>
            <button type="button" class="plp-reset" id="product-reset">{{ data_get($filter, 'reset', 'Sıfırla') }}</button>
            <div class="plp-seg plp-views" role="group" aria-label="{{ __t('ui_display', 'Gösterim', 'frontend') }}">
              <button type="button" data-cols="1" aria-label="{{ __t('ui_list_view', 'Liste', 'frontend') }}" title="{{ __t('ui_list_view', 'Liste', 'frontend') }}">1</button>
              <button type="button" data-cols="2" aria-label="{{ __t('ui_grid_view', 'Izgara', 'frontend') }} 2" title="2">2</button>
              <button type="button" data-cols="3" aria-label="{{ __t('ui_grid_view', 'Izgara', 'frontend') }} 3" title="3">3</button>
              <button type="button" data-cols="4" aria-label="{{ __t('ui_grid_view', 'Izgara', 'frontend') }} 4" title="4">4</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="plp-filter-mask" id="product-filter-mask" hidden></div>

    <div class="mx-auto max-w-[1440px] px-5 md:px-8">
      <div class="product-grid" id="product-grid" data-cols="3"></div>
      <div class="plp-empty" id="product-empty" hidden>
        <span class="plp-empty-icon" aria-hidden="true"><i class="bx bx-search-alt-2"></i></span>
        <p>{{ data_get($filter, 'empty', 'Bu seçime uygun ürün yok.') }}</p>
        <button type="button" class="plp-empty-btn" id="product-empty-reset">{{ data_get($filter, 'empty_reset', 'Filtrelemeyi sıfırla') }}</button>
      </div>
      <nav class="plp-pager" id="product-pager" aria-label="{{ __t('ui_pages', 'Sayfalar', 'frontend') }}" hidden></nav>
    </div>
  </section>

  <section class="product-seo" id="product-seo">
    <div class="mx-auto max-w-[1440px] px-5 md:px-8">
      <p class="page-intro-kicker font-display italic">{{ data_get($seo, 'kicker', 'Rehber') }}</p>
      <h2>{{ data_get($seo, 'title') }}</h2>
      <div class="product-seo-clip">
        <div class="product-seo-body">
          @foreach ((array) data_get($seo, 'body', []) as $para)
            <p>{{ $para }}</p>
          @endforeach
        </div>
      </div>
      <button type="button" class="product-seo-more" id="product-seo-more" aria-expanded="false">{{ data_get($seo, 'more', 'Devamını oku') }}</button>
    </div>
  </section>
@endsection

@push('scripts')
  @php
    $jsProducts = $productItems->values();
    $jsProductsUrl = m_url('products');
  @endphp
  <script>
    window.SILVA_CATS = @json($cats);
    window.SILVA_COLORS = @json($colors);
    window.SILVA_PRODUCTS = @json($jsProducts);
    window.SILVA_PRODUCTS_URL = @json($jsProductsUrl);
    window.SILVA_FEATURES = @json($featuresForJs);
    window.SILVA_LABELS = @json($silvaLabels);
  </script>
  <script src="{{ silva_asset('js/products.js') }}"></script>
  <script src="{{ silva_asset('js/cart.js') }}"></script>
  <script src="{{ silva_asset('js/search.js') }}"></script>
  <script src="{{ silva_asset('js/catalog.js') }}"></script>
  <script src="{{ silva_asset('js/main.js') }}"></script>
@endpush
