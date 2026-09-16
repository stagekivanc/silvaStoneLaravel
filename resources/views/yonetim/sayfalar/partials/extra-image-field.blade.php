@php
    $allowVideo = !empty($allowVideo);
    $imageFieldId = 'extra_image_' . $langCode . '_' . str_replace(['.', '[', ']'], '_', $fieldKey);
    $namePath = implode('][', $keys);
    $storedValue = is_string($val) ? trim($val) : '';
    $isExternalUrl = $storedValue !== '' && filter_var($storedValue, FILTER_VALIDATE_URL);
    $normalized = ltrim($storedValue, '/');
    $resolvedLocal = $storedValue !== '' && !$isExternalUrl
        ? resolve_public_media_path($normalized)
        : null;
    $imageSrc = $isExternalUrl
        ? $storedValue
        : ($resolvedLocal
            ? public_path_to_asset_url($resolvedLocal) . '?v=' . rawurlencode(asset_cache_buster($resolvedLocal))
            : ($storedValue !== '' ? homepage_media_url($storedValue) : null));
    $mediaPath = $isExternalUrl ? (parse_url($storedValue, PHP_URL_PATH) ?: $storedValue) : $storedValue;
    $mediaExt = strtolower(pathinfo((string) $mediaPath, PATHINFO_EXTENSION));
    $isVideo = $allowVideo && in_array($mediaExt, ['mp4', 'webm', 'mov', 'ogg', 'm4v'], true);
    $fileMissing = $storedValue !== '' && !$isExternalUrl && !$resolvedLocal;
    $accept = $allowVideo
        ? 'image/png,image/jpeg,image/webp,image/gif,image/svg+xml,.svg,video/mp4,video/webm,video/quicktime,video/ogg,.mp4,.webm,.mov,.ogg,.m4v'
        : 'image/png,image/jpeg,image/webp,image/gif,image/svg+xml,.svg';
    $maxKb = $allowVideo ? 102400 : 2048;
    $hint = $allowVideo
        ? 'PNG, JPG, WEBP, SVG, GIF veya MP4/WEBM/MOV — görsel maks. 2MB, video maks. 100MB'
        : 'PNG, JPG, WEBP, SVG veya GIF — maks. 2MB';
@endphp

<div class="space-y-3">
    @if($imageSrc)
    <div id="{{ $imageFieldId }}_preview" class="relative group aspect-video max-w-md rounded-xl overflow-hidden border border-slate-200 bg-slate-100 shadow-inner">
        @if($fileMissing)
            <div class="w-full h-full flex flex-col items-center justify-center gap-2 px-4 text-center bg-amber-50">
                <span class="text-xs font-bold text-amber-700">Dosya bulunamadı</span>
            </div>
        @elseif($isVideo)
            <video src="{{ $imageSrc }}" class="w-full h-full object-cover" muted playsinline controls></video>
        @else
            <img src="{{ $imageSrc }}" class="w-full h-full object-cover bg-white" alt="{{ $fieldLabel }}">
        @endif
        <button type="button"
                class="extra-image-remove absolute inset-0 bg-red-500/80 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all font-bold text-sm"
                data-preview="{{ $imageFieldId }}_preview"
                data-remove="{{ $imageFieldId }}_remove"
                data-current="{{ $imageFieldId }}_current">SİL</button>
    </div>
    @endif

    <input type="hidden"
           name="translations[{{ $langCode }}][extras][{{ $namePath }}]"
           id="{{ $imageFieldId }}_current"
           value="{{ $isExternalUrl ? '' : $storedValue }}">
    <input type="hidden"
           name="remove_extra_images[{{ $langCode }}][{{ $namePath }}]"
           id="{{ $imageFieldId }}_remove"
           value="0">

    <input type="file"
           name="extra_images[{{ $langCode }}][{{ $namePath }}]"
           id="{{ $imageFieldId }}"
           accept="{{ $accept }}"
           data-max-kb="{{ $maxKb }}"
           data-allow-video="{{ $allowVideo ? '1' : '0' }}"
           class="extra-image-input w-full bg-slate-50 border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
    <p class="text-[10px] text-slate-400">{{ $hint }}</p>
</div>
