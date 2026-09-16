@php
  $product = $product ?? null;
  $colors = $colors ?? \App\Models\ProductColor::optionsMap(null, false);
@endphp
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-8 space-y-5">
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Silva Stone</h3>
        <a href="{{ route('yonetim.urun-renkleri.index') }}" class="text-[11px] text-blue-600 font-medium hover:underline">Renkleri yönet →</a>
    </div>

    <div class="space-y-2">
        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Renk</label>
        <select name="color" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl">
            <option value="">—</option>
            @foreach($colors as $key => $label)
                <option value="{{ $key }}" @selected(old('color', $product->color ?? '') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div class="space-y-2">
            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Ebat</label>
            <input type="text" name="panel_size" value="{{ old('panel_size', $product->panel_size ?? '600x1200') }}"
                   class="w-full bg-slate-50 border border-slate-200 py-3.5 px-5 rounded-xl" placeholder="600x1200">
        </div>
        <div class="space-y-2">
            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kalınlık</label>
            <input type="text" name="thick" value="{{ old('thick', $product->thick ?? '') }}"
                   class="w-full bg-slate-50 border border-slate-200 py-3.5 px-5 rounded-xl" placeholder="3-4">
        </div>
    </div>

    <div class="space-y-2">
        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Özel Sipariş Ölçü</label>
        <input type="text" name="size_extra" value="{{ old('size_extra', $product->size_extra ?? '') }}"
               class="w-full bg-slate-50 border border-slate-200 py-3.5 px-5 rounded-xl" placeholder="1200x2400 / 1200x3000">
    </div>

    <div class="space-y-2">
        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kaynak URL</label>
        <input type="text" name="source_url" value="{{ old('source_url', $product->source_url ?? '') }}"
               class="w-full bg-slate-50 border border-slate-200 py-3.5 px-5 rounded-xl text-sm">
    </div>

    <div class="space-y-2">
        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Hover Görseli</label>
        <input type="file" name="hover_image" accept="image/*" class="w-full bg-slate-50 border border-slate-200 py-3 px-4 rounded-xl">
    </div>

    <div class="flex flex-col gap-3 pt-1">
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="indoor" value="1" class="w-4 h-4" @checked(old('indoor', $product->indoor ?? true))>
            <span class="text-sm font-medium text-slate-700">{{ \App\Models\ProductFeature::labelFor('indoor', null, 'İç mekân') }}</span>
        </label>
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="outdoor" value="1" class="w-4 h-4" @checked(old('outdoor', $product->outdoor ?? true))>
            <span class="text-sm font-medium text-slate-700">{{ \App\Models\ProductFeature::labelFor('outdoor', null, 'Dış mekân') }}</span>
        </label>
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="checkbox" name="depot" value="1" class="w-4 h-4" @checked(old('depot', $product->depot ?? true))>
            <span class="text-sm font-medium text-slate-700">{{ \App\Models\ProductFeature::labelFor('depot', null, 'Stokta') }}</span>
        </label>
        <a href="{{ route('yonetim.urun-ozellikleri.index') }}" class="text-[11px] text-blue-600 font-medium hover:underline pt-1">Özellik etiketlerini yönet →</a>
    </div>
</div>
