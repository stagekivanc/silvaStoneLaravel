(function projectDetail() {
  const root = document.getElementById('project-root');
  if (!root || !window.SILVA_PROJECTS) return;

  const projects = window.SILVA_PROJECTS;
  const places = window.SILVA_PROJECT_PLACES || {};
  const types = window.SILVA_PROJECT_TYPES || {};
  const esc = (v) => String(v).replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));
  const hrefOf = window.silvaProjectHref;

  const id = new URLSearchParams(location.search).get('id');
  const project = projects.find((p) => p.id === id);
  if (!project) {
    location.replace(window.SILVA_PROJECTS_URL || window.SILVA_ROUTES?.projects || '/');
    return;
  }

  const typeName = types[project.type] || project.type;
  const placeName = places[project.place] || project.place;
  const gallery = (project.imgs && project.imgs.length ? project.imgs : [project.img]).filter(Boolean);
  const product = (window.SILVA_PRODUCTS || []).find(
    (p) =>
      p.code === project.product ||
      window.silvaTitle(p) === project.product ||
      p.name === project.product
  );
  const pageUrl = `https://silvastone.acarkon.com/${hrefOf(project)}`;
  const feats = project.feats && project.feats.length ? project.feats : ['A2 yangın sınıfı', 'İç ve dış mekâna uygun', 'Hafif ve hızlı montaj', 'Su ve neme dayanıklı'];

  document.title = `${project.title} | Silva Stone`;
  document.querySelector('meta[name="description"]')?.setAttribute('content', project.lead || project.title);
  document.querySelector('link[rel="canonical"]')?.setAttribute('href', pageUrl);
  document.querySelector('meta[property="og:title"]')?.setAttribute('content', `${project.title} | Silva Stone`);
  document.querySelector('meta[property="og:url"]')?.setAttribute('href', pageUrl);
  document.querySelector('meta[property="og:description"]')?.setAttribute('content', project.lead || '');
  if (gallery[0]) document.querySelector('meta[property="og:image"]')?.setAttribute('content', gallery[0]);

  const projectsListUrl = window.SILVA_PROJECTS_URL || window.SILVA_ROUTES?.projects || '/';
  const productsListUrl = window.SILVA_PRODUCTS_URL || window.SILVA_ROUTES?.products || '/';
  const contactUrl = window.SILVA_ROUTES?.contact || '/';
  const crumb = document.getElementById('pj-crumb');
  if (crumb) {
    crumb.innerHTML = `
      <a href="${esc(projectsListUrl)}">Projeler</a>
      <span>/</span>
      <a href="${esc(projectsListUrl)}?type=${esc(project.type)}">${esc(typeName)}</a>
      <span>/</span>
      <span>${esc(project.title)}</span>`;
  }

  const hero = document.getElementById('pj-hero');
  if (hero && gallery[0]) {
    hero.innerHTML = `
      <button type="button" class="pj-hero-open" aria-label="Görseli büyüt">
        <img src="${esc(gallery[0])}" alt="${esc(project.title)}" />
      </button>
      <div class="pj-hero-cap">
        <div class="pj-hero-cap-inner">
          <p class="page-intro-kicker font-display italic">${esc(typeName)} · ${esc(placeName)}</p>
          <h1>${esc(project.title)}</h1>
          <span>${esc(project.cityLabel)}${project.year ? ` · ${esc(project.year)}` : ''}</span>
        </div>
      </div>`;
    hero.addEventListener('click', (e) => {
      if (!e.target.closest('.pj-hero-open') || !window.Fancybox) return;
      window.Fancybox.show(
        gallery.map((src) => ({ src, type: 'image', caption: project.title })),
        { startIndex: 0, Hash: false }
      );
    });
  }

  const facts = document.getElementById('pj-facts');
  if (facts) {
    const rows = [
      ['Şehir', project.cityLabel],
      ['Mekân', placeName],
      ['Tip', typeName],
      ['Yüzey', project.product || '—'],
      ['Yıl', project.year || '—'],
      ['Alan', project.area || '—'],
    ];
    facts.innerHTML = rows
      .map(([dt, dd]) => `<div><dt>${esc(dt)}</dt><dd>${esc(dd)}</dd></div>`)
      .join('');
  }

  const copy = document.getElementById('pj-copy');
  if (copy) {
    copy.innerHTML = `
      <p class="page-intro-kicker font-display italic">Hikâye</p>
      <p class="pj-lead">${esc(project.lead || '')}</p>
      <p class="pj-body">${esc(project.body || '')}</p>`;
  }

  const aside = document.getElementById('pj-aside');
  if (aside) {
    const productHref = product ? window.silvaHref(product) : project.product ? `${productsListUrl}?q=${encodeURIComponent(project.product)}` : productsListUrl;
    const productTitle = product ? window.silvaTitle(product) : project.product || 'Koleksiyon';
    const productImg = product && product.img ? product.img : gallery[0];
    aside.innerHTML = `
      <p class="page-intro-kicker font-display italic">Yüzey</p>
      <a class="pj-surface" href="${esc(productHref)}">
        ${productImg ? `<img src="${esc(productImg)}" alt="${esc(productTitle)}" />` : ''}
        <span>
          <strong>${esc(productTitle)}</strong>
          <em>Kullanılan panel</em>
        </span>
      </a>
      <a href="${esc(contactUrl)}" class="pj-cta">Bu uygulamayı konuş</a>
      <a href="${esc(projectsListUrl)}?type=${esc(project.type)}" class="pj-ghost">${esc(typeName)} projeleri</a>`;
  }

  const galleryEl = document.getElementById('pj-gallery');
  const rest = gallery.slice(1);
  if (galleryEl && rest.length) {
    galleryEl.innerHTML = rest
      .map(
        (src, i) =>
          `<button type="button" class="pj-shot" data-index="${i + 1}" aria-label="Görseli büyüt">
            <img src="${esc(src)}" alt="${esc(project.title)}" />
          </button>`
      )
      .join('');
    galleryEl.addEventListener('click', (e) => {
      const btn = e.target.closest('.pj-shot');
      if (!btn || !window.Fancybox) return;
      const start = Number(btn.dataset.index) || 0;
      window.Fancybox.show(
        gallery.map((src) => ({ src, type: 'image', caption: project.title })),
        { startIndex: start, Hash: false }
      );
    });
  }

  const featsEl = document.getElementById('project-feats');
  if (featsEl) {
    featsEl.innerHTML = feats
      .map(
        (item, i) => `<article class="pj-note">
          <span>${String(i + 1).padStart(2, '0')}</span>
          <h3>${esc(item)}</h3>
        </article>`
      )
      .join('');
  }

  const related = projects.filter((p) => p.id !== project.id && (p.type === project.type || p.place === project.place)).slice(0, 3);
  const relatedWrap = document.getElementById('project-related');
  if (relatedWrap) {
    relatedWrap.innerHTML = (related.length ? related : projects.filter((p) => p.id !== project.id).slice(0, 3))
      .map((p) => {
        const t = types[p.type] || p.type;
        const pl = places[p.place] || p.place;
        return `<a href="${esc(hrefOf(p))}" class="project-card">
          <img src="${esc(p.img)}" alt="${esc(p.title)}" />
          <div class="project-card-shade"></div>
          <span class="project-card-place">${esc(pl)}</span>
          <div class="project-card-meta">
            <h2>${esc(p.title)}</h2>
            <p>${esc(t)} · ${esc(p.cityLabel)}</p>
          </div>
        </a>`;
      })
      .join('');
  }
})();
