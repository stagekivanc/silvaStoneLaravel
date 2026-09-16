@extends('frontend.layouts.app')

@section('title', data_get($page, 'seo_title') ?: 'Silva Stone | Acarkon Dekoratif Taş Duvar Panelleri')
@section('meta_description', data_get($page, 'seo_description') ?: 'Silva Stone by Acarkon — modern iç mekânlar için dekoratif taş duvar panelleri.')

@php
  $hero = data_get($homepage, 'hero', []);
  $slides = collect(data_get($hero, 'slides', []))->values();
  $firstSlide = $slides->first() ?: [];
  $intro = data_get($homepage, 'intro', []);
  $features = data_get($homepage, 'features', []);
  $featureItems = collect(data_get($features, 'items', []))->values();
  $productsSec = data_get($homepage, 'products', []);
  $spaces = data_get($homepage, 'spaces', []);
  $spaceItems = collect(data_get($spaces, 'items', []))->values();
  $stores = data_get($homepage, 'stores', []);
  $projects = data_get($homepage, 'projects', []);
@endphp

@section('content')
  {{-- Hero --}}
  <section class="hero relative min-h-[100svh] overflow-hidden bg-void">
    <div class="hero-media absolute inset-0" aria-hidden="true">
      @foreach ($slides as $i => $slide)
        <img
          src="{{ homepage_media_url(data_get($slide, 'image')) }}"
          alt=""
          class="hero-media-img {{ $i === 0 ? 'is-active' : '' }}"
          data-hero-bg="{{ $i }}"
        />
      @endforeach
      <div class="hero-media-shade absolute inset-0"></div>
    </div>

    <div class="relative z-10 mx-auto flex min-h-[100svh] max-w-site flex-col justify-center gap-10 px-5 py-28 md:flex-row md:items-center md:justify-between md:gap-12 md:px-8 md:py-24">
      <div class="max-w-xl md:max-w-lg lg:max-w-xl">
        <p id="hero-kicker" class="hero-reveal hero-delay-1 font-display italic text-[15px] tracking-[0.06em] text-white/70 md:text-base">
          {{ data_get($firstSlide, 'kicker') }}
        </p>
        <h1 id="hero-title" class="hero-reveal hero-delay-1 mt-2 text-[clamp(2.4rem,8vw,5.4rem)] font-light leading-[0.94] tracking-[-0.045em] text-white">
          {{ data_get($firstSlide, 'title') }}
        </h1>
        <p id="hero-lead" class="hero-reveal hero-delay-2 mt-5 max-w-md text-[15px] font-light leading-relaxed text-white/80 md:mt-6 md:text-base">
          {{ data_get($firstSlide, 'lead') }}
        </p>
        <div class="hero-reveal hero-delay-3 mt-8 flex flex-wrap items-center gap-3 md:mt-10">
          <a id="hero-cta" href="{{ silva_url(data_get($hero, 'primary_url', '#katalog')) }}" class="rounded-full bg-white px-6 py-3.5 text-[13px] font-medium text-ink transition hover:bg-mist">
            {{ data_get($hero, 'primary_label', 'Koleksiyonu incele') }}
          </a>
          <a href="{{ silva_url(data_get($hero, 'secondary_url')) }}" class="rounded-full border border-white/35 px-6 py-3.5 text-[13px] font-medium text-white transition hover:border-white hover:bg-white/10">
            {{ data_get($hero, 'secondary_label', 'Showroom') }}
          </a>
        </div>
      </div>

      <div class="hero-spotlight hero-reveal hero-delay-3 shrink-0" id="hero-spotlight">
        <div class="hero-spotlight-top">
          <p>{{ data_get($hero, 'spotlight_label', 'Yeni ürünler') }}</p>
          <div class="hero-spotlight-nav">
            <button type="button" class="hero-spotlight-btn" id="hero-prod-prev" aria-label="Önceki ürün">
              <i class="bx bx-chevron-left"></i>
            </button>
            <button type="button" class="hero-spotlight-btn" id="hero-prod-next" aria-label="Sonraki ürün">
              <i class="bx bx-chevron-right"></i>
            </button>
          </div>
        </div>
        <div class="hero-spotlight-viewport">
          <div class="hero-spotlight-track" id="hero-spotlight-track"></div>
        </div>
        <div class="hero-spotlight-foot">
          <span class="hero-spotlight-count" id="hero-prod-count"></span>
          <div class="hero-spotlight-dots" id="hero-prod-dots" aria-hidden="true"></div>
        </div>
      </div>
    </div>

    <div class="hero-scene-bar">
      <div class="hero-scene-dots" id="hero-scene-dots"></div>
    </div>
  </section>

  {{-- Intro --}}
  <section class="bg-white">
    <div class="mx-auto grid max-w-site gap-10 px-5 py-16 md:grid-cols-12 md:gap-12 md:px-8 md:py-24">
      <p class="text-[11px] font-medium uppercase tracking-[0.22em] text-stone md:col-span-3">{{ data_get($intro, 'eyebrow') }}</p>
      <div class="md:col-span-9">
        <p class="max-w-3xl text-[clamp(1.4rem,2.8vw,2.25rem)] font-light leading-snug tracking-[-0.02em] text-ink">
          {{ data_get($intro, 'title') }}
        </p>
        <p class="mt-6 max-w-2xl text-[15px] font-light leading-relaxed text-stone">
          {{ data_get($intro, 'text') }}
        </p>
      </div>
    </div>
  </section>

  {{-- Features --}}
  <section id="neden" class="bg-white py-16 md:py-24">
    <div class="mx-auto max-w-site px-5 md:px-8">
      <div class="mb-10 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
          <h2 class="text-[clamp(1.75rem,3vw,2.5rem)] font-light tracking-[-0.03em]">{{ data_get($features, 'title') }}</h2>
          <p class="mt-3 max-w-xl text-[15px] font-light text-stone">{{ data_get($features, 'subtitle') }}</p>
        </div>
        <a href="{{ silva_url(data_get($features, 'catalog_url')) }}" target="_blank" rel="noopener" class="text-[13px] font-medium text-ink underline decoration-line underline-offset-8 transition hover:decoration-ink">
          {{ data_get($features, 'catalog_label') }}
        </a>
      </div>
      <div class="feature-grid">
        @foreach ($featureItems as $i => $item)
          <article class="feature-item">
            <span>{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
            <div>
              <h3>{{ data_get($item, 'title') }}</h3>
              <p>{{ data_get($item, 'text') }}</p>
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Products --}}
  <section id="katalog" class="bg-white py-16 md:py-24">
    <div class="mx-auto max-w-site px-5 md:px-8">
      <div class="mb-10">
        <p class="text-[11px] font-medium uppercase tracking-[0.2em] text-stone">{{ data_get($productsSec, 'eyebrow') }}</p>
        <h2 class="mt-2 text-[clamp(1.75rem,3vw,2.5rem)] font-light tracking-[-0.03em]">{{ data_get($productsSec, 'title') }}</h2>
      </div>
    </div>
    <div class="catalog-accordion" id="catalog-accordion" role="list"></div>
    <div class="mx-auto max-w-site px-5 md:px-8">
      <div class="home-products-foot">
        <a href="{{ silva_url(data_get($productsSec, 'cta_url')) }}" class="home-products-cta">{{ data_get($productsSec, 'cta_label') }}</a>
      </div>
    </div>
  </section>

  {{-- Spaces --}}
  <section id="alanlar" class="bg-white py-20 md:py-28">
    <div class="mx-auto max-w-site px-5 md:px-8">
      <div class="mb-10 max-w-xl">
        <h2 class="text-[clamp(1.75rem,3vw,2.5rem)] font-light tracking-[-0.03em]">{{ data_get($spaces, 'title') }}</h2>
        <p class="mt-3 text-[15px] font-light text-stone">{{ data_get($spaces, 'subtitle') }}</p>
      </div>
      <div class="space-grid">
        @foreach ($spaceItems as $item)
          <a href="{{ silva_url(data_get($item, 'url')) }}" class="space-tile">
            <i class="bx {{ data_get($item, 'icon', 'bx-home-alt-2') }}" aria-hidden="true"></i>
            <h3>{{ data_get($item, 'title') }}</h3>
            <p>{{ data_get($item, 'text') }}</p>
          </a>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Stores --}}
  <section class="overflow-hidden bg-white py-20 md:py-28">
    <div class="mx-auto max-w-site px-5 md:px-8">
      <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
        <div class="max-w-xl">
          <p class="text-[11px] font-medium uppercase tracking-[0.2em] text-stone">{{ data_get($stores, 'eyebrow') }}</p>
          <h2 class="mt-3 text-[clamp(1.75rem,3vw,2.5rem)] font-light tracking-[-0.03em]">{{ data_get($stores, 'title') }}</h2>
          <p class="mt-3 text-[15px] font-light text-stone">{{ data_get($stores, 'subtitle') }}</p>
        </div>
        <a href="{{ silva_url(data_get($stores, 'cta_url')) }}" class="inline-flex items-center gap-2 text-[13px] font-medium text-ink underline decoration-line underline-offset-8 transition hover:decoration-ink">
          <span>{{ data_get($stores, 'cta_label') }}</span>
          <i class="bx bx-right-arrow-alt text-lg"></i>
        </a>
      </div>
    </div>
    <div class="store-marquee store-marquee--cities mt-10" aria-label="Showroom şehirleri">
      <div class="store-marquee-row" data-store-rail="ltr"></div>
      <div class="store-marquee-row store-marquee-row--rtl" data-store-rail="rtl"></div>
    </div>
  </section>

  {{-- Projects --}}
  <section id="projeler" class="overflow-hidden bg-white py-20 md:py-28">
    <div class="mx-auto max-w-site px-5 md:px-8">
      <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div class="max-w-xl">
          <h2 class="text-[clamp(1.75rem,3vw,2.5rem)] font-light tracking-[-0.03em]">{{ data_get($projects, 'title') }}</h2>
          <p class="mt-3 text-[15px] font-light text-stone">{{ data_get($projects, 'subtitle') }}</p>
        </div>
        <a href="{{ silva_url(data_get($projects, 'cta_url')) }}" class="text-[13px] font-medium text-ink underline decoration-line underline-offset-8 transition hover:decoration-ink">{{ data_get($projects, 'cta_label') }}</a>
      </div>
    </div>

    <div class="projects-rail mt-12 md:mt-14" id="projects-rail"></div>

    <div class="mx-auto mt-6 flex max-w-site items-center justify-between gap-4 px-5 md:px-8">
      <p class="text-[12px] font-light text-stone">{{ data_get($projects, 'hint') }}</p>
      <div class="flex gap-2">
        <button type="button" class="project-nav-btn" id="projects-prev" aria-label="Önceki">
          <i class="bx bx-left-arrow-alt"></i>
        </button>
        <button type="button" class="project-nav-btn" id="projects-next" aria-label="Sonraki">
          <i class="bx bx-right-arrow-alt"></i>
        </button>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <script src="{{ silva_asset('js/products.js') }}"></script>
  <script>
    (function () {
      const base = (window.SILVA_BASE || '').replace(/\/$/, '') + '/';
      const fix = (p) => {
        if (!p || /^https?:\/\//i.test(p) || p.startsWith('/') || p.startsWith('data:')) return p;
        return base + p.replace(/^\.\//, '');
      };
      (window.SILVA_PRODUCTS || []).forEach((p) => {
        p.img = fix(p.img);
        p.imgHover = fix(p.imgHover);
        p.imgs = (p.imgs || []).map(fix);
        if (p.url && !/^https?:\/\//i.test(p.url)) p.url = fix(p.url);
      });
      window.silvaHref = (p) => base + 'urun.html?code=' + encodeURIComponent(p.code);

      @php
        $jsSlides = $slides->map(function ($s) {
          return [
            'img' => homepage_media_url(data_get($s, 'image')),
            'kicker' => data_get($s, 'kicker'),
            'title' => data_get($s, 'title'),
            'lead' => data_get($s, 'lead'),
            'tone' => data_get($s, 'tone', 'dark'),
          ];
        })->values();
      @endphp
      window.SILVA_HERO_SLIDES = @json($jsSlides);
    })();
  </script>
  @php
    $homeProjects = \App\Models\Project::query()
      ->where('status', true)
      ->where('home_status', true)
      ->with('translations')
      ->orderBy('order')
      ->orderBy('id')
      ->limit(6)
      ->get()
      ->map(fn ($p) => $p->toFrontendArray())
      ->values();
    if ($homeProjects->isEmpty()) {
      $homeProjects = \App\Models\Project::query()
        ->where('status', true)
        ->with('translations')
        ->orderBy('order')
        ->orderBy('id')
        ->limit(6)
        ->get()
        ->map(fn ($p) => $p->toFrontendArray())
        ->values();
    }
  @endphp
  <script>
    window.SILVA_PROJECT_PLACES = @json(\App\Support\SilvaProjectsDefaults::places());
    window.SILVA_PROJECT_TYPES = @json(\App\Support\SilvaProjectsDefaults::types());
    window.SILVA_PROJECTS = @json($homeProjects);
    window.SILVA_PROJECTS_URL = @json(m_url('projects'));
    window.silvaProjectHref = function (p) {
      return p.url || (window.SILVA_PROJECTS_URL + '/' + encodeURIComponent(p.slug || p.id));
    };
  </script>
  @php
    $homeProducts = \App\Models\Product::query()
      ->where('status', true)
      ->where('home_status', true)
      ->with(['translations', 'category'])
      ->orderBy('order')
      ->orderBy('id')
      ->limit(8)
      ->get()
      ->map(fn ($p) => $p->toFrontendArray())
      ->values();
  @endphp
  <script>
    window.SILVA_CATS = @json(array_merge(['all' => 'Tümü'], \App\Models\ProductCategory::query()->where('status', true)->orderBy('order')->get()->mapWithKeys(fn ($c) => [$c->slug => $c->name])->all()));
    window.SILVA_COLORS = @json(\App\Models\ProductColor::filterMap());
    window.SILVA_PRODUCTS = @json($homeProducts);
    window.SILVA_HOME_FEATURED = @json($homeProducts->pluck('code')->values());
    window.SILVA_PRODUCTS_URL = @json(m_url('products'));
  </script>
  <script src="{{ silva_asset('js/products.js') }}"></script>
  <script src="{{ silva_asset('js/projects.js') }}"></script>
  <script src="{{ silva_asset('js/cart.js') }}"></script>
  <script src="{{ silva_asset('js/search.js') }}"></script>
  <script src="{{ silva_asset('js/stores.js') }}"></script>
  <script src="{{ silva_asset('js/main.js') }}"></script>
@endpush
