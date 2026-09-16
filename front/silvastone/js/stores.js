(function SilvaStores() {
  const STORES = window.SILVA_STORES || [
    { city: 'konya', cityLabel: 'Konya', lat: 37.9218, lng: 32.5084, name: 'Acarkon Genel Merkez', addr: 'Horozluhan Mah. Gümüşlü Sk. No:48 Selçuklu / Konya', maps: 'Horozluhan+Mah.+G%C3%BCm%C3%BC%C5%9Fl%C3%BC+Sk.+No%3A48+Sel%C3%A7uklu+%2F+Konya' },
    { city: 'istanbul', cityLabel: 'İstanbul', lat: 41.097, lng: 28.802, name: 'Acarkon Store — İstanbul', addr: 'Başak Mah. Enkoop 2. Sokak SOM Rezidans 1 No:1-I Başakşehir / İstanbul', maps: 'Ba%C5%9Fak+Mah.+Enkoop+2.+Sokak+SOM+Rezidans+1+No%3A1-I+Ba%C5%9Fak%C5%9Fehir+%2F+%C4%B0stanbul' },
    { city: 'ankara', cityLabel: 'Ankara', lat: 39.9555, lng: 32.8918, name: 'Acarkon Store — Ankara', addr: 'Önder Mah. Sarıçam Cad. No:8-B Siteler-Altındağ / Ankara', maps: '%C3%96nder+Mah.+Sar%C4%B1%C3%A7am+Cad.+No%3A8-B+Siteler-Alt%C4%B1nda%C4%9F+%2F+Ankara' },
    { city: 'antalya', cityLabel: 'Antalya', lat: 36.8969, lng: 30.7133, name: 'Acarkon Store — Antalya', addr: 'Mehmetçik Mah. 938 Sk. No:8 Murat Sit. A Blok No:1 Muratpaşa / Antalya', maps: 'Mehmet%C3%A7ik+Mah.+938+Sk.+No%3A8+Murat+Sit.+A+Blok+No%3A1+Muratpa%C5%9Fa+%2F+Antalya' },
    { city: 'bursa', cityLabel: 'Bursa', lat: 40.2116, lng: 28.9867, name: 'Acarkon Store — Bursa', addr: 'Ataevler Mah. Ata Cad. Özgür Park Sit. No:H/144 Nilüfer / Bursa', maps: 'Ataevler+Mah.+Ata+Cad.+%C3%96zg%C3%BCr+Park+Sit.+No%3AH%2F144+Nil%C3%BCfer+%2F+Bursa' },
    { city: 'tekirdag', cityLabel: 'Tekirdağ', lat: 40.9781, lng: 27.5114, name: 'Acarkon Store — Tekirdağ', addr: '100. Yıl Mah. Tevfik Kaptan Sok. No:52C Süleymanpaşa / Tekirdağ', maps: '100.+Y%C4%B1l+Mah.+Tevfik+Kaptan+Sok.+No%3A52C+S%C3%BCleymanpa%C5%9Fa+%2F+Tekirda%C4%9F' },
    { city: 'edirne', cityLabel: 'Edirne', lat: 41.6771, lng: 26.5556, name: 'Acarkon Store — Edirne', addr: 'Cumhuriyet Mah. Tema Edirne Sitesi, Kuvayi Milliye Bul. Altı 81/B 37 Edirne', maps: 'Cumhuriyet+Mah.+Tema+Edirne+Sitesi%2C+Kuvayi+Milliye+Bul.+Alt%C4%B1+81%2FB+37+Edirne' },
    { city: 'mardin', cityLabel: 'Mardin', lat: 37.3129, lng: 40.7436, name: 'Acarkon Store — Mardin', addr: 'Vali Ozan Cad. Nur Mah. Kanza Binaları Altı No:55 Artuklu / Mardin', maps: 'Vali+Ozan+Cad.+Nur+Mah.+Kanza+Binalar%C4%B1+Alt%C4%B1+No%3A55+Artuklu+%2F+Mardin' },
    { city: 'eregli', cityLabel: 'Konya Ereğli', lat: 37.5063, lng: 34.0517, name: 'Acarkon Store — Ereğli', addr: 'Yunuslu Mah. Kazım Karabekir Cad. Hayat Sitesi Altı No:48/A Ereğli / Konya', maps: 'Yunuslu+Mah.+Kaz%C4%B1m+Karabekir+Cad.+Hayat+Sitesi+Alt%C4%B1+No%3A48%2FA+Ere%C4%9Fli+%2F+Konya' },
    { city: 'eskisehir', cityLabel: 'Eskişehir', lat: 39.7767, lng: 30.5206, name: 'Acarkon Store — Eskişehir', addr: 'Yakında', maps: '' },
  ];

  const storesUrl = window.SILVA_STORES_URL || (window.SILVA_ROUTES && window.SILVA_ROUTES.stores) || '';
  const esc = (v) => String(v).replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));

  const rails = document.querySelectorAll('[data-store-rail]');
  if (rails.length) {
    const cities = [...new Map(STORES.map((s) => [s.city, s.cityLabel])).entries()];
    const rowA = cities;
    const rowB = [...cities].reverse();
    const setHtml = (list) =>
      list
        .map(
          ([id, label]) =>
            `<a class="store-city-name" href="${esc(storesUrl)}${String(storesUrl).includes('?') ? '&' : '?'}city=${esc(id)}">${esc(label)}</a>`
        )
        .join('');
    const fill = (el, list) => {
      if (!el || !list.length) return;
      const loop = list.length < 8 ? [...list, ...list] : list;
      const html = setHtml(loop);
      el.innerHTML = `<div class="store-ticker-set">${html}</div><div class="store-ticker-set" aria-hidden="true">${html}</div>`;
    };
    fill(document.querySelector('[data-store-rail="ltr"]'), rowA);
    fill(document.querySelector('[data-store-rail="rtl"]'), rowB);
  }

  const grid = document.getElementById('store-grid');
  if (!grid || grid.children.length) return;

  const cardsHtml = STORES.map((store, i) => {
    const num = String(i + 1).padStart(2, '0');
    const map = store.maps
      ? `<a href="https://www.google.com/maps/dir/?api=1&destination=${store.maps}" target="_blank" rel="noopener" class="store-card-link store-card-link--map">
            <i class="bx bx-map-alt"></i>
            <span>Yol tarifi al</span>
          </a>`
      : '';
    return `<article class="store-card" data-city="${esc(store.city)}" data-lat="${store.lat}" data-lng="${store.lng}">
      <div class="store-card-top">
        <span class="store-card-num">${num}</span>
        <p class="store-card-city">${esc(store.cityLabel)}</p>
      </div>
      <h3 class="store-card-name">${esc(store.name)}</h3>
      <p class="store-card-addr">${esc(store.addr)}</p>
      <div class="store-card-actions">
        <a href="tel:+908503460226" class="store-card-link">
          <i class="bx bx-phone"></i>
          +90 850 346 02 26
        </a>
        ${map}
      </div>
    </article>`;
  }).join('');

  grid.innerHTML = cardsHtml;
})();
