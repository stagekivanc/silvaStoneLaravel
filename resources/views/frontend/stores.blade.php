@extends('frontend.layouts.app')

@section('title', data_get($page, 'seo_title') ?: 'Showroom | Silva Stone')
@section('meta_description', data_get($page, 'seo_description') ?: 'Türkiye genelindeki Acarkon Store’larda Silva Stone panelleri görün, dokunun ve sipariş edin.')
@section('body_attrs', 'data-page="stores"')

@section('og_image', silva_asset('assets/acarkon-store-hero.jpg'))

@php
  $intro = data_get($stores, 'intro', []);
  $filter = data_get($stores, 'filter', []);
  $items = collect(data_get($stores, 'items', []))->values();
  $cities = $items
    ->map(fn ($s) => ['id' => data_get($s, 'city'), 'label' => data_get($s, 'city_label')])
    ->unique('id')
    ->values();
  $phone = \App\Models\Setting::get('phone', data_get($stores, 'phone', '+90 850 346 02 26'));
  $phoneRaw = \App\Models\Setting::get('phone_raw', data_get($stores, 'phone_raw', '+908503460226'));
  $storesForJs = $items->map(function ($s) {
    return [
      'city' => data_get($s, 'city'),
      'cityLabel' => data_get($s, 'city_label'),
      'lat' => (float) data_get($s, 'lat'),
      'lng' => (float) data_get($s, 'lng'),
      'name' => data_get($s, 'name'),
      'addr' => data_get($s, 'address'),
      'maps' => data_get($s, 'maps'),
    ];
  })->values();
@endphp

@push('head')
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endpush

@section('content')
  <section class="page-intro page-intro--white">
    <div class="mx-auto max-w-site px-5 md:px-8">
      <div class="page-intro-top">
        <div>
          <p class="page-intro-kicker font-display italic">{{ data_get($intro, 'kicker', 'Showroom') }}</p>
          <h1>{{ data_get($intro, 'title', 'Satış Noktaları') }}</h1>
        </div>
        <div class="page-intro-aside">
          <p>{{ data_get($intro, 'aside') }}</p>
        </div>
      </div>
      <div class="page-intro-media store-map" id="store-map" role="region" aria-label="Showroom konum haritası"></div>
    </div>
  </section>

  <section class="bg-white py-16 md:py-24">
    <div class="mx-auto max-w-site px-5 md:px-8">
      <div class="store-filter">
        <label class="store-city-select">
          <span>{{ data_get($filter, 'city_label', 'Şehir') }}</span>
          <span class="store-city-field">
            <select id="store-city" aria-label="{{ data_get($filter, 'city_label', 'Şehir') }}">
              <option value="all">{{ data_get($filter, 'all_cities', 'Tüm şehirler') }}</option>
              @foreach ($cities as $city)
                <option value="{{ data_get($city, 'id') }}">{{ data_get($city, 'label') }}</option>
              @endforeach
            </select>
            <i class="bx bx-chevron-down"></i>
          </span>
        </label>
        <button type="button" class="store-near" id="store-near">
          <i class="bx bx-current-location"></i>
          {{ data_get($filter, 'near_label', 'Bana en yakın') }}
        </button>
      </div>
      <p class="store-filter-note" id="store-filter-note" hidden></p>

      <div class="store-grid" id="store-grid">
        @foreach ($items as $i => $store)
          @php
            $maps = trim((string) data_get($store, 'maps', ''));
            $num = str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT);
          @endphp
          <article
            class="store-card"
            data-city="{{ data_get($store, 'city') }}"
            data-lat="{{ data_get($store, 'lat') }}"
            data-lng="{{ data_get($store, 'lng') }}"
          >
            <div class="store-card-top">
              <span class="store-card-num">{{ $num }}</span>
              <p class="store-card-city">{{ data_get($store, 'city_label') }}</p>
            </div>
            <h3 class="store-card-name">{{ data_get($store, 'name') }}</h3>
            <p class="store-card-addr">{{ data_get($store, 'address') }}</p>
            <div class="store-card-actions">
              <a href="tel:{{ $phoneRaw }}" class="store-card-link">
                <i class="bx bx-phone"></i>
                {{ $phone }}
              </a>
              @if ($maps !== '')
                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $maps }}" target="_blank" rel="noopener" class="store-card-link store-card-link--map">
                  <i class="bx bx-map-alt"></i>
                  <span>Yol tarifi al</span>
                </a>
              @endif
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <script>
    window.SILVA_STORES = @json($storesForJs);
    window.SILVA_STORES_URL = @json(m_url('stores'));
  </script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <script src="{{ silva_asset('js/cart.js') }}"></script>
  <script src="{{ silva_asset('js/search.js') }}"></script>
  <script src="{{ silva_asset('js/stores.js') }}"></script>
  <script src="{{ silva_asset('js/main.js') }}"></script>
@endpush
