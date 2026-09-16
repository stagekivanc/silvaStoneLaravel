(function renderSiteHeader() {
  const root = document.getElementById('site-header-root');
  if (!root) return;

  const apply = (html) => {
    const wrap = document.createElement('div');
    wrap.innerHTML = String(html || '').trim();
    const header = wrap.querySelector('#site-header');
    if (!header) return false;
    root.replaceWith(header);
    document.dispatchEvent(new Event('silva:header-ready'));
    return true;
  };

  const xhr = new XMLHttpRequest();
  xhr.open('GET', 'header.html', false);
  try {
    xhr.send();
    if (xhr.status >= 200 && xhr.status < 400 && apply(xhr.responseText)) return;
  } catch (err) {
    /* file:// veya ağ yoksa aşağıdaki kopya kullanılır */
  }

  apply(`
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
          <a href="index.html#neden" class="nav-drop-link">Özellikler</a>
          <a href="urunler.html" class="nav-drop-link">Koleksiyon</a>
          <a href="magazalar.html" class="nav-drop-link">Showroom</a>
          <a href="projeler.html" class="nav-drop-link">Projeler</a>
          <a href="iletisim.html" class="nav-drop-link">İletişim</a>
          <a href="assets/silva-stone-2026-katalog.pdf" target="_blank" rel="noopener" class="nav-drop-link nav-drop-link--muted">Online katalog</a>
          <div class="nav-drop-mobile">
            <p class="nav-drop-label">Dil</p>
            <div class="nav-drop-langs" role="group" aria-label="Dil">
              <button type="button" class="lang-btn is-active" data-lang="tr">TR</button>
              <button type="button" class="lang-btn" data-lang="en">EN</button>
            </div>
            <a href="iletisim.html" class="nav-drop-cta">Teklif Al</a>
            <a href="tel:+908503460226" class="nav-drop-phone">+90 850 346 02 26</a>
          </div>
        </nav>
      </div>
    </div>
    <a href="index.html" class="header-brand justify-self-center flex flex-col items-center leading-none">
      <img src="assets/silvalogo-white.svg" alt="Silva Stone" class="logo-header-light h-14 w-auto md:h-14" />
      <img src="assets/silvalogo.svg" alt="Silva Stone" class="logo-header-dark hidden h-14 w-auto md:h-14" />
    </a>
    <div class="header-actions flex items-center justify-self-end gap-2 md:gap-2.5">
      <div class="pill-btn header-lang flex overflow-hidden rounded-full text-[13px] font-medium tracking-wide md:text-[14px]" role="group" aria-label="Dil">
        <button type="button" class="lang-btn is-active px-3 py-3 md:px-4" data-lang="tr">TR</button>
        <button type="button" class="lang-btn px-3 py-3 md:px-4" data-lang="en">EN</button>
      </div>
      <a href="iletisim.html" class="pill-btn header-cta inline-flex items-center gap-1.5 rounded-full px-4 py-3 text-[13px] font-medium md:gap-2 md:px-6 md:text-[15px]">
        <span class="header-cta-text">Teklif Al</span>
        <i class="bx bx-plus text-[17px]"></i>
      </a>
    </div>
  </div>
</header>`);
})();
