@extends('frontend.layouts.app')

@section('title', __t('account_offer_no', 'Teklif No', 'frontend') . ' ' . $offer->application_number . ' | Silva Stone')
@section('body_class', 'customerOfferPage')

@section('content')
@php
    $lang = app()->getLocale();
    $panelUrl = route('customer.panel', ['lang' => $lang, 'tab' => 'teklifler']);
@endphp

<main class="w-full bg-brand-gray/30 py-8 md:py-12 px-4 sm:px-6">
    <div class="w-full max-w-4xl mx-auto">
        <a href="{{ $panelUrl }}" class="inline-flex items-center text-xs text-gray-500 hover:text-brand-red mb-6 transition-colors">
            ← {{ __t('account_offer_back', 'Teklif geçmişine dön', 'frontend') }}
        </a>

        <div class="bg-white border border-gray-100 p-6 md:p-8 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
                <div>
                    <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 mb-1">{{ __t('account_offer_no', 'Teklif No', 'frontend') }}</div>
                    <h1 class="text-2xl font-light text-gray-900">{{ $offer->application_number }}</h1>
                </div>
                <span class="inline-flex self-start px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-brand-gray text-gray-700">
                    {{ $offer->statusLabel() }}
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm mb-6">
                <div>
                    <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">{{ __t('account_offer_date', 'Tarih', 'frontend') }}</div>
                    <div>{{ $offer->created_at?->format('d.m.Y H:i') }}</div>
                </div>
                <div>
                    <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">{{ __t('account_offer_source', 'Kaynak', 'frontend') }}</div>
                    <div>{{ $offer->isCartQuote() ? __t('account_offer_source_cart', 'Sepet teklifi', 'frontend') : __t('account_source_form', 'Form', 'frontend') }}</div>
                </div>
                <div>
                    <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">{{ __t('account_offer_items', 'Kalem', 'frontend') }}</div>
                    <div>{{ is_array($offer->items) ? count($offer->items) : ($offer->product ? 1 : '—') }}</div>
                </div>
            </div>

            @if($offer->message)
            <div class="mb-6">
                <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">{{ __t('account_offer_note', 'Notunuz', 'frontend') }}</div>
                <p class="text-sm text-gray-600 leading-relaxed bg-brand-gray/40 p-4">{{ $offer->message }}</p>
            </div>
            @endif
        </div>

        @if(!empty($offer->items) && is_array($offer->items))
        <div class="bg-white border border-gray-100 p-6 md:p-8">
            <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900 mb-5">{{ __t('account_offer_products', 'Ürünler', 'frontend') }}</h2>
            <div class="space-y-3">
                @foreach($offer->items as $item)
                <div class="flex gap-4 p-4 bg-brand-gray/30 border border-gray-100">
                    @if(!empty($item['image']))
                    <img src="{{ $item['image'] }}" alt="" class="w-16 h-20 object-cover bg-white shrink-0">
                    @endif
                    <div class="min-w-0 flex-1">
                        <div class="text-sm font-medium text-gray-900">{{ $item['name'] ?? __t('account_offer_product', 'Ürün', 'frontend') }}</div>
                        <div class="text-xs text-gray-500 mt-1">
                            @if(!empty($item['sku'])) SKU: {{ $item['sku'] }} · @endif
                            @if(!empty($item['color'])) {{ __t('cart_color', 'Renk', 'frontend') }}: {{ $item['color'] }} · @endif
                            @if(!empty($item['size'])) {{ __t('cart_size', 'Beden', 'frontend') }}: {{ $item['size'] }} · @endif
                            {{ __t('cart_qty', 'Adet', 'frontend') }}: {{ $item['qty'] ?? 1 }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @elseif($offer->product)
        <div class="bg-white border border-gray-100 p-6 md:p-8">
            <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900 mb-5">{{ __t('account_offer_product', 'Ürün', 'frontend') }}</h2>
            <div class="text-sm font-medium">{{ $offer->product->name }}</div>
        </div>
        @endif
    </div>
</main>
@endsection
