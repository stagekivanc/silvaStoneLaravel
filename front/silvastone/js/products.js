window.SILVA_CATS = window.SILVA_CATS || { all: 'Tümü', stonex: 'Stonex', stoneart: 'Stoneart' };
window.SILVA_COLORS = window.SILVA_COLORS || { all: 'Tümü', beyaz: 'Beyaz', krem: 'Krem', bej: 'Bej', antrasit: 'Antrasit', siyah: 'Siyah' };
window.SILVA_PRODUCTS = window.SILVA_PRODUCTS || [];
window.SILVA_PRODUCTS_URL = window.SILVA_PRODUCTS_URL || 'urunler.html';
window.silvaTitle =
  window.silvaTitle ||
  ((p) => (p && (p.title || String(p.name || '').replace(/\s+Duvar Paneli$/, ''))) || '');
window.silvaHref =
  window.silvaHref ||
  ((p) => (p && p.href) || window.SILVA_PRODUCTS_URL + '/' + encodeURIComponent((p && (p.slug || p.code)) || ''));
window.silvaFold =
  window.silvaFold ||
  ((s) =>
  String(s || '')
    .toLocaleLowerCase('tr')
    .replace(/ı/g, 'i')
    .replace(/İ/g, 'i')
    .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, ''));
window.silvaMatch =
  window.silvaMatch ||
  ((p, q) => {
  const needle = window.silvaFold(q).replace(/\s+/g, ' ').trim();
  if (!needle) return true;
  const hay = window.silvaFold(
    [p.code, p.name, window.silvaTitle(p), p.cat, window.SILVA_CATS[p.cat], window.SILVA_COLORS[p.color], p.color].join(' ')
  );
  const compact = hay.replace(/\s+/g, '');
  return needle.split(' ').every((part) => hay.includes(part) || compact.includes(part.replace(/\s/g, '')));
  });
