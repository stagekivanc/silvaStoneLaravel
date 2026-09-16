@extends('frontend.layouts.app')

@section('body_class', 'sustainabilityPage')
@section('header_class', 'headerLight')

@section('content')
@php
    $hero = $sustainability['hero'] ?? [];
    $facility = $sustainability['facility'] ?? [];
    $panels = $sustainability['panels'] ?? [];
    $closing = $sustainability['closing'] ?? [];
    $sampleCta = $sustainability['sample_cta'] ?? [];

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
            <h1 class="heroTitle">{{ $hero['title'] ?? '' }}</h1>
            @if(($hero['subtitle'] ?? '') !== '')
                <p class="heroSubtitle">{{ $hero['subtitle'] }}</p>
            @endif
        </div>

        <div class="heroArt" aria-hidden="true">
            <img class="heroRunner" src="{{ homepage_media_url($hero['image'] ?? '') }}" alt="{{ $hero['image_alt'] ?? '' }}">
        </div>
    </div>
</section>

<section class="sustainFacility">
    <div class="facilityMedia" aria-hidden="true">
        <img src="{{ homepage_media_url($facility['image'] ?? '') }}" alt="">
    </div>
    <div class="container">
        <p class="facilityText">{{ $facility['text'] ?? '' }}</p>
    </div>
</section>

<section class="sustainPanels">
    <div class="panelRow">
        @foreach($panels as $panel)
            <article class="sustainPanel">
                <div class="panelInner">
                    <div class="panelText">
                        @foreach([
                            ['text' => $panel['text_a'] ?? '', 'lead' => !empty($panel['text_a_lead'])],
                            ['text' => $panel['text_b'] ?? '', 'lead' => !empty($panel['text_b_lead'])],
                        ] as $paragraph)
                            @continue(trim((string) ($paragraph['text'] ?? '')) === '')
                            <p @class(['panelTextLead' => $paragraph['lead']])>
                                {{ $paragraph['text'] }}
                            </p>
                        @endforeach
                    </div>
                    <div class="panelBadge">
                        <span class="badgeIcon">
                            <img src="{{ homepage_media_url($panel['badge_icon'] ?? '') }}" alt="">
                        </span>
                        <p class="badgeLabel">{{ $panel['badge_label'] ?? '' }}</p>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</section>

<section class="sustainClosing">
    <div class="container">
        <p class="closingText">{{ $closing['text'] ?? '' }}</p>
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
