@extends('yonetim.layouts.admin')

@section('title', 'Ürün Düzenle')
@section('page_title', 'Ürün Düzenle: ' . $product->name)

@section('content')
@php
    $hasSku = \Illuminate\Support\Facades\Schema::hasColumn('products', 'sku');
    $hasBadge = \Illuminate\Support\Facades\Schema::hasColumn('products', 'badge');
    $hasHome = \Illuminate\Support\Facades\Schema::hasColumn('products', 'home_status');
    $nestedCategories = $categories->whereNull('parent_id')->sortBy('order')->values();
    if ($nestedCategories->isEmpty()) {
        $nestedCategories = $categories->sortBy('order')->values();
    }
@endphp
<div class="max-w-7xl mx-auto" x-data="productManager()">
    <div class="flex items-center justify-between mb-8">
        <a href="{{ route('yonetim.urunler.index') }}" class="flex items-center gap-2 text-slate-400 hover:text-slate-900 transition-colors group">
            <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Listeye Dön</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-8 p-6 bg-red-50 border border-red-100 rounded-2xl">
            <div class="flex items-center gap-3 text-red-600 mb-4">
                <i data-lucide="alert-circle" class="w-5 h-5"></i>
                <span class="text-sm font-bold uppercase tracking-widest">Bir Hata Oluştu</span>
            </div>
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-xs text-red-500 font-medium">• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @include('yonetim.partials.language-tabs')

    <form action="{{ route('yonetim.urunler.update', $product->id) }}" method="POST" enctype="multipart/form-data" novalidate class="grid grid-cols-1 lg:grid-cols-3 gap-8" onsubmit="prepareProductFormSubmit()">
        @csrf
        @method('PUT')

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-1 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-1 overflow-x-auto no-scrollbar">
                <button type="button" @click="tab = 'general'" :class="tab === 'general' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-500 hover:bg-slate-50'" class="flex-1 py-3 px-4 rounded-xl text-[11px] font-bold uppercase tracking-wider transition-all flex items-center justify-center gap-2 min-w-[120px]">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    Genel
                </button>
                <button type="button" @click="tab = 'gallery'" :class="tab === 'gallery' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-500 hover:bg-slate-50'" class="flex-1 py-3 px-4 rounded-xl text-[11px] font-bold uppercase tracking-wider transition-all flex items-center justify-center gap-2 min-w-[120px]">
                    <i data-lucide="image" class="w-4 h-4"></i>
                    Galeri
                </button>
                <button type="button" @click="tab = 'documents'" :class="tab === 'documents' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-500 hover:bg-slate-50'" class="flex-1 py-3 px-4 rounded-xl text-[11px] font-bold uppercase tracking-wider transition-all flex items-center justify-center gap-2 min-w-[120px]">
                    <i data-lucide="file" class="w-4 h-4"></i>
                    Belgeler
                </button>
            </div>

            <div x-show="tab === 'general'" data-product-tab-panel class="space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-8 space-y-6">
                    @foreach(\App\Models\Language::active() as $lang)
                    <div data-lang-area="{{ $lang->code }}" class="{{ $lang->is_default ? '' : 'hidden' }} space-y-6">
                        @php $trans = $product->translations->where('lang_key', $lang->code)->first(); @endphp
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Ürün Adı ({{ strtoupper($lang->code) }})</label>
                                <input type="text" name="translations[{{ $lang->code }}][name]" required
                                       class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all font-bold"
                                       value="{{ $trans->name ?? '' }}">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Slug (URL)</label>
                                <input type="text" name="translations[{{ $lang->code }}][slug]" required
                                       class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all font-mono text-sm"
                                       value="{{ $trans->slug ?? '' }}">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Alt Başlık</label>
                            <input type="text" name="translations[{{ $lang->code }}][title]"
                                   class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all"
                                   value="{{ $trans->title ?? '' }}">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kısa Açıklama</label>
                            <textarea name="translations[{{ $lang->code }}][short_description]" rows="3"
                                      class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all leading-relaxed">{{ $trans->short_description ?? '' }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Detaylı Açıklama</label>
                            <textarea name="translations[{{ $lang->code }}][description]" rows="8"
                                      class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all leading-relaxed">{{ $trans->description ?? '' }}</textarea>
                        </div>

                        @include('yonetim.urunler.partials.feature-fields', ['lang' => $lang, 'product' => $product])

                        <div class="pt-6 border-t border-slate-100 space-y-6">
                            <h4 class="text-[11px] font-bold text-slate-900 uppercase tracking-widest flex items-center gap-2">
                                <i data-lucide="search" class="w-3.5 h-3.5 text-blue-500"></i>
                                SEO ({{ strtoupper($lang->code) }})
                            </h4>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Meta Başlığı</label>
                                <input type="text" name="translations[{{ $lang->code }}][seo_title]" value="{{ $trans->seo_title ?? '' }}"
                                       class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-2.5 px-4 rounded-xl text-sm focus:border-blue-500 transition-all font-bold">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Meta Açıklaması</label>
                                <textarea name="translations[{{ $lang->code }}][seo_description]" rows="3"
                                          class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-2.5 px-4 rounded-xl text-sm focus:border-blue-500 transition-all leading-relaxed">{{ $trans->seo_description ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div x-show="tab === 'gallery'" data-product-tab-panel class="space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-8 space-y-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if($product->gallery)
                            @foreach($product->gallery as $img)
                                <div class="relative group aspect-[4/5] rounded-xl overflow-hidden border border-slate-200 bg-slate-50">
                                    <img src="{{ homepage_media_url($img) }}" class="w-full h-full object-cover" alt="">
                                    <input type="hidden" name="old_gallery[]" value="{{ $img }}">
                                    <button type="button" onclick="this.parentElement.remove()" class="absolute inset-0 bg-red-500/80 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all font-bold text-xs">SİL</button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Yeni Görseller Ekle</label>
                        <input type="file" name="gallery[]" multiple accept="image/jpeg,image/png,image/gif,image/webp" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-sm">
                        <p class="text-[10px] text-slate-400 ml-1">Ürün detay galerisi · scrub görselleri</p>
                    </div>
                </div>
            </div>

            <div x-show="tab === 'documents'" data-product-tab-panel class="space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-8">
                    @include('yonetim.urunler.partials.document-fields', ['product' => $product])
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6 space-y-6 sticky top-8">
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kategori</label>
                        <select name="category_id" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all font-bold">
                            @foreach($nestedCategories as $parent)
                                @if($parent->relationLoaded('children') || method_exists($parent, 'children'))
                                    @php $children = $parent->children ?? collect(); @endphp
                                    @if($children->isNotEmpty())
                                        <optgroup label="{{ $parent->name }}">
                                            <option value="{{ $parent->id }}" {{ (int) $product->category_id === (int) $parent->id ? 'selected' : '' }}>{{ $parent->name }} (Tümü)</option>
                                            @foreach($children as $child)
                                                <option value="{{ $child->id }}" {{ (int) $product->category_id === (int) $child->id ? 'selected' : '' }}>— {{ $child->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @else
                                        <option value="{{ $parent->id }}" {{ (int) $product->category_id === (int) $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                                    @endif
                                @else
                                    <option value="{{ $parent->id }}" {{ (int) $product->category_id === (int) $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    @if($hasSku)
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">SKU / Stok Kodu</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->getAttributes()['sku'] ?? '') }}"
                               class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-sm font-mono focus:border-blue-500 transition-all"
                               placeholder="TRK-MNT-001">
                    </div>
                    @endif

                    @if($hasBadge)
                    @php
                        $badge = old('badge', $product->getAttributes()['badge'] ?? '');
                        $badgeOptions = \App\Models\ProductBadge::optionsMap(null, false);
                    @endphp
                    <div class="space-y-2">
                        <div class="flex items-center justify-between gap-3">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Rozet</label>
                            <a href="{{ route('yonetim.urun-rozetleri.index') }}" class="text-[11px] text-blue-600 font-medium hover:underline">Rozetleri yönet →</a>
                        </div>
                        <select name="badge" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-sm font-bold focus:border-blue-500 transition-all">
                            <option value="" {{ $badge === '' || $badge === null ? 'selected' : '' }}>Yok</option>
                            @foreach($badgeOptions as $slug => $label)
                                <option value="{{ $slug }}" {{ $badge === $slug ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    @include('yonetim.urunler.partials.silva-fields', ['product' => $product])

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Ana Görsel</label>
                        @if($product->main_image)
                        <div id="productMainImagePreview" class="relative group w-full mb-2">
                            <img src="{{ homepage_media_url($product->main_image) }}" class="w-full h-40 object-cover rounded-xl border border-slate-100" alt="">
                            <button type="button" id="productRemoveMainImage" class="absolute inset-0 bg-red-500/80 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all font-bold text-sm rounded-xl">SİL</button>
                        </div>
                        <input type="hidden" name="remove_main_image" id="remove_main_image" value="0">
                        @endif
                        <input type="file" name="main_image" accept="image/jpeg,image/png,image/gif,image/webp" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-sm">
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Durum</label>
                        <select name="status" class="bg-slate-50 border border-slate-200 text-slate-900 py-2 px-4 rounded-xl text-sm font-bold">
                            <option value="1" {{ $product->status ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !$product->status ? 'selected' : '' }}>Pasif</option>
                        </select>
                    </div>

                    @if($hasHome)
                    <div class="flex items-center justify-between pt-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Anasayfada Göster</label>
                        <select name="home_status" class="bg-slate-50 border border-slate-200 text-slate-900 py-2 px-4 rounded-xl text-sm font-bold">
                            <option value="1" {{ $product->home_status ? 'selected' : '' }}>Evet</option>
                            <option value="0" {{ !$product->home_status ? 'selected' : '' }}>Hayır</option>
                        </select>
                    </div>
                    @endif

                    <div class="flex items-center justify-between pt-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Sıralama</label>
                        <input type="number" name="order" value="{{ $product->order }}" class="w-24 bg-slate-50 border border-slate-200 text-slate-900 py-2 px-4 rounded-xl text-sm text-center font-bold">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-blue-600 text-white font-extrabold rounded-xl shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all flex items-center justify-center gap-3">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        Güncelle
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function productManager() {
    return { tab: 'general' };
}
function featureListEditor(initial) {
    return {
        rows: Array.isArray(initial) && initial.length ? initial : [''],
        addRow() { this.rows.push(''); this.$nextTick(() => window.lucide && window.lucide.createIcons()); },
        removeRow(index) {
            this.rows.splice(index, 1);
            if (!this.rows.length) this.rows = [''];
        }
    };
}
document.addEventListener('DOMContentLoaded', function () {
    var removeMainImageBtn = document.getElementById('productRemoveMainImage');
    var removeMainImageInput = document.getElementById('remove_main_image');
    var mainImagePreview = document.getElementById('productMainImagePreview');
    if (removeMainImageBtn && removeMainImageInput) {
        removeMainImageBtn.addEventListener('click', function () {
            removeMainImageInput.value = '1';
            if (mainImagePreview) mainImagePreview.remove();
        });
    }
});
</script>
@endpush
@endsection
