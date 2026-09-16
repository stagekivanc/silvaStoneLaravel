@extends('frontend.layouts.app')

@section('title', data_get($page, 'seo_title') ?: 'Tüm Ürünler | Silva Stone')
@section('body_class', 'productsPage')

@section('content')
@php
    $lang = app()->getLocale();
    $allProducts = $products ?? collect();
    $currentCategory = $currentCategory ?? null;
    $catalogCategories = $catalogCategories ?? collect();
    $catalogCounts = $catalogCounts ?? [];
    $totalCount = $catalogCounts['all'] ?? $allProducts->count();
    $pageTitle = $currentCategory?->name ?: 'Tüm Ürünler';
    $currentId = $currentCategory?->id;
    $currentParentId = $currentCategory?->parent_id;
    $openParentId = $currentParentId ?: $currentId;
    $renkler = [
        'siyah' => '#111',
        'beyaz' => '#fff',
        'lacivert' => '#1e3a5f',
        'gri' => '#9ca3af',
        'haki' => '#8b8b5e',
        'turuncu' => '#f97316',
        'bordo' => '#7f1d1d',
        'sari' => '#d4ff00',
    ];
@endphp

<main class="w-full bg-white min-h-screen">
    <section class="w-full border-b border-gray-100 bg-brand-gray">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-8 py-10 md:py-14">
            <nav class="text-xs text-gray-400 uppercase tracking-[0.15em] mb-5">
                <a href="{{ route('home', ['lang' => $lang]) }}" class="hover:text-brand-red transition-colors">Ana Sayfa</a>
                <span class="mx-2">/</span>
                <a href="{{ route('module.dispatcher', ['lang' => $lang, 'module' => 'urunler']) }}" class="hover:text-brand-red transition-colors">Ürünler</a>
                @if($currentCategory)
                    <span class="mx-2">/</span>
                    <span class="text-gray-600">{{ $pageTitle }}</span>
                @endif
            </nav>
            <h1 class="text-3xl md:text-4xl font-light text-gray-900 tracking-tight mb-3">{{ $pageTitle }}</h1>
            <p class="text-sm text-gray-500 font-light max-w-xl leading-relaxed">
                Profesyonel iş kıyafetleri koleksiyonumuzu keşfedin. Kategori ve filtrelerle aradığınız ürünü kolayca bulun.
            </p>
        </div>
    </section>

    <section class="w-full py-8 md:py-12">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-8">
            <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">

                <aside class="w-full lg:w-72 xl:w-80 shrink-0 self-start">
                    <button id="filterToggle" type="button" class="lg:hidden w-full flex items-center justify-between px-5 py-4 border border-gray-200 bg-white text-sm font-bold uppercase tracking-[0.15em] text-gray-900 mb-4">
                        <span class="flex items-center gap-2"><i class="bx bx-filter-alt text-lg"></i> Filtrele</span>
                        <i class="bx bx-chevron-down text-xl transition-transform" id="filterChevron"></i>
                    </button>

                    <div id="filterPanel" class="hidden lg:block lg:sticky lg:top-24 space-y-6">

                        {{-- Kategoriler (accordion) --}}
                        <div class="border border-gray-200 p-5">
                            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-[0.2em] mb-4 pb-3 border-b border-gray-100">Kategoriler</h3>
                            <ul class="space-y-1" id="categoryFilters">
                                <li class="pb-2 mb-2 border-b border-gray-100">
                                    <a href="{{ route('module.dispatcher', ['lang' => $lang, 'module' => 'urunler']) }}" class="w-full flex items-center justify-between py-1.5 text-left transition-colors {{ !$currentCategory ? 'text-brand-red' : 'text-gray-900 hover:text-brand-red' }}">
                                        <span class="text-sm font-medium">Tümü</span>
                                        <span class="text-xs text-gray-400">{{ $totalCount }}</span>
                                    </a>
                                </li>
                                @foreach($catalogCategories as $category)
                                    @php
                                        $isOpen = $openParentId === $category->id;
                                        $isParentActive = $currentId === $category->id && ! $currentParentId;
                                        $hasChildren = $category->relationLoaded('children') && $category->children->isNotEmpty();
                                    @endphp
                                    <li class="category-group" data-parent="{{ $category->id }}">
                                        <div class="flex items-center justify-between gap-2 py-1.5">
                                            <a href="{{ route('module.dispatcher', ['lang' => $lang, 'module' => 'urunler', 'slug' => $category->slug]) }}" class="flex items-center justify-between flex-1 min-w-0 text-left transition-colors {{ $isParentActive ? 'text-brand-red' : 'text-gray-800 hover:text-brand-red' }}">
                                                <span class="text-sm font-medium truncate">{{ $category->name }}</span>
                                                <span class="text-xs text-gray-400 shrink-0 ml-2">{{ $catalogCounts[$category->id] ?? 0 }}</span>
                                            </a>
                                            @if($hasChildren)
                                            <button type="button" class="category-toggle w-7 h-7 flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors shrink-0" aria-label="Alt kategorileri aç/kapat">
                                                <i class="bx bx-chevron-down text-lg transition-transform duration-300 {{ $isOpen ? 'rotate-180' : '' }}"></i>
                                            </button>
                                            @endif
                                        </div>
                                        @if($hasChildren)
                                        <ul class="subcategory-list ml-3 mt-1 mb-2 space-y-0.5 border-l border-gray-100 pl-4 {{ $isOpen ? '' : 'hidden' }}">
                                            @foreach($category->children as $child)
                                                @php $childActive = $currentId === $child->id; @endphp
                                                <li>
                                                    <a href="{{ route('module.dispatcher', ['lang' => $lang, 'module' => 'urunler', 'slug' => $child->slug]) }}" class="w-full flex items-center justify-between py-1.5 text-left transition-colors {{ $childActive ? 'text-brand-red' : 'text-gray-500 hover:text-gray-900' }}">
                                                        <span class="text-xs truncate">{{ $child->name }}</span>
                                                        <span class="text-[10px] text-gray-400 shrink-0 ml-2">{{ $catalogCounts[$child->id] ?? 0 }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Beden --}}
                        <div class="border border-gray-200 p-5">
                            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-[0.2em] mb-4 pb-3 border-b border-gray-100">Beden</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['S','M','L','XL','XXL'] as $beden)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="size" value="{{ $beden }}" class="peer sr-only">
                                    <span class="inline-flex items-center justify-center w-10 h-10 border border-gray-200 text-xs font-medium text-gray-600 peer-checked:bg-brand-dark peer-checked:text-white peer-checked:border-brand-dark hover:border-gray-900 transition-colors">{{ $beden }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Renk --}}
                        <div class="border border-gray-200 p-5">
                            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-[0.2em] mb-4 pb-3 border-b border-gray-100">Renk</h3>
                            <div class="space-y-2">
                                @foreach($renkler as $slug => $hex)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="color" value="{{ $slug }}" class="w-4 h-4 accent-brand-red">
                                    <span class="w-4 h-4 rounded-full border border-gray-200 shrink-0" style="background-color: {{ $hex }}"></span>
                                    <span class="text-sm text-gray-600 group-hover:text-gray-900 capitalize">{{ ucfirst($slug) }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Etiketler --}}
                        <div class="border border-gray-200 p-5">
                            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-[0.2em] mb-4 pb-3 border-b border-gray-100">Etiketler</h3>
                            <div class="space-y-3">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="badge" value="yeni" class="w-4 h-4 accent-brand-red">
                                    <span class="text-sm text-gray-600 group-hover:text-gray-900">Yeni Gelenler</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="badge" value="cok-satan" class="w-4 h-4 accent-brand-red">
                                    <span class="text-sm text-gray-600 group-hover:text-gray-900">Çok Satanlar</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="badge" value="indirim" class="w-4 h-4 accent-brand-red">
                                    <span class="text-sm text-gray-600 group-hover:text-gray-900">İndirimli Ürünler</span>
                                </label>
                            </div>
                        </div>

                        <button type="button" id="clearFilters" class="w-full py-3 border border-gray-300 text-xs font-bold uppercase tracking-[0.15em] text-gray-600 hover:border-brand-red hover:text-brand-red transition-colors">
                            Filtreleri Temizle
                        </button>
                    </div>
                </aside>

                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-100">
                        <p class="text-sm text-gray-500">
                            <span id="productCount" class="font-semibold text-gray-900">{{ $allProducts->count() }}</span> ürün listeleniyor
                        </p>
                        <div class="flex items-center gap-3">
                            <label for="sortSelect" class="text-xs font-bold uppercase tracking-[0.15em] text-gray-500 shrink-0">Sırala:</label>
                            <select id="sortSelect" class="px-4 py-2.5 border border-gray-200 bg-white text-sm text-gray-900 outline-none focus:border-brand-dark transition-colors cursor-pointer">
                                <option value="default">Önerilen</option>
                                <option value="name-asc">İsim: A-Z</option>
                                <option value="name-desc">İsim: Z-A</option>
                            </select>
                        </div>
                    </div>

                    <div id="noResults" class="{{ $allProducts->isEmpty() ? 'flex' : 'hidden' }} flex-col items-center justify-center py-20 text-center">
                        <i class="bx bx-search-alt text-5xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-light text-gray-900 mb-2">Ürün bulunamadı</h3>
                        <p class="text-sm text-gray-500 mb-6">Seçtiğiniz filtrelere uygun ürün yok. Filtreleri değiştirmeyi deneyin.</p>
                        <button type="button" onclick="document.getElementById('clearFilters').click()" class="px-6 py-3 border border-gray-900 text-xs font-bold tracking-[0.15em] uppercase hover:bg-gray-900 hover:text-white transition-colors">
                            Filtreleri Temizle
                        </button>
                    </div>

                    @if($allProducts->isNotEmpty())
                    <div id="productGrid" class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6 lg:gap-8">
                        @foreach($allProducts as $urun)
                            @include('frontend.partials.product-card', ['product' => $urun])
                        @endforeach
                    </div>
                    @else
                    <div id="productGrid" class="hidden grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6 lg:gap-8"></div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterToggle = document.getElementById('filterToggle');
    const filterPanel = document.getElementById('filterPanel');
    const filterChevron = document.getElementById('filterChevron');
    const productGrid = document.getElementById('productGrid');
    const productCount = document.getElementById('productCount');
    const noResults = document.getElementById('noResults');
    const clearFilters = document.getElementById('clearFilters');
    const sortSelect = document.getElementById('sortSelect');

    if (filterToggle && filterPanel) {
        filterToggle.addEventListener('click', () => {
            filterPanel.classList.toggle('hidden');
            filterChevron?.classList.toggle('rotate-180');
        });
    }

    document.querySelectorAll('.category-toggle').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const group = btn.closest('.category-group');
            const list = group?.querySelector('.subcategory-list');
            const icon = btn.querySelector('i');
            list?.classList.toggle('hidden');
            icon?.classList.toggle('rotate-180');
        });
    });

    if (!productGrid) return;

    const allCards = Array.from(productGrid.querySelectorAll('.product-card'));

    function getCheckedValues(name) {
        return Array.from(document.querySelectorAll(`#filterPanel input[name="${name}"]:checked`)).map(el => el.value);
    }

    function applyFilters() {
        const sizes = getCheckedValues('size');
        const colors = getCheckedValues('color');
        const badges = getCheckedValues('badge');

        let visible = allCards.filter(card => {
            const cardSizes = (card.dataset.sizes || '').split(',').filter(Boolean);
            const cardColors = (card.dataset.colors || '').split(',').filter(Boolean);
            const cardBadge = card.dataset.badge || '';

            if (sizes.length && !sizes.some(s => cardSizes.includes(s))) return false;
            if (colors.length && !colors.some(c => cardColors.includes(c))) return false;
            if (badges.length && !badges.includes(cardBadge)) return false;

            return true;
        });

        applySort(visible);
    }

    function applySort(cards) {
        const sort = sortSelect?.value || 'default';
        let sorted = [...cards];

        if (sort === 'name-asc') {
            sorted.sort((a, b) => (a.dataset.name || '').localeCompare(b.dataset.name || '', 'tr'));
        } else if (sort === 'name-desc') {
            sorted.sort((a, b) => (b.dataset.name || '').localeCompare(a.dataset.name || '', 'tr'));
        }

        allCards.forEach(card => card.classList.add('hidden'));
        sorted.forEach(card => {
            card.classList.remove('hidden');
            productGrid.appendChild(card);
        });

        if (productCount) productCount.textContent = sorted.length;
        noResults?.classList.toggle('hidden', sorted.length > 0);
        noResults?.classList.toggle('flex', sorted.length === 0);
        productGrid.classList.toggle('hidden', sorted.length === 0);
    }

    document.querySelectorAll('#filterPanel input').forEach(input => {
        input.addEventListener('change', applyFilters);
    });

    sortSelect?.addEventListener('change', applyFilters);

    clearFilters?.addEventListener('click', () => {
        document.querySelectorAll('#filterPanel input[type="checkbox"]').forEach(cb => { cb.checked = false; });
        if (sortSelect) sortSelect.value = 'default';
        applyFilters();
    });
});
</script>
@endpush
