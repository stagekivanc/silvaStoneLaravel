@extends('frontend.layouts.app')

@section('title', data_get($page, 'seo_title') ?: (data_get($legal, 'doc.title') . ' | Silva Stone'))
@section('meta_description', data_get($page, 'seo_description') ?: data_get($legal, 'intro.aside'))
@section('body_attrs', 'data-page="legal"')
@section('body_class', 'bg-soft')

@php
  $lang = app()->getLocale();
  $intro = data_get($legal, 'intro', []);
  $doc = data_get($legal, 'doc', []);
  $nav = collect(data_get($legal, 'nav', []))->values();
  $activeType = $page->type ?? '';
  $bodyHtml = data_get($page, 'body_content') ?: data_get($legal, 'body_html', '');
  $bodyHtml = \App\Support\SilvaLegalDefaults::resolveBodyHtml((string) $bodyHtml);
@endphp

@section('content')
  <section class="page-intro page-intro--plain">
    <div class="mx-auto max-w-site px-5 md:px-8">
      <div class="page-intro-top">
        <div>
          <p class="page-intro-kicker font-display italic">{{ data_get($intro, 'kicker', 'Sözleşmeler') }}</p>
          <h1>{{ data_get($intro, 'title') ?: data_get($page, 'title') }}</h1>
        </div>
        <div class="page-intro-aside">
          <p>{{ data_get($intro, 'aside') }}</p>
        </div>
      </div>
    </div>
  </section>

  <section class="legal-section">
    <div class="legal-shell mx-auto max-w-site px-5 md:px-8">
      <nav class="legal-nav" aria-label="Sözleşme sayfaları">
        <p class="legal-nav-label">Sözleşmeler</p>
        @foreach ($nav as $item)
          @php
            $type = data_get($item, 'type');
            $href = $type === 'external'
              ? data_get($item, 'url')
              : m_url($type);
            $isActive = $type === $activeType;
          @endphp
          <a href="{{ $href }}" @class(['is-active' => $isActive])>
            <span>{{ data_get($item, 'number') }}</span>{{ data_get($item, 'label') }}
          </a>
        @endforeach
      </nav>

      <article class="legal-doc">
        <p class="legal-kicker font-display italic">{{ data_get($doc, 'number') }}</p>
        <h2>{{ data_get($doc, 'title') ?: data_get($page, 'title') }}</h2>
        <div class="legal-body">
          {!! $bodyHtml !!}
        </div>
      </article>
    </div>
  </section>
@endsection

@push('scripts')
  <script src="{{ silva_asset('js/cart.js') }}"></script>
  <script src="{{ silva_asset('js/search.js') }}"></script>
  <script src="{{ silva_asset('js/main.js') }}"></script>
@endpush
