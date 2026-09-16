window.SILVA_PROJECT_PLACES = window.SILVA_PROJECT_PLACES || { all: 'Tümü', indoor: 'İç mekân', outdoor: 'Dış mekân' };
window.SILVA_PROJECT_TYPES = window.SILVA_PROJECT_TYPES || {
  all: 'Tümü',
  otel: 'Otel',
  restoran: 'Restoran',
  konut: 'Konut',
  ofis: 'Ofis',
  cephe: 'Cephe',
};
window.SILVA_PROJECTS = window.SILVA_PROJECTS || [];
window.SILVA_PROJECTS_URL = window.SILVA_PROJECTS_URL || 'projeler.html';
window.silvaProjectHref =
  window.silvaProjectHref ||
  ((p) => (p && p.url) || window.SILVA_PROJECTS_URL + '/' + encodeURIComponent(p.slug || p.id));

(function homeProjectsRail() {
  const rail = document.getElementById('projects-rail');
  if (!rail) return;
  const esc = (v) => String(v).replace(/[&<>"]/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]));
  const types = window.SILVA_PROJECT_TYPES || {};
  const places = window.SILVA_PROJECT_PLACES || {};
  const href = window.silvaProjectHref;
  rail.innerHTML = (window.SILVA_PROJECTS || [])
    .slice(0, 6)
    .map((p, i) => {
      const num = String(i + 1).padStart(2, '0');
      const type = types[p.type] || p.type;
      const place = places[p.place] || p.place;
      return `<a href="${esc(href(p))}" class="project-panel">
        <img src="${esc(p.img)}" alt="${esc(p.title)}" class="project-panel-img" />
        <div class="project-panel-shade"></div>
        <div class="project-panel-meta">
          <span class="project-panel-num">${num}</span>
          <h3>${esc(p.title)}</h3>
          <p>${esc(type)} · ${esc(p.cityLabel)} · ${esc(place)}</p>
        </div>
      </a>`;
    })
    .join('');
})();
