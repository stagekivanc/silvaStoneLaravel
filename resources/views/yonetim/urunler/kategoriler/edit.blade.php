@extends('yonetim.layouts.admin')

@section('title', 'Ürün Kategorisi Düzenle')
@section('page_title', 'Ürün Kategorisi Düzenle: ' . $category->name)

@section('content')
@php $uploadLimits = upload_limits(); @endphp
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <a href="{{ route('yonetim.urun-kategorileri.index') }}" class="flex items-center gap-2 text-slate-400 hover:text-slate-900 transition-colors group">
            <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Listeye Dön</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm space-y-1">
            <p class="font-bold flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                Kayıt sırasında hata oluştu
            </p>
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @include('yonetim.partials.language-tabs')

    <form action="{{ route('yonetim.urun-kategorileri.update', $category->id) }}" method="POST" enctype="multipart/form-data" id="categoryEditForm" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        @method('PUT')
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-8 space-y-6">
                @foreach(\App\Models\Language::active() as $lang)
                <div data-lang-area="{{ $lang->code }}" class="{{ $lang->is_default ? '' : 'hidden' }} space-y-6">
                    @php
                        $trans = $category->translations->where('lang_key', $lang->code)->first();
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                                <i data-lucide="type" class="w-3 h-3 text-slate-300"></i>
                                Kategori Adı ({{ strtoupper($lang->code) }})
                            </label>
                            <input type="text" name="translations[{{ $lang->code }}][name]" @if($lang->is_default) required @endif
                                   class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all font-bold" 
                                   value="{{ $trans->name ?? '' }}">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                                <i data-lucide="link" class="w-3 h-3 text-slate-300"></i>
                                Slug (URL) ({{ strtoupper($lang->code) }})
                            </label>
                            <input type="text" name="translations[{{ $lang->code }}][slug]" @if($lang->is_default) required @endif
                                   class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all font-mono text-sm" 
                                   value="{{ $trans->slug ?? '' }}">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                            <i data-lucide="align-left" class="w-3 h-3 text-slate-300"></i>
                            Açıklama Metni ({{ strtoupper($lang->code) }})
                        </label>
                        <textarea name="translations[{{ $lang->code }}][description]" rows="4" 
                                  class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all leading-relaxed">{{ $trans->description ?? '' }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                            <i data-lucide="home" class="w-3 h-3 text-slate-300"></i>
                            Home Açıklama ({{ strtoupper($lang->code) }})
                        </label>
                        <textarea name="translations[{{ $lang->code }}][home_description]" rows="2" 
                                  class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all leading-relaxed"
                                  placeholder="Anasayfa kartında görünecek kısa açıklama">{{ $trans->home_description ?? '' }}</textarea>
                    </div>

                    <div class="pt-6 border-t border-slate-100 space-y-6">
                        <h4 class="text-[11px] font-bold text-slate-900 uppercase tracking-widest flex items-center gap-2">
                            <i data-lucide="search" class="w-3.5 h-3.5 text-blue-500"></i>
                            SEO Ayarları ({{ strtoupper($lang->code) }})
                        </h4>
                        <div class="grid grid-cols-1 gap-6">
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
                </div>
                @endforeach
            </div>
        </div>

        <div class="space-y-8">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6 space-y-6 sticky top-8">
                <div class="space-y-4">
                    <p class="text-[10px] text-amber-700 font-semibold bg-amber-50 border border-amber-100 rounded-lg px-3 py-2">
                        Görsel limitleri: Kategori 2MB, İkon 1MB, İkon Home 2MB. Toplam yükleme: {{ $uploadLimits['post_max_size'] }}.
                    </p>

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kategori Görseli</label>
                        @if($category->image)
                        <div id="categoryImagePreview" class="relative group mb-2 p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-center">
                            <img src="{{ $category->image_url }}" alt="" class="max-h-24 object-contain">
                            <button type="button" data-remove-field="remove_image" data-preview="categoryImagePreview" class="absolute inset-0 bg-red-500/80 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all font-bold text-sm rounded-xl">SİL</button>
                        </div>
                        <input type="hidden" name="remove_image" id="remove_image" value="0">
                        @endif
                        <input type="file" name="image" accept="image/png,image/jpeg,image/webp,image/gif" data-max-kb="2048" class="category-file-input w-full bg-slate-50 border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-sm">
                        <p class="text-[10px] text-slate-400 file-size-hint hidden"></p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">İkon (SVG/PNG)</label>
                        @if($category->icon)
                        <div id="categoryIconPreview" class="relative group mb-2 p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-center">
                            <img src="{{ $category->icon_url }}" alt="" class="max-h-24 object-contain">
                            <button type="button" data-remove-field="remove_icon" data-preview="categoryIconPreview" class="absolute inset-0 bg-red-500/80 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all font-bold text-sm rounded-xl">SİL</button>
                        </div>
                        <input type="hidden" name="remove_icon" id="remove_icon" value="0">
                        @endif
                        <input type="file" name="icon" accept="image/png,image/svg+xml,image/jpeg,image/webp" data-max-kb="1024" class="category-file-input w-full bg-slate-50 border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-sm">
                        <p class="text-[10px] text-slate-400 file-size-hint hidden"></p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">İkon Home (SVG/PNG)</label>
                        @if($category->icon_home)
                        <div id="categoryIconHomePreview" class="relative group mb-2 p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-center">
                            <img src="{{ $category->icon_home_url }}" alt="" class="max-h-24 object-contain">
                            <button type="button" data-remove-field="remove_icon_home" data-preview="categoryIconHomePreview" class="absolute inset-0 bg-red-500/80 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all font-bold text-sm rounded-xl">SİL</button>
                        </div>
                        <input type="hidden" name="remove_icon_home" id="remove_icon_home" value="0">
                        @endif
                        <input type="file" name="icon_home" accept="image/png,image/svg+xml,image/jpeg,image/webp" data-max-kb="2048" class="category-file-input w-full bg-slate-50 border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-sm">
                        <p class="text-[10px] text-slate-400 file-size-hint hidden"></p>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest text-[10px]">Durum</label>
                        <select name="status" class="bg-slate-50 border border-slate-200 text-slate-900 py-2 px-4 rounded-xl text-sm font-bold">
                            <option value="1" {{ $category->status == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ $category->status == '0' ? 'selected' : '' }}>Pasif</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest text-[10px]">Anasayfa Durum</label>
                        <select name="home_status" class="bg-slate-50 border border-slate-200 text-slate-900 py-2 px-4 rounded-xl text-sm font-bold">
                            <option value="1" {{ $category->home_status == '1' ? 'selected' : '' }}>Görünsün</option>
                            <option value="0" {{ $category->home_status == '0' ? 'selected' : '' }}>Görünmesin</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest text-[10px]">Sıralama</label>
                        <input type="number" name="order" value="{{ $category->order }}" class="w-24 bg-slate-50 border border-slate-200 text-slate-900 py-2 px-4 rounded-xl text-sm text-center font-bold">
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
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('categoryEditForm');
    const postMaxBytes = {{ (int) $uploadLimits['post_max_bytes'] }};

    function formatMb(bytes) {
        return (bytes / (1024 * 1024)).toFixed(2);
    }

    document.querySelectorAll('.category-file-input').forEach(function (input) {
        input.addEventListener('change', function () {
            const hint = input.parentElement.querySelector('.file-size-hint');
            const maxKb = parseInt(input.dataset.maxKb || '2048', 10);
            const file = input.files[0];

            if (!file) {
                hint.classList.add('hidden');
                hint.textContent = '';
                return;
            }

            const sizeMb = formatMb(file.size);
            const tooLarge = file.size > maxKb * 1024;
            hint.textContent = `Seçilen: ${file.name} (${sizeMb} MB)`;
            hint.classList.remove('hidden');
            hint.classList.toggle('text-red-600', tooLarge);
            hint.classList.toggle('font-semibold', tooLarge);
            hint.classList.toggle('text-slate-400', !tooLarge);
        });
    });

    document.querySelectorAll('[data-remove-field]').forEach(function (button) {
        button.addEventListener('click', function () {
            const fieldId = button.dataset.removeField;
            const previewId = button.dataset.preview;
            const removeInput = document.getElementById(fieldId);
            const preview = document.getElementById(previewId);

            if (removeInput) {
                removeInput.value = '1';
            }
            if (preview) {
                preview.remove();
            }
        });
    });

    form.addEventListener('submit', function (event) {
        let totalBytes = 0;
        let hasOversizedFile = false;

        document.querySelectorAll('.category-file-input').forEach(function (input) {
            const maxKb = parseInt(input.dataset.maxKb || '2048', 10);
            const file = input.files[0];
            if (!file) return;

            totalBytes += file.size;
            if (file.size > maxKb * 1024) {
                hasOversizedFile = true;
            }
        });

        if (hasOversizedFile) {
            event.preventDefault();
            alert('Seçilen dosyalardan biri izin verilen boyutu aşıyor. Lütfen daha küçük dosyalar seçin.');
            return;
        }

        if (totalBytes > postMaxBytes) {
            event.preventDefault();
            alert(`Seçilen dosyaların toplam boyutu çok büyük (${formatMb(totalBytes)} MB). Sunucu limiti: {{ $uploadLimits['post_max_size'] }}. Dosyaları tek tek yüklemeyi deneyin.`);
        }
    });
});
</script>
@endpush
@endsection
