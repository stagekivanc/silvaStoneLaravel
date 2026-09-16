@extends('frontend.layouts.app')

@section('body_class', 'aboutPage')
@section('header_class', 'headerLight')

@section('content')
@php
    $hero = $about['hero'] ?? [];
    $timeline = $about['timeline'] ?? [];
    $stats = $about['stats'] ?? [];
    $approach = $about['approach'] ?? [];
    $vision = $about['vision'] ?? [];
@endphp

<section class="aboutHero">
    <div class="container">
        <p class="pageTag">
            <span class="pageTagMark" aria-hidden="true"></span>
            <span class="pageTagLabel">{{ $hero['tag'] ?? '' }}</span>
        </p>
        <h1 class="heroTitle">{{ $hero['title'] ?? '' }}</h1>
        <div class="heroGallery">
            @foreach(($hero['gallery'] ?? []) as $item)
                <figure class="galleryItem">
                    <img src="{{ homepage_media_url($item['image'] ?? '') }}" alt="{{ $item['alt'] ?? '' }}">
                </figure>
            @endforeach
        </div>
    </div>
</section>

<section class="aboutTimeline">
    <div class="container">
        <div class="timelineLayout">
            <h2 class="sectionTitle">{{ $timeline['title'] ?? '' }}</h2>
            <div class="timelineList">
                @foreach(($timeline['items'] ?? []) as $item)
                    <article @class(['timelineItem', 'isActive' => !empty($item['active']) || $loop->first])>
                        <span class="timelineDot" aria-hidden="true"></span>
                        <div class="timelineBody">
                            <h3 class="itemTitle">{{ $item['title'] ?? '' }}</h3>
                            <p class="itemYear">{{ $item['year'] ?? '' }}</p>
                            <p class="itemText">{{ $item['text'] ?? '' }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="aboutStats">
    <div class="container">
        <p class="statsBackdrop" aria-hidden="true">{{ $stats['backdrop'] ?? '' }}</p>
    </div>
    <div class="stat">
        <div class="container">
            <ul class="statsGrid">
                @foreach(($stats['items'] ?? []) as $stat)
                    <li class="statsItem">
                        <p class="statsValue">
                            <span
                                data-count="{{ $stat['count'] ?? 0 }}"
                                @if(($stat['suffix'] ?? '') !== '') data-suffix="{{ $stat['suffix'] }}" @endif
                                @if(($stat['group'] ?? '') !== '') data-group="{{ $stat['group'] }}" @endif
                            >0</span>
                        </p>
                        <p class="statsLabel">{{ $stat['label'] ?? '' }}</p>
                        <p class="statsText">{{ $stat['text'] ?? '' }}</p>
                        <span class="statsIcon">
                            <img src="{{ homepage_media_url($stat['icon'] ?? '') }}" alt="">
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

<section class="aboutApproach">
    <div class="container">
        <p class="pageTag">
            <span class="pageTagMark" aria-hidden="true"></span>
            <span class="pageTagLabel">{{ $approach['tag'] ?? '' }}</span>
        </p>
        <h2 class="sectionTitle">{!! nl2br(e($approach['title'] ?? '')) !!}</h2>
        <p class="sectionText">{{ $approach['text'] ?? '' }}</p>
    </div>
</section>

<section class="aboutVision">
    <div class="container">
        <p class="visionBackdrop" aria-hidden="true">{{ $vision['backdrop'] ?? '' }}</p>
        <div class="visionLayout">
            <figure class="visionVisual">
                <img src="{{ homepage_media_url($vision['image'] ?? '') }}" alt="{{ $vision['image_alt'] ?? '' }}">
            </figure>
            <div class="visionCards">
                @foreach(($vision['cards'] ?? []) as $card)
                    <article class="visionCard">
                        <h3 class="cardTitle">{{ $card['title'] ?? '' }}</h3>
                        <span class="cardLine" aria-hidden="true"></span>
                        <p class="cardText">{{ $card['text'] ?? '' }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
