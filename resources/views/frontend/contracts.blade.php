@extends('frontend.layouts.app')

@section('title', data_get($page, 'seo_title') ?: 'Sözleşmeler | Silva Stone')
@section('meta_description', data_get($page, 'seo_description') ?: 'Silva Stone gizlilik, aydınlatma, çerez, güvenlik ve KVKK metinleri.')
@section('body_attrs') data-page="legal" @endsection
@section('body_class', 'bg-soft')

@php
  $intro = data_get($contracts, 'intro', []);
  $items = collect(data_get($contracts, 'items', []))->values();
@endphp

@section('content')
  <section class="page-intro page-intro--plain">
    <div class="mx-auto max-w-site px-5 md:px-8">
      <div class="page-intro-top">
        <div>
          <p class="page-intro-kicker font-display italic">{{ data_get($intro, 'kicker', 'Yasal') }}</p>
          <h1>{{ data_get($intro, 'title', 'Sözleşmeler') }}</h1>
        </div>
        <div class="page-intro-aside">
          <p>{{ data_get($intro, 'aside') }}</p>
        </div>
      </div>
    </div>
  </section>

  <section class="legal-section">
    <div class="mx-auto max-w-site px-5 md:px-8">
      <div class="legal-index">
        @foreach ($items as $item)
          <a href="{{ m_url(data_get($item, 'type')) }}" class="legal-index-item">
            <span class="legal-index-num">{{ data_get($item, 'number') }}</span>
            <strong>{{ data_get($item, 'label') }}</strong>
          </a>
        @endforeach
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <script src="{{ silva_asset('js/cart.js') }}"></script>
  <script src="{{ silva_asset('js/search.js') }}"></script>
  <script src="{{ silva_asset('js/main.js') }}"></script>
@endpush
