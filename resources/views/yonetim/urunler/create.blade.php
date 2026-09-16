@extends('yonetim.layouts.admin')

@section('title', 'Yeni Ürün Ekle')
@section('page_title', 'Yeni Ürün Ekle')

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

    <form action="{{ route('yonetim.urunler.store') }}" method="POST" enctype="multipart/form-data" novalidate class="grid grid-cols-1 lg:grid-cols-3 gap-8" onsubmit="prepareProductFormSubmit()">
        @csrf

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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Ürün Adı ({{ strtoupper($lang->code) }})</label>
                                <input type="text" name="translations[{{ $lang->code }}][name]" required
                                       class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all font-bold"
                                       placeholder="Örn: Premium Reflektörlü İş Montu">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Slug (URL)</label>
                                <input type="text" name="translations[{{ $lang->code }}][slug]" required
                                       class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all font-mono text-sm"
                                       placeholder="premium-reflektorlu-is-montu">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Alt Başlık</label>
                            <input type="text" name="translations[{{ $lang->code }}][title]"
                                   class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kısa Açıklama</label>
                            <textarea name="translations[{{ $lang->code }}][short_description]" rows="3"
                                      class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all leading-relaxed"></textarea>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Detaylı Açıklama</label>
                            <textarea name="translations[{{ $lang->code }}][description]" rows="8"
                                      class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all leading-relaxed"></textarea>
                        </div>

                        @include('yonetim.urunler.partials.feature-fields', ['lang' => $lang])

                        <div class="pt-6 border-t border-slate-100 space-y-6">
                            <h4 class="text-[11px] font-bold text-slate-900 uppercase tracking-widest flex items-center gap-2">
                                <i data-lucide="search" class="w-3.5 h-3.5 text-blue-500"></i>
                                SEO ({{ strtoupper($lang->code) }})
                            </h4>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Meta Başlığı</label>
                                <input type="text" name="translations[{{ $lang->code }}][seo_title]"
                                       class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-2.5 px-4 rounded-xl text-sm focus:border-blue-500 transition-all font-bold">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Meta Açıklaması</label>
                                <textarea name="translations[{{ $lang->code }}][seo_description]" rows="3"
                                          class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-2.5 px-4 rounded-xl text-sm focus:border-blue-500 transition-all leading-relaxed"></textarea>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div x-show="tab === 'gallery'" data-product-tab-panel class="space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-8 space-y-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Galeri Görselleri</label>
                        <input type="file" name="gallery[]" multiple accept="image/jpeg,image/png,image/gif,image/webp" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-sm">
                        <p class="text-[10px] text-slate-400 ml-1">Ürün detay galerisi · scrub görselleri</p>
                    </div>
                </div>
            </div>

            <div x-show="tab === 'documents'" data-product-tab-panel class="space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-8">
                    @include('yonetim.urunler.partials.document-fields')
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
                                @php $children = $parent->children ?? collect(); @endphp
                                @if($children->isNotEmpty())
                                    <optgroup label="{{ $parent->name }}">
                                        <option value="{{ $parent->id }}">{{ $parent->name }} (Tümü)</option>
                                        @foreach($children as $child)
                                            <option value="{{ $child->id }}">— {{ $child->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @else
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    @if($hasSku)
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">SKU / Stok Kodu</label>
                        <input type="text" name="sku" value="{{ old('sku') }}"
                               class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-sm font-mono focus:border-blue-500 transition-all"
                               placeholder="TRK-MNT-001">
                    </div>
                    @endif

                    @if($hasBadge)
                    @php $badgeOptions = \App\Models\ProductBadge::optionsMap(null, false); @endphp
                    <div class="space-y-2">
                        <div class="flex items-center justify-between gap-3">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Rozet</label>
                            <a href="{{ route('yonetim.urun-rozetleri.index') }}" class="text-[11px] text-blue-600 font-medium hover:underline">Rozetleri yönet →</a>
                        </div>
                        <select name="badge" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-sm font-bold focus:border-blue-500 transition-all">
                            <option value="">Yok</option>
                            @foreach($badgeOptions as $slug => $label)
                                <option value="{{ $slug }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    @include('yonetim.urunler.partials.silva-fields')

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Ana Görsel</label>
                        <input type="file" name="main_image" accept="image/jpeg,image/png,image/gif,image/webp" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-sm">
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Durum</label>
                        <select name="status" class="bg-slate-50 border border-slate-200 text-slate-900 py-2 px-4 rounded-xl text-sm font-bold">
                            <option value="1">Aktif</option>
                            <option value="0">Pasif</option>
                        </select>
                    </div>

                    @if($hasHome)
                    <div class="flex items-center justify-between pt-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Anasayfada Göster</label>
                        <select name="home_status" class="bg-slate-50 border border-slate-200 text-slate-900 py-2 px-4 rounded-xl text-sm font-bold">
                            <option value="1">Evet</option>
                            <option value="0" selected>Hayır</option>
                        </select>
                    </div>
                    @endif

                    <div class="flex items-center justify-between pt-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Sıralama</label>
                        <input type="number" name="order" value="0" class="w-24 bg-slate-50 border border-slate-200 text-slate-900 py-2 px-4 rounded-xl text-sm text-center font-bold">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-blue-600 text-white font-extrabold rounded-xl shadow-lg shadow-blue-600/20 hover:bg-blue-700 transition-all flex items-center justify-center gap-3">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        Kaydet
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
</script>
@endpush
@endsection
