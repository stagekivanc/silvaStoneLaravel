@extends('frontend.layouts.app')

@section('title', data_get($page, 'seo_title') ?: 'Projeler | Silva Stone Uygulamaları')
@section('meta_description', data_get($page, 'seo_description') ?: 'Silva Stone dekoratif taş panellerinin otel, konut, restoran ve cephe uygulamaları.')
@section('body_attrs', 'data-page="projects"')

@php
  $intro = data_get($projectsPage, 'intro', []);
  $filter = data_get($projectsPage, 'filter', []);
  $places = \App\Support\SilvaProjectsDefaults::places();
  $types = \App\Support\SilvaProjectsDefaults::types();
  $cities = collect(\App\Models\ProjectCity::filterOptions());
@endphp

@section('content')
  <section class="page-intro page-intro--plain page-intro--white">
    <div class="mx-auto max-w-[1440px] px-5 md:px-8">
      <div class="page-intro-top">
        <div>
          <p class="page-intro-kicker font-display italic">{{ data_get($intro, 'kicker', 'Uygulamalar') }}</p>
          <h1>{{ data_get($intro, 'title', 'Projeler') }}</h1>
        </div>
        <div class="page-intro-aside">
          <p>{{ data_get($intro, 'aside') }}</p>
        </div>
      </div>
    </div>
  </section>

  <section class="plp" id="project-list">
    <div class="plp-bar-wrap">
      <div class="mx-auto max-w-[1440px] px-5 md:px-8">
        <div class="plp-toolbar" id="project-filters">
          <div class="plp-seg" role="group" aria-label="{{ data_get($filter, 'place_label', 'Mekân') }}">
            @foreach ($places as $value => $label)
              <button type="button" data-filter="place" data-value="{{ $value }}">{{ $label }}</button>
            @endforeach
          </div>
          <span class="plp-rule" aria-hidden="true"></span>
          <div class="plp-seg" role="group" aria-label="{{ data_get($filter, 'type_label', 'Tip') }}">
            @foreach ($types as $value => $label)
              <button type="button" data-filter="type" data-value="{{ $value }}">{{ $label }}</button>
            @endforeach
          </div>
          <span class="plp-rule" aria-hidden="true"></span>
          <label class="plp-city">
            <select id="project-city" aria-label="{{ __t('ui_city', 'Şehir', 'frontend') }}">
              <option value="all">{{ data_get($filter, 'all_cities', 'Tüm şehirler') }}</option>
              @foreach ($cities as $city)
                <option value="{{ data_get($city, 'id') }}">{{ data_get($city, 'label') }}</option>
              @endforeach
            </select>
            <i class="bx bx-chevron-down"></i>
          </label>
          <div class="plp-toolbar-end">
            <p id="project-count" class="plp-count"></p>
            <button type="button" class="plp-reset" id="project-reset">{{ data_get($filter, 'reset', 'Sıfırla') }}</button>
          </div>
        </div>
      </div>
    </div>

    <div class="mx-auto max-w-[1440px] px-5 md:px-8">
      <div class="project-grid" id="project-grid"></div>
      <div class="plp-empty" id="project-empty" hidden>
        <span class="plp-empty-icon" aria-hidden="true"><i class="bx bx-building-house"></i></span>
        <p>{{ data_get($filter, 'empty_title', 'Bu seçime uygun proje yok.') }}</p>
        <button type="button" class="plp-empty-btn" id="project-empty-reset">{{ data_get($filter, 'empty_reset', 'Filtrelemeyi sıfırla') }}</button>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <script>
    window.SILVA_PROJECT_PLACES = @json($places);
    window.SILVA_PROJECT_TYPES = @json($types);
    window.SILVA_PROJECTS = @json($projectItems->values());
    window.SILVA_PROJECTS_URL = @json(m_url('projects'));
    window.silvaProjectHref = function (p) {
      return p.url || (window.SILVA_PROJECTS_URL + '/' + encodeURIComponent(p.slug || p.id));
    };
  </script>
  <script src="{{ silva_asset('js/cart.js') }}"></script>
  <script src="{{ silva_asset('js/search.js') }}"></script>
  <script src="{{ silva_asset('js/projects-list.js') }}"></script>
  <script src="{{ silva_asset('js/main.js') }}"></script>
@endpush
