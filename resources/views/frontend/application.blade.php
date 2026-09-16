@extends('frontend.layouts.app')

@section('title', data_get($page ?? null, 'seo_title') ?: 'B2B & Toptan Satış | Silva Stone')
@section('body_class', 'b2bPage')
@section('header_dark', true)
@section('footer_no_gap', true)

@section('content')
@php
    $lang = app()->getLocale();
    $homeUrl = route('home', ['lang' => $lang]);
    $referencesUrl = route('module.dispatcher', ['lang' => $lang, 'module' => 'referanslar']);
    $contactUrl = route('module.dispatcher', ['lang' => $lang, 'module' => 'iletisim']);
    $form = $quote['form'] ?? [];
    $sectorOptions = $form['sectors'] ?? [];
    $materialOptions = $form['materials'] ?? [];
    $requestOptions = $form['requests'] ?? [];
@endphp

<main class="w-full bg-[#0a0a0a] text-white min-h-screen">

    <section class="w-full border-b border-white/[0.06]">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-8 py-16 md:py-28 text-center">
            <nav class="b2b-reveal text-xs text-white/30 uppercase tracking-[0.15em] mb-10">
                <a href="{{ $homeUrl }}" class="hover:text-brand-red transition-colors">Ana Sayfa</a>
                <span class="mx-2">/</span>
                <span class="text-white/50">B2B & Toptan</span>
            </nav>

            <div class="max-w-3xl mx-auto">
                <span class="b2b-reveal inline-block text-[10px] font-bold text-brand-red uppercase tracking-[0.35em] mb-5" data-delay="1">Kurumsal Çözümler</span>
                <h1 class="b2b-reveal text-4xl md:text-5xl lg:text-6xl font-light text-white tracking-tight mb-6" data-delay="2">B2B & Toptan Satış</h1>
                <p class="b2b-reveal text-sm md:text-base text-white/40 font-light leading-relaxed mx-auto" data-delay="3">
                    Fabrikadan kurumsal müşteriye doğrudan tedarik. Logo baskı, özel dikim ve toplu siparişlerde Silva Stone yanınızda.
                </p>
            </div>

            <div id="b2bStats" class="mt-14 md:mt-20 grid grid-cols-1 sm:grid-cols-3 gap-6 md:gap-10 max-w-3xl mx-auto">
                <div class="b2b-stat py-2">
                    <span class="b2b-stat-num block text-4xl md:text-5xl font-black text-brand-red tracking-tight mb-3" data-target="50" data-suffix="+">0+</span>
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-white/30">Min. Toptan Adet</span>
                </div>
                <div class="b2b-stat py-2">
                    <span class="b2b-stat-num block text-4xl md:text-5xl font-black text-brand-red tracking-tight mb-3" data-target="500" data-suffix="+">0+</span>
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-white/30">Kurumsal Referans</span>
                </div>
                <div class="b2b-stat py-2">
                    <span class="b2b-stat-num block text-4xl md:text-5xl font-black text-brand-red tracking-tight mb-3" data-target="48" data-suffix="s">0s</span>
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-white/30">Teklif Dönüş Süresi</span>
                </div>
            </div>
        </div>
    </section>

    <section class="w-full py-16 md:py-24 border-b border-white/[0.06]">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-8">
            <div class="b2b-reveal text-center max-w-xl mx-auto mb-12 md:mb-16">
                <span class="inline-block text-[10px] font-bold text-brand-red uppercase tracking-[0.35em] mb-4">Neden Silva Stone?</span>
                <h2 class="text-2xl md:text-3xl font-light tracking-tight">Kurumsal Avantajlar</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
                @foreach([
                    ['icon' => 'bx-purchase-tag', 'title' => 'Toptan Fiyat', 'text' => 'Adet bazlı özel fiyatlandırma ve proje indirimleri.'],
                    ['icon' => 'bx-brush', 'title' => 'Logo & Baskı', 'text' => 'Nakış, serigrafi ve transfer baskı ile kurumsal kimlik.'],
                    ['icon' => 'bx-cut', 'title' => 'Özel Dikim', 'text' => 'Sektörünüze özel model, kumaş ve renk seçenekleri.'],
                    ['icon' => 'bx-receipt', 'title' => 'Faturalı Satış', 'text' => 'Kurumsal fatura, vadeli ödeme ve sözleşmeli tedarik.'],
                ] as $i => $a)
                <div class="b2b-reveal group p-6 md:p-7 rounded-2xl border border-white/[0.08] bg-white/[0.02] hover:bg-white/[0.05] hover:border-brand-red/30 transition-all duration-300" data-delay="{{ ($i % 4) + 1 }}">
                    <i class="bx {{ $a['icon'] }} text-2xl text-brand-red mb-5 inline-block group-hover:scale-110 transition-transform"></i>
                    <h3 class="text-xs font-bold uppercase tracking-[0.12em] text-white mb-2">{{ $a['title'] }}</h3>
                    <p class="text-sm text-white/40 leading-relaxed">{{ $a['text'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="w-full py-16 md:py-24 border-b border-white/[0.06] relative overflow-hidden">
        <div class="absolute inset-0 opacity-[0.025] pointer-events-none" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 28px 28px;"></div>
        <div class="max-w-[1600px] mx-auto px-4 sm:px-8 relative">
            <div class="b2b-reveal text-center max-w-2xl mx-auto mb-14 md:mb-16">
                <span class="inline-block text-[10px] font-bold text-brand-red uppercase tracking-[0.35em] mb-4">Süreç</span>
                <h2 class="text-2xl md:text-4xl font-light tracking-tight mb-4">Nasıl Çalışır?</h2>
                <p class="text-sm text-white/40 font-light leading-relaxed">Talepten teslimata dört adımda kurumsal tedarik süreciniz.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-5">
                @foreach([
                    ['icon' => 'bx-conversation', 'title' => 'Talep & Keşif', 'detail' => 'İhtiyaçlarınızı, adet ve bütçenizi paylaşın. Sektörünüze uygun ürün önerileri sunalım.', 'time' => '~1 gün'],
                    ['icon' => 'bx-file-blank', 'title' => 'Teklif & Numune', 'detail' => 'Detaylı fiyat teklifi hazırlanır. Talep halinde numune gönderimi yapılır.', 'time' => '1–2 gün'],
                    ['icon' => 'bx-cog', 'title' => 'Üretim & Baskı', 'detail' => 'Onay sonrası üretim ve logo uygulama süreci başlar.', 'time' => '5–15 gün'],
                    ['icon' => 'bx-package', 'title' => 'Teslimat', 'detail' => 'Toplu sevkiyat veya parça parça teslimat planı uygulanır.', 'time' => '1–5 gün'],
                ] as $i => $s)
                <div class="b2b-reveal group relative p-6 md:p-7 rounded-2xl border border-white/[0.08] bg-white/[0.02] hover:bg-white/[0.05] hover:border-brand-red/30 transition-all duration-300" data-delay="{{ ($i % 4) + 1 }}">
                    <div class="flex items-start justify-between mb-6">
                        <span class="w-12 h-12 rounded-xl bg-brand-red/10 border border-brand-red/20 flex items-center justify-center text-brand-red text-xl group-hover:bg-brand-red group-hover:text-white transition-all duration-300">
                            <i class="bx {{ $s['icon'] }}"></i>
                        </span>
                        <span class="text-[10px] font-black text-white/15 tracking-widest">{{ str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <h3 class="text-sm font-bold uppercase tracking-[0.12em] text-white mb-2 group-hover:text-brand-red transition-colors">{{ $s['title'] }}</h3>
                    <p class="text-sm text-white/40 leading-relaxed mb-5">{{ $s['detail'] }}</p>
                    <span class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-[0.15em] text-white/25">
                        <i class="bx bx-time-five text-brand-red/70"></i>{{ $s['time'] }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="w-full py-16 md:py-24 border-b border-white/[0.06]">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-8 text-center">
            <h2 class="b2b-reveal text-xs font-bold tracking-[0.3em] uppercase text-white/30 mb-10">Hizmet Verdiğimiz Sektörler</h2>
            <div class="b2b-reveal flex flex-wrap justify-center gap-3 mb-12" data-delay="1">
                @foreach(['Otomotiv', 'Sağlık', 'Gıda & Restoran', 'İnşaat', 'Lojistik', 'Perakende', 'Otel & Turizm', 'Fabrika & Üretim'] as $sector)
                    <span class="px-5 py-2.5 border border-white/10 bg-white/[0.03] text-xs font-bold tracking-[0.1em] uppercase text-white/50 hover:border-brand-red/40 hover:text-white transition-colors">{{ $sector }}</span>
                @endforeach
            </div>
            <a href="{{ $referencesUrl }}" class="b2b-reveal inline-flex items-center gap-2 text-xs font-bold tracking-[0.15em] uppercase text-brand-red hover:text-white transition-colors" data-delay="2">
                Referanslarımızı İnceleyin <i class="bx bx-right-arrow-alt text-lg"></i>
            </a>
        </div>
    </section>

    <section class="w-full py-20 md:py-32 pb-24 md:pb-36">
        <div class="max-w-xl mx-auto px-4 sm:px-8">
            <div class="b2b-reveal text-center mb-10">
                <span class="inline-block text-[10px] font-bold text-brand-red uppercase tracking-[0.35em] mb-4">Teklif Alın</span>
                <h2 class="text-2xl md:text-3xl font-light tracking-tight mb-3">Projenizi Başlatalım</h2>
                <p class="text-sm text-white/40 leading-relaxed">Formu doldurun, B2B ekibimiz 48 saat içinde dönüş yapsın.</p>
            </div>

            @if(session('quote_success'))
                <div class="b2b-reveal b2b-alert b2b-alert-success mb-6" role="status">{{ session('quote_success') }}</div>
            @endif
            @if($errors->any())
                <div class="b2b-reveal b2b-alert b2b-alert-error mb-6" role="alert">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="b2b-reveal p-8 md:p-10 rounded-2xl border border-white/10 bg-white/[0.03]" data-delay="1">
                <form action="{{ route('quote.store', ['lang' => app()->getLocale()]) }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="text" name="website" value="" class="b2b-honeypot" tabindex="-1" autocomplete="off" aria-hidden="true">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-white/35 mb-2">Firma Adı *</label>
                            <input type="text" name="company" required class="b2b-input w-full px-4 py-3 text-sm transition-colors" value="{{ old('company') }}" placeholder="Firma ünvanı">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-white/35 mb-2">Telefon</label>
                            <input type="tel" name="phone" class="b2b-input w-full px-4 py-3 text-sm transition-colors" value="{{ old('phone') }}" placeholder="0(5xx) xxx xx xx">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-white/35 mb-2">E-posta *</label>
                        <input type="email" name="email" required class="b2b-input w-full px-4 py-3 text-sm transition-colors" value="{{ old('email') }}" placeholder="ornek@sirket.com">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-white/35 mb-2">Sektör</label>
                            <select name="sector" class="b2b-input w-full px-4 py-3 text-sm transition-colors">
                                <option value="">Seçiniz</option>
                                @foreach($sectorOptions as $option)
                                    <option value="{{ $option['value'] ?? '' }}" @selected(old('sector') === ($option['value'] ?? null))>{{ $option['label'] ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-white/35 mb-2">Tahmini Adet</label>
                            <select name="material" class="b2b-input w-full px-4 py-3 text-sm transition-colors">
                                <option value="">Seçiniz</option>
                                @foreach($materialOptions as $option)
                                    <option value="{{ $option['value'] ?? '' }}" @selected(old('material') === ($option['value'] ?? null))>{{ $option['label'] ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-white/35 mb-2">Talep Tipi</label>
                        <select name="request" class="b2b-input w-full px-4 py-3 text-sm transition-colors">
                            <option value="">Seçiniz</option>
                            @foreach($requestOptions as $option)
                                <option value="{{ $option['value'] ?? '' }}" @selected(old('request') === ($option['value'] ?? null))>{{ $option['label'] ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>

                    @include('frontend.partials.recaptcha')

                    <button type="submit" class="w-full py-4 bg-brand-red text-white text-xs font-bold tracking-[0.2em] uppercase hover:bg-white hover:text-black transition-colors">
                        Teklif Talep Et
                    </button>
                </form>
                <p class="text-[11px] text-white/25 mt-6 text-center">
                    veya <a href="mailto:satis@turksantekstil.com" class="text-brand-red hover:text-white transition-colors">satis@turksantekstil.com</a>
                </p>
            </div>

            <ul class="b2b-reveal mt-10 flex flex-wrap justify-center gap-x-8 gap-y-3 text-[11px] text-white/30 uppercase tracking-[0.12em]" data-delay="2">
                <li class="flex items-center gap-2"><i class="bx bx-check text-brand-red"></i> Ücretsiz teklif</li>
                <li class="flex items-center gap-2"><i class="bx bx-check text-brand-red"></i> Numune imkanı</li>
                <li class="flex items-center gap-2"><i class="bx bx-check text-brand-red"></i> Vadeli ödeme</li>
            </ul>
        </div>
    </section>

</main>
@endsection

@push('styles')
<style>
    .b2b-reveal {
        opacity: 0;
        transform: translateY(36px);
        transition: opacity 0.75s cubic-bezier(0.22, 1, 0.36, 1), transform 0.75s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .b2b-reveal.is-visible {
        opacity: 1;
        transform: translateY(0);
    }
    .b2b-reveal[data-delay="1"] { transition-delay: 0.08s; }
    .b2b-reveal[data-delay="2"] { transition-delay: 0.16s; }
    .b2b-reveal[data-delay="3"] { transition-delay: 0.24s; }
    .b2b-reveal[data-delay="4"] { transition-delay: 0.32s; }

    .b2b-input {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.1);
        color: #fff;
    }
    .b2b-input::placeholder { color: rgba(255,255,255,0.25); }
    .b2b-input:focus {
        outline: none;
        border-color: #E50914;
        background: rgba(255,255,255,0.06);
    }
    .b2b-input option { background: #111; color: #fff; }

    .b2b-honeypot { position: absolute !important; left: -9999px !important; width: 1px !important; height: 1px !important; overflow: hidden !important; }
    .b2b-alert { padding: 14px 16px; border-radius: 10px; font-size: 13px; line-height: 1.5; }
    .b2b-alert ul { margin: 0; padding-left: 18px; }
    .b2b-alert-success { background: #e9f8ef; color: #126b35; }
    .b2b-alert-error { background: #fff0f0; color: #a61414; }

    .b2b-stat {
        opacity: 0;
        transform: translateY(24px);
        transition: opacity 0.6s ease, transform 0.6s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .b2b-stat.is-visible {
        opacity: 1;
        transform: translateY(0);
    }
    .b2b-stat:nth-child(1) { transition-delay: 0.1s; }
    .b2b-stat:nth-child(2) { transition-delay: 0.25s; }
    .b2b-stat:nth-child(3) { transition-delay: 0.4s; }
    .b2b-stat-num { display: inline-block; font-variant-numeric: tabular-nums; }

    .formRecaptcha { margin-top: 6px; }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const reveals = document.querySelectorAll('.b2b-reveal');
    if (reveals.length) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('is-visible');
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        reveals.forEach(el => observer.observe(el));
    }

    const statsWrap = document.getElementById('b2bStats');
    if (!statsWrap) return;

    let statsAnimated = false;
    function easeOutQuart(t) { return 1 - Math.pow(1 - t, 4); }

    function runCounters() {
        statsWrap.querySelectorAll('.b2b-stat-num').forEach(el => {
            const target = parseInt(el.dataset.target || '0', 10);
            const suffix = el.dataset.suffix || '';
            const duration = 1800;
            const start = performance.now();

            function tick(now) {
                const progress = Math.min((now - start) / duration, 1);
                const value = Math.round(easeOutQuart(progress) * target);
                el.textContent = value + suffix;
                if (progress < 1) requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
        });
    }

    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting || statsAnimated) return;
            statsAnimated = true;
            statsWrap.querySelectorAll('.b2b-stat').forEach(el => el.classList.add('is-visible'));
            runCounters();
        });
    }, { threshold: 0.3 });

    statsObserver.observe(statsWrap);
})();
</script>
@endpush
