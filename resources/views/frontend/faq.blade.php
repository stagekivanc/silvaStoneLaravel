@extends('frontend.layouts.app')

@section('title', data_get($page, 'seo_title') ?: 'Sıkça Sorulan Sorular')
@section('body_class', 'faqPage')

@section('content')
@php
    $lang = app()->getLocale();
    $homeUrl = route('home', ['lang' => $lang]);
    $contactUrl = route('module.dispatcher', ['lang' => $lang, 'module' => 'iletisim']);
    $hero = $content['hero'] ?? [];
    $cta = $content['cta'] ?? [];
@endphp

<style>
    .faq-cat-btn.active {
        background-color: #111111;
        color: #fff;
        border-color: #111111;
    }
    .faq-item.is-open .faq-chevron {
        transform: rotate(180deg);
    }
    .faq-item.is-open .faq-answer {
        max-height: 400px;
        opacity: 1;
        padding-top: 0.75rem;
        padding-bottom: 1.25rem;
    }
    .faq-answer {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transition: max-height 0.4s ease, opacity 0.3s ease, padding 0.3s ease;
    }
    .faq-panel { display: none; }
    .faq-panel.is-active { display: block; }
</style>

<main class="w-full bg-white min-h-screen">
    <section class="w-full border-b border-gray-100 bg-brand-gray">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-8 py-12 md:py-16">
            <nav class="text-xs text-gray-400 uppercase tracking-[0.15em] mb-6">
                <a href="{{ $homeUrl }}" class="hover:text-brand-red transition-colors">Ana Sayfa</a>
                <span class="mx-2">/</span>
                <span class="text-gray-600">{{ $hero['title'] ?? 'Sıkça Sorulan Sorular' }}</span>
            </nav>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-light text-gray-900 tracking-tight mb-4">{{ $hero['title'] ?? 'Sıkça Sorulan Sorular' }}</h1>
            <p class="text-sm md:text-base text-gray-500 font-light max-w-2xl leading-relaxed">
                {{ $hero['subtitle'] ?? '' }}
            </p>
        </div>
    </section>

    <section class="w-full py-12 md:py-16">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16">
                <aside class="lg:col-span-4 xl:col-span-3">
                    <p class="text-[10px] font-bold tracking-[0.25em] uppercase text-gray-400 mb-4">Kategoriler</p>
                    <div class="flex lg:flex-col gap-2 overflow-x-auto lg:overflow-visible pb-2 lg:pb-0 -mx-1 px-1">
                        @foreach($faqCategories as $i => $cat)
                        <button type="button"
                            class="faq-cat-btn shrink-0 lg:w-full flex items-center gap-3 px-4 py-3 border border-gray-200 rounded-lg text-left text-xs font-bold tracking-wide uppercase text-gray-600 hover:border-gray-900 transition-colors {{ $i === 0 ? 'active' : '' }}"
                            data-cat="{{ $cat['id'] }}">
                            <i class="bx {{ $cat['icon'] }} text-lg text-brand-red"></i>
                            {{ $cat['label'] }}
                        </button>
                        @endforeach
                    </div>

                    <div class="hidden lg:block mt-10 p-6 bg-brand-gray border border-brand-border">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-[0.12em] mb-2">{{ $cta['title'] ?? 'Cevabı bulamadınız mı?' }}</h3>
                        <p class="text-sm text-gray-500 leading-relaxed mb-4">{{ $cta['text'] ?? '' }}</p>
                        <a href="{{ $contactUrl }}" class="inline-flex items-center gap-2 text-xs font-bold tracking-[0.15em] uppercase text-brand-red hover:text-brand-dark transition-colors">
                            {{ $cta['label'] ?? 'Bize Ulaşın' }} <i class="bx bx-right-arrow-alt"></i>
                        </a>
                    </div>
                </aside>

                <div class="lg:col-span-8 xl:col-span-9">
                    @foreach($faqCategories as $i => $cat)
                    <div class="faq-panel {{ $i === 0 ? 'is-active' : '' }}" id="panel-{{ $cat['id'] }}">
                        <div class="flex items-center gap-3 mb-8 pb-6 border-b border-gray-100">
                            <span class="w-11 h-11 rounded-full bg-red-50 flex items-center justify-center text-brand-red text-xl">
                                <i class="bx {{ $cat['icon'] }}"></i>
                            </span>
                            <div>
                                <h2 class="text-xl md:text-2xl font-semibold text-gray-900 tracking-tight">{{ $cat['label'] }}</h2>
                                <p class="text-sm text-gray-400 mt-0.5">{{ count($cat['sorular']) }} soru</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            @foreach($cat['sorular'] as $j => $item)
                            <div class="faq-item border border-gray-100 rounded-xl overflow-hidden hover:border-gray-200 transition-colors {{ ($i === 0 && $j === 0) ? 'is-open' : '' }}">
                                <button type="button" class="faq-trigger w-full flex items-center justify-between gap-4 px-5 py-4 text-left bg-white hover:bg-gray-50/50 transition-colors" aria-expanded="{{ ($i === 0 && $j === 0) ? 'true' : 'false' }}">
                                    <span class="text-sm md:text-base font-medium text-gray-900 leading-snug">{{ $item['soru'] }}</span>
                                    <i class="bx bx-chevron-down faq-chevron text-xl text-gray-400 shrink-0 transition-transform duration-300"></i>
                                </button>
                                <div class="faq-answer px-5 text-sm text-gray-500 leading-relaxed border-t border-transparent">
                                    {{ $item['cevap'] }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="lg:hidden w-full border-t border-gray-100 bg-brand-gray py-10">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-8 text-center">
            <p class="text-sm text-gray-500 mb-4">Cevabı bulamadınız mı?</p>
            <a href="{{ $contactUrl }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-brand-dark text-white text-xs font-bold tracking-[0.2em] uppercase hover:bg-brand-red transition-colors">
                İletişime Geçin
            </a>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
(function () {
    const catBtns = document.querySelectorAll('.faq-cat-btn');
    const panels = document.querySelectorAll('.faq-panel');

    catBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.cat;
            catBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            panels.forEach(p => p.classList.toggle('is-active', p.id === 'panel-' + id));
        });
    });

    document.querySelectorAll('.faq-trigger').forEach(trigger => {
        trigger.addEventListener('click', () => {
            const item = trigger.closest('.faq-item');
            const isOpen = item.classList.contains('is-open');
            const panel = item.closest('.faq-panel');
            panel.querySelectorAll('.faq-item').forEach(i => {
                i.classList.remove('is-open');
                i.querySelector('.faq-trigger')?.setAttribute('aria-expanded', 'false');
            });
            if (!isOpen) {
                item.classList.add('is-open');
                trigger.setAttribute('aria-expanded', 'true');
            }
        });
    });
})();
</script>
@endpush
