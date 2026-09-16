@extends('frontend.layouts.app')

@section('title', ($product['title'] ?? 'Ürün') . ' | Silva Stone')
@section('meta_description', $product['lead'] ?: (($product['title'] ?? '') . ' — Silva Stone dekoratif taş duvar paneli.'))
@section('body_attrs') data-page="product" @endsection
@section('og_image', $product['img'] ?? '')

@php
  $detail = data_get($productsPage, 'detail', []);
  $listUrl = m_url('products');
  $catName = $categoryMap[$product['cat']] ?? $product['cat'];
  $colorName = $colorMap[$product['color']] ?? $product['color'];
  $gallery = $product['imgs'] ?? [];
  $sizeLabel = \App\Support\SilvaProductsDefaults::formatSize($product['size'] ?? null);
  $thick = \App\Support\SilvaProductsDefaults::formatThick($product['thick'] ?? null);
  $mm = 'mm';
  $featureLabels = $featureLabels ?? \App\Models\ProductFeature::labelMap();
  $wa = preg_replace('/\D+/', '', \App\Models\Setting::get('whatsapp', '908503460226'));
  $relatedFrom = str_replace(':cat', $catName, data_get($detail, 'related_from', ':cat koleksiyonundan'));
@endphp

@push('head')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
@endpush

@section('content')
  <section class="pdp">
    <div class="mx-auto max-w-[1440px] px-5 md:px-8">
      <nav class="pdp-crumb">
        <a href="{{ $listUrl }}">{{ data_get($detail, 'collection_crumb', 'Koleksiyon') }}</a>
        <span>/</span>
        <a href="{{ $listUrl }}?cat={{ $product['cat'] }}">{{ $catName }}</a>
        <span>/</span>
        <span>{{ $product['code'] }}</span>
      </nav>
      <div class="pdp-layout">
        <div class="pdp-media" id="pdp-media">
          @if (count($gallery))
            <div class="pdp-stage-wrap">
              <button type="button" class="pdp-stage" id="pdp-open" aria-label="{{ __t('ui_enlarge_image', 'Görseli büyüt', 'frontend') }}">
                <img id="pdp-main" src="{{ $gallery[0] }}" alt="{{ $product['name'] }}" />
              </button>
              @if (count($gallery) > 1)
                <button type="button" class="pdp-arrow pdp-arrow-prev" data-pdp-nav="-1" aria-label="{{ __t('ui_prev', 'Önceki', 'frontend') }}">
                  <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15.2 4.8 7.8 12l7.4 7.2"/></svg>
                </button>
                <button type="button" class="pdp-arrow pdp-arrow-next" data-pdp-nav="1" aria-label="{{ __t('ui_next', 'Sonraki', 'frontend') }}">
                  <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8.8 4.8 16.2 12l-7.4 7.2"/></svg>
                </button>
              @endif
            </div>
            @if (count($gallery) > 1)
              <div class="pdp-thumbs">
                @foreach ($gallery as $i => $src)
                  <button type="button" class="pdp-thumb{{ $i === 0 ? ' is-active' : '' }}" data-src="{{ $src }}" data-index="{{ $i }}" aria-label="Görsel {{ $i + 1 }}">
                    <img src="{{ $src }}" alt="" />
                  </button>
                @endforeach
              </div>
            @endif
          @else
            <span class="plp-ph">{{ data_get($detail, 'image_pending', 'Ürün resmi hazırlanıyor') }}</span>
          @endif
        </div>
        <div class="pdp-info">
          <p class="page-intro-kicker font-display italic">{{ $catName }}</p>
          <p class="pdp-code">{{ $product['code'] }}</p>
          <h1>{{ $product['title'] }}</h1>
          <p class="pdp-lead">{{ $product['lead'] ?: ($product['title'] . ' (' . $product['code'] . '), ' . $catName . '. ' . $colorName . ', ' . $sizeLabel . ' ' . $mm . ', ' . $thick . ' ' . $mm . '.') }}</p>
          <div class="pdp-chips">
            <span>{{ $colorName }}</span>
            <span>{{ $sizeLabel }} {{ $mm }}</span>
            @if ($thick !== '')<span>{{ $thick }} {{ $mm }}</span>@endif
            @if ($product['indoor'])<span>{{ $featureLabels['indoor'] ?? 'İç mekana uygun' }}</span>@endif
            @if ($product['outdoor'])<span>{{ $featureLabels['outdoor'] ?? 'Dış mekana uygun' }}</span>@endif
            @if ($product['depot'])<span>{{ $featureLabels['depot'] ?? 'Stokta' }}</span>@endif
          </div>
          <dl class="pdp-specs">
            <div><dt>{{ data_get($detail, 'spec_code', 'Ürün kodu') }}</dt><dd>{{ $product['code'] }}</dd></div>
            <div><dt>{{ data_get($detail, 'spec_collection', 'Koleksiyon') }}</dt><dd>{{ $catName }}</dd></div>
            <div><dt>{{ data_get($detail, 'spec_color', 'Renk') }}</dt><dd>{{ $colorName }}</dd></div>
            <div><dt>{{ data_get($detail, 'spec_size', 'Ölçü') }}</dt><dd>{{ $sizeLabel }} {{ $mm }}</dd></div>
            <div><dt>{{ data_get($detail, 'spec_thick', 'İncelik') }}</dt><dd>{{ $thick !== '' ? $thick . ' ' . $mm : '—' }}</dd></div>
            <div><dt>{{ data_get($detail, 'spec_extra', 'Özel sipariş') }}</dt><dd>{{ $product['sizeExtra'] ? \App\Support\SilvaProductsDefaults::formatSize($product['sizeExtra']) . ' ' . $mm : '—' }}</dd></div>
          </dl>
          <div class="pdp-buy">
            <div class="pdp-qty" role="group" aria-label="{{ data_get($detail, 'qty', 'Adet') }}">
              <button type="button" id="pdp-qty-minus" aria-label="{{ __t('ui_decrease', 'Azalt', 'frontend') }}">−</button>
              <input id="pdp-qty" type="number" min="1" max="99" value="1" inputmode="numeric" aria-label="{{ data_get($detail, 'qty', 'Adet') }}" />
              <button type="button" id="pdp-qty-plus" aria-label="{{ __t('ui_increase', 'Artır', 'frontend') }}">+</button>
            </div>
            <button type="button" class="pdp-cart" id="pdp-add"><i class="bx bx-shopping-bag"></i> {{ data_get($detail, 'add_cart', 'Sepete ekle') }}</button>
            <a class="pdp-quote" id="pdp-quote" target="_blank" rel="noopener">{{ data_get($detail, 'quote', 'Teklif al') }}</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="pdp-related">
    <div class="mx-auto max-w-[1440px] px-5 md:px-8">
      <p class="page-intro-kicker font-display italic">{{ data_get($detail, 'related_kicker', 'Koleksiyon') }}</p>
      <h2>{{ $relatedFrom }}</h2>
      <div class="pdp-related-grid">
        @foreach ($related as $item)
          @php
            $itemSize = \App\Support\SilvaProductsDefaults::formatSize($item['size'] ?? null);
            $itemThick = \App\Support\SilvaProductsDefaults::formatThick($item['thick'] ?? null);
          @endphp
          <article class="plp-card">
            <a href="{{ $item['href'] }}" class="plp-card-media">
              @if ($item['img'])
                <img src="{{ $item['img'] }}" alt="{{ $item['name'] }}" loading="lazy" />
              @else
                <span class="plp-ph">{{ data_get($detail, 'image_pending', 'Ürün resmi hazırlanıyor') }}</span>
              @endif
            </a>
            <div class="plp-card-info">
              <p class="plp-card-meta"><span>{{ $item['code'] }}</span><span>{{ $categoryMap[$item['cat']] ?? $item['cat'] }}</span></p>
              <h2><a href="{{ $item['href'] }}">{{ $item['title'] }}</a></h2>
              <p class="plp-card-spec">{{ $colorMap[$item['color']] ?? $item['color'] }} · {{ $itemSize }} {{ $mm }}@if ($itemThick !== '') · {{ $itemThick }} {{ $mm }}@endif</p>
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
  <script>
    (function () {
      var gallery = @json($gallery);
      var title = @json($product['title'] ?? '');
      var code = @json($product['code'] ?? '');
      var catName = @json($catName);
      var sizeLabel = @json($sizeLabel);
      var thick = @json($thick);
      var mm = @json($mm);
      var img = @json($product['img'] ?? '');
      var pageUrl = @json(url()->current());
      var wa = @json($wa);
      var index = 0;
      var main = document.getElementById('pdp-main');
      var qtyInput = document.getElementById('pdp-qty');
      var quoteEl = document.getElementById('pdp-quote');

      function setIndex(next) {
        if (!gallery.length || !main) return;
        index = (next + gallery.length) % gallery.length;
        main.src = gallery[index];
        document.querySelectorAll('.pdp-thumb').forEach(function (el, n) {
          el.classList.toggle('is-active', n === index);
        });
      }

      function qtyValue() {
        return Math.max(1, Math.min(99, Number(qtyInput && qtyInput.value) || 1));
      }

      function syncQuote() {
        if (!quoteEl) return;
        var message = [
          'Merhaba,', '',
          'Silva Stone web sitesi üzerinden ulaşıyorum.', '',
          title + ' ürünü için fiyat bilgisi almak istiyorum.',
          'Ürün kodu: ' + code,
          'Koleksiyon: ' + catName,
          'Ölçü: ' + sizeLabel + ' ' + mm + (thick ? ' · ' + thick + ' ' + mm : ''),
          'Adet: ' + qtyValue(), '',
          'Ürün linki:', pageUrl, '',
          'Bilgilendirmenizi rica ederim.', 'Saygılarımla'
        ].join('\n');
        quoteEl.href = 'https://wa.me/' + wa + '?text=' + encodeURIComponent(message);
      }

      document.getElementById('pdp-media')?.addEventListener('click', function (e) {
        var nav = e.target.closest('[data-pdp-nav]');
        if (nav) { setIndex(index + Number(nav.getAttribute('data-pdp-nav'))); return; }
        var thumb = e.target.closest('.pdp-thumb');
        if (thumb) { setIndex(Number(thumb.getAttribute('data-index')) || 0); return; }
        if (!e.target.closest('#pdp-open') || !window.Fancybox || !gallery.length) return;
        window.Fancybox.show(gallery.map(function (src) { return { src: src, type: 'image', caption: title }; }), { startIndex: index, Hash: false });
      });

      document.getElementById('pdp-qty-minus')?.addEventListener('click', function () {
        qtyInput.value = String(Math.max(1, qtyValue() - 1)); syncQuote();
      });
      document.getElementById('pdp-qty-plus')?.addEventListener('click', function () {
        qtyInput.value = String(Math.min(99, qtyValue() + 1)); syncQuote();
      });
      qtyInput?.addEventListener('change', syncQuote);
      syncQuote();
    })();
  </script>
  <script src="{{ silva_asset('js/cart.js') }}"></script>
  <script src="{{ silva_asset('js/product.js') }}"></script>
  <script src="{{ silva_asset('js/main.js') }}"></script>
@endpush
