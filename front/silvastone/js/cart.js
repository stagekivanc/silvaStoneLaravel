(function SilvaCart() {
  const KEY = 'silvastone-cart';
  const productsUrl = () => window.SILVA_PRODUCTS_URL || window.SILVA_ROUTES?.products || '/';
  const privacyUrl = () => window.SILVA_ROUTES?.privacy || '/';
  const kvkkUrl = () => window.SILVA_ROUTES?.kvkk || '/';

  const read = () => {
    try {
      const data = JSON.parse(localStorage.getItem(KEY) || '[]');
      return Array.isArray(data) ? data : [];
    } catch {
      return [];
    }
  };

  const write = (items) => {
    localStorage.setItem(KEY, JSON.stringify(items));
    render();
  };

  const totalQty = (items) => items.reduce((n, item) => n + item.qty, 0);

  const add = (item) => {
    const qty = Math.max(1, Number(item.qty) || 1);
    const items = read();
    const found = items.find((row) => row.code === item.code);
    if (found) found.qty += qty;
    else items.push({ code: item.code, name: item.name, img: item.img, qty });
    write(items);
    open();
  };

  const setQty = (code, qty) => {
    write(
      qty < 1
        ? read().filter((row) => row.code !== code)
        : read().map((row) => (row.code === code ? { ...row, qty } : row))
    );
  };

  const open = () => {
    document.body.classList.add('cart-open');
    const drawer = document.getElementById('cart-drawer');
    const toggle = document.getElementById('cart-toggle');
    if (drawer) drawer.setAttribute('aria-hidden', 'false');
    if (toggle) toggle.setAttribute('aria-expanded', 'true');
  };

  const close = () => {
    document.body.classList.remove('cart-open');
    const drawer = document.getElementById('cart-drawer');
    const toggle = document.getElementById('cart-toggle');
    if (drawer) drawer.setAttribute('aria-hidden', 'true');
    if (toggle) toggle.setAttribute('aria-expanded', 'false');
  };

  const openQuote = () => {
    const items = read();
    if (!items.length) {
      open();
      return;
    }
    close();
    fillQuoteItems(items);
    const modal = document.getElementById('quote-modal');
    const success = document.getElementById('quote-success');
    const form = document.getElementById('quote-form');
    if (success) success.hidden = true;
    if (form) form.hidden = false;
    document.body.classList.add('quote-open');
    if (modal) modal.setAttribute('aria-hidden', 'false');
    document.getElementById('qf-name')?.focus();
  };

  const closeQuote = () => {
    document.body.classList.remove('quote-open');
    const modal = document.getElementById('quote-modal');
    if (modal) modal.setAttribute('aria-hidden', 'true');
  };

  const fillQuoteItems = (items) => {
    const box = document.getElementById('quote-items');
    if (!box) return;
    box.innerHTML = items
      .map(
        (item) => `<li>
          <span>${esc(item.code)}</span>
          <strong>${esc(item.name)}</strong>
          <em>× ${item.qty}</em>
        </li>`
      )
      .join('');
  };

  const esc = (v) =>
    String(v).replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));

  const render = () => {
    const items = read();
    const qty = totalQty(items);
    document.querySelectorAll('.cart-badge').forEach((badge) => {
      badge.hidden = qty === 0;
      badge.textContent = String(qty);
    });
    const list = document.getElementById('cart-list');
    const empty = document.getElementById('cart-empty');
    const foot = document.getElementById('cart-foot');
    const countEl = document.getElementById('cart-count');
    if (countEl) countEl.textContent = qty ? `${qty} ürün` : '';
    if (!list) return;
    if (!items.length) {
      list.innerHTML = '';
      if (empty) empty.hidden = false;
      if (foot) foot.hidden = true;
      return;
    }
    if (empty) empty.hidden = true;
    if (foot) foot.hidden = false;
    list.innerHTML = items
      .map(
        (item) => `<article class="cart-item" data-code="${esc(item.code)}">
          <div class="cart-item-media">${
            item.img
              ? `<img src="${esc(item.img)}" alt="${esc(item.name)}" />`
              : ''
          }</div>
          <div class="cart-item-info">
            <p>${esc(item.code)}</p>
            <h3>${esc(item.name)}</h3>
            <div class="cart-qty">
              <button type="button" data-cart-qty="-1" aria-label="Azalt">−</button>
              <span>${item.qty}</span>
              <button type="button" data-cart-qty="1" aria-label="Artır">+</button>
              <button type="button" class="cart-item-remove" data-cart-remove aria-label="Kaldır"><i class="bx bx-trash"></i></button>
            </div>
          </div>
        </article>`
      )
      .join('');
  };

  const fillContact = () => {
    const field = document.getElementById('cf-message');
    if (!field) return;
    const items = read();
    if (!items.length || field.value.trim()) return;
    field.value = `Teklif talebi:\n${items.map((item) => `• ${item.code} — ${item.name} × ${item.qty}`).join('\n')}`;
    const interest = document.getElementById('cf-interest');
    if (interest) interest.value = 'catalog';
  };

  const mount = () => {
    if (document.getElementById('cart-drawer')) return;
    const cluster = document.querySelector('#site-header .justify-self-end');
    if (cluster) {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.id = 'cart-toggle';
      btn.className = 'pill-btn cart-toggle';
      btn.setAttribute('aria-label', 'Sepet');
      btn.setAttribute('aria-expanded', 'false');
      btn.setAttribute('aria-controls', 'cart-drawer');
      btn.innerHTML = '<i class="bx bx-shopping-bag"></i><span class="cart-badge" hidden>0</span>';
      cluster.appendChild(btn);
    }

    document.body.insertAdjacentHTML(
      'beforeend',
      `<div class="cart-overlay" id="cart-overlay"></div>
      <aside class="cart-drawer" id="cart-drawer" aria-hidden="true" aria-labelledby="cart-title">
        <div class="cart-head">
          <div>
            <h2 id="cart-title">Sepet</h2>
            <p id="cart-count"></p>
          </div>
          <button type="button" class="cart-close" id="cart-close" aria-label="Kapat"><i class="bx bx-x"></i></button>
        </div>
        <div class="cart-body">
          <div class="cart-empty" id="cart-empty">
            <span><i class="bx bx-shopping-bag"></i></span>
            <p>Sepetiniz boş.</p>
            <a href="${productsUrl()}" class="cart-empty-btn">Ürünlere git</a>
          </div>
          <div class="cart-list" id="cart-list"></div>
        </div>
        <div class="cart-foot" id="cart-foot" hidden>
          <button type="button" class="cart-checkout" id="cart-checkout">Teklif talebi gönder</button>
          <p>Fiyat için Acarkon ekibi dönüş yapar.</p>
        </div>
      </aside>
      <div class="quote-overlay" id="quote-overlay"></div>
      <div class="quote-modal" id="quote-modal" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="quote-title">
        <div class="quote-panel">
          <div class="quote-head">
            <div>
              <p class="page-intro-kicker font-display italic">Teklif</p>
              <h2 id="quote-title">Teklif talebi</h2>
            </div>
            <button type="button" class="cart-close" id="quote-close" aria-label="Kapat"><i class="bx bx-x"></i></button>
          </div>
          <form class="quote-form" id="quote-form">
            <p class="quote-lead">Sepetteki ürünler için fiyat ve metraj dönüşü alacağız. Tüm iletişim ve proje bilgilerini doldurun.</p>
            <ul class="quote-items" id="quote-items"></ul>
            <div class="contact-form-grid">
              <div class="contact-field">
                <label for="qf-name">Ad Soyad</label>
                <input id="qf-name" name="name" type="text" required autocomplete="name" placeholder="Adınız ve soyadınız" />
              </div>
              <div class="contact-field">
                <label for="qf-company">Firma / ünvan</label>
                <input id="qf-company" name="company" type="text" autocomplete="organization" placeholder="Varsa firma adı" />
              </div>
              <div class="contact-field">
                <label for="qf-phone">Telefon</label>
                <input id="qf-phone" name="phone" type="tel" required autocomplete="tel" placeholder="05xx xxx xx xx" />
              </div>
              <div class="contact-field">
                <label for="qf-email">E-posta</label>
                <input id="qf-email" name="email" type="email" required autocomplete="email" placeholder="ornek@mail.com" />
              </div>
              <div class="contact-field">
                <label for="qf-city">Şehir</label>
                <input id="qf-city" name="city" type="text" required autocomplete="address-level1" placeholder="Proje şehri" />
              </div>
              <div class="contact-field">
                <label for="qf-district">İlçe</label>
                <input id="qf-district" name="district" type="text" autocomplete="address-level2" placeholder="İlçe" />
              </div>
              <div class="contact-field">
                <label for="qf-space">Mekân tipi</label>
                <div class="contact-select">
                  <select id="qf-space" name="space" required>
                    <option value="">Seçin</option>
                    <option value="Konut">Konut</option>
                    <option value="Otel">Otel</option>
                    <option value="Ofis">Ofis</option>
                    <option value="Mağaza">Mağaza / showroom</option>
                    <option value="Restoran">Restoran / kafe</option>
                    <option value="Dış cephe">Dış cephe</option>
                    <option value="Diğer">Diğer</option>
                  </select>
                  <i class="bx bx-chevron-down"></i>
                </div>
              </div>
              <div class="contact-field">
                <label for="qf-area">Yaklaşık m²</label>
                <input id="qf-area" name="area" type="text" inputmode="decimal" placeholder="Örn. 120" />
              </div>
              <div class="contact-field">
                <label for="qf-place">Uygulama</label>
                <div class="contact-select">
                  <select id="qf-place" name="place">
                    <option value="İç mekân">İç mekân</option>
                    <option value="Dış mekân">Dış mekân</option>
                    <option value="İç ve dış mekân">İç ve dış mekân</option>
                  </select>
                  <i class="bx bx-chevron-down"></i>
                </div>
              </div>
              <div class="contact-field">
                <label for="qf-install">Montaj ihtiyacı</label>
                <div class="contact-select">
                  <select id="qf-install" name="install">
                    <option value="Henüz emin değilim">Henüz emin değilim</option>
                    <option value="Evet, montaj da istiyorum">Evet, montaj da istiyorum</option>
                    <option value="Hayır, yalnızca malzeme">Hayır, yalnızca malzeme</option>
                  </select>
                  <i class="bx bx-chevron-down"></i>
                </div>
              </div>
              <div class="contact-field contact-field--full">
                <label for="qf-note">Proje notu</label>
                <textarea id="qf-note" name="note" rows="3" placeholder="Teslim tarihi, yüzey, özel ölçü veya ek notunuz..."></textarea>
              </div>
            </div>
            <div class="contact-form-foot">
              <label class="contact-consent">
                <input id="qf-kvkk" type="checkbox" required />
                <span>Kişisel verilerin <a href="${kvkkUrl()}">aydınlatma metni</a> ve <a href="${privacyUrl()}">gizlilik politikası</a> kapsamında işlenmesini kabul ediyorum.</span>
              </label>
              <button type="submit" class="contact-submit">
                <span>Talebi gönder</span>
                <i class="bx bx-right-arrow-alt"></i>
              </button>
            </div>
          </form>
          <p class="contact-success" id="quote-success" hidden>Teşekkürler — teklif talebiniz alındı. En kısa sürede dönüş yapacağız.</p>
        </div>
      </div>`
    );

    document.getElementById('cart-toggle')?.addEventListener('click', (e) => {
      e.stopPropagation();
      if (document.body.classList.contains('cart-open')) close();
      else open();
    });
    document.getElementById('cart-close')?.addEventListener('click', close);
    document.getElementById('cart-overlay')?.addEventListener('click', close);
    document.getElementById('cart-checkout')?.addEventListener('click', openQuote);
    document.getElementById('quote-close')?.addEventListener('click', closeQuote);
    document.getElementById('quote-overlay')?.addEventListener('click', closeQuote);
    document.getElementById('cart-list')?.addEventListener('click', (e) => {
      const row = e.target.closest('.cart-item');
      if (!row) return;
      if (e.target.closest('[data-cart-remove]')) {
        setQty(row.dataset.code, 0);
        return;
      }
      const step = e.target.closest('[data-cart-qty]');
      if (!step) return;
      const item = read().find((rowItem) => rowItem.code === row.dataset.code);
      if (!item) return;
      setQty(row.dataset.code, item.qty + Number(step.dataset.cartQty));
    });
    document.getElementById('quote-form')?.addEventListener('submit', (e) => {
      e.preventDefault();
      const items = read();
      const val = (id) => document.getElementById(id)?.value.trim() || '';
      const lines = [
        'Silva Stone teklif talebi',
        '',
        `Ad Soyad: ${val('qf-name')}`,
        `Firma: ${val('qf-company') || '-'}`,
        `Telefon: ${val('qf-phone')}`,
        `E-posta: ${val('qf-email')}`,
        `Şehir: ${val('qf-city')}`,
        `İlçe: ${val('qf-district') || '-'}`,
        `Mekân tipi: ${val('qf-space')}`,
        `Yaklaşık m²: ${val('qf-area') || '-'}`,
        `Uygulama: ${val('qf-place')}`,
        `Montaj: ${val('qf-install')}`,
        '',
        'Ürünler:',
        ...items.map((item) => `• ${item.code} — ${item.name} × ${item.qty}`),
        '',
        `Not: ${val('qf-note') || '-'}`,
      ];
      const mailto = `mailto:bilgi@acarkon.com?subject=${encodeURIComponent('Silva Stone teklif talebi')}&body=${encodeURIComponent(lines.join('\n'))}`;
      window.location.href = mailto;
      document.getElementById('quote-form').hidden = true;
      const success = document.getElementById('quote-success');
      if (success) success.hidden = false;
    });
    document.querySelectorAll('#site-header a.pill-btn').forEach((link) => {
      if (!/Teklif/i.test(link.textContent || '')) return;
      link.addEventListener('click', (e) => {
        e.preventDefault();
        open();
      });
    });
    document.addEventListener('keydown', (e) => {
      if (e.key !== 'Escape') return;
      if (document.body.classList.contains('quote-open')) closeQuote();
      else if (document.body.classList.contains('cart-open')) close();
    });

    render();
    fillContact();
  };

  window.SilvaCart = { add, open, close, openQuote, read };

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', mount);
  else mount();
})();
