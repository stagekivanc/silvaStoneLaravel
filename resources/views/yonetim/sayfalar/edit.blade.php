@extends('yonetim.layouts.admin')

@section('title', $page->name . ' Düzenle')
@section('page_title', $page->name . ' Düzenle')

@section('content')
@php $uploadLimits = upload_limits(); @endphp
<div class="max-w-7xl mx-auto">
    <!-- Header Actions -->
    <div class="flex items-center justify-between mb-8">
        <a href="{{ route('yonetim.sayfalar.index') }}" class="flex items-center gap-2 text-slate-400 hover:text-slate-900 transition-colors group">
            <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
            <span class="text-xs font-bold uppercase tracking-widest">Listeye Dön</span>
        </a>
        
        <div class="flex items-center gap-3">
            <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-blue-100">
                Sistem Sayfası: {{ $page->slug }}
            </span>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
            <div class="flex items-center gap-3 mb-2">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                <p class="text-sm text-red-700 font-bold">Bir hata oluştu:</p>
            </div>
            <ul class="list-disc list-inside text-xs text-red-600 space-y-1 ml-8">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div x-data="{ tab: '{{ request('tab', 'content') }}' }">
        <!-- Tab Navigation -->
        <div class="flex items-center gap-2 mb-8 bg-slate-100 p-1.5 rounded-2xl w-fit">
            <button @click="tab = 'content'" 
                    :class="tab === 'content' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                    class="px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all flex items-center gap-2">
                <i data-lucide="layout" class="w-4 h-4"></i>
                Genel İçerik
            </button>
            <button @click="tab = 'seo'" 
                    :class="tab === 'seo' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                    class="px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all flex items-center gap-2">
                <i data-lucide="search" class="w-4 h-4"></i>
                SEO Ayarları
            </button>
            @if(count($dynamicFields) > 0)
            <button @click="tab = 'custom'" 
                    :class="tab === 'custom' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-900'"
                    class="px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all flex items-center gap-2">
                <i data-lucide="layers" class="w-4 h-4"></i>
                Sayfa Özellikleri
            </button>
            @endif
        </div>

        @include('yonetim.partials.language-tabs')

        <form action="{{ route('yonetim.sayfalar.update', $page->id) }}" method="POST" enctype="multipart/form-data" id="pageEditForm">
            @csrf
            <input type="hidden" name="active_tab" :value="tab">
            <input type="hidden" name="active_lang" id="activeLangInput" value="{{ request('lang', 'tr') }}">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Content Area -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Tab: Content -->
                    <div x-show="tab === 'content'" class="animate-in fade-in slide-in-from-bottom-4 duration-500 space-y-8">
                        @if($page->type === 'contact')
                        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden transition-all hover:shadow-md">
                            <div class="bg-slate-50 border-b border-slate-100 px-8 py-5 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-orange-500/10 text-orange-600 flex items-center justify-center">
                                    <i data-lucide="image" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900">Üst Banner Görseli</h3>
                                    <p class="text-[11px] text-slate-400 font-medium">İletişim sayfasının üst kısmında görünen geniş görsel</p>
                                </div>
                            </div>
                            <div class="p-8 space-y-4">
                                @if($page->image)
                                <div id="contactHeroImagePreview" class="relative group aspect-[21/9] rounded-xl overflow-hidden border border-slate-200 bg-slate-100 shadow-inner">
                                    <img src="{{ asset('uploads/'.$page->image) }}" class="w-full h-full object-cover" alt="İletişim banner görseli">
                                    <button type="button" id="contactHeroRemoveImage" class="absolute inset-0 bg-red-500/80 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all font-bold text-sm">SİL</button>
                                </div>
                                <input type="hidden" name="remove_image" id="remove_image" value="0">
                                @endif

                                <div class="relative">
                                    <input type="file" name="image" id="page_image" class="hidden" accept="image/png,image/jpeg,image/webp,image/gif" data-max-kb="2048">
                                    <label for="page_image" class="flex flex-col items-center justify-center w-full min-h-[140px] border-2 border-dashed border-slate-200 rounded-2xl cursor-pointer hover:border-orange-400 hover:bg-orange-50/20 transition-all group/upload">
                                        <div class="flex flex-col items-center justify-center py-6 px-4 text-center">
                                            <i data-lucide="upload-cloud" class="w-8 h-8 text-slate-300 mb-2"></i>
                                            <p class="text-[11px] text-slate-600 font-extrabold uppercase tracking-wider">Banner Görseli Seç</p>
                                            <p class="text-[10px] text-slate-400 mt-1">PNG, JPG veya WEBP — önerilen geniş format</p>
                                            <p class="text-[10px] text-amber-700 font-semibold mt-2">Maks. 2MB (toplam yükleme: {{ $uploadLimits['post_max_size'] }})</p>
                                        </div>
                                    </label>
                                    <p id="page_image_hint" class="text-[10px] text-slate-400 mt-2 hidden"></p>
                                </div>
                                <p class="text-[11px] text-slate-500 leading-relaxed">Ana Başlık ve Alt Başlık metinleri bu görselin üzerinde gösterilir. Görsel yüklemezseniz varsayılan görsel kullanılır.</p>
                            </div>
                        </div>
                        @endif

                        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden transition-all hover:shadow-md">
                            <div class="bg-slate-50 border-b border-slate-100 px-8 py-5 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-600 flex items-center justify-center">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900">Sayfa İçeriği</h3>
                                    <p class="text-[11px] text-slate-400 font-medium">Görsel alanlardaki metinleri düzenleyin</p>
                                </div>
                            </div>

                            <div class="p-8 space-y-6">
                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                                        <i data-lucide="layout" class="w-3 h-3 text-slate-300"></i>
                                        Sayfa Tipi (Şablon)
                                    </label>
                                    @php
                                        $pageTypes = [
                                            'index' => 'Anasayfa',
                                            'contact' => 'İletişim',
                                            'products' => 'Ürünler',
                                            'application' => 'B2B & Toptan',
                                            'references' => 'Referanslar',
                                            'faq' => 'SSS',
                                            'privacy-policy' => 'Gizlilik Politikası',
                                            'kvkk' => 'Aydınlatma Metni',
                                            'cookie-policy' => 'Çerez Politikası',
                                            'terms' => 'Güvenlik Politikası',
                                            'kvkk-law' => 'KVKK Kanunu Metni',
                                            'personal-data' => 'Kişisel Veri Koruma',
                                            'contracts' => 'Sözleşmeler',
                                            'stores' => 'Showroom / Mağazalar',
                                            'projects' => 'Projeler',
                                            'products' => 'Ürünler',
                                            'return-policy' => 'İade Koşulları',
                                            'shipping-policy' => 'Kargo & Teslimat',
                                            'login' => 'Giriş / Kayıt',
                                            'search' => 'Arama',
                                            'corporate' => 'Kurumsal',
                                            'solution' => 'Sektörel Çözüm',
                                            'sustainability' => 'Sürdürülebilirlik',
                                        ];
                                    @endphp
                                    <select name="type" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all font-medium">
                                        @foreach($pageTypes as $value => $label)
                                            <option value="{{ $value }}" {{ $page->type == $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                @foreach(\App\Models\Language::active() as $lang)
                                <div data-lang-area="{{ $lang->code }}" class="{{ $lang->is_default ? '' : 'hidden' }} space-y-6">
                                    @php
                                        $trans = $page->translations->where('lang_key', $lang->code)->first();
                                    @endphp
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                                                <i data-lucide="tag" class="w-3 h-3 text-slate-300"></i>
                                                Sayfa Adı ({{ strtoupper($lang->code) }})
                                            </label>
                                            <input type="text" name="translations[{{ $lang->code }}][name]" 
                                                   class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all font-bold" 
                                                   value="{{ $trans->name ?? '' }}" placeholder="Örn: Bayimiz Ol">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                                                <i data-lucide="link" class="w-3 h-3 text-slate-300"></i>
                                                Slug (URL) ({{ strtoupper($lang->code) }})
                                            </label>
                                            <input type="text" name="translations[{{ $lang->code }}][slug]" 
                                                   class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all font-mono text-sm" 
                                                   value="{{ $trans->slug ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                                            <i data-lucide="type" class="w-3 h-3 text-slate-300"></i>
                                            Alt Başlık ({{ strtoupper($lang->code) }})
                                        </label>
                                        <input type="text" name="translations[{{ $lang->code }}][subtitle]" 
                                               class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all font-medium" 
                                               value="{{ $trans->subtitle ?? '' }}">
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                                            <i data-lucide="heading" class="w-3 h-3 text-slate-300"></i>
                                            Ana Başlık ({{ strtoupper($lang->code) }})
                                        </label>
                                        <input type="text" name="translations[{{ $lang->code }}][title]" 
                                               class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all font-bold text-lg" 
                                               value="{{ $trans->title ?? '' }}">
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                                            <i data-lucide="align-left" class="w-3 h-3 text-slate-300"></i>
                                            Kısa Açıklama ({{ strtoupper($lang->code) }})
                                        </label>
                                        <textarea name="translations[{{ $lang->code }}][content_text]" rows="4" 
                                                  class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all leading-relaxed">{{ $trans->content_text ?? '' }}</textarea>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-2">
                                            <i data-lucide="layout-template" class="w-3 h-3 text-slate-300"></i>
                                            Detaylı İçerik ({{ strtoupper($lang->code) }})
                                        </label>
                                        <div class="rounded-xl overflow-hidden border border-slate-200 shadow-inner bg-slate-50">
                                            <div id="editor-container-{{ $lang->code }}" class="quill-editor-area" data-target="body_content_{{ $lang->code }}"></div>
                                            <textarea name="translations[{{ $lang->code }}][body_content]" id="body_content_{{ $lang->code }}" style="display:none;">{{ $trans->body_content ?? '' }}</textarea>
                                        </div>
                                    </div>                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Tab: SEO -->
                    <div x-show="tab === 'seo'" class="animate-in fade-in slide-in-from-bottom-4 duration-500 space-y-8">
                        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                            <div class="bg-slate-50 border-b border-slate-100 px-8 py-5">
                                <h3 class="text-sm font-bold text-slate-900">SEO Ayarları</h3>
                            </div>
                            
                            <div class="p-8 space-y-8">
                                @foreach(\App\Models\Language::active() as $lang)
                                <div data-lang-area="{{ $lang->code }}" class="{{ $lang->is_default ? '' : 'hidden' }} space-y-6">
                                    @php
                                        $trans = $page->translations->where('lang_key', $lang->code)->first();
                                    @endphp
                                    <div class="space-y-3">
                                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest px-1">Meta Başlığı ({{ strtoupper($lang->code) }})</label>
                                        <input type="text" name="translations[{{ $lang->code }}][seo_title]" 
                                               class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all font-medium" 
                                               value="{{ $trans->seo_title ?? '' }}">
                                    </div>

                                    <div class="space-y-3">
                                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest px-1">Meta Açıklaması ({{ strtoupper($lang->code) }})</label>
                                        <textarea name="translations[{{ $lang->code }}][seo_description]" rows="4" 
                                                  class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all leading-relaxed">{{ $trans->seo_description ?? '' }}</textarea>
                                    </div>

                                    <div class="space-y-3">
                                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest px-1">Anahtar Kelimeler ({{ strtoupper($lang->code) }})</label>
                                        <input type="text" name="translations[{{ $lang->code }}][seo_keywords]" 
                                               class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:border-blue-500 transition-all font-mono text-sm" 
                                               value="{{ $trans->seo_keywords ?? '' }}">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Tab: Custom Fields -->
                    @if(count($dynamicFields) > 0)
                    <div x-show="tab === 'custom'" class="animate-in fade-in slide-in-from-bottom-4 duration-500 space-y-8">
                        @foreach($dynamicFields as $section)
                            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden p-8 space-y-6 transition-all hover:shadow-md">
                                <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-4">{{ $section['name'] }}</h3>
                                
                                @foreach(\App\Models\Language::active() as $lang)
                                <div data-lang-area="{{ $lang->code }}" class="{{ $lang->is_default ? '' : 'hidden' }} space-y-6">
                                    @php
                                        $trans = $page->translations->where('lang_key', $lang->code)->first();
                                        $extras = $trans->extras ?? [];
                                    @endphp
                                    
                                    @if($section['type'] == 'group')
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                            @foreach($section['fields'] as $field)
                                                @php
                                                    $keys = explode('.', $field['key']);
                                                    $val = page_extra_field_value($extras, $field['key'], $field['default'] ?? null);
                                                @endphp
                                                <div class="space-y-2 {{ in_array($field['type'], ['textarea', 'html', 'image', 'media', 'gallery', 'file']) ? 'md:col-span-2 lg:col-span-3' : '' }}">
                                                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">{{ $field['label'] }} ({{ strtoupper($lang->code) }})</label>
                                                    
                                                    @if($field['type'] == 'textarea')
                                                        <textarea name="translations[{{ $lang->code }}][extras][{{ implode('][', $keys) }}]" rows="4" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3 px-4 rounded-xl focus:border-blue-500 transition-all font-medium leading-relaxed">{{ $val }}</textarea>
                                                    @elseif($field['type'] == 'html')
                                                        @php
                                                            $extraEditorId = 'quill-extra-' . $lang->code . '-' . str_replace('.', '-', $field['key']);
                                                            $extraTextareaId = 'html-field-' . $lang->code . '-' . str_replace('.', '-', $field['key']);
                                                        @endphp
                                                        <div class="rounded-xl overflow-hidden border border-slate-200 shadow-inner bg-slate-50">
                                                            <div id="{{ $extraEditorId }}" class="quill-editor-area" data-target="{{ $extraTextareaId }}"></div>
                                                            <textarea name="translations[{{ $lang->code }}][extras][{{ implode('][', $keys) }}]" id="{{ $extraTextareaId }}" style="display:none;">{{ $val }}</textarea>
                                                        </div>
                                                    @elseif(in_array($field['type'], ['image', 'media'], true))
                                                        @include('yonetim.sayfalar.partials.extra-image-field', [
                                                            'langCode' => $lang->code,
                                                            'fieldKey' => $field['key'],
                                                            'fieldLabel' => $field['label'],
                                                            'keys' => $keys,
                                                            'val' => $val,
                                                            'allowVideo' => $field['type'] === 'media',
                                                        ])
                                                    @elseif($field['type'] == 'file')
                                                        @include('yonetim.sayfalar.partials.extra-file-field', [
                                                            'langCode' => $lang->code,
                                                            'fieldKey' => $field['key'],
                                                            'fieldLabel' => $field['label'],
                                                            'keys' => $keys,
                                                            'val' => $val,
                                                        ])
                                                    @elseif($field['type'] == 'gallery')
                                                        @include('yonetim.sayfalar.partials.extra-gallery-field', [
                                                            'langCode' => $lang->code,
                                                            'fieldKey' => $field['key'],
                                                            'fieldLabel' => $field['label'],
                                                            'keys' => $keys,
                                                            'val' => $val,
                                                        ])
                                                    @else
                                                        <input type="text" name="translations[{{ $lang->code }}][extras][{{ implode('][', $keys) }}]" value="{{ $val }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3 px-4 rounded-xl focus:border-blue-500 transition-all font-medium">
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($section['type'] == 'repeater')
                                        <div class="space-y-6">
                                            @php
                                                $sectionKeys = explode('.', $section['key']);
                                                $sectionNamePath = implode('][', $sectionKeys);
                                                $sectionValues = data_get($extras, $section['key'], []);
                                            @endphp
                                            @for($i = 0; $i < ($section['count'] ?? 1); $i++)
                                                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 space-y-4">
                                                    <span class="text-[10px] font-extrabold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg uppercase tracking-widest">{{ $section['name'] }} #{{ $i + 1 }}</span>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        @foreach($section['fields'] as $field)
                                                            @php
                                                                $fieldPath = explode('.', $field['key']);
                                                                $val = data_get($sectionValues[$i] ?? [], $field['key'], data_get($section['defaults'][$i] ?? [], $field['key']));
                                                                $repeaterKeys = array_merge($sectionKeys, [$i], $fieldPath);
                                                                $repeaterFieldKey = implode('.', $repeaterKeys);
                                                                $repeaterNamePath = implode('][', $repeaterKeys);
                                                            @endphp
                                                            <div class="space-y-1 {{ in_array($field['type'], ['textarea', 'html', 'image', 'media', 'file']) ? 'md:col-span-2' : '' }}">
                                                                <label class="text-[10px] font-bold text-slate-400 uppercase">{{ $field['label'] }}</label>
                                                                @if($field['type'] === 'textarea')
                                                                    <textarea name="translations[{{ $lang->code }}][extras][{{ $repeaterNamePath }}]" rows="3" class="w-full bg-white border border-slate-200 py-2 px-3 rounded-lg text-sm">{{ $val }}</textarea>
                                                                @elseif($field['type'] === 'html')
                                                                    @php
                                                                        $repeaterEditorId = 'quill-extra-' . $lang->code . '-' . str_replace('.', '-', $repeaterFieldKey);
                                                                        $repeaterTextareaId = 'html-field-' . $lang->code . '-' . str_replace('.', '-', $repeaterFieldKey);
                                                                    @endphp
                                                                    <div class="rounded-xl overflow-hidden border border-slate-200 shadow-inner bg-white">
                                                                        <div id="{{ $repeaterEditorId }}" class="quill-editor-area" data-target="{{ $repeaterTextareaId }}"></div>
                                                                        <textarea name="translations[{{ $lang->code }}][extras][{{ $repeaterNamePath }}]" id="{{ $repeaterTextareaId }}" style="display:none;">{{ $val }}</textarea>
                                                                    </div>
                                                                @elseif(in_array($field['type'], ['image', 'media'], true))
                                                                    @include('yonetim.sayfalar.partials.extra-image-field', [
                                                                        'langCode' => $lang->code,
                                                                        'fieldKey' => $repeaterFieldKey,
                                                                        'fieldLabel' => $field['label'],
                                                                        'keys' => $repeaterKeys,
                                                                        'val' => $val,
                                                                        'allowVideo' => $field['type'] === 'media',
                                                                    ])
                                                                @elseif($field['type'] === 'file')
                                                                    @include('yonetim.sayfalar.partials.extra-file-field', [
                                                                        'langCode' => $lang->code,
                                                                        'fieldKey' => $repeaterFieldKey,
                                                                        'fieldLabel' => $field['label'],
                                                                        'keys' => $repeaterKeys,
                                                                        'val' => $val,
                                                                    ])
                                                                @else
                                                                    <input type="text" name="translations[{{ $lang->code }}][extras][{{ $repeaterNamePath }}]" value="{{ $val }}" class="w-full bg-white border border-slate-200 py-2 px-3 rounded-lg text-sm">
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Right Sidebar Area (Always Visible) -->
                <div class="space-y-8">
                    <!-- Publish Card -->
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden p-6 sticky top-8">
                        <button type="submit" class="w-full py-4 bg-blue-600 text-white font-extrabold rounded-xl shadow-lg shadow-blue-600/20 hover:bg-blue-700 hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-3">
                            <i data-lucide="save" class="w-5 h-5"></i>
                            Güncellemeleri Kaydet
                        </button>
                        
                        <div class="mt-6 pt-6 border-t border-slate-100 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Durum</span>
                                <span class="flex items-center gap-1.5 text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg uppercase">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Son Güncelleme</span>
                                <span class="text-[10px] font-bold text-slate-600 uppercase">{{ $page->updated_at->format('d.m.Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Page Image Card -->
                    @if(!in_array($page->type, ['contact', 'corporate'], true))
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden transition-all hover:shadow-md">
                        <div class="bg-slate-50 border-b border-slate-100 px-6 py-4 flex items-center gap-3">
                            <i data-lucide="image" class="w-4 h-4 text-blue-500"></i>
                            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                                Sayfa Görseli
                            </h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="space-y-4">
                                @if($page->image)
                                    <div id="pageImagePreview" class="relative group aspect-video rounded-xl overflow-hidden border border-slate-200 bg-slate-100 shadow-inner">
                                        <img src="{{ asset('uploads/'.$page->image) }}" class="w-full h-full object-cover" alt="Mevcut Görsel">
                                        <button type="button" id="pageRemoveImage" class="absolute inset-0 bg-red-500/80 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all font-bold text-sm">SİL</button>
                                    </div>
                                    <input type="hidden" name="remove_image" id="remove_image" value="0">
                                @endif

                                <div class="relative">
                                    <input type="file" name="image" id="page_image" class="hidden" accept="image/*" data-max-kb="2048">
                                    <label for="page_image" class="flex flex-col items-center justify-center w-full min-h-[140px] border-2 border-dashed border-slate-200 rounded-2xl cursor-pointer hover:border-blue-400 hover:bg-blue-50/20 transition-all group/upload">
                                        <div class="flex flex-col items-center justify-center py-6 px-4 text-center">
                                            <i data-lucide="upload-cloud" class="w-8 h-8 text-slate-300 mb-2"></i>
                                            <p class="text-[11px] text-slate-600 font-extrabold uppercase tracking-wider">Görsel Seç</p>
                                            <p class="text-[10px] text-amber-700 font-semibold mt-2">Maks. 2MB (toplam yükleme: {{ $uploadLimits['post_max_size'] }})</p>
                                        </div>
                                    </label>
                                    <p id="page_image_hint" class="text-[10px] text-slate-400 mt-2 hidden"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .quill-editor-area .ql-editor { min-height: 220px; font-size: 14px; line-height: 1.6; }
    .quill-editor-area .ql-container { font-family: inherit; }
</style>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var editors = {};
    document.querySelectorAll('.quill-editor-area').forEach(function (container) {
        var targetId = container.getAttribute('data-target');
        if (!targetId) return;

        var quill = new Quill('#' + container.id, {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link'],
                    ['clean']
                ]
            }
        });

        var textarea = document.getElementById(targetId);
        if (textarea && textarea.value) {
            quill.root.innerHTML = textarea.value;
        }

        editors[targetId] = quill;
    });

    // Alpine x-show ile gizli sekmede init olan editörlerin yüksekliğini düzelt
    document.querySelectorAll('button').forEach(function (button) {
        button.addEventListener('click', function () {
            setTimeout(function () {
                Object.keys(editors).forEach(function (targetId) {
                    var quill = editors[targetId];
                    if (quill && quill.root) {
                        quill.root.style.minHeight = '220px';
                    }
                });
            }, 50);
        });
    });

    document.getElementById('pageEditForm').addEventListener('submit', function(event) {
        Object.keys(editors).forEach(function (targetId) {
            var textarea = document.getElementById(targetId);
            if (textarea) {
                textarea.value = editors[targetId].root.innerHTML;
            }
        });

        var imageInput = document.getElementById('page_image');
        var file = imageInput.files[0];
        if (file) {
            var maxBytes = 2048 * 1024;
            var postMaxBytes = {{ (int) $uploadLimits['post_max_bytes'] }};
            if (file.size > maxBytes) {
                event.preventDefault();
                alert('Seçilen görsel çok büyük. Maksimum 2MB yükleyebilirsiniz.');
                return;
            }
            if (file.size > postMaxBytes) {
                event.preventDefault();
                alert('Seçilen görsel sunucu yükleme limitini aşıyor ({{ $uploadLimits['post_max_size'] }}).');
            }
        }
    });

    document.getElementById('page_image').addEventListener('change', function () {
        var hint = document.getElementById('page_image_hint');
        var file = this.files[0];
        if (!file) {
            hint.classList.add('hidden');
            return;
        }
        var sizeMb = (file.size / (1024 * 1024)).toFixed(2);
        hint.textContent = 'Seçilen: ' + file.name + ' (' + sizeMb + ' MB)';
        hint.classList.remove('hidden');
        hint.classList.toggle('text-red-600', file.size > 2048 * 1024);
        hint.classList.toggle('font-semibold', file.size > 2048 * 1024);
    });

    function bindPageImageRemove(buttonId, inputId, previewId) {
        var removeBtn = document.getElementById(buttonId);
        var removeInput = document.getElementById(inputId);
        var preview = document.getElementById(previewId);
        if (!removeBtn || !removeInput) return;

        removeBtn.addEventListener('click', function () {
            removeInput.value = '1';
            if (preview) preview.remove();
        });
    }

    bindPageImageRemove('contactHeroRemoveImage', 'remove_image', 'contactHeroImagePreview');
    bindPageImageRemove('pageRemoveImage', 'remove_image', 'pageImagePreview');

    document.querySelectorAll('.extra-image-remove').forEach(function (button) {
        button.addEventListener('click', function () {
            var removeInput = document.getElementById(button.dataset.remove);
            var currentInput = document.getElementById(button.dataset.current);
            var preview = document.getElementById(button.dataset.preview);

            if (removeInput) removeInput.value = '1';
            if (currentInput) currentInput.value = '';
            if (preview) preview.remove();
        });
    });

    document.querySelectorAll('.extra-file-remove').forEach(function (button) {
        button.addEventListener('click', function () {
            var removeInput = document.getElementById(button.dataset.remove);
            var currentInput = document.getElementById(button.dataset.current);
            var preview = document.getElementById(button.dataset.preview);

            if (removeInput) removeInput.value = '1';
            if (currentInput) currentInput.value = '';
            if (preview) preview.remove();
        });
    });
</script>
@endpush
@endsection
