@extends('frontend.layouts.app')

@section('title', data_get($project, 'seo_title') ?: (($projectModel->title ?? 'Proje') . ' | Silva Stone'))
@section('meta_description', data_get($project, 'seo_description') ?: ($project['lead'] ?? ''))
@section('body_attrs', 'data-page="project"')
@section('og_image', $project['img'] ?? '')

@php
  $detail = data_get($projectsPage, 'detail', []);
  $facts = data_get($detail, 'facts', []);
  $gallery = $project['imgs'] ?? [];
  $feats = $project['feats'] ?? [];
  $typeName = \App\Models\ProjectType::optionsMap()[$project['type']] ?? $project['type'];
  $placeName = \App\Models\ProjectPlace::optionsMap()[$project['place']] ?? $project['place'];
  $listUrl = m_url('projects');
  $contactUrl = m_url('contact');
@endphp

@push('head')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
@endpush

@section('content')
  <article class="pj">
    <div class="mx-auto max-w-[1440px] px-5 md:px-8">
      <nav class="pj-crumb">
        <a href="{{ $listUrl }}">{{ __t('ui_projects', 'Projeler', 'frontend') }}</a>
        <span>/</span>
        <a href="{{ $listUrl }}?type={{ $project['type'] }}">{{ $typeName }}</a>
        <span>/</span>
        <span>{{ $project['title'] }}</span>
      </nav>
    </div>

    <div class="pj-hero" id="pj-hero">
      @if (!empty($gallery[0]))
        <button type="button" class="pj-hero-open" aria-label="{{ __t('ui_enlarge_image', 'Görseli büyüt', 'frontend') }}" data-gallery-index="0">
          <img src="{{ $gallery[0] }}" alt="{{ $project['title'] }}" />
        </button>
        <div class="pj-hero-cap">
          <div class="pj-hero-cap-inner">
            <p class="page-intro-kicker font-display italic">{{ $typeName }} · {{ $placeName }}</p>
            <h1>{{ $project['title'] }}</h1>
            <span>{{ $project['cityLabel'] }}{{ !empty($project['year']) ? ' · ' . $project['year'] : '' }}</span>
          </div>
        </div>
      @endif
    </div>

    <div class="mx-auto max-w-[1440px] px-5 md:px-8">
      <dl class="pj-facts">
        <div><dt>{{ data_get($facts, 'city', 'Şehir') }}</dt><dd>{{ $project['cityLabel'] ?: '—' }}</dd></div>
        <div><dt>{{ data_get($facts, 'place', 'Mekân') }}</dt><dd>{{ $placeName }}</dd></div>
        <div><dt>{{ data_get($facts, 'type', 'Tip') }}</dt><dd>{{ $typeName }}</dd></div>
        <div><dt>{{ data_get($facts, 'product', 'Yüzey') }}</dt><dd>{{ $project['product'] ?: '—' }}</dd></div>
        <div><dt>{{ data_get($facts, 'year', 'Yıl') }}</dt><dd>{{ $project['year'] ?: '—' }}</dd></div>
        <div><dt>{{ data_get($facts, 'area', 'Alan') }}</dt><dd>{{ $project['area'] ?: '—' }}</dd></div>
      </dl>

      <div class="pj-split">
        <div class="pj-copy">
          <p class="page-intro-kicker font-display italic">{{ data_get($detail, 'story_kicker', 'Hikâye') }}</p>
          <p class="pj-lead">{{ $project['lead'] }}</p>
          <p class="pj-body">{{ $project['body'] }}</p>
        </div>
        <aside class="pj-aside">
          <p class="page-intro-kicker font-display italic">{{ data_get($detail, 'surface_kicker', 'Yüzey') }}</p>
          <a class="pj-surface" href="{{ m_url('products') }}{{ $project['product'] ? '?q=' . urlencode($project['product']) : '' }}">
            @if (!empty($gallery[0]))
              <img src="{{ $gallery[0] }}" alt="{{ $project['product'] ?: $project['title'] }}" />
            @endif
            <span>
              <strong>{{ $project['product'] ?: __t('ui_collection', 'Koleksiyon', 'frontend') }}</strong>
              <em>{{ data_get($detail, 'surface_hint', 'Kullanılan panel') }}</em>
            </span>
          </a>
          <a href="{{ $contactUrl }}" class="pj-cta">{{ data_get($detail, 'cta', 'Bu uygulamayı konuş') }}</a>
          <a href="{{ $listUrl }}?type={{ $project['type'] }}" class="pj-ghost">{{ $typeName }} {{ data_get($detail, 'type_projects', 'projeleri') }}</a>
        </aside>
      </div>

      @if (count($gallery) > 1)
        <div class="pj-gallery" id="pj-gallery">
          @foreach (array_slice($gallery, 1) as $i => $src)
            <button type="button" class="pj-shot" data-gallery-index="{{ $i + 1 }}" aria-label="{{ __t('ui_enlarge_image', 'Görseli büyüt', 'frontend') }}">
              <img src="{{ $src }}" alt="{{ $project['title'] }}" />
            </button>
          @endforeach
        </div>
      @endif
    </div>
  </article>

  <section class="pj-notes">
    <div class="mx-auto max-w-[1440px] px-5 md:px-8">
      <p class="page-intro-kicker font-display italic">{{ data_get($detail, 'notes_kicker', 'Uygulama') }}</p>
      <h2>{{ data_get($detail, 'notes_title', 'Notlar') }}</h2>
      <div class="pj-notes-grid">
        @foreach ($feats as $i => $feat)
          <article class="pj-note">
            <span>{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
            <h3>{{ $feat }}</h3>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  <section class="pj-more">
    <div class="mx-auto max-w-[1440px] px-5 md:px-8">
      <p class="page-intro-kicker font-display italic">{{ data_get($detail, 'related_kicker', 'Keşfet') }}</p>
      <h2>{{ data_get($detail, 'related_title', 'Diğer uygulamalar') }}</h2>
      <div class="project-grid project-grid--related">
        @foreach ($related as $item)
          <a href="{{ $item['url'] }}" class="project-card">
            <img src="{{ $item['img'] }}" alt="{{ $item['title'] }}" />
            <div class="project-card-shade"></div>
            <span class="project-card-place">{{ \App\Models\ProjectPlace::optionsMap()[$item['place']] ?? $item['place'] }}</span>
            <div class="project-card-meta">
              <h2>{{ $item['title'] }}</h2>
              <p>{{ \App\Models\ProjectType::optionsMap()[$item['type']] ?? $item['type'] }} · {{ $item['cityLabel'] }}</p>
            </div>
          </a>
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
      var title = @json($project['title'] ?? '');
      document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-gallery-index]');
        if (!btn || !window.Fancybox || !gallery.length) return;
        var start = Number(btn.getAttribute('data-gallery-index')) || 0;
        window.Fancybox.show(
          gallery.map(function (src) { return { src: src, type: 'image', caption: title }; }),
          { startIndex: start, Hash: false }
        );
      });
    })();
  </script>
  <script src="{{ silva_asset('js/cart.js') }}"></script>
  <script src="{{ silva_asset('js/search.js') }}"></script>
  <script src="{{ silva_asset('js/main.js') }}"></script>
@endpush
