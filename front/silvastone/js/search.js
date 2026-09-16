(function SilvaSearch() {
  const mount = () => {
    const menu = document.querySelector('#site-header .menu-wrap');
    if (!menu || document.getElementById('search-toggle')) return;

    const cluster = document.createElement('div');
    cluster.className = 'header-left justify-self-start';
    menu.parentNode.insertBefore(cluster, menu);
    cluster.appendChild(menu);

    const toggle = document.createElement('button');
    toggle.type = 'button';
    toggle.id = 'search-toggle';
    toggle.className = 'pill-btn search-toggle';
    toggle.setAttribute('aria-label', 'Ürün ara');
    toggle.setAttribute('aria-expanded', 'false');
    toggle.setAttribute('aria-controls', 'search-overlay');
    toggle.innerHTML = '<i class="bx bx-search"></i>';
    cluster.appendChild(toggle);

    const placeSearch = () => {
      const actions = document.querySelector('#site-header .header-actions');
      const cta = actions?.querySelector('.header-cta');
      const mobile = window.matchMedia('(max-width: 767px)').matches;
      if (mobile && actions) {
        if (cta) actions.insertBefore(toggle, cta);
        else actions.insertBefore(toggle, actions.firstChild);
        return;
      }
      cluster.appendChild(toggle);
    };
    placeSearch();
    window.addEventListener('resize', placeSearch);

    document.body.insertAdjacentHTML(
      'beforeend',
      `<div class="search-overlay" id="search-overlay" aria-hidden="true">
        <div class="search-panel" role="dialog" aria-modal="true" aria-labelledby="search-title">
          <form class="search-form" id="search-form" action="urunler.html" method="get">
            <i class="bx bx-search" aria-hidden="true"></i>
            <input id="search-input" type="search" name="q" placeholder="Ürün adı veya kodu yazın" autocomplete="off" />
            <kbd>esc</kbd>
          </form>
          <p class="search-hint" id="search-title">Koleksiyonda ad, kod ve yüzey arayın</p>
          <div class="search-hits" id="search-hits"></div>
        </div>
      </div>`
    );

    const overlay = document.getElementById('search-overlay');
    const input = document.getElementById('search-input');
    const hits = document.getElementById('search-hits');
    const form = document.getElementById('search-form');
    const esc = (v) => String(v).replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));

    const ensureProducts = (cb) => {
      if (window.SILVA_PRODUCTS) {
        cb();
        return;
      }
      const existing = document.querySelector('script[src$="js/products.js"]');
      if (existing) {
        if (window.SILVA_PRODUCTS) cb();
        else existing.addEventListener('load', cb, { once: true });
        return;
      }
      const script = document.createElement('script');
      script.src = 'js/products.js';
      script.onload = cb;
      document.head.appendChild(script);
    };

    const open = () => {
      document.body.classList.add('search-open');
      overlay.setAttribute('aria-hidden', 'false');
      toggle.setAttribute('aria-expanded', 'true');
      const current = new URLSearchParams(location.search).get('q');
      if (current && !input.value) input.value = current;
      input.focus();
      input.select();
      ensureProducts(renderHits);
    };

    const close = () => {
      document.body.classList.remove('search-open');
      overlay.setAttribute('aria-hidden', 'true');
      toggle.setAttribute('aria-expanded', 'false');
    };

    const go = (q) => {
      const term = (q || '').trim();
      close();
      if (!term) {
        location.href = 'urunler.html';
        return;
      }
      location.href = `urunler.html?q=${encodeURIComponent(term)}`;
    };

    const renderHits = () => {
      const products = window.SILVA_PRODUCTS || [];
      const term = input.value.trim();
      if (!term) {
        hits.innerHTML = '';
        return;
      }
      const list = products.filter((p) => window.silvaMatch(p, term)).slice(0, 6);
      if (!list.length) {
        hits.innerHTML = `<p class="search-empty">Eşleşen ürün yok. Enter ile tüm listede arayın.</p>`;
        return;
      }
      const title = window.silvaTitle;
      const href = window.silvaHref;
      const cats = window.SILVA_CATS || {};
      hits.innerHTML =
        `<ul>${list
          .map(
            (p) => `<li>
              <a href="${esc(href(p))}">
                <img src="${esc(p.img)}" alt="" />
                <span>
                  <strong>${esc(title(p))}</strong>
                  <em>${esc(p.code)} · ${esc(cats[p.cat] || p.cat)}</em>
                </span>
              </a>
            </li>`
          )
          .join('')}</ul>
        <button type="button" class="search-all" data-search-all>Tüm sonuçları gör</button>`;
    };

    toggle.addEventListener('click', (e) => {
      e.stopPropagation();
      if (document.body.classList.contains('search-open')) close();
      else open();
    });
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) close();
    });
    form.querySelector('kbd')?.addEventListener('click', close);
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      go(input.value);
    });
    input.addEventListener('input', () => ensureProducts(renderHits));
    hits.addEventListener('click', (e) => {
      if (!e.target.closest('[data-search-all]')) return;
      go(input.value);
    });
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && document.body.classList.contains('search-open')) {
        e.preventDefault();
        close();
        return;
      }
      if ((e.key === 'k' || e.key === 'K') && (e.metaKey || e.ctrlKey)) {
        e.preventDefault();
        open();
      }
    });
  };

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', mount);
  else mount();
})();
