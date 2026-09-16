@php
  $isEdit = isset($project);
  $project = $project ?? null;
  $translations = $isEdit ? $project->translations->keyBy('lang_key') : collect();
@endphp

@extends('yonetim.layouts.admin')

@section('title', $isEdit ? 'Proje Düzenle' : 'Yeni Proje Ekle')
@section('page_title', $isEdit ? 'Proje Düzenle' : 'Yeni Proje Ekle')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <a href="{{ route('yonetim.projeler.index') }}" class="flex items-center gap-2 text-slate-400 hover:text-slate-900 transition-colors group">
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

    <form action="{{ $isEdit ? route('yonetim.projeler.update', $project) : route('yonetim.projeler.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-8 space-y-6">
                @foreach(\App\Models\Language::active() as $lang)
                    @php
                        $tr = $translations->get($lang->code);
                        $featsText = old("translations.{$lang->code}.feats_text", implode("\n", (array) ($tr->feats ?? [])));
                    @endphp
                    <div data-lang-area="{{ $lang->code }}" class="{{ $lang->is_default ? '' : 'hidden' }} space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Başlık ({{ strtoupper($lang->code) }})</label>
                                <input type="text" name="translations[{{ $lang->code }}][title]"
                                       value="{{ old("translations.{$lang->code}.title", $tr->title ?? '') }}"
                                       class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all font-bold"
                                       placeholder="Örn: Lobi feature wall" {{ $lang->is_default ? 'required' : '' }}>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Slug (URL)</label>
                                <input type="text" name="translations[{{ $lang->code }}][slug]"
                                       value="{{ old("translations.{$lang->code}.slug", $tr->slug ?? '') }}"
                                       class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all font-mono text-sm"
                                       placeholder="lobi-feature-wall">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Özet (Lead)</label>
                            <textarea name="translations[{{ $lang->code }}][lead]" rows="3"
                                      class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all leading-relaxed">{{ old("translations.{$lang->code}.lead", $tr->lead ?? '') }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Detay Metni</label>
                            <textarea name="translations[{{ $lang->code }}][body]" rows="8"
                                      class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all leading-relaxed">{{ old("translations.{$lang->code}.body", $tr->body ?? '') }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Uygulama Notları (her satır bir madde)</label>
                            <textarea name="translations[{{ $lang->code }}][feats_text]" rows="4"
                                      class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all leading-relaxed"
                                      placeholder="Feature wall odak&#10;Kayrak doku">{{ $featsText }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">SEO Başlık</label>
                                <input type="text" name="translations[{{ $lang->code }}][seo_title]"
                                       value="{{ old("translations.{$lang->code}.seo_title", $tr->seo_title ?? '') }}"
                                       class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">SEO Açıklama</label>
                                <input type="text" name="translations[{{ $lang->code }}][seo_description]"
                                       value="{{ old("translations.{$lang->code}.seo_description", $tr->seo_description ?? '') }}"
                                       class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-8 space-y-6">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Galeri</h3>
                @if($isEdit && !empty($project->gallery))
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach((array) $project->gallery as $i => $item)
                            @php $path = is_array($item) ? ($item['path'] ?? '') : $item; @endphp
                            <label class="relative block rounded-xl overflow-hidden border border-slate-200 bg-slate-50 aspect-[4/3] cursor-pointer group">
                                <img src="{{ \App\Models\Project::mediaUrl($path) }}" alt="" class="w-full h-full object-cover">
                                <span class="absolute inset-x-0 bottom-0 bg-black/60 text-white text-[10px] font-bold uppercase tracking-wider py-2 text-center opacity-0 group-hover:opacity-100 transition">Kaldır</span>
                                <input type="checkbox" name="remove_gallery[]" value="{{ $i }}" class="absolute top-2 right-2 w-4 h-4">
                            </label>
                        @endforeach
                    </div>
                @endif
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Yeni Galeri Görselleri</label>
                    <input type="file" name="gallery[]" multiple accept="image/*"
                           class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3 px-4 rounded-xl">
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-8 space-y-6">
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Tip</label>
                    <select name="type" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl">
                        @forelse(($types ?? []) as $key => $label)
                            <option value="{{ $key }}" @selected(old('type', $project->type ?? '') === $key)>{{ $label }}</option>
                        @empty
                            <option value="">Önce tip ekleyin</option>
                        @endforelse
                    </select>
                    <a href="{{ route('yonetim.proje-tipleri.index') }}" class="text-[11px] text-blue-600 font-medium hover:underline">Tipleri yönet →</a>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Mekân</label>
                    <select name="place" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl">
                        @forelse(($places ?? []) as $key => $label)
                            <option value="{{ $key }}" @selected(old('place', $project->place ?? '') === $key)>{{ $label }}</option>
                        @empty
                            <option value="">Önce mekân ekleyin</option>
                        @endforelse
                    </select>
                    <a href="{{ route('yonetim.proje-mekanlari.index') }}" class="text-[11px] text-blue-600 font-medium hover:underline">Mekânları yönet →</a>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Şehir</label>
                    <select name="city" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl">
                        @forelse(($cities ?? []) as $key => $label)
                            <option value="{{ $key }}" @selected(old('city', $project->city ?? '') === $key)>{{ $label }}</option>
                        @empty
                            <option value="">Önce şehir ekleyin</option>
                        @endforelse
                    </select>
                    <a href="{{ route('yonetim.proje-sehirleri.index') }}" class="text-[11px] text-blue-600 font-medium hover:underline">Şehirleri yönet →</a>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kullanılan Yüzey</label>
                    <input type="text" name="product_name" value="{{ old('product_name', $project->product_name ?? '') }}"
                           class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl" placeholder="Slate Antrasit">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Yıl</label>
                        <input type="text" name="year" value="{{ old('year', $project->year ?? '') }}"
                               class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl" placeholder="2025">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Alan</label>
                        <input type="text" name="area" value="{{ old('area', $project->area ?? '') }}"
                               class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl" placeholder="86 m²">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Sıra</label>
                    <input type="number" name="order" value="{{ old('order', $project->order ?? 0) }}" min="0"
                           class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl">
                </div>

                <div class="flex flex-col gap-3 pt-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="status" value="1" class="w-4 h-4 rounded border-slate-300 text-blue-600"
                               @checked(old('status', $project->status ?? true))>
                        <span class="text-sm font-medium text-slate-700">Aktif</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="home_status" value="1" class="w-4 h-4 rounded border-slate-300 text-blue-600"
                               @checked(old('home_status', $project->home_status ?? false))>
                        <span class="text-sm font-medium text-slate-700">Anasayfada göster</span>
                    </label>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-8 space-y-4">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Kapak Görseli</h3>
                @if($isEdit && $project->image_url)
                    <div class="rounded-xl overflow-hidden border border-slate-200 aspect-[4/3] bg-slate-50">
                        <img src="{{ $project->image_url }}" alt="" class="w-full h-full object-cover">
                    </div>
                    <label class="flex items-center gap-2 text-xs text-red-500 font-medium cursor-pointer">
                        <input type="checkbox" name="remove_main_image" value="1" class="w-4 h-4">
                        Kapak görselini kaldır
                    </label>
                @endif
                <input type="file" name="main_image" accept="image/*"
                       class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3 px-4 rounded-xl">
            </div>

            <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/20">
                {{ $isEdit ? 'Değişiklikleri Kaydet' : 'Projeyi Kaydet' }}
            </button>
        </div>
    </form>
</div>
@endsection
