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
@endphp

<header id="site-header" class="fixed inset-x-0 top-0 z-[60] transition-all duration-500">
  <div class="header-bar relative mx-auto grid max-w-[1440px] grid-cols-3 items-center px-4 py-4 md:px-8 md:py-5">
    <div class="menu-wrap relative justify-self-start">
      <button type="button" id="menu-toggle" class="pill-btn group inline-flex items-center gap-2 rounded-full px-5 py-3 text-[14px] font-medium md:px-6 md:text-[15px]" aria-expanded="false" aria-controls="nav-dropdown">
        <span>Menu</span>
        <i class="bx bx-grid-alt text-[17px] transition duration-300" id="menu-toggle-icon"></i>
                </button>

      <div id="nav-dropdown" class="nav-dropdown" aria-hidden="true">
        <div class="nav-drop-head">
          <span>Menu</span>
          <button type="button" class="nav-drop-close" aria-label="Kapat">
            <i class="bx bx-x"></i>
                    </button>
        </div>
        <nav class="nav-dropdown-inner">
          <a href="{{ $homeUrl }}#neden" class="nav-drop-link">Özellikler</a>
          <a href="{{ $productsUrl }}" class="nav-drop-link">Koleksiyon</a>
          <a href="{{ $storesUrl }}" class="nav-drop-link">Showroom</a>
          <a href="{{ $projectsUrl }}" class="nav-drop-link">Projeler</a>
          <a href="{{ $contactUrl }}" class="nav-drop-link">İletişim</a>
          <a href="{{ $catalogUrl }}" target="_blank" rel="noopener" class="nav-drop-link nav-drop-link--muted">Online katalog</a>
          <div class="nav-drop-mobile">
            <p class="nav-drop-label">Dil</p>
            <div class="nav-drop-langs" role="group" aria-label="Dil">
              <a href="{{ route('home', ['lang' => 'tr']) }}" class="lang-btn {{ $lang === 'tr' ? 'is-active' : '' }}" data-lang="tr">TR</a>
              <a href="{{ route('home', ['lang' => 'en']) }}" class="lang-btn {{ $lang === 'en' ? 'is-active' : '' }}" data-lang="en">EN</a>
            </div>
            <a href="{{ $contactUrl }}" class="nav-drop-cta">Teklif Al</a>
            <a href="tel:{{ $phoneRaw }}" class="nav-drop-phone">{{ $phoneLabel }}</a>
                </div>
</nav>
        </div>
    </div>

    <a href="{{ $homeUrl }}" class="header-brand justify-self-center flex flex-col items-center leading-none">
      <img src="{{ silva_asset('assets/silvalogo-white.svg') }}" alt="Silva Stone" class="logo-header-light h-14 w-auto md:h-14" />
      <img src="{{ silva_asset('assets/silvalogo.svg') }}" alt="Silva Stone" class="logo-header-dark hidden h-14 w-auto md:h-14" />
    </a>

    <div class="header-actions flex items-center justify-self-end gap-2 md:gap-2.5">
      <div class="pill-btn header-lang flex overflow-hidden rounded-full text-[13px] font-medium tracking-wide md:text-[14px]" role="group" aria-label="Dil">
        <a href="{{ route('home', ['lang' => 'tr']) }}" class="lang-btn {{ $lang === 'tr' ? 'is-active' : '' }} px-3 py-3 md:px-4" data-lang="tr">TR</a>
        <a href="{{ route('home', ['lang' => 'en']) }}" class="lang-btn {{ $lang === 'en' ? 'is-active' : '' }} px-3 py-3 md:px-4" data-lang="en">EN</a>
            </div>
      <a href="{{ $contactUrl }}" class="pill-btn header-cta inline-flex items-center gap-1.5 rounded-full px-4 py-3 text-[13px] font-medium md:gap-2 md:px-6 md:text-[15px]">
        <span class="header-cta-text">Teklif Al</span>
        <i class="bx bx-plus text-[17px]"></i>
      </a>
    </div>
</div>
</header>
