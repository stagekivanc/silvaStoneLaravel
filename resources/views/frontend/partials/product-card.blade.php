@php
$lang = app()->getLocale();
$productUrl = route('module.dispatcher', [
    'lang'   => $lang,
    'module' => 'urun',
    'slug'   => $product->slug ?? $product->id,
]);
$imageUrl  = $product->image_url ?? front_asset('img/images.jpeg');
$category  = $product->category;
if ($category && $category->parent_id && ! $category->relationLoaded('parent')) {
    $category->load('parent');
}
$catName   = $category?->name ?? '';
$parentSlug = $category?->parent?->slug ?? ($category && ! $category->parent_id ? $category->slug : '');
$subSlug   = $category && $category->parent_id ? ($category->slug ?? '') : '';
$badge     = method_exists($product, 'badgeKey') ? $product->badgeKey() : ($product->badge ?? '');
$badgeLabel = method_exists($product, 'badgeLabel') ? $product->badgeLabel($lang) : $badge;
$sizes     = method_exists($product, 'sizeOptions') ? $product->sizeOptions() : [];
$colors    = method_exists($product, 'colorOptions') ? $product->colorOptions() : [];
$galleries = [];
if (!empty($product->gallery)) {
    $gallery = is_string($product->gallery) ? json_decode($product->gallery, true) : (array) $product->gallery;
    foreach (array_slice((array) $gallery, 0, 3) as $g) {
        $url = homepage_media_url(is_array($g) ? ($g['path'] ?? $g['url'] ?? '') : $g);
        if ($url) $galleries[] = $url;
    }
}
if (empty($galleries) && $imageUrl) {
    $galleries[] = $imageUrl;
}
@endphp

<a href="{{ $productUrl }}"
   class="product-card {{ empty($extraClass) ? 'w-full' : '' }} min-w-0 flex flex-col group cursor-pointer {{ $extraClass ?? '' }}"
   data-name="{{ $product->name }}"
   data-category="{{ $parentSlug }}"
   data-subcategory="{{ $subSlug }}"
   data-sizes="{{ implode(',', $sizes) }}"
   data-colors="{{ implode(',', $colors) }}"
   data-badge="{{ $badge }}">
    <div class="w-full aspect-[4/5] bg-[#f5f5f5] mb-4 overflow-hidden relative scrub-card" onmouseleave="resetScrubImage(this)">

        {{-- Badge --}}
        @if($badge !== '')
            <span class="absolute top-3 left-3 z-20 px-2.5 py-1 {{ $badge === 'yeni' ? 'bg-gray-900' : 'bg-brand-red' }} text-white text-[9px] font-bold tracking-widest uppercase">{{ $badgeLabel }}</span>
        @endif

        {{-- Görseller --}}
        @foreach($galleries as $i => $img)
        <img src="{{ $img }}" alt="{{ $product->name }}" class="absolute inset-0 w-full h-full object-cover mix-blend-multiply {{ $i === 0 ? 'opacity-100' : 'opacity-0' }} transition-opacity duration-300 scrub-img" style="z-index: {{ $i === 0 ? 1 : 0 }}">
        @endforeach

        {{-- Scrub Zones --}}
        <div class="absolute inset-0 z-10 flex w-full h-full">
            <div class="flex-1 h-full cursor-e-resize" onmouseover="showScrubImage(this, 0)"></div>
            <div class="flex-1 h-full cursor-e-resize" onmouseover="showScrubImage(this, 1)"></div>
            <div class="flex-1 h-full cursor-e-resize" onmouseover="showScrubImage(this, 2)"></div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 z-20 p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
            <span class="w-full py-3 bg-white/95 backdrop-blur text-[10px] font-bold tracking-[0.2em] uppercase text-gray-900 group-hover:bg-gray-900 group-hover:text-white transition-colors block text-center">İncele</span>
        </div>
    </div>

    <div class="flex flex-col items-start gap-1">
        <span class="text-[9px] text-gray-400 uppercase tracking-[0.15em]">{{ $catName }}</span>
        <h3 class="text-[10px] md:text-[11px] font-semibold tracking-wider text-gray-900 uppercase line-clamp-2 group-hover:text-brand-red transition-colors">{{ $product->name }}</h3>
    </div>
</a>

