@extends('frontend.layouts.app')

@section('title', __t('search_meta_title', 'Arama Sonuçları | Silva Stone', 'frontend'))
@section('body_class', 'searchPage')

@section('content')
@php($lang = app()->getLocale())

<main class="w-full bg-white min-h-screen">
    <section class="w-full border-b border-gray-100 bg-brand-gray">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-8 py-12 md:py-16">
            <nav class="text-xs text-gray-400 uppercase tracking-[0.15em] mb-6" aria-label="{{ __t('breadcrumb_aria_label', 'İçerik yolu', 'frontend') }}">
                <a href="{{ route('home', ['lang' => $lang]) }}" class="hover:text-brand-red transition-colors">{{ __t('error_404_home', 'Ana Sayfa', 'frontend') }}</a>
                <span class="mx-2">/</span>
                <span class="text-gray-600">{{ __t('search_breadcrumb', 'Arama', 'frontend') }}</span>
            </nav>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-light text-gray-900 tracking-tight mb-4">{{ __t('search_title', 'Arama Sonuçları', 'frontend') }}</h1>
            <p class="text-sm md:text-base text-gray-500 font-light max-w-2xl leading-relaxed mb-8">
                @if($query !== '')
                    {!! e(form_t('search_result_meta', '“:query” için :count sonuç bulundu.', ['query' => $query, 'count' => $results->count()])) !!}
                @else
                    {{ __t('search_hint', 'Ürün adı, kategori veya ürün açıklamasıyla arama yapabilirsiniz.', 'frontend') }}
                @endif
            </p>

            <form action="{{ route('search', ['lang' => $lang]) }}" method="GET" class="max-w-2xl">
                <div class="flex items-center border-b-2 border-gray-200 focus-within:border-brand-red transition-colors pb-3 group">
                    <i class="bx bx-search text-2xl text-gray-300 group-focus-within:text-brand-red transition-colors mr-4"></i>
                    <input
                        type="search"
                        name="q"
                        value="{{ $query }}"
                        placeholder="{{ __t('search_placeholder', 'Ne aramıştınız?', 'frontend') }}"
                        required
                        class="w-full bg-transparent text-lg md:text-xl font-light tracking-tight text-gray-900 placeholder-gray-300 focus:outline-none focus:ring-0"
                    >
                    <button type="submit" class="ml-4 shrink-0 px-5 py-2.5 bg-brand-dark text-white text-[10px] font-bold tracking-[0.2em] uppercase hover:bg-brand-red transition-colors">
                        {{ __t('search_submit', 'Ara', 'frontend') }}
                    </button>
                </div>
            </form>
        </div>
    </section>

    <section class="w-full py-10 md:py-14">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-8">
            @if($query === '')
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <i class="bx bx-search-alt text-5xl text-gray-300 mb-4"></i>
                    <h2 class="text-lg font-light text-gray-900 mb-2">{{ __t('search_empty_prompt_title', 'Arama yapın', 'frontend') }}</h2>
                    <p class="text-sm text-gray-500 mb-8 max-w-md">{{ __t('search_empty_prompt_text', 'Mont, pantolon, ayakkabı veya kategori adı yazarak ürünlerimiz arasında arama yapabilirsiniz.', 'frontend') }}</p>
                </div>
            @elseif($results->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <i class="bx bx-search-alt text-5xl text-gray-300 mb-4"></i>
                    <h2 class="text-lg font-light text-gray-900 mb-2">{{ __t('search_empty_title', 'Sonuç bulunamadı', 'frontend') }}</h2>
                    <p class="text-sm text-gray-500 mb-8 max-w-md">
                        {{ form_t('search_empty_description', '“:query” araması için eşleşen sonuç yok. Farklı bir kelime deneyin.', ['query' => $query]) }}
                    </p>
                    <a href="{{ route('module.dispatcher', ['lang' => $lang, 'module' => 'urunler']) }}" class="px-8 py-3 border border-gray-900 text-xs font-bold tracking-[0.15em] uppercase hover:bg-gray-900 hover:text-white transition-colors">
                        {{ __t('search_view_products', 'Tüm Ürünleri Gör', 'frontend') }}
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-6">
                    @foreach($results as $result)
                        <a href="{{ $result['url'] }}" class="border border-gray-200 p-5 hover:border-brand-red transition-colors group">
                            <p class="text-[10px] tracking-[0.2em] uppercase text-gray-400 mb-2">{{ $result['type'] }}</p>
                            <h3 class="text-base md:text-lg font-medium text-gray-900 group-hover:text-brand-red transition-colors mb-2">{{ $result['title'] }}</h3>
                            <p class="text-sm text-gray-500 line-clamp-3">{{ strip_tags((string) $result['description']) }}</p>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</main>
@endsection
