@php
    $galleryItems = is_array($val) ? array_values(array_filter($val)) : [];
    $namePath = implode('][', $keys);
@endphp

<div class="space-y-4">
    @if(count($galleryItems) > 0)
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        @foreach($galleryItems as $galleryImage)
        <div class="relative group rounded-xl overflow-hidden border border-slate-200 bg-slate-100">
            <img src="{{ asset('uploads/' . ltrim($galleryImage, '/')) }}" class="w-full h-28 object-cover" alt="{{ $fieldLabel }}">
            <label class="absolute inset-0 bg-red-500/80 text-white flex flex-col items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-all font-bold text-xs cursor-pointer">
                <input type="checkbox" name="remove_extra_gallery[{{ $langCode }}][{{ $namePath }}][]" value="{{ $galleryImage }}" class="rounded border-white">
                SİL
            </label>
            <input type="hidden" name="old_extra_gallery[{{ $langCode }}][{{ $namePath }}][]" value="{{ $galleryImage }}">
        </div>
        @endforeach
    </div>
    @endif

    <input type="file"
           name="extra_gallery_files[{{ $langCode }}][{{ $namePath }}][]"
           accept="image/png,image/jpeg,image/webp,image/gif"
           multiple
           class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
    <p class="text-[10px] text-slate-400">Birden fazla görsel seçebilirsiniz. PNG, JPG veya WEBP — her biri maks. 2MB</p>
</div>
