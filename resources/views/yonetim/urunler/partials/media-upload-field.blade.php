@php
    $currentName = $currentName ?? '';
    $fileName = $fileName ?? '';
    $removeName = $removeName ?? '';
    $label = $label ?? 'Görsel';
    $value = trim((string) ($value ?? ''));
    $accept = $accept ?? 'image/png,image/jpeg,image/webp,image/gif,image/svg+xml,.svg';
    $hint = $hint ?? 'PNG, JPG, WEBP, SVG veya GIF · En fazla 10 MB';
    $fieldId = $fieldId ?? ('tech_media_' . substr(md5($currentName . $fileName), 0, 12));
    $previewUrl = $value !== '' ? homepage_media_url($value) : null;
    $isSvg = str_ends_with(strtolower(parse_url($value, PHP_URL_PATH) ?: $value), '.svg');
@endphp

<div class="space-y-2">
    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">{{ $label }}</label>

    @if($previewUrl)
        <div id="{{ $fieldId }}_preview" class="relative group w-full max-w-md rounded-xl overflow-hidden border border-slate-200 bg-white shadow-inner {{ $isSvg ? 'p-4' : '' }}">
            <img src="{{ $previewUrl }}" alt="{{ $label }}" class="{{ $isSvg ? 'h-16 w-auto mx-auto' : 'w-full h-36 object-cover bg-slate-50' }}">
            <button type="button"
                    class="js-tech-media-remove absolute inset-0 bg-red-500/80 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all font-bold text-sm"
                    data-preview="{{ $fieldId }}_preview"
                    data-remove="{{ $fieldId }}_remove"
                    data-current="{{ $fieldId }}_current">SİL</button>
        </div>
    @endif

    <input type="hidden" name="{{ $currentName }}" id="{{ $fieldId }}_current" value="{{ $value }}">
    <input type="hidden" name="{{ $removeName }}" id="{{ $fieldId }}_remove" value="0">
    <input type="file"
           name="{{ $fileName }}"
           accept="{{ $accept }}"
           class="w-full bg-white border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-xs file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
    <p class="text-[10px] text-slate-400">{{ $hint }}</p>
</div>

@once
@push('scripts')
<script>
document.addEventListener('click', function (event) {
    var button = event.target.closest('.js-tech-media-remove');
    if (!button) return;

    var removeInput = document.getElementById(button.getAttribute('data-remove'));
    var currentInput = document.getElementById(button.getAttribute('data-current'));
    var preview = document.getElementById(button.getAttribute('data-preview'));

    if (removeInput) removeInput.value = '1';
    if (currentInput) currentInput.value = '';
    if (preview) preview.remove();
});
</script>
@endpush
@endonce
