@php
    $savedDocuments = [];
    if (isset($product)) {
        $rawDocuments = $product->getAttributes()['documents'] ?? null;
        if (is_string($rawDocuments)) {
            $rawDocuments = json_decode($rawDocuments, true);
        }
        $savedDocuments = is_array($rawDocuments) ? array_values($rawDocuments) : [];
    }
@endphp

<input type="hidden" name="documents_present" value="1">

<div class="space-y-6" id="productDocumentsManager">
    <div class="pb-4 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4 text-blue-500"></i>
                Ürün Dökümanları
            </h3>
            <p class="text-xs text-slate-400 mt-1">Teknik çizim, katalog veya 3D (GLB/GLTF) dosyalarını buradan ekleyin. 3D dosyası ürün görselinin yerinde döner.</p>
        </div>
        <button type="button" onclick="addProductDocumentRow()"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-50 text-blue-600 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-blue-100 transition-all">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Döküman Ekle
        </button>
    </div>

    <div class="space-y-4" id="productDocumentsList">
        @foreach($savedDocuments as $index => $doc)
            <div class="product-document-row p-6 bg-slate-50 border border-slate-200 rounded-2xl space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Döküman {{ $index + 1 }}</span>
                    <button type="button" onclick="removeProductDocumentRow(this)"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-red-500 hover:bg-red-50 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-all">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        Sil
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Başlık</label>
                        <input type="text"
                               name="documents[{{ $index }}][title]"
                               value="{{ $doc['title'] ?? '' }}"
                               placeholder="Örn: ISO 9001"
                               class="w-full bg-white border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-sm focus:border-blue-500 transition-all font-bold">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Alt Başlık</label>
                        <input type="text"
                               name="documents[{{ $index }}][subtitle]"
                               value="{{ $doc['subtitle'] ?? '' }}"
                               placeholder="Örn: Kalite Yönetim Sistemi"
                               class="w-full bg-white border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-sm focus:border-blue-500 transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Dosya</label>
                    @if(!empty($doc['file']))
                        <input type="hidden" name="documents[{{ $index }}][file]" value="{{ $doc['file'] }}">
                        <input type="hidden" name="documents[{{ $index }}][file_ext]" value="{{ $doc['file_ext'] ?? '' }}">
                        <div class="flex items-center gap-3 p-3 bg-white border border-slate-200 rounded-xl mb-2">
                            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-[10px] font-bold uppercase">
                                {{ strtoupper($doc['file_ext'] ?? 'PDF') }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="text-xs font-bold text-slate-700 truncate">{{ basename((string) ($doc['file'] ?? 'Dosya')) }}</div>
                                <div class="text-[10px] text-slate-400">Mevcut dosya — yeni yüklerseniz değiştirilir</div>
                            </div>
                        </div>
                    @endif
                    <input type="file"
                           name="document_files[{{ $index }}]"
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.glb,.gltf,.usdz,.png,.jpg,.jpeg,.webp"
                           class="w-full bg-white border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-sm">
                    <p class="text-[10px] text-slate-400 ml-1">PDF, görsel, 3D (GLB/GLTF/USDZ), Word, Excel, ZIP (max 20MB)</p>
                </div>
            </div>
        @endforeach
    </div>

    @if(count($savedDocuments) === 0)
        <div class="p-8 border-2 border-dashed border-slate-200 rounded-2xl text-center" id="productDocumentsEmpty">
            <p class="text-sm text-slate-400">Henüz döküman eklenmedi.</p>
        </div>
    @endif
</div>

<template id="productDocumentRowTemplate">
    <div class="product-document-row p-6 bg-slate-50 border border-slate-200 rounded-2xl space-y-4">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest product-document-label">Döküman</span>
            <button type="button" onclick="removeProductDocumentRow(this)"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-red-500 hover:bg-red-50 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-all">
                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                Sil
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2">
                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Başlık</label>
                <input type="text"
                       data-field="title"
                       placeholder="Örn: ISO 9001"
                       class="w-full bg-white border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-sm focus:border-blue-500 transition-all font-bold">
            </div>
            <div class="space-y-2">
                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Alt Başlık</label>
                <input type="text"
                       data-field="subtitle"
                       placeholder="Örn: Kalite Yönetim Sistemi"
                       class="w-full bg-white border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-sm focus:border-blue-500 transition-all">
            </div>
        </div>

        <div class="space-y-2">
            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Dosya</label>
            <input type="file"
                   data-field="file"
                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.glb,.gltf,.usdz,.png,.jpg,.jpeg,.webp"
                   class="w-full bg-white border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-sm">
            <p class="text-[10px] text-slate-400 ml-1">PDF, görsel, 3D (GLB/GLTF/USDZ), Word, Excel, ZIP (max 20MB)</p>
        </div>
    </div>
</template>

@once
@push('scripts')
<script>
function reindexProductDocumentRows() {
    var rows = document.querySelectorAll('#productDocumentsList .product-document-row');
    rows.forEach(function (row, index) {
        var label = row.querySelector('.product-document-label');
        if (label) {
            label.textContent = 'Döküman ' + (index + 1);
        }

        var titleInput = row.querySelector('[data-field="title"]') || row.querySelector('input[name*="[title]"]');
        var subtitleInput = row.querySelector('[data-field="subtitle"]') || row.querySelector('input[name*="[subtitle]"]');
        var fileInput = row.querySelector('[data-field="file"]') || row.querySelector('input[type="file"]');
        var fileHidden = row.querySelector('input[name*="[file]"][type="hidden"]');
        var extHidden = row.querySelector('input[name*="[file_ext]"][type="hidden"]');

        if (titleInput) titleInput.name = 'documents[' + index + '][title]';
        if (subtitleInput) subtitleInput.name = 'documents[' + index + '][subtitle]';
        if (fileInput) fileInput.name = 'document_files[' + index + ']';
        if (fileHidden) fileHidden.name = 'documents[' + index + '][file]';
        if (extHidden) extHidden.name = 'documents[' + index + '][file_ext]';
    });

    var emptyState = document.getElementById('productDocumentsEmpty');
    if (emptyState) {
        emptyState.style.display = rows.length > 0 ? 'none' : 'block';
    }
}

function addProductDocumentRow() {
    var template = document.getElementById('productDocumentRowTemplate');
    var list = document.getElementById('productDocumentsList');
    if (!template || !list) return;

    list.appendChild(template.content.cloneNode(true));
    reindexProductDocumentRows();

    if (window.lucide) {
        window.lucide.createIcons();
    }
}

function removeProductDocumentRow(button) {
    var row = button.closest('.product-document-row');
    if (!row) return;
    row.remove();
    reindexProductDocumentRows();
}

function prepareProductFormSubmit() {
    document.querySelectorAll('[data-product-tab-panel]').forEach(function (panel) {
        panel.style.display = 'block';
        panel.style.visibility = 'visible';
        panel.style.position = 'static';
        panel.style.height = 'auto';
        panel.style.overflow = 'visible';
        panel.style.opacity = '1';
    });
    reindexProductDocumentRows();
}
</script>
@endpush
@endonce
