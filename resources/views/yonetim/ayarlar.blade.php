@extends('yonetim.layouts.admin')

@section('title', 'Genel Ayarlar')
@section('page_title', 'Genel Ayarlar')

@php
    $active_tab = request()->get('tab', 'site');
    $tabs = [
        'site' => ['label' => 'Site Ayarları', 'icon' => 'globe'],
        'seo' => ['label' => 'SEO Ayarları', 'icon' => 'search'],
        'logo' => ['label' => 'Logo Ayarları', 'icon' => 'image'],
        'urun-detay' => ['label' => 'Ürün Detay', 'icon' => 'shirt'],
        'katalog' => ['label' => 'Teknik Katalog', 'icon' => 'book-open'],
        'sosyal' => ['label' => 'Sosyal Medya', 'icon' => 'share-2'],
        'yasal' => ['label' => 'Footer Bağlantıları', 'icon' => 'link-2'],
        'mail' => ['label' => 'Mail Ayarları', 'icon' => 'mail'],
        'mailjet' => ['label' => 'Mailjet Ayarları', 'icon' => 'send'],
        'recaptcha' => ['label' => 'Google reCAPTCHA', 'icon' => 'shield-check'],
        'kodlar' => ['label' => 'Takip Kodları', 'icon' => 'code'],
    ];
@endphp

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Tab Navigation -->
    <div class="flex flex-wrap gap-1.5 mb-8 bg-white p-3 rounded-2xl border border-slate-200 shadow-sm overflow-x-auto">
        @foreach($tabs as $key => $tab)
            <a href="{{ route('yonetim.ayarlar', ['tab' => $key]) }}" 
               class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold transition-all {{ $active_tab == $key ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <i data-lucide="{{ $tab['icon'] }}" class="w-3.5 h-3.5"></i>
                {{ $tab['label'] }}
            </a>
        @endforeach
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl animate-[fadeIn_0.3s_ease-out]">
            <div class="flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i>
                <p class="text-sm text-emerald-700 font-bold">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Tab Content -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden animate-[fadeIn_0.3s_ease-out]">
        <form action="{{ route('yonetim.ayarlar.update') }}" method="POST" class="p-5 md:p-8 lg:p-12" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="active_tab" value="{{ $active_tab }}">
            
            @if($active_tab == 'site')
                <div class="space-y-8">
                    <div class="border-b border-slate-100 pb-6">
                        <h3 class="text-xl font-display font-bold text-slate-900">Site ve Firma Bilgileri</h3>
                        <p class="text-slate-500 text-sm mt-1">Sitenin genel görünümü ve iletişim bilgilerini buradan yönetebilirsiniz.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Site Adı</label>
                            <input type="text" name="site_name" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('site_name', 'Silva Stone') }}">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Firma Adı</label>
                            <input type="text" name="company_name" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('company_name', 'Silva Stone') }}">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">E-Posta Adresi</label>
                            <input type="email" name="contact_email" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('contact_email', '') }}">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Telefon Numarası</label>
                            <input type="text" name="contact_phone" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('contact_phone', '+90 (212) 000 00 00') }}">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">WhatsApp Numarası</label>
                            <input type="text" name="contact_whatsapp" maxlength="15" data-phone-format="digits" placeholder="905xxxxxxxxx" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('contact_whatsapp') }}">
                            <p class="text-[11px] text-slate-400 ml-1">Sadece rakam, ülke kodu ile (ör. 905xxxxxxxxx)</p>
                        </div>
                        <div class="col-span-1 md:col-span-2 space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Adres Bilgisi</label>
                            <textarea name="contact_address" rows="3" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all">{{ \App\Models\Setting::get('contact_address', 'Ataköy 7-8-9-10. Kısım Mah. Çobançeşme E-5 Yan Yol Cad. No: 20/1, İç Kapı No: 123 Bakırköy/İstanbul') }}</textarea>
                        </div>
                        <div class="col-span-1 md:col-span-2 space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Google Harita Linki (URL)</label>
                            <input type="text" name="contact_map" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('contact_map') }}" placeholder="https://maps.google.com/...">
                        </div>
                    </div>
                </div>
            @endif

            @if($active_tab == 'seo')
                <div class="space-y-8">
                    <div class="border-b border-slate-100 pb-6">
                        <h3 class="text-xl font-display font-bold text-slate-900">SEO Ayarları</h3>
                        <p class="text-slate-500 text-sm mt-1">Arama motoru görünürlüğü için meta etiketlerini düzenleyin.</p>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Meta Başlığı (Title)</label>
                            <input type="text" name="seo_title" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('seo_title', 'Silva Stone') }}">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Meta Açıklaması (Description)</label>
                            <textarea name="seo_description" rows="3" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all">{{ \App\Models\Setting::get('seo_description', 'Yeşil dönüşüm ve yenilenebilir enerji yatırımlarınızda profesyonel danışmanlık.') }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Anahtar Kelimeler (Keywords)</label>
                            <input type="text" name="seo_keywords" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('seo_keywords', 'iş kıyafeti, iş yeleği, üniforma, toptan, türksan') }}">
                        </div>
                        
                        <div class="pt-6 border-t border-slate-100">
                            <div class="flex items-center gap-2 mb-4">
                                <i data-lucide="map" class="w-5 h-5 text-blue-500"></i>
                                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Otomatik Oluşturulan Sitemap Linkleri</h4>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @php
                                    $defaultLang = \App\Models\Language::default()->code ?? 'tr';
                                    $sitemaps = [
                                        'Ana Sitemap Dizini (Google Search Console için)' => route('sitemap.index'),
                                        'Dil Sitemap Dizini' => route('sitemap.lang', ['lang' => $defaultLang]),
                                        'Ana Sayfalar' => route('sitemap.main', ['lang' => $defaultLang]),
                                        'Ürünler' => route('sitemap.technologies', ['lang' => $defaultLang]),
                                        'Ürün Kategorileri' => route('sitemap.technology-categories', ['lang' => $defaultLang]),
                                        'Diğer Sayfalar' => route('sitemap.pages', ['lang' => $defaultLang]),
                                    ];
                                @endphp
                                
                                @foreach($sitemaps as $label => $url)
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">{{ $label }}</label>
                                    <div class="relative group">
                                        <input type="text" readonly value="{{ $url }}" class="w-full bg-slate-50 border border-slate-200 text-slate-600 py-2.5 px-4 rounded-xl text-xs font-mono focus:outline-none cursor-pointer hover:bg-white hover:border-blue-300 transition-all" onclick="this.select(); document.execCommand('copy');">
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-300 group-hover:text-blue-500 transition-colors pointer-events-none">
                                            <i data-lucide="copy" class="w-4 h-4"></i>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($active_tab == 'logo')
                <div class="space-y-8">
                    <div class="border-b border-slate-100 pb-6">
                        <h3 class="text-xl font-display font-bold text-slate-900">Logo Ayarları</h3>
                        <p class="text-slate-500 text-sm mt-1">Site genelinde kullanılan logoları buradan güncelleyebilirsiniz. Favicon ve logo ön yüzde otomatik kullanılır.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <div class="space-y-4">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Ana Logo (Koyu Renk)</label>
                            <div class="relative bg-slate-50 rounded-3xl p-8 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 hover:border-blue-400 transition-all cursor-pointer group" onclick="document.getElementById('site_logo').click()">
                                <input type="file" name="site_logo" id="site_logo" class="hidden" accept="image/*" onchange="previewImage(this, 'site_logo_preview')">
                                <img id="site_logo_preview" src="{{ \App\Models\Setting::get('site_logo') ? asset('uploads/'.\App\Models\Setting::get('site_logo')) : asset('assets/stage_black_logo.svg') }}" class="h-12 w-auto mb-4 opacity-80 group-hover:opacity-100 transition-all">
                                <span class="text-xs font-bold text-slate-400 group-hover:text-blue-500">Logoyu Değiştir</span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Favicon (32x32)</label>
                            <div class="relative bg-slate-50 rounded-3xl p-8 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 hover:border-blue-400 transition-all cursor-pointer group" onclick="document.getElementById('site_favicon').click()">
                                <input type="file" name="site_favicon" id="site_favicon" class="hidden" accept="image/*" onchange="previewImage(this, 'favicon_preview')">
                                @if(\App\Models\Setting::get('site_favicon'))
                                    <img id="favicon_preview" src="{{ asset('uploads/'.\App\Models\Setting::get('site_favicon')) }}" class="w-10 h-10 object-contain mb-4">
                                @else
                                    <div id="favicon_preview" class="w-10 h-10 bg-white rounded-lg shadow-sm border border-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-400 mb-4 group-hover:border-blue-400 group-hover:text-blue-500 uppercase">ICO</div>
                                @endif
                                <span class="text-xs font-bold text-slate-400 group-hover:text-blue-500">Faviconu Değiştir</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($active_tab == 'sosyal')
                <div class="space-y-8">
                    <div class="border-b border-slate-100 pb-6">
                        <h3 class="text-xl font-display font-bold text-slate-900">Sosyal Medya Hesapları</h3>
                        <p class="text-slate-500 text-sm mt-1">Footer ve iletişim sayfasındaki bağlantıları düzenleyin.</p>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-700 text-white flex items-center justify-center shrink-0 shadow-lg">
                                <i data-lucide="facebook" class="w-5 h-5"></i>
                            </div>
                            <input type="text" name="social_facebook" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('social_facebook', '') }}" placeholder="Facebook Sayfa Linki">
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0 shadow-lg">
                                <i data-lucide="instagram" class="w-5 h-5"></i>
                            </div>
                            <input type="text" name="social_instagram" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('social_instagram', '') }}">
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-lg">
                                <i data-lucide="linkedin" class="w-5 h-5"></i>
                            </div>
                            <input type="text" name="social_linkedin" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('social_linkedin', '') }}">
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0 shadow-lg">
                                <i data-lucide="twitter" class="w-5 h-5"></i>
                            </div>
                            <input type="text" name="social_twitter" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('social_twitter', '') }}" placeholder="Twitter (eski alan)">
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-black text-white flex items-center justify-center shrink-0 shadow-lg font-bold text-sm">X</div>
                            <input type="text" name="social_x" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('social_x', '') }}" placeholder="https://x.com/lmc">
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-red-600 text-white flex items-center justify-center shrink-0 shadow-lg">
                                <i data-lucide="youtube" class="w-5 h-5"></i>
                            </div>
                            <input type="text" name="social_youtube" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('social_youtube', '') }}" placeholder="YouTube kanal linki">
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-red-700 text-white flex items-center justify-center shrink-0 shadow-lg">
                                <i data-lucide="pin" class="w-5 h-5"></i>
                            </div>
                            <input type="text" name="social_pinterest" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('social_pinterest', '') }}" placeholder="Pinterest profil linki">
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-sky-500 text-white flex items-center justify-center shrink-0 shadow-lg">
                                <i data-lucide="send" class="w-5 h-5"></i>
                            </div>
                            <input type="text" name="social_telegram" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('social_telegram', '') }}" placeholder="Telegram kanal / profil linki">
                        </div>
                    </div>
                </div>
            @endif

            @if($active_tab == 'katalog')
                @php
                    $catalogPdf = \App\Models\Setting::get('catalog_pdf');
                    $catalogUrl = catalog_pdf_url();
                @endphp
                <div class="space-y-8">
                    <div class="border-b border-slate-100 pb-6">
                        <h3 class="text-xl font-display font-bold text-slate-900">Teknik Katalog</h3>
                        <p class="text-slate-500 text-sm mt-1">Header’daki “Teknik Katalog” ve footer’daki “E-Katalog (PDF)” aynı dosyayı kullanır. Tek PDF yüklemeniz yeterlidir.</p>
                    </div>

                    <div class="p-6 rounded-2xl border border-slate-200 bg-slate-50/60 space-y-4 max-w-2xl">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0">
                                <i data-lucide="book-open" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900">Katalog PDF</h4>
                                <p class="text-xs text-slate-500 mt-1">Yüklenen dosya hem menüde hem footer’da açılır.</p>
                            </div>
                        </div>
                        @if($catalogPdf)
                            <div class="flex items-center justify-between gap-3 p-4 bg-white border border-slate-200 rounded-xl">
                                <a href="{{ uploaded_file_url($catalogPdf) }}" target="_blank" class="text-sm font-bold text-blue-600 hover:underline truncate">{{ $catalogPdf }}</a>
                                <label class="inline-flex items-center gap-2 text-xs font-bold text-red-500 cursor-pointer shrink-0">
                                    <input type="checkbox" name="remove_catalog_pdf" value="1" class="rounded border-slate-300 text-red-500 focus:ring-red-500">
                                    Kaldır
                                </label>
                            </div>
                        @endif
                        <input type="file" name="catalog_pdf" accept=".pdf,application/pdf" class="w-full bg-white border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 transition-all">
                    </div>

                    <div class="p-5 rounded-2xl border border-blue-100 bg-blue-50 text-sm text-blue-900 max-w-2xl">
                        <p class="font-bold mb-2">Kullanıldığı yerler</p>
                        <ul class="space-y-1 text-blue-800">
                            <li>Header → Teknik Katalog: {{ $catalogUrl ?: 'PDF yüklenince görünür' }}</li>
                            <li>Footer → E-Katalog (PDF): {{ $catalogUrl ?: 'PDF yüklenince görünür' }}</li>
                        </ul>
                    </div>
                </div>
            @endif

            @if($active_tab == 'yasal')
                @php
                    $cookiePdf = \App\Models\Setting::get('legal_cookie_policy_pdf');
                    $termsPdf = \App\Models\Setting::get('legal_terms_pdf');
                @endphp
                <div class="space-y-8">
                    <div class="border-b border-slate-100 pb-6">
                        <h3 class="text-xl font-display font-bold text-slate-900">Footer Bağlantıları</h3>
                        <p class="text-slate-500 text-sm mt-1">Sitedeki “Destek ve Yasal” alanı ile alt bardaki yasal linkleri buradan yönetin. Katalog PDF’i <a href="{{ route('yonetim.ayarlar', ['tab' => 'katalog']) }}" class="font-bold text-blue-600 hover:underline">Teknik Katalog</a> sekmesinden yüklenir.</p>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                        <div class="p-6 rounded-2xl border border-slate-200 bg-slate-50/60 space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center shrink-0">
                                    <i data-lucide="headphones" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900">Teknik Destek</h4>
                                    <p class="text-xs text-slate-500 mt-1">Boş bırakılırsa iletişim sayfasına gider.</p>
                                </div>
                            </div>
                            <input type="text" name="footer_technical_support_url" value="{{ \App\Models\Setting::get('footer_technical_support_url', '') }}" placeholder="{{ menu_page_url('contact') }}" class="w-full bg-white border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 transition-all">
                        </div>

                        <div class="p-6 rounded-2xl border border-slate-200 bg-slate-50/60 space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                                    <i data-lucide="shield" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900">KVKK ve Aydınlatma Metni</h4>
                                    <p class="text-xs text-slate-500 mt-1">Boş bırakılırsa KVKK sayfası kullanılır.</p>
                                </div>
                            </div>
                            <input type="text" name="footer_kvkk_url" value="{{ \App\Models\Setting::get('footer_kvkk_url', '') }}" placeholder="{{ menu_page_url('kvkk') }}" class="w-full bg-white border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 transition-all">
                        </div>

                        <div class="p-6 rounded-2xl border border-slate-200 bg-slate-50/60 space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0">
                                    <i data-lucide="cookie" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900">Çerez Politikası</h4>
                                    <p class="text-xs text-slate-500 mt-1">PDF yükleyebilir veya harici URL girebilirsiniz.</p>
                                </div>
                            </div>
                            @if($cookiePdf)
                                <div class="flex items-center justify-between gap-3 p-4 bg-white border border-slate-200 rounded-xl">
                                    <a href="{{ uploaded_file_url($cookiePdf) }}" target="_blank" class="text-sm font-bold text-blue-600 hover:underline truncate">{{ $cookiePdf }}</a>
                                    <label class="inline-flex items-center gap-2 text-xs font-bold text-red-500 cursor-pointer shrink-0">
                                        <input type="checkbox" name="remove_legal_cookie_policy_pdf" value="1" class="rounded border-slate-300 text-red-500 focus:ring-red-500">
                                        Kaldır
                                    </label>
                                </div>
                            @endif
                            <input type="file" name="legal_cookie_policy_pdf" accept=".pdf,application/pdf" class="w-full bg-white border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 transition-all">
                            <input type="text" name="legal_cookie_policy_url" value="{{ \App\Models\Setting::get('legal_cookie_policy_url', '') }}" placeholder="https://... veya /tr/cerez-politikasi" class="w-full bg-white border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 transition-all">
                        </div>

                        <div class="p-6 rounded-2xl border border-slate-200 bg-slate-50/60 space-y-4 xl:col-span-2">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-violet-600 text-white flex items-center justify-center shrink-0">
                                    <i data-lucide="file-text" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900">Kullanım Sözleşmesi</h4>
                                    <p class="text-xs text-slate-500 mt-1">Alt bardaki “Kullanım Sözleşmesi” linki için PDF veya URL.</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    @if($termsPdf)
                                        <div class="flex items-center justify-between gap-3 p-4 bg-white border border-slate-200 rounded-xl">
                                            <a href="{{ uploaded_file_url($termsPdf) }}" target="_blank" class="text-sm font-bold text-blue-600 hover:underline truncate">{{ $termsPdf }}</a>
                                            <label class="inline-flex items-center gap-2 text-xs font-bold text-red-500 cursor-pointer shrink-0">
                                                <input type="checkbox" name="remove_legal_terms_pdf" value="1" class="rounded border-slate-300 text-red-500 focus:ring-red-500">
                                                Kaldır
                                            </label>
                                        </div>
                                    @endif
                                    <input type="file" name="legal_terms_pdf" accept=".pdf,application/pdf" class="w-full bg-white border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 transition-all">
                                </div>
                                <input type="text" name="legal_terms_url" value="{{ \App\Models\Setting::get('legal_terms_url', '') }}" placeholder="https://... veya /tr/kullanim-sozlesmesi" class="w-full bg-white border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="p-5 rounded-2xl border border-blue-100 bg-blue-50 text-sm text-blue-900">
                        <p class="font-bold mb-2">Footer önizlemesi</p>
                        <ul class="space-y-1 text-blue-800">
                            <li>Teknik Destek: {{ footer_technical_support_url() }}</li>
                            <li>KVKK: {{ footer_kvkk_url() }}</li>
                            <li>Çerez Politikası: {{ footer_cookie_policy_url() }}</li>
                            <li>Kullanım Sözleşmesi: {{ footer_terms_url() }}</li>
                        </ul>
                    </div>
                </div>
            @endif

            @if($active_tab == 'mail')
                <div class="space-y-8">
                    <div class="border-b border-slate-100 pb-6">
                        <h3 class="text-xl font-display font-bold text-slate-900">SMTP Mail Ayarları</h3>
                        <p class="text-slate-500 text-sm mt-1">Sistemden gönderilecek e-postalar için sunucu yapılandırması.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">SMTP Host</label>
                            <input type="text" name="mail_host" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('mail_host', 'smtp.yandex.com') }}">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">SMTP Port</label>
                            <input type="text" name="mail_port" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('mail_port', '465') }}">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">E-Posta (User)</label>
                            <input type="text" name="mail_user" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('mail_user', '') }}">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Şifre</label>
                            <input type="password" name="mail_pass" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('mail_pass', '********') }}">
                        </div>
                    </div>
                </div>
            @endif

            @if($active_tab == 'mailjet')
                <div class="space-y-8">
                    <div class="border-b border-slate-100 pb-6">
                        <h3 class="text-xl font-display font-bold text-slate-900">Mailjet API Ayarları</h3>
                        <p class="text-slate-500 text-sm mt-1">Mailjet servislerini kullanmak için API anahtarlarını yapılandırın.</p>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100 flex items-start gap-4 mb-6">
                            <i data-lucide="info" class="w-5 h-5 text-blue-600 shrink-0 mt-0.5"></i>
                            <div class="text-sm text-blue-800 leading-relaxed">
                                <p class="font-bold mb-1">Mailjet Nedir?</p>
                                <p>Mailjet, yüksek teslimat oranına sahip bir toplu e-posta gönderim servisidir. <a href="https://app.mailjet.com/auth/get_api_key" target="_blank" class="underline font-bold">Buradan</a> API anahtarlarınızı alabilirsiniz.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Mailjet API Key</label>
                                <input type="text" name="mailjet_api_key" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('mailjet_api_key') }}" placeholder="apikey">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Mailjet Secret Key</label>
                                <input type="text" name="mailjet_secret_key" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('mailjet_secret_key') }}" placeholder="secretkey">
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Varsayılan Gönderici E-Posta</label>
                            <input type="email" name="mailjet_sender_email" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('mailjet_sender_email') }}" placeholder="info@domain.com">
                        </div>

                        <label class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100 cursor-pointer group">
                            <div class="relative inline-flex items-center">
                                <input type="checkbox" name="mailjet_status" value="1" class="sr-only peer" {{ \App\Models\Setting::get('mailjet_status') ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </div>
                            <span class="text-sm font-bold text-slate-700 select-none">Mailjet Sistemini Aktifleştir</span>
                        </label>
                    </div>
                </div>
            @endif

            @if($active_tab == 'recaptcha')
                <div class="space-y-8">
                    <div class="border-b border-slate-100 pb-6">
                        <h3 class="text-xl font-display font-bold text-slate-900">Google reCAPTCHA v2 Ayarları</h3>
                        <p class="text-slate-500 text-sm mt-1">Formların güvenliği için Google reCAPTCHA v2 (I'm not a robot) anahtarlarını ekleyin.</p>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100 flex items-start gap-4 mb-6">
                            <i data-lucide="info" class="w-5 h-5 text-blue-600 shrink-0 mt-0.5"></i>
                            <div class="text-sm text-blue-800 leading-relaxed">
                                <p class="font-bold mb-1">Nasıl Alınır?</p>
                                <p>Google reCAPTCHA anahtarlarını <a href="https://www.google.com/recaptcha/admin" target="_blank" class="underline font-bold">buradan</a> oluşturabilirsiniz. Tip olarak <strong>reCAPTCHA v2 "Ben robot değilim"</strong> seçeneğini seçtiğinizden emin olun.</p>
                                <p class="mt-2">`.env` içinde <code class="font-mono text-xs bg-white/70 px-1.5 py-0.5 rounded">RECAPTCHA_SITE_KEY</code> ve <code class="font-mono text-xs bg-white/70 px-1.5 py-0.5 rounded">RECAPTCHA_SECRET_KEY</code> tanımlıysa widget otomatik aktif olur (panel anahtarlarının önüne geçer).</p>
                                @if(trim((string) config('services.recaptcha.site_key')) !== '' && trim((string) config('services.recaptcha.secret_key')) !== '')
                                    <p class="mt-2 font-bold text-emerald-700">Şu an .env anahtarlarıyla aktif.</p>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">reCAPTCHA Site Key</label>
                            <input type="text" name="recaptcha_site_key" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('recaptcha_site_key') }}" placeholder="6Ld...">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">reCAPTCHA Secret Key</label>
                            <input type="text" name="recaptcha_secret_key" class="w-full bg-slate-50 border border-slate-200 text-slate-900 py-3.5 px-5 rounded-xl focus:outline-none focus:border-blue-500/50 focus:bg-white transition-all" value="{{ \App\Models\Setting::get('recaptcha_secret_key') }}" placeholder="6Ld...">
                        </div>
                        
                        <label class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100 cursor-pointer group">
                            <div class="relative inline-flex items-center">
                                <input type="checkbox" name="recaptcha_status" value="1" class="sr-only peer" {{ \App\Models\Setting::get('recaptcha_status') ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </div>
                            <span class="text-sm font-bold text-slate-700 select-none">reCAPTCHA Sistemini Aktifleştir</span>
                        </label>
                    </div>
                </div>
            @endif

            @if($active_tab == 'urun-detay')
                @php
                    $faqRows = setting_json('product_detail_faqs');
                    if ($faqRows === []) {
                        $faqRows = [['question' => '', 'answer' => '']];
                    }
                    $shoeChart = setting_json('product_size_chart_shoes');
                    $apparelChart = setting_json('product_size_chart_apparel');
                    $shoeHeaders = $shoeChart['headers'] ?? ['EU', 'US', 'UK', 'Cm'];
                    $shoeRows = $shoeChart['rows'] ?? [['', '', '', '']];
                    $apparelHeaders = $apparelChart['headers'] ?? ['Beden', 'Göğüs', 'Bel', 'Kalça'];
                    $apparelRows = $apparelChart['rows'] ?? [['', '', '', '']];
                    if ($shoeRows === []) { $shoeRows = [['', '', '', '']]; }
                    if ($apparelRows === []) { $apparelRows = [['', '', '', '']]; }
                @endphp
                <div class="space-y-10">
                    <div class="border-b border-slate-100 pb-6">
                        <h3 class="text-xl font-display font-bold text-slate-900">Ürün Detay İçerikleri</h3>
                        <p class="text-slate-500 text-sm mt-1">Ürün detay sayfasındaki SSS, beden tablosu, kargo ve kurumsal blok metinlerini buradan yönetin.</p>
                    </div>

                    <div class="space-y-4" x-data="productFaqEditor({{ Js::from(array_values($faqRows)) }})">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider">SSS (Sıkça Sorulan Sorular)</h4>
                                <p class="text-xs text-slate-400 mt-1">Tüm ürün detay sayfalarında SSS sekmesinde gösterilir.</p>
                            </div>
                            <button type="button" @click="addRow()" class="inline-flex items-center gap-1.5 px-3 py-2 bg-slate-900 text-white rounded-xl text-[11px] font-bold hover:bg-slate-800 transition-colors">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i> Soru Ekle
                            </button>
                        </div>
                        <div class="space-y-4">
                            <template x-for="(row, index) in rows" :key="index">
                                <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-3">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400" x-text="'Soru ' + (index + 1)"></span>
                                        <button type="button" @click="removeRow(index)" class="text-slate-400 hover:text-red-500 text-xs font-bold">Sil</button>
                                    </div>
                                    <input type="text" :name="'product_faqs[' + index + '][question]'" x-model="rows[index].question" placeholder="Soru" class="w-full bg-white border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-sm focus:border-blue-500">
                                    <textarea :name="'product_faqs[' + index + '][answer]'" x-model="rows[index].answer" rows="3" placeholder="Cevap" class="w-full bg-white border border-slate-200 text-slate-900 py-3 px-4 rounded-xl text-sm focus:border-blue-500"></textarea>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 border-t border-slate-100 pt-8">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Kargo Başlık</label>
                            <input type="text" name="product_shipping_cargo_title" value="{{ \App\Models\Setting::get('product_shipping_cargo_title') }}" class="w-full bg-slate-50 border border-slate-200 py-3 px-4 rounded-xl text-sm">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Kargo Metin</label>
                            <textarea name="product_shipping_cargo_text" rows="3" class="w-full bg-slate-50 border border-slate-200 py-3 px-4 rounded-xl text-sm">{{ \App\Models\Setting::get('product_shipping_cargo_text') }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Teslimat Başlık</label>
                            <input type="text" name="product_shipping_delivery_title" value="{{ \App\Models\Setting::get('product_shipping_delivery_title') }}" class="w-full bg-slate-50 border border-slate-200 py-3 px-4 rounded-xl text-sm">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Teslimat Metin</label>
                            <textarea name="product_shipping_delivery_text" rows="3" class="w-full bg-slate-50 border border-slate-200 py-3 px-4 rounded-xl text-sm">{{ \App\Models\Setting::get('product_shipping_delivery_text') }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">İade Başlık</label>
                            <input type="text" name="product_shipping_return_title" value="{{ \App\Models\Setting::get('product_shipping_return_title') }}" class="w-full bg-slate-50 border border-slate-200 py-3 px-4 rounded-xl text-sm">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">İade Metin</label>
                            <textarea name="product_shipping_return_text" rows="3" class="w-full bg-slate-50 border border-slate-200 py-3 px-4 rounded-xl text-sm">{{ \App\Models\Setting::get('product_shipping_return_text') }}</textarea>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-8 space-y-4">
                        <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Kurumsal Promo Bloğu</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="text" name="product_promo_eyebrow" value="{{ \App\Models\Setting::get('product_promo_eyebrow') }}" placeholder="Eyebrow" class="w-full bg-slate-50 border border-slate-200 py-3 px-4 rounded-xl text-sm">
                            <input type="text" name="product_promo_cta" value="{{ \App\Models\Setting::get('product_promo_cta') }}" placeholder="CTA" class="w-full bg-slate-50 border border-slate-200 py-3 px-4 rounded-xl text-sm">
                            <input type="text" name="product_promo_title" value="{{ \App\Models\Setting::get('product_promo_title') }}" placeholder="Başlık" class="w-full bg-slate-50 border border-slate-200 py-3 px-4 rounded-xl text-sm md:col-span-2">
                            <textarea name="product_promo_text" rows="2" placeholder="Açıklama" class="w-full bg-slate-50 border border-slate-200 py-3 px-4 rounded-xl text-sm md:col-span-2">{{ \App\Models\Setting::get('product_promo_text') }}</textarea>
                            <input type="text" name="product_promo_item1_title" value="{{ \App\Models\Setting::get('product_promo_item1_title') }}" placeholder="Madde 1 başlık" class="w-full bg-slate-50 border border-slate-200 py-3 px-4 rounded-xl text-sm">
                            <input type="text" name="product_promo_item1_text" value="{{ \App\Models\Setting::get('product_promo_item1_text') }}" placeholder="Madde 1 metin" class="w-full bg-slate-50 border border-slate-200 py-3 px-4 rounded-xl text-sm">
                            <input type="text" name="product_promo_item2_title" value="{{ \App\Models\Setting::get('product_promo_item2_title') }}" placeholder="Madde 2 başlık" class="w-full bg-slate-50 border border-slate-200 py-3 px-4 rounded-xl text-sm">
                            <input type="text" name="product_promo_item2_text" value="{{ \App\Models\Setting::get('product_promo_item2_text') }}" placeholder="Madde 2 metin" class="w-full bg-slate-50 border border-slate-200 py-3 px-4 rounded-xl text-sm">
                            <input type="text" name="product_promo_item3_title" value="{{ \App\Models\Setting::get('product_promo_item3_title') }}" placeholder="Madde 3 başlık" class="w-full bg-slate-50 border border-slate-200 py-3 px-4 rounded-xl text-sm">
                            <input type="text" name="product_promo_item3_text" value="{{ \App\Models\Setting::get('product_promo_item3_text') }}" placeholder="Madde 3 metin" class="w-full bg-slate-50 border border-slate-200 py-3 px-4 rounded-xl text-sm">
                        </div>
                    </div>

                    @foreach([
                        ['key' => 'shoes', 'label' => 'Ayakkabı Beden Tablosu', 'headers' => $shoeHeaders, 'rows' => $shoeRows, 'note' => $shoeChart['note'] ?? '', 'footer' => $shoeChart['footer'] ?? ''],
                        ['key' => 'apparel', 'label' => 'Giyim Beden Tablosu', 'headers' => $apparelHeaders, 'rows' => $apparelRows, 'note' => $apparelChart['note'] ?? '', 'footer' => $apparelChart['footer'] ?? ''],
                    ] as $chart)
                    <div class="border-t border-slate-100 pt-8 space-y-4" x-data="sizeChartEditor({{ Js::from(array_values($chart['headers'])) }}, {{ Js::from(array_values($chart['rows'])) }})">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ $chart['label'] }}</h4>
                                <p class="text-xs text-slate-400 mt-1">Beden tablosu modalında gösterilir.</p>
                            </div>
                            <button type="button" @click="addRow()" class="inline-flex items-center gap-1.5 px-3 py-2 bg-slate-900 text-white rounded-xl text-[11px] font-bold">Satır Ekle</button>
                        </div>
                        <textarea name="{{ $chart['key'] }}_note" rows="2" placeholder="Tablo üstü not" class="w-full bg-slate-50 border border-slate-200 py-3 px-4 rounded-xl text-sm">{{ $chart['note'] }}</textarea>
                        <div class="grid grid-cols-4 gap-2">
                            <template x-for="(header, hIndex) in headers" :key="'h'+hIndex">
                                <input type="text" :name="'{{ $chart['key'] }}_headers[' + hIndex + ']'" x-model="headers[hIndex]" class="bg-white border border-slate-200 py-2 px-3 rounded-lg text-xs font-bold uppercase">
                            </template>
                        </div>
                        <div class="space-y-2">
                            <template x-for="(row, rIndex) in rows" :key="'r'+rIndex">
                                <div class="grid grid-cols-[1fr_1fr_1fr_1fr_auto] gap-2 items-center">
                                    <template x-for="(cell, cIndex) in row" :key="'c'+rIndex+'-'+cIndex">
                                        <input type="text" :name="'{{ $chart['key'] }}_rows[' + rIndex + '][' + cIndex + ']'" x-model="rows[rIndex][cIndex]" class="bg-slate-50 border border-slate-200 py-2 px-3 rounded-lg text-sm">
                                    </template>
                                    <button type="button" @click="removeRow(rIndex)" class="text-slate-400 hover:text-red-500 text-xs font-bold px-2">Sil</button>
                                </div>
                            </template>
                        </div>
                        <input type="text" name="{{ $chart['key'] }}_footer" value="{{ $chart['footer'] }}" placeholder="Tablo altı not" class="w-full bg-slate-50 border border-slate-200 py-3 px-4 rounded-xl text-sm">
                    </div>
                    @endforeach
                </div>
            @endif

            @if($active_tab == 'kodlar')
                <div class="space-y-8">
                    <div class="border-b border-slate-100 pb-6">
                        <h3 class="text-xl font-display font-bold text-slate-900">Takip ve Analiz Kodları</h3>
                        <p class="text-slate-500 text-sm mt-1">Google Analytics, GTM, FB Pixel veya özel CSS/JS kodlarını ekleyin.</p>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Header Kodları ( <span class="text-blue-500">&lt;/head&gt;</span> öncesi)</label>
                            <textarea name="header_scripts" rows="6" class="w-full bg-slate-900 border border-slate-800 text-emerald-400 font-mono text-xs py-5 px-6 rounded-2xl focus:outline-none focus:border-blue-500/50 transition-all shadow-inner" placeholder="<!-- GTM TAGS HERE -->">{{ \App\Models\Setting::get('header_scripts') }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Body Kodları ( <span class="text-blue-500">&lt;body&gt;</span> sonrası)</label>
                            <textarea name="body_scripts" rows="6" class="w-full bg-slate-900 border border-slate-800 text-emerald-400 font-mono text-xs py-5 px-6 rounded-2xl focus:outline-none focus:border-blue-500/50 transition-all shadow-inner" placeholder="<!-- FB PIXEL HERE -->">{{ \App\Models\Setting::get('body_scripts') }}</textarea>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-12 flex justify-end">
                <button type="submit" class="px-10 py-4 bg-blue-600 text-white font-extrabold rounded-2xl shadow-lg shadow-blue-600/20 hover:bg-blue-700 hover:-translate-y-1 transition-all">
                    Değişiklikleri Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@push('scripts')
<script>
    function onlyDigits(value, maxLength) {
        return value.replace(/\D+/g, '').slice(0, maxLength);
    }

    function productFaqEditor(initialRows) {
        return {
            rows: (initialRows && initialRows.length) ? initialRows.map(function (r) {
                return {
                    question: r.question || r.s || '',
                    answer: r.answer || r.c || '',
                };
            }) : [{ question: '', answer: '' }],
            addRow: function () { this.rows.push({ question: '', answer: '' }); },
            removeRow: function (index) {
                this.rows.splice(index, 1);
                if (!this.rows.length) this.rows.push({ question: '', answer: '' });
            },
        };
    }

    function sizeChartEditor(initialHeaders, initialRows) {
        var colCount = (initialHeaders && initialHeaders.length) ? initialHeaders.length : 4;
        return {
            headers: (initialHeaders && initialHeaders.length) ? initialHeaders.slice() : Array(colCount).fill(''),
            rows: (initialRows && initialRows.length) ? initialRows.map(function (r) {
                var row = r.slice();
                while (row.length < colCount) row.push('');
                return row.slice(0, colCount);
            }) : [Array(colCount).fill('')],
            addRow: function () { this.rows.push(Array(this.headers.length).fill('')); },
            removeRow: function (index) {
                this.rows.splice(index, 1);
                if (!this.rows.length) this.rows.push(Array(this.headers.length).fill(''));
            },
        };
    }

    document.querySelectorAll('[data-phone-format="digits"]').forEach(function (input) {
        input.addEventListener('input', function () {
            this.value = onlyDigits(this.value, 15);
        });
        if (input.value) {
            input.value = onlyDigits(input.value, 15);
        }
    });

    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById(previewId);
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    // Handle div-based placeholder
                    preview.innerHTML = `<img src="${e.target.result}" class="w-10 h-10 object-contain">`;
                    preview.classList.remove('text-slate-400');
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
