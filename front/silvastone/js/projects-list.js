(function projectListing() {
  const grid = document.getElementById('project-grid');
  if (!grid) return;

  const root = document.getElementById('project-list');
  const countEl = document.getElementById('project-count');
  const emptyEl = document.getElementById('project-empty');
  const resetBtn = document.getElementById('project-reset');
  const emptyReset = document.getElementById('project-empty-reset');
  const citySelect = document.getElementById('project-city');
  const PROJECTS = window.SILVA_PROJECTS || [];
  const PLACES = window.SILVA_PROJECT_PLACES || {};
  const TYPES = window.SILVA_PROJECT_TYPES || {};

  const cities = [...new Map(PROJECTS.map((p) => [p.city, p.cityLabel])).entries()].sort((a, b) =>
    a[1].localeCompare(b[1], 'tr')
  );

  if (citySelect) {
    citySelect.innerHTML =
      `<option value="all">Tüm şehirler</option>` +
      cities.map(([id, label]) => `<option value="${id}">${label}</option>`).join('');
  }

  const defaults = { place: 'all', type: 'all', city: 'all' };

  const readState = () => {
    const q = new URLSearchParams(location.search);
    const place = PLACES[q.get('place')] ? q.get('place') : 'all';
    const type = TYPES[q.get('type')] ? q.get('type') : 'all';
    const city = cities.some(([id]) => id === q.get('city')) ? q.get('city') : 'all';
    return { place, type, city };
  };

  const writeState = (s) => {
    const q = new URLSearchParams();
    if (s.place !== 'all') q.set('place', s.place);
    if (s.type !== 'all') q.set('type', s.type);
    if (s.city !== 'all') q.set('city', s.city);
    const base = window.SILVA_PROJECTS_URL || location.pathname;
    history.replaceState({}, '', q.toString() ? `${base}?${q}` : base);
  };

  const filtered = (s) =>
    PROJECTS.filter((p) => {
      if (s.place !== 'all' && p.place !== s.place) return false;
      if (s.type !== 'all' && p.type !== s.type) return false;
      if (s.city !== 'all' && p.city !== s.city) return false;
      return true;
    });

  const esc = (v) => String(v).replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));

  const sync = (s) => {
    root.querySelectorAll('[data-filter="place"]').forEach((btn) => {
      btn.classList.toggle('is-active', btn.dataset.value === s.place);
    });
    root.querySelectorAll('[data-filter="type"]').forEach((btn) => {
      btn.classList.toggle('is-active', btn.dataset.value === s.type);
    });
    if (citySelect && citySelect.value !== s.city) citySelect.value = s.city;
    citySelect?.closest('.plp-city')?.classList.toggle('is-active', s.city !== 'all');
    root.classList.toggle('is-filtered', s.place !== 'all' || s.type !== 'all' || s.city !== 'all');
  };

  const render = () => {
    const s = readState();
    const list = filtered(s);
    sync(s);
    if (countEl) countEl.textContent = `${list.length} proje`;
    if (!list.length) {
      grid.innerHTML = '';
      if (emptyEl) emptyEl.hidden = false;
      return;
    }
    if (emptyEl) emptyEl.hidden = true;
    grid.innerHTML = list
      .map((p, i) => {
        const num = String(i + 1).padStart(2, '0');
        const type = TYPES[p.type] || p.type;
        const place = PLACES[p.place] || p.place;
        return `<a href="${esc(window.silvaProjectHref(p))}" class="project-card">
          <img src="${esc(p.img)}" alt="${esc(p.title)}" />
          <div class="project-card-shade"></div>
          <span class="project-card-place">${esc(place)}</span>
          <div class="project-card-meta">
            <span class="project-card-num">${num}</span>
            <h2>${esc(p.title)}</h2>
            <p>${esc(type)} · ${esc(p.cityLabel)}${p.product ? ` · ${esc(p.product)}` : ''}</p>
          </div>
        </a>`;
      })
      .join('');
  };

  const setFilter = (key, value) => {
    const s = readState();
    s[key] = value;
    writeState(s);
    render();
  };

  const reset = () => {
    writeState({ ...defaults });
    render();
  };

  root.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-filter]');
    if (!btn || !root.contains(btn)) return;
    setFilter(btn.dataset.filter, btn.dataset.value);
  });
  citySelect?.addEventListener('change', () => {
    setFilter('city', citySelect.value || 'all');
  });
  resetBtn?.addEventListener('click', reset);
  emptyReset?.addEventListener('click', reset);

  render();
})();
