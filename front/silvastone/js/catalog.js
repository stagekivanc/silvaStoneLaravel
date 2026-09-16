(function catalogListing() {
  const grid = document.getElementById('product-grid');
  if (!grid) return;

  const root = document.getElementById('product-list');
  const pager = document.getElementById('product-pager');
  const countEl = document.getElementById('product-count');
  const emptyEl = document.getElementById('product-empty');
  const resetBtn = document.getElementById('product-reset');

  const PRODUCTS = window.SILVA_PRODUCTS;
  const CATS = window.SILVA_CATS;
  const COLORS = window.SILVA_COLORS;
  const LABELS = window.SILVA_LABELS || {};
  const FEATURES = Array.isArray(window.SILVA_FEATURES) ? window.SILVA_FEATURES : [];
  const mm = LABELS.mm || 'mm';
  const pending = LABELS.image_pending || 'Ürün resmi hazırlanıyor';
  const countSuffix = LABELS.count_suffix || 'yüzey';

  const allowedValues = (key) =>
    FEATURES.filter((f) => f.key === key).map((f) => String(f.value));

  const formatSize = (size) => String(size || '600x1200').replace(/[xX]/g, '×');
  const formatThick = (thick) => String(thick || '').replace(/-/g, '–');

  const defaults = { cat: 'all', color: 'all', size: '', indoor: '', outdoor: '', thick: '', depot: '', q: '', page: 1, cols: 3, per: 24 };

  const readState = () => {
    const q = new URLSearchParams(location.search);
    const per = [12, 24, 36].includes(Number(q.get('per'))) ? Number(q.get('per')) : defaults.per;
    const cols = Math.min(4, Math.max(1, Number(q.get('cols') || defaults.cols)));
    return {
      cat: CATS[q.get('cat')] ? q.get('cat') : 'all',
      color: COLORS[q.get('color')] ? q.get('color') : 'all',
      size: allowedValues('size').includes(q.get('size') || '') ? q.get('size') : '',
      indoor: q.get('indoor') === '1' ? '1' : '',
      outdoor: q.get('outdoor') === '1' ? '1' : '',
      thick: allowedValues('thick').includes(q.get('thick') || '') ? q.get('thick') : '',
      depot: q.get('depot') === '1' ? '1' : '',
      page: Math.max(1, Number(q.get('page') || 1)),
      cols,
      per,
      q: (q.get('q') || '').trim().slice(0, 80),
    };
  };

  const writeState = (s) => {
    const q = new URLSearchParams();
    if (s.cat !== 'all') q.set('cat', s.cat);
    if (s.color !== 'all') q.set('color', s.color);
    if (s.size) q.set('size', s.size);
    if (s.indoor) q.set('indoor', '1');
    if (s.outdoor) q.set('outdoor', '1');
    if (s.thick) q.set('thick', s.thick);
    if (s.depot) q.set('depot', '1');
    if (s.q) q.set('q', s.q);
    if (s.page > 1) q.set('page', String(s.page));
    if (s.cols !== 3) q.set('cols', String(s.cols));
    if (s.per !== 24) q.set('per', String(s.per));
    const base = window.SILVA_PRODUCTS_URL || location.pathname;
    history.replaceState({}, '', q.toString() ? `${base}?${q}` : base);
  };

  const filtered = (s) =>
    PRODUCTS.filter((p) => {
      if (s.cat !== 'all' && p.cat !== s.cat) return false;
      if (s.color !== 'all' && p.color !== s.color) return false;
      if (s.size && p.size !== s.size) return false;
      if (s.indoor && !p.indoor) return false;
      if (s.outdoor && !p.outdoor) return false;
      if (s.thick && p.thick !== s.thick) return false;
      if (s.depot && !p.depot) return false;
      if (s.q && !window.silvaMatch(p, s.q)) return false;
      return true;
    });

  const esc = (v) => String(v).replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));

  const galleryOf = (p) => [...new Set((p.imgs && p.imgs.length ? p.imgs : [p.img]).filter(Boolean))];

  const media = (p) => {
    const gallery = galleryOf(p);
    if (!gallery.length) return `<span class="plp-ph">${esc(pending)}</span>`;
    const ticks =
      gallery.length > 1
        ? `<span class="plp-hover-bar" aria-hidden="true">${gallery
            .map((_, i) => `<span class="plp-hover-tick${i === 0 ? ' is-on' : ''}"></span>`)
            .join('')}</span>`
        : '';
    return `<img src="${esc(gallery[0])}" alt="${esc(p.name)}" loading="lazy" onerror="this.outerHTML='<span class=&quot;plp-ph&quot;>${esc(pending)}</span>'" />${ticks}`;
  };

  const title = window.silvaTitle;
  const href = window.silvaHref;

  const sync = (s) => {
    root.querySelectorAll('#product-cats [data-filter="cat"]').forEach((btn) => {
      btn.classList.toggle('is-active', btn.dataset.value === s.cat);
    });
    root.querySelectorAll('.plp-dot').forEach((btn) => {
      btn.classList.toggle('is-active', btn.dataset.value === s.color);
    });
    root.querySelectorAll('.plp-chip').forEach((btn) => {
      btn.classList.toggle('is-active', s[btn.dataset.filter] === btn.dataset.value);
    });
    root.querySelectorAll('[data-cols]').forEach((btn) => {
      btn.classList.toggle('is-active', Number(btn.dataset.cols) === s.cols);
    });
    root.classList.toggle(
      'is-filtered',
      s.cat !== 'all' || s.color !== 'all' || Boolean(s.size || s.indoor || s.outdoor || s.thick || s.depot || s.q)
    );
    const extra = [s.color !== 'all', Boolean(s.size), Boolean(s.indoor), Boolean(s.outdoor), Boolean(s.thick), Boolean(s.depot)].filter(Boolean).length;
    const badge = document.getElementById('product-filter-badge');
    if (badge) {
      badge.hidden = extra === 0;
      badge.textContent = String(extra);
    }
    const kicker = document.querySelector('.page-intro-kicker');
    const heading = document.querySelector('.page-intro h1');
    const aside = document.querySelector('.page-intro-aside p');
    if (heading && !heading.dataset.base) heading.dataset.base = heading.textContent;
    if (kicker && !kicker.dataset.base) kicker.dataset.base = kicker.textContent;
    if (aside && !aside.dataset.base) aside.dataset.base = aside.textContent;
    if (s.q) {
      if (kicker) kicker.textContent = 'Arama';
      if (heading) heading.textContent = s.q;
      if (aside) aside.textContent = 'Ürün adı ve koduna göre sonuçlar. Filtreler aynı listede çalışır.';
    } else {
      if (kicker && kicker.dataset.base) kicker.textContent = kicker.dataset.base;
      if (heading && heading.dataset.base) heading.textContent = heading.dataset.base;
      if (aside && aside.dataset.base) aside.textContent = aside.dataset.base;
    }
  };

  const render = () => {
    const s = readState();
    const list = filtered(s);
    const pages = Math.max(1, Math.ceil(list.length / s.per));
    const page = Math.min(s.page, pages);
    if (page !== s.page) {
      s.page = page;
      writeState(s);
    }
    const slice = list.slice((page - 1) * s.per, page * s.per);
    sync(s);
    grid.dataset.cols = String(s.cols);
    countEl.textContent = `${list.length} ${countSuffix}`;
    const emptyCopy = document.querySelector('#product-empty p');
    const emptyBtn = document.getElementById('product-empty-reset');
    if (emptyCopy) {
      emptyCopy.textContent = s.q ? `“${s.q}” ile eşleşen ürün yok.` : (LABELS.empty || 'Bu seçime uygun ürün yok.');
    }
    if (emptyBtn) emptyBtn.textContent = s.q ? 'Aramayı temizle' : (LABELS.empty_reset || 'Filtrelemeyi sıfırla');

    if (!slice.length) {
      grid.innerHTML = '';
      emptyEl.hidden = false;
      pager.hidden = true;
      pager.innerHTML = '';
      return;
    }
    emptyEl.hidden = true;
    grid.innerHTML = slice
      .map((p) => {
        const gallery = galleryOf(p);
        const scrubAttrs =
          gallery.length > 1
            ? ` is-scrub" data-gallery="${gallery.map(esc).join('|')}" data-index="0`
            : '';
        return `<article class="plp-card">
          <a href="${href(p)}" class="plp-card-media${scrubAttrs}">${media(p)}</a>
          <div class="plp-card-info">
            <p class="plp-card-meta"><span>${esc(p.code)}</span><span>${esc(CATS[p.cat])}</span></p>
            <h2><a href="${href(p)}">${esc(title(p))}</a></h2>
            <p class="plp-card-spec">${esc(COLORS[p.color])} · ${esc(formatSize(p.size))} ${esc(mm)}${p.thick ? ` · ${esc(formatThick(p.thick))} ${esc(mm)}` : ''}</p>
          </div>
        </article>`;
      })
      .join('');

    if (pages <= 1) {
      pager.hidden = true;
      pager.innerHTML = '';
      return;
    }
    pager.hidden = false;
    let html = `<button type="button" data-page="${page - 1}" ${page === 1 ? 'disabled' : ''} aria-label="Önceki">‹</button>`;
    for (let n = 1; n <= pages; n += 1) {
      html += `<button type="button" data-page="${n}" class="${n === page ? 'is-active' : ''}">${String(n).padStart(2, '0')}</button>`;
    }
    html += `<button type="button" data-page="${page + 1}" ${page === pages ? 'disabled' : ''} aria-label="Sonraki">›</button>`;
    pager.innerHTML = html;
  };

  const apply = (patch, scroll) => {
    writeState({ ...readState(), ...patch });
    render();
    if (scroll) root.scrollIntoView({ behavior: 'smooth', block: 'start' });
  };

  root.addEventListener('click', (e) => {
    const catBtn = e.target.closest('#product-cats [data-filter="cat"]');
    if (catBtn) {
      apply({ cat: catBtn.dataset.value, page: 1 });
      return;
    }
    const dot = e.target.closest('.plp-dot');
    if (dot) {
      apply({ color: dot.dataset.value, page: 1 });
      return;
    }
    const chip = e.target.closest('.plp-chip');
    if (chip) {
      const key = chip.dataset.filter;
      const val = chip.dataset.value;
      apply({ [key]: readState()[key] === val ? '' : val, page: 1 });
      return;
    }
    const colBtn = e.target.closest('[data-cols]');
    if (colBtn) apply({ cols: Number(colBtn.dataset.cols) });
  });

  const reset = () => {
    const s = readState();
    apply({ ...defaults, per: s.per, cols: s.cols });
  };
  resetBtn?.addEventListener('click', reset);
  const sheetReset = document.getElementById('product-sheet-reset');
  if (sheetReset) sheetReset.addEventListener('click', reset);
  const emptyReset = document.getElementById('product-empty-reset');
  if (emptyReset) emptyReset.addEventListener('click', reset);

  const sheet = document.getElementById('product-filter-sheet');
  const mask = document.getElementById('product-filter-mask');
  const filterToggle = document.getElementById('product-filter-toggle');
  const filterClose = document.getElementById('product-filter-close');
  const filterApply = document.getElementById('product-filter-apply');
  const filterRule = root.querySelector('#product-filters .plp-rule');

  const closeFilters = () => {
    document.body.classList.remove('plp-filters-open');
    sheet?.classList.remove('is-open');
    sheet?.setAttribute('aria-hidden', 'true');
    filterToggle?.setAttribute('aria-expanded', 'false');
    if (mask) mask.hidden = true;
  };

  const openFilters = () => {
    document.body.classList.add('plp-filters-open');
    sheet?.classList.add('is-open');
    sheet?.setAttribute('aria-hidden', 'false');
    filterToggle?.setAttribute('aria-expanded', 'true');
    if (mask) mask.hidden = false;
  };

  const placeSheet = () => {
    if (!sheet) return;
    if (window.matchMedia('(max-width: 1023px)').matches) {
      document.body.appendChild(sheet);
      if (mask) document.body.appendChild(mask);
    } else if (filterRule) {
      filterRule.after(sheet);
      closeFilters();
    }
  };
  placeSheet();
  window.addEventListener('resize', placeSheet);

  filterToggle?.addEventListener('click', () => {
    if (sheet?.classList.contains('is-open')) closeFilters();
    else openFilters();
  });
  filterClose?.addEventListener('click', closeFilters);
  filterApply?.addEventListener('click', closeFilters);
  mask?.addEventListener('click', closeFilters);
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && document.body.classList.contains('plp-filters-open')) closeFilters();
  });

  pager.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-page]');
    if (!btn || btn.disabled) return;
    apply({ page: Number(btn.dataset.page) }, true);
  });

  const showSlide = (media, index) => {
    const imgs = (media.dataset.gallery || '').split('|').filter(Boolean);
    if (!imgs.length) return;
    const i = Math.min(imgs.length - 1, Math.max(0, index));
    if (Number(media.dataset.index) === i) return;
    media.dataset.index = String(i);
    const img = media.querySelector('img');
    if (img) img.src = imgs[i];
    media.querySelectorAll('.plp-hover-tick').forEach((tick, n) => tick.classList.toggle('is-on', n === i));
  };

  grid.addEventListener(
    'pointerenter',
    (e) => {
      const media = e.target.closest('.plp-card-media.is-scrub');
      if (!media || media.dataset.preloaded) return;
      media.dataset.preloaded = '1';
      (media.dataset.gallery || '').split('|').forEach((src) => {
        if (!src) return;
        const preload = new Image();
        preload.src = src;
      });
    },
    true
  );

  grid.addEventListener('pointermove', (e) => {
    if (e.pointerType === 'touch') return;
    const media = e.target.closest('.plp-card-media.is-scrub');
    if (!media) return;
    const count = (media.dataset.gallery || '').split('|').filter(Boolean).length;
    if (count < 2) return;
    const rect = media.getBoundingClientRect();
    const x = (e.clientX - rect.left) / rect.width;
    showSlide(media, Math.floor(Math.min(0.999, Math.max(0, x)) * count));
  });

  grid.addEventListener(
    'pointerleave',
    (e) => {
      const media = e.target.closest('.plp-card-media.is-scrub');
      if (!media || media.contains(e.relatedTarget)) return;
      showSlide(media, 0);
    },
    true
  );

  window.addEventListener('popstate', render);
  render();

  const seo = document.getElementById('product-seo');
  const seoMore = document.getElementById('product-seo-more');
  if (seo && seoMore) {
    seoMore.addEventListener('click', () => {
      const open = seo.classList.toggle('is-open');
      seoMore.textContent = open ? 'Daha az göster' : 'Devamını oku';
      seoMore.setAttribute('aria-expanded', String(open));
    });
  }
})();
