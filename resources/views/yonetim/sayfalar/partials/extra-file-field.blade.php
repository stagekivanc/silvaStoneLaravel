@php
    $fileFieldId = 'extra_file_' . $langCode . '_' . str_replace(['.', '[', ']'], '_', $fieldKey);
    $namePath = implode('][', $keys);
    $storedValue = is_string($val) ? trim($val) : '';
    $isExternalUrl = $storedValue !== '' && filter_var($storedValue, FILTER_VALIDATE_URL);
    $isFrontendAsset = str_starts_with(ltrim($storedValue, '/'), 'front-assets/');
    $fileSrc = $storedValue
        ? ($isExternalUrl
            ? $storedValue
            : ($isFrontendAsset ? url(ltrim($storedValue, '/')) : asset('uploads/' . ltrim($storedValue, '/'))))
        : null;
    $fileLabel = $storedValue ? basename(parse_url($storedValue, PHP_URL_PATH) ?: $storedValue) : '';
@endphp

<div class="space-y-3">
    @if($fileSrc)
        <div id="{{ $fileFieldId }}_preview" class="relative group max-w-md rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 flex items-center justify-between gap-3">
            <a href="{{ $fileSrc }}" target="_blank" rel="noopener" class="text-sm font-semibold text-blue-600 hover:underline truncate">
                {{ $fileLabel ?: 'Mevcut dosya' }}
            </a>
            <button type="button"
                    class="extra-file-remove shrink-0 text-xs font-bold text-red-600 hover:text-red-700"
                    data-preview="{{ $fileFieldId }}_preview"
                    data-remove="{{ $fileFieldId }}_remove"
                    data-current="{{ $fileFieldId }}_current">Kaldır</button>
        </div>
    @endif

    <input type="hidden"
           name="translations[{{ $langCode }}][extras][{{ $namePath }}]"
           id="{{ $fileFieldId }}_current"
           value="{{ $storedValue }}">
    <input type="hidden"
           name="remove_extra_files[{{ $langCode }}][{{ $namePath }}]"
           id="{{ $fileFieldId }}_remove"
           value="0">

    <input type="file"
           name="extra_files[{{ $langCode }}][{{ $namePath }}]"
           id="{{ $fileFieldId }}"
           accept=".pdf,.jpg,.jpeg,.png,.webp,.gif,application/pdf,image/*"
           data-max-kb="10240"
           class="extra-file-input w-full bg-slate-50 border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

    <p class="text-[10px] text-slate-400">PDF / görsel yükleyin (maks. 10MB)</p>
</div>
