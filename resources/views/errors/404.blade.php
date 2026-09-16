@extends('frontend.layouts.app')

@section('title', __t('error_404_meta_title', '404 - Sayfa Bulunamadı', 'frontend'))
@section('robots', 'noindex, nofollow')
@section('body_attrs') data-page="404" @endsection

@php
  $lang = app()->getLocale();
  $homeUrl = route('home', ['lang' => $lang]);
  $productsUrl = m_url('products');
  $projectsUrl = m_url('projects');
  $contactUrl = m_url('contact');
  $storesUrl = m_url('stores');
@endphp

@section('content')
  <section class="page-intro page-intro--plain page-intro--white">
    <div class="mx-auto max-w-site px-5 md:px-8">
      <div class="page-intro-top">
        <div>
          <p class="page-intro-kicker font-display italic">{{ __t('error_404_badge', 'Hata 404', 'frontend') }}</p>
          <h1>{{ __t('error_404_heading', 'Sayfa bulunamadı', 'frontend') }}</h1>
        </div>
        <div class="page-intro-aside">
          <p>{{ __t('error_404_description', 'Aradığınız sayfa taşınmış, silinmiş veya hiç var olmamış olabilir. Ana sayfaya dönerek gezintiye devam edebilirsiniz.', 'frontend') }}</p>
        </div>
      </div>
    </div>
  </section>

  <section class="bg-white pb-20 md:pb-28">
    <div class="mx-auto max-w-site px-5 md:px-8">
      <p class="text-[clamp(5rem,18vw,9rem)] font-light leading-none tracking-[-0.06em] text-ink/10">404</p>

      <div class="mt-8 flex flex-wrap gap-3">
        <a href="{{ $homeUrl }}" class="inline-flex items-center gap-2 rounded-full bg-ink px-6 py-3.5 text-[13px] font-medium text-white transition hover:bg-void">
          {{ __t('error_404_home', 'Ana Sayfa', 'frontend') }}
        </a>
        <a href="{{ $productsUrl }}" class="inline-flex items-center gap-2 rounded-full border border-line px-6 py-3.5 text-[13px] font-medium text-ink transition hover:border-ink hover:bg-soft">
          {{ __t('error_404_products', 'Ürünleri İncele', 'frontend') }}
        </a>
        <a href="{{ $contactUrl }}" class="inline-flex items-center gap-2 rounded-full border border-line px-6 py-3.5 text-[13px] font-medium text-ink transition hover:border-ink hover:bg-soft">
          {{ __t('error_404_link_contact', 'İletişim', 'frontend') }}
        </a>
      </div>

      <div class="mt-14 border-t border-line pt-8">
        <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-stone">{{ __t('error_404_popular_pages', 'Popüler Sayfalar', 'frontend') }}</p>
        <div class="mt-4 flex flex-wrap gap-x-6 gap-y-3 text-sm font-light text-stone">
          <a href="{{ $productsUrl }}" class="transition hover:text-ink">{{ __t('error_404_link_products', 'Tüm Ürünler', 'frontend') }}</a>
          <a href="{{ $projectsUrl }}" class="transition hover:text-ink">{{ __t('nav_projects', 'Projeler', 'frontend') }}</a>
          <a href="{{ $storesUrl }}" class="transition hover:text-ink">{{ __t('nav_showroom', 'Showroom', 'frontend') }}</a>
          <a href="{{ $contactUrl }}" class="transition hover:text-ink">{{ __t('error_404_link_contact', 'İletişim', 'frontend') }}</a>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <script src="{{ silva_asset('js/cart.js') }}"></script>
  <script src="{{ silva_asset('js/search.js') }}"></script>
  <script src="{{ silva_asset('js/main.js') }}"></script>
@endpush
