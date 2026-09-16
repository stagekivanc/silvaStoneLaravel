@extends('frontend.layouts.app')

@section('body_class', 'sectorsPage')
@section('header_class', 'headerLight')

@section('content')
@php
    $hero = $solution['hero'] ?? [];
    $sectors = $solution['sectors'] ?? [];
    $cert = $solution['cert'] ?? [];
    $gallery = $solution['gallery'] ?? [];
    $sampleCta = $solution['sample_cta'] ?? [];

    $sampleCtaUrl = trim((string) ($sampleCta['url'] ?? ''));
    if ($sampleCtaUrl === '' || $sampleCtaUrl === '#') {
        $sampleCtaUrl = menu_page_url('application') ?: menu_page_url('contact') ?: '#';
    }
@endphp

<section class="pageHero">
    <div class="container">
        <div class="title">
            @if(($hero['tag'] ?? '') !== '')
                <p class="pageTag">
                    <span class="pageTagMark" aria-hidden="true"></span>
                    <span class="pageTagLabel">{{ $hero['tag'] }}</span>
                </p>
            @endif
            <h1 class="heroTitle">
                @if(($hero['title_lead'] ?? '') !== '')
                    <span class="titleLead">{{ $hero['title_lead'] }}</span>
                @endif
                {{ $hero['title'] ?? '' }}
            </h1>
            @if(($hero['subtitle'] ?? '') !== '')
                <p class="heroSubtitle">{{ $hero['subtitle'] }}</p>
            @endif
        </div>

        <div class="heroArt" aria-hidden="true">
            <img class="heroRunner" src="{{ homepage_media_url($hero['image'] ?? '') }}" alt="{{ $hero['image_alt'] ?? '' }}">
        </div>
    </div>
</section>

<section class="sectorPageDetail">
    <div class="container">
        <ul class="sectorTabs" role="tablist">
            @foreach($sectors as $sector)
                @php $sectorId = $sector['id'] ?? ('sector-' . $loop->iteration); @endphp
                <li class="sectorTab {{ $loop->first ? 'isActive' : '' }}" role="presentation" id="{{ $sectorId }}">
                    <button
                        type="button"
                        role="tab"
                        id="sectorTab-{{ $sectorId }}"
                        aria-controls="sectorPanel-{{ $sectorId }}"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                        data-sector="{{ $sectorId }}"
                    >{{ $sector['label'] ?? '' }}</button>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="sectorPanels">
        @foreach($sectors as $sector)
            @php $sectorId = $sector['id'] ?? ('sector-' . $loop->iteration); @endphp
            <div
                class="sectorPanel {{ $loop->first ? 'isActive' : '' }}"
                id="sectorPanel-{{ $sectorId }}"
                data-sector="{{ $sectorId }}"
                role="tabpanel"
                aria-labelledby="sectorTab-{{ $sectorId }}"
                @if(!$loop->first) hidden @endif
            >
                <div class="container">
                    <figure class="sectorVisual">
                        <img src="{{ homepage_media_url($sector['image'] ?? '') }}" alt="{{ $sector['image_alt'] ?? '' }}">
                    </figure>
                </div>
                <div class="sectorFeatures">
                    <div class="container">
                        @foreach(($sector['features'] ?? []) as $feature)
                            <article class="featureCol">
                                <h3 class="featureLabel">{{ $feature['label'] ?? '' }}</h3>
                                <img class="featureIcon" src="{{ homepage_media_url($feature['icon'] ?? '') }}" alt="" aria-hidden="true">
                                <p class="featureText">{{ $feature['text'] ?? '' }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<section class="sectorPageCert">
    <div class="container">
        <p class="certText">{{ $cert['text'] ?? '' }}</p>
    </div>
</section>

<section class="techPageGallery">
    <div class="container">
        <p class="pageTag tag2">
            <span class="pageTagMark" aria-hidden="true"></span>
            <span class="pageTagLabel">{{ $gallery['tag'] ?? '' }}</span>
        </p>
        <div class="modelGrid">
            @foreach(($gallery['items'] ?? []) as $item)
                <a
                    class="modelCard"
                    data-fancybox="sectorGallery"
                    href="{{ homepage_media_url($item['image'] ?? '') }}"
                    data-caption="{{ $item['caption'] ?? '' }}"
                >
                    <img src="{{ homepage_media_url($item['image'] ?? '') }}" alt="{{ $item['alt'] ?? '' }}">
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="sampleCta">
    <div class="container sampleCtaInner">
        <div class="sampleCtaCopy">
            <h2 class="sampleCtaTitle">{{ $sampleCta['title'] ?? '' }}</h2>
            <p class="sampleCtaText">{!! $sampleCta['text_html'] ?? '' !!}</p>
        </div>
        <figure class="sampleCtaVisual">
            @if(!empty($sampleCta['show_bars']))
                <img class="sampleCtaBars" src="{{ homepage_media_url($sampleCta['bars'] ?? '') }}" alt="" aria-hidden="true">
            @endif
            <img class="sampleCtaShoe" src="{{ homepage_media_url($sampleCta['image'] ?? '') }}" alt="{{ $sampleCta['image_alt'] ?? '' }}">
        </figure>
        <a class="arrowLink" href="{{ $sampleCtaUrl }}">
            <span class="linkLabel">{{ $sampleCta['label'] ?? '' }}</span>
            <span class="linkIcon" aria-hidden="true"></span>
        </a>
    </div>
</section>
@endsection
