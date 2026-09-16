@extends('frontend.layouts.app')

@section('title', __t('error_404_meta_title', '404 - Sayfa Bulunamadı', 'frontend'))
@section('robots', 'noindex, nofollow')

@section('content')
@php
    $lang = app()->getLocale();
    $homeUrl = route('home', ['lang' => $lang]);
    $productsUrl = route('module.dispatcher', ['lang' => $lang, 'module' => 'urunler']);
    $referencesUrl = route('module.dispatcher', ['lang' => $lang, 'module' => 'referanslar']);
    $contactUrl = route('module.dispatcher', ['lang' => $lang, 'module' => 'iletisim']);
    $loginUrl = route('module.dispatcher', ['lang' => $lang, 'module' => 'giris']);
@endphp

<main class="w-full bg-white min-h-[70vh] flex items-center">
    <section class="w-full py-16 md:py-24">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-8">
            <div class="max-w-2xl mx-auto text-center">
                <span class="text-[10px] font-bold tracking-[0.35em] text-brand-red uppercase block mb-6">{{ __t('error_404_badge', 'Hata 404', 'frontend') }}</span>
                <h1 class="text-[120px] md:text-[180px] font-light text-gray-900 leading-none tracking-tighter mb-2">404</h1>
                <h2 class="text-2xl md:text-3xl font-light text-gray-900 tracking-tight mb-4">{{ __t('error_404_heading', 'Sayfa bulunamadı', 'frontend') }}</h2>
                <p class="text-sm md:text-base text-gray-500 font-light leading-relaxed mb-10 max-w-md mx-auto">
                    {{ __t('error_404_description', 'Aradığınız sayfa taşınmış, silinmiş veya hiç var olmamış olabilir. Ana sayfaya dönerek gezintiye devam edebilirsiniz.', 'frontend') }}
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-14">
                    <a href="{{ $homeUrl }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-brand-dark text-white text-xs font-bold tracking-[0.2em] uppercase hover:bg-brand-red transition-colors w-full sm:w-auto">
                        {{ __t('error_404_home', 'Ana Sayfa', 'frontend') }}
                    </a>
                    <a href="{{ $productsUrl }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 border border-gray-900 text-gray-900 text-xs font-bold tracking-[0.2em] uppercase hover:bg-gray-900 hover:text-white transition-colors w-full sm:w-auto">
                        {{ __t('error_404_products', 'Ürünleri İncele', 'frontend') }}
                    </a>
                </div>

                <div class="border-t border-gray-100 pt-10">
                    <p class="text-xs text-gray-400 uppercase tracking-[0.2em] mb-5">{{ __t('error_404_popular_pages', 'Popüler Sayfalar', 'frontend') }}</p>
                    <div class="flex flex-wrap justify-center gap-x-8 gap-y-3 text-sm">
                        <a href="{{ $productsUrl }}" class="text-gray-500 hover:text-brand-red transition-colors">{{ __t('error_404_link_products', 'Tüm Ürünler', 'frontend') }}</a>
                        <a href="{{ $referencesUrl }}" class="text-gray-500 hover:text-brand-red transition-colors">{{ __t('error_404_link_references', 'Referanslar', 'frontend') }}</a>
                        <a href="{{ $contactUrl }}" class="text-gray-500 hover:text-brand-red transition-colors">{{ __t('error_404_link_contact', 'İletişim', 'frontend') }}</a>
                        <a href="{{ $loginUrl }}" class="text-gray-500 hover:text-brand-red transition-colors">{{ __t('error_404_link_login', 'Giriş Yap', 'frontend') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
