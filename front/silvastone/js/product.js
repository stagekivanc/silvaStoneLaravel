(function productDetail() {
  const root = document.getElementById('pdp-root');
  if (!root || !window.SILVA_PRODUCTS) return;

  const products = window.SILVA_PRODUCTS;
  const cats = window.SILVA_CATS;
  const colors = window.SILVA_COLORS;
  const title = window.silvaTitle;
  const href = window.silvaHref;
  const desc = window.silvaDesc;
  const esc = (v) => String(v).replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));

  const code = new URLSearchParams(location.search).get('code');
  const product = products.find((p) => p.code === code);
  if (!product) {
    location.replace(window.SILVA_PRODUCTS_URL || window.SILVA_ROUTES?.products || '/');
    return;
  }

  const name = title(product);
  const catName = cats[product.cat] || product.cat;
  const colorName = colors[product.color] || product.color;
  const thick = product.thick.replace('-', '–');
  const pageUrl = `https://silvastone.acarkon.com/${href(product)}`;

  document.title = `${name} | Silva Stone`;
  document.querySelector('meta[name="description"]')?.setAttribute(
    'content',
    `${name} — ${catName} koleksiyonu. 600×1200 mm, ${thick} mm. İç ve dış mekân.`
  );
  document.querySelector('link[rel="canonical"]')?.setAttribute('href', pageUrl);
  document.querySelector('meta[property="og:title"]')?.setAttribute('content', `${name} | Silva Stone`);
  document.querySelector('meta[property="og:url"]')?.setAttribute('href', pageUrl);

  const productsListUrl = window.SILVA_PRODUCTS_URL || window.SILVA_ROUTES?.products || '/';
  document.getElementById('pdp-crumb').innerHTML = `
    <a href="${esc(productsListUrl)}">Koleksiyon</a>
    <span>/</span>
    <a href="${esc(productsListUrl)}?cat=${esc(product.cat)}">${esc(catName)}</a>
    <span>/</span>
    <span>${esc(product.code)}</span>`;

  const gallery = (product.imgs && product.imgs.length ? product.imgs : [product.img]).filter(Boolean);
  const thumbs =
    gallery.length > 1
      ? `<div class="pdp-thumbs">${gallery
          .map(
            (src, i) =>
              `<button type="button" class="pdp-thumb${i === 0 ? ' is-active' : ''}" data-src="${esc(src)}" data-index="${i}" aria-label="Görsel ${i + 1}"><img src="${esc(src)}" alt="" /></button>`
          )
          .join('')}</div>`
      : '';
  const arrows =
    gallery.length > 1
      ? `<button type="button" class="pdp-arrow pdp-arrow-prev" data-pdp-nav="-1" aria-label="Önceki görsel">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15.2 4.8 7.8 12l7.4 7.2"/></svg>
        </button>
        <button type="button" class="pdp-arrow pdp-arrow-next" data-pdp-nav="1" aria-label="Sonraki görsel">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8.8 4.8 16.2 12l-7.4 7.2"/></svg>
        </button>`
      : '';
  document.getElementById('pdp-media').innerHTML = gallery.length
    ? `<div class="pdp-stage-wrap">
        <button type="button" class="pdp-stage" id="pdp-open" aria-label="Görseli büyüt"><img id="pdp-main" src="${esc(gallery[0])}" alt="${esc(product.name)}" /></button>
        ${arrows}
      </div>${thumbs}`
    : `<span class="plp-ph">Ürün resmi hazırlanıyor</span>`;

  let galleryIndex = 0;
  const setIndex = (next) => {
    if (!gallery.length) return;
    galleryIndex = (next + gallery.length) % gallery.length;
    const main = document.getElementById('pdp-main');
    if (main) main.src = gallery[galleryIndex];
    document.querySelectorAll('.pdp-thumb').forEach((el, n) => {
      el.classList.toggle('is-active', n === galleryIndex);
    });
  };

  document.getElementById('pdp-media').addEventListener('click', (e) => {
    const nav = e.target.closest('[data-pdp-nav]');
    if (nav) {
      setIndex(galleryIndex + Number(nav.dataset.pdpNav));
      return;
    }
    const btn = e.target.closest('.pdp-thumb');
    if (btn) {
      setIndex(Number(btn.dataset.index) || 0);
      return;
    }
    if (!e.target.closest('#pdp-open') || !window.Fancybox) return;
    window.Fancybox.show(
      gallery.map((src) => ({ src, type: 'image', caption: name })),
      { startIndex: galleryIndex, Hash: false }
    );
  });

  document.getElementById('pdp-info').innerHTML = `
    <p class="page-intro-kicker font-display italic">${esc(catName)}</p>
    <p class="pdp-code">${esc(product.code)}</p>
    <h1>${esc(name)}</h1>
    <p class="pdp-lead">${esc(desc(product))}</p>
    <div class="pdp-chips">
      <span>${esc(colorName)}</span>
      <span>600×1200 mm</span>
      <span>${esc(thick)} mm</span>
      <span>İç mekana uygun</span>
      <span>Dış mekana uygun</span>
      ${product.depot ? '<span>Stokta</span>' : ''}
    </div>
    <dl class="pdp-specs">
      <div><dt>Ürün kodu</dt><dd>${esc(product.code)}</dd></div>
      <div><dt>Koleksiyon</dt><dd>${esc(catName)}</dd></div>
      <div><dt>Renk</dt><dd>${esc(colorName)}</dd></div>
      <div><dt>Ölçü</dt><dd>600×1200 mm</dd></div>
      <div><dt>İncelik</dt><dd>${esc(thick)} mm</dd></div>
      <div><dt>Özel sipariş</dt><dd>${esc(product.sizeExtra)}</dd></div>
    </dl>
    <div class="pdp-buy">
      <div class="pdp-qty" role="group" aria-label="Adet">
        <button type="button" id="pdp-qty-minus" aria-label="Azalt">−</button>
        <input id="pdp-qty" type="number" min="1" max="99" value="1" inputmode="numeric" aria-label="Adet" />
        <button type="button" id="pdp-qty-plus" aria-label="Artır">+</button>
      </div>
      <button type="button" class="pdp-cart" id="pdp-add"><i class="bx bx-shopping-bag"></i> Sepete ekle</button>
      <a class="pdp-quote" id="pdp-quote" target="_blank" rel="noopener">Teklif al</a>
    </div>`;
  const qtyInput = document.getElementById('pdp-qty');
  const qtyValue = () => Math.max(1, Math.min(99, Number(qtyInput?.value) || 1));
  const quoteEl = document.getElementById('pdp-quote');
  const syncQuote = () => {
    if (!quoteEl) return;
    const qty = qtyValue();
    const message = [
      'Merhaba,',
      '',
      'Silva Stone web sitesi üzerinden ulaşıyorum.',
      '',
      `${name} ürünü için fiyat bilgisi almak istiyorum.`,
      `Ürün kodu: ${product.code}`,
      `Koleksiyon: ${catName}`,
      `Ölçü: 600×1200 mm · ${thick} mm`,
      `Adet: ${qty}`,
      '',
      'Ürün linki:',
      pageUrl,
      '',
      'Bilgilendirmenizi rica ederim.',
      'Saygılarımla',
    ].join('\n');
    quoteEl.href = `https://wa.me/908503460226?text=${encodeURIComponent(message)}`;
  };
  document.getElementById('pdp-qty-minus')?.addEventListener('click', () => {
    qtyInput.value = String(Math.max(1, qtyValue() - 1));
    syncQuote();
  });
  document.getElementById('pdp-qty-plus')?.addEventListener('click', () => {
    qtyInput.value = String(Math.min(99, qtyValue() + 1));
    syncQuote();
  });
  qtyInput?.addEventListener('input', syncQuote);
  syncQuote();
  document.getElementById('pdp-add')?.addEventListener('click', () => {
    window.SilvaCart?.add({ code: product.code, name, img: product.img, qty: qtyValue() });
  });

  const related = products.filter((p) => p.cat === product.cat && p.code !== product.code).slice(0, 4);
  document.getElementById('pdp-related-title').textContent = `${catName} koleksiyonundan`;
  document.getElementById('pdp-related').innerHTML = related
    .map(
      (p) => `<article class="plp-card">
        <a href="${href(p)}" class="plp-card-media">${
          p.img
            ? `<img src="${esc(p.img)}" alt="${esc(p.name)}" loading="lazy" />`
            : `<span class="plp-ph">Ürün resmi hazırlanıyor</span>`
        }</a>
        <div class="plp-card-info">
          <p class="plp-card-meta"><span>${esc(p.code)}</span><span>${esc(cats[p.cat])}</span></p>
          <h2><a href="${href(p)}">${esc(title(p))}</a></h2>
          <p class="plp-card-spec">${esc(colors[p.color])} · 600×1200 mm · ${esc(p.thick.replace('-', '–'))} mm</p>
        </div>
      </article>`
    )
    .join('');
})();
