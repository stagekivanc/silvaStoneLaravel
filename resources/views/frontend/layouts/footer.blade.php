@php
  $lang = app()->getLocale();
  $homeUrl = route('home', ['lang' => $lang]);
  $contactUrl = route('module.dispatcher', ['lang' => $lang, 'module' => ($pages['contact'] ?? 'iletisim')]);
  $storesUrl = route('module.dispatcher', ['lang' => $lang, 'module' => ($pages['stores'] ?? 'magazalar')]);
  $projectsUrl = route('module.dispatcher', ['lang' => $lang, 'module' => ($pages['projects'] ?? 'projeler')]);
  $productsUrl = route('module.dispatcher', ['lang' => $lang, 'module' => ($pages['products'] ?? 'urunler')]);
  $catalogUrl = silva_url(\App\Models\Setting::get('catalog_url', 'silvastone/assets/silva-stone-2026-katalog.pdf'));
  $phoneRaw = \App\Models\Setting::get('phone_raw', '+908503460226');
  $phoneLabel = \App\Models\Setting::get('phone', '+90 850 346 02 26');
  $email = \App\Models\Setting::get('email', 'bilgi@acarkon.com');
  $address = \App\Models\Setting::get('address', 'Horozluhan Mahallesi Hotamış Sk. No:49 Selçuklu / Konya');
  $wa = preg_replace('/\D+/', '', \App\Models\Setting::get('whatsapp', '908503460226'));
  $ctaTitle = \App\Models\Setting::get('footer_cta_title', 'Mekânınız için doğru yüzeyi seçin');
  $ctaText = \App\Models\Setting::get('footer_cta_text', 'Katalogu inceleyin veya en yakın Acarkon Store’dan numune alın.');
  $brandText = \App\Models\Setting::get('footer_brand_text', 'Silva Stone, Acarkon Orman Ürünleri ürün ailesinin dekoratif taş duvar paneli markasıdır.');
@endphp

<footer class="site-footer bg-void text-white/55">
  <div class="border-b border-white/10">
    <div class="site-footer-top mx-auto flex max-w-site flex-col gap-6 px-5 py-10 md:flex-row md:items-center md:justify-between md:px-8 md:py-12">
      <div>
        <p class="text-[clamp(1.5rem,3vw,2.25rem)] font-light tracking-[-0.03em] text-white/90 md:leading-snug">{{ $ctaTitle }}</p>
        <p class="mt-2 max-w-md text-sm font-light text-white/45">{{ $ctaText }}</p>
      </div>
      <div class="site-footer-cta flex flex-wrap gap-3">
        <a href="{{ $catalogUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-3 text-[13px] font-medium text-ink transition hover:bg-mist">
          <i class="bx bx-book-open text-base"></i>
          <span>Online katalog</span>
        </a>
        <a href="{{ $storesUrl }}" class="inline-flex items-center gap-2 rounded-full border border-white/20 px-5 py-3 text-[13px] font-medium text-white transition hover:border-white/40 hover:bg-white/5">
          <i class="bx bx-map text-base"></i>
          <span>Showroom</span>
        </a>
        <a href="{{ $contactUrl }}" class="inline-flex items-center gap-2 rounded-full border border-white/20 px-5 py-3 text-[13px] font-medium text-white transition hover:border-white/40 hover:bg-white/5">
          <i class="bx bx-envelope text-base"></i>
          <span>İletişim</span>
        </a>
      </div>
    </div>
  </div>

  <div class="mx-auto max-w-site px-5 pt-14 md:px-8 md:pt-16">
    <div class="grid gap-12 border-b border-white/10 pb-14 lg:grid-cols-12 lg:gap-10">
      <div class="site-footer-brand lg:col-span-4">
        <a href="{{ $homeUrl }}" class="inline-flex flex-col items-start">
          <img src="{{ silva_asset('assets/silvalogo-white.svg') }}" alt="Silva Stone" class="h-14 w-auto md:h-14" />
        </a>
        <p class="mt-5 max-w-sm text-sm font-light leading-relaxed">{{ $brandText }}</p>
        <div class="mt-6 flex flex-wrap gap-2">
          <a href="{{ \App\Models\Setting::get('instagram', 'https://www.instagram.com/acarkon/') }}" target="_blank" rel="noopener" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/15 text-white/70 transition hover:border-white/35 hover:text-white" aria-label="Instagram">
            <i class="bx bxl-instagram text-lg"></i>
          </a>
          <a href="{{ \App\Models\Setting::get('facebook', 'https://www.facebook.com/') }}" target="_blank" rel="noopener" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/15 text-white/70 transition hover:border-white/35 hover:text-white" aria-label="Facebook">
            <i class="bx bxl-facebook text-lg"></i>
          </a>
          <a href="{{ \App\Models\Setting::get('youtube', 'https://www.youtube.com/') }}" target="_blank" rel="noopener" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/15 text-white/70 transition hover:border-white/35 hover:text-white" aria-label="YouTube">
            <i class="bx bxl-youtube text-lg"></i>
          </a>
          <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/15 text-white/70 transition hover:border-white/35 hover:text-white" aria-label="WhatsApp">
            <i class="bx bxl-whatsapp text-lg"></i>
          </a>
        </div>
      </div>

      <div class="site-footer-navs grid grid-cols-2 gap-8 sm:grid-cols-3 lg:col-span-5">
        <div>
          <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-white">Silva Stone</p>
          <ul class="mt-4 space-y-2.5 text-sm font-light">
            <li><a href="{{ $homeUrl }}#neden" class="transition hover:text-white">Özellikler</a></li>
            <li><a href="{{ $productsUrl }}" class="transition hover:text-white">Koleksiyon</a></li>
            <li><a href="{{ $homeUrl }}#alanlar" class="transition hover:text-white">Mekânlar</a></li>
            <li><a href="{{ $catalogUrl }}" target="_blank" rel="noopener" class="transition hover:text-white">Online katalog</a></li>
            <li><a href="{{ $projectsUrl }}" class="transition hover:text-white">Projeler</a></li>
          </ul>
        </div>
        <div>
          <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-white">Satış</p>
          <ul class="mt-4 space-y-2.5 text-sm font-light">
            <li><a href="{{ $storesUrl }}" class="transition hover:text-white">Showroom</a></li>
            <li><a href="https://acarkon.com/tr/pages/satis-noktalari" target="_blank" rel="noopener" class="transition hover:text-white">Acarkon Store</a></li>
            <li><a href="https://acarkon.com/tr/pages/bayi-basvuru" target="_blank" rel="noopener" class="transition hover:text-white">Bayi Ol</a></li>
            <li><a href="{{ $contactUrl }}" class="transition hover:text-white">İletişim</a></li>
          </ul>
        </div>
        <div>
          <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-white">Kurumsal</p>
          <ul class="mt-4 space-y-2.5 text-sm font-light">
            <li><a href="https://acarkon.com/tr/pages/hakkimizda" target="_blank" rel="noopener" class="transition hover:text-white">Hakkımızda</a></li>
            <li><a href="https://acarkon.com/tr/pages/markalar" target="_blank" rel="noopener" class="transition hover:text-white">Markalar</a></li>
            <li><a href="{{ m_url('contracts') }}" class="transition hover:text-white">Sözleşmeler</a></li>
            <li><a href="https://acarkon.com" target="_blank" rel="noopener" class="transition hover:text-white">acarkon.com</a></li>
          </ul>
        </div>
      </div>

      <div class="site-footer-contact lg:col-span-3">
        <p class="text-[11px] font-medium uppercase tracking-[0.16em] text-white">İletişim</p>
        <ul class="mt-4 space-y-4 text-sm font-light">
          <li class="flex gap-3">
            <i class="bx bx-map mt-0.5 text-base text-white/40"></i>
            <span>{{ $address }}</span>
          </li>
          <li class="flex gap-3">
            <i class="bx bx-phone mt-0.5 text-base text-white/40"></i>
            <a href="tel:{{ $phoneRaw }}" class="text-white transition hover:opacity-80">{{ $phoneLabel }}</a>
          </li>
          <li class="flex gap-3">
            <i class="bx bx-envelope mt-0.5 text-base text-white/40"></i>
            <a href="mailto:{{ $email }}" class="transition hover:text-white">{{ $email }}</a>
          </li>
        </ul>
      </div>
    </div>

    <div class="site-footer-legal flex flex-col gap-5 py-7 md:flex-row md:items-center md:justify-between">
      <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-5">
        <p class="text-xs font-light">© <span id="year">{{ date('Y') }}</span> {{ \App\Models\Setting::get('company_name', 'Acarkon Orman Ürünleri') }} · Silva Stone</p>
          <div class="flex flex-wrap gap-4 text-xs font-light">
            <a href="{{ m_url('privacy-policy') }}" class="transition hover:text-white">Gizlilik</a>
            <a href="{{ m_url('cookie-policy') }}" class="transition hover:text-white">Çerezler</a>
            <a href="{{ m_url('kvkk') }}" class="transition hover:text-white">KVKK</a>
          </div>
      </div>
      <a href="https://stagedijital.com" target="_blank" rel="noopener" class="inline-flex items-center gap-3 text-xs font-light transition hover:text-white">
        <img src="{{ silva_asset('assets/stage-logo.svg') }}" alt="Stage Dijital" class="stage-logo h-5 w-auto" />
      </a>
    </div>
  </div>

  <div class="flex justify-center px-5 py-8 md:py-10">
    <a href="https://acarkon.com" target="_blank" rel="noopener" class="inline-flex opacity-70 transition hover:opacity-100">
      <img src="{{ silva_asset('assets/acarkon-logo-dark.png') }}" alt="Acarkon" class="logo-on-dark h-2.5 w-auto md:h-6" />
    </a>
  </div>
</footer>
