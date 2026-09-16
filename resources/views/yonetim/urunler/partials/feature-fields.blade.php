@php
    $trans = isset($product) ? $product->translations->where('lang_key', $lang->code)->first() : null;
    $rawFeatures = $trans->features ?? [];
    $featureLines = [];

    if (is_array($rawFeatures)) {
        if (isset($rawFeatures['items']) && is_array($rawFeatures['items'])) {
            foreach ($rawFeatures['items'] as $item) {
                $text = is_array($item)
                    ? trim((string) ($item['title'] ?? $item['text'] ?? ''))
                    : trim((string) $item);
                if ($text !== '') {
                    $featureLines[] = $text;
                }
            }
        } else {
            foreach ($rawFeatures as $item) {
                if (is_string($item) && trim($item) !== '') {
                    $featureLines[] = trim($item);
                } elseif (is_array($item)) {
                    $text = trim((string) ($item['title'] ?? $item['text'] ?? $item['value'] ?? ''));
                    if ($text !== '') {
                        $featureLines[] = $text;
                    }
                }
            }
        }
    }

    if ($featureLines === []) {
        $featureLines = [''];
    }
@endphp

<div class="space-y-4" x-data="featureListEditor({{ Js::from(array_values($featureLines)) }})">
    <div class="pb-4 border-b border-slate-100 flex items-start justify-between gap-4">
        <div>
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <i data-lucide="sparkles" class="w-4 h-4 text-blue-500"></i>
                Ürün Özellik Maddeleri ({{ strtoupper($lang->code) }})
            </h3>
            <p class="text-xs text-slate-400 mt-1">Detay sayfasında madde madde gösterilir. Beden / renk / kumaş varyantları ayrı sekmeden seçilir.</p>
        </div>
        <button type="button" @click="addRow()" class="inline-flex items-center gap-1.5 px-3 py-2 bg-slate-900 text-white rounded-xl text-[11px] font-bold hover:bg-slate-800 transition-colors">
            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
            Madde Ekle
        </button>
    </div>

    <div class="space-y-3">
        <template x-for="(row, index) in rows" :key="index">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-400 text-[11px] font-bold flex items-center justify-center shrink-0" x-text="index + 1"></span>
                <input type="text"
                       :name="'translations[{{ $lang->code }}][features][' + index + ']'"
                       x-model="rows[index]"
                       placeholder="Örn: Fermuarlı yan cepler"
                       class="flex-1 bg-slate-50 border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-sm focus:border-blue-500 transition-all">
                <button type="button" @click="removeRow(index)" class="w-10 h-10 rounded-xl border border-slate-200 text-slate-400 hover:text-red-500 hover:border-red-300 flex items-center justify-center shrink-0" title="Sil">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </div>
        </template>
    </div>
</div>
