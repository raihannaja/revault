const $ = s => document.querySelector(s);
const esc = s => String(s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
const KEY = 'revault-v2';
const seed = () => ({
  role: 'penerima',
  items: [
    { id: 1, nama: 'Pakaian layak pakai', kategori: 'Pakaian', kondisi: 'Baik', lokasi: 'Malang', deskripsi: 'Satu kantong kemeja dan kaos, sudah dicuci.', foto: 'https://picsum.photos/seed/pakaian/600/420', status: 'tersedia', wa: '6281234567801', owner: 'Rina' },
    { id: 2, nama: 'Kardus bekas', kategori: 'Kardus', kondisi: 'Baik', lokasi: 'Malang', deskripsi: '12 kardus ukuran sedang, cocok untuk pindahan.', foto: 'https://picsum.photos/seed/kardus/600/420', status: 'tersedia', wa: '6281234567802', owner: 'Budi' },
    { id: 3, nama: 'Buku bekas kuliah', kategori: 'Buku', kondisi: 'Baik', lokasi: 'Malang', deskripsi: 'Buku teknik dan manajemen semester 1-4.', foto: 'https://picsum.photos/seed/buku/600/420', status: 'tersedia', wa: '6281234567801', owner: 'Rina' },
    { id: 4, nama: 'Kipas angin meja', kategori: 'Elektronik', kondisi: 'Cukup', lokasi: 'Malang', deskripsi: 'Masih menyala, kecepatan 2 kurang stabil.', foto: 'https://picsum.photos/seed/kipas/600/420', status: 'tersedia', wa: '6281234567803', owner: 'Sari' }
  ],
  requests: [], me: {}
});
let state;
try { state = JSON.parse(localStorage.getItem(KEY)) || seed(); } catch { state = seed(); }
const save = () => { try { localStorage.setItem(KEY, JSON.stringify(state)); } catch { } };
const OWNER = 'Saya'; // identitas penyalur demo
let activeItem = null, photoData = '';

function toast(msg) {
  const t = $('#toast'); t.textContent = msg;
  t.classList.remove('opacity-0', 'translate-y-4');
  clearTimeout(toast.t); toast.t = setTimeout(() => t.classList.add('opacity-0', 'translate-y-4'), 2400);
}
const normWa = v => {
  let d = String(v).replace(/\D/g, '');
  if (d.startsWith('0')) d = '62' + d.slice(1);
  else if (d.startsWith('8')) d = '62' + d;
  return d;
};
const validWa = d => /^62\d{8,13}$/.test(d);
const waLink = (it, text) => `https://wa.me/${it.wa}?text=${encodeURIComponent(text)}`;
const WA_ICON = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a9 9 0 0 1-13.4 7.8L3 21l1.3-4.4A9 9 0 1 1 21 12Z"/><path d="M9 9c0 3 3 6 6 6l1-2-2-1-1 .8a4 4 0 0 1-1.800-1.800L12 10l-1-2-2 1Z"/></svg>';
const imgTag = (src, cls) => src ? `<img src="${esc(src)}" alt="" class="${cls}" onerror="this.style.opacity=0">` : `<div class="${cls}"></div>`;
const badge = s => {
  const m = {
    menunggu: ['bg-sun/25 text-amber-900', 'Menunggu'], diterima: ['bg-forest-soft text-forest', 'Diterima'], ditolak: ['bg-red-100 text-red-800', 'Ditolak'],
    tersedia: ['bg-forest-soft text-forest', 'Tersedia'], tersalurkan: ['bg-ink/10 text-ink', 'Tersalurkan']
  }[s];
  return `<span class="rounded-full px-3 py-1 text-xs font-medium ${m[0]}">${m[1]}</span>`;
};

function setRole(r) {
  state.role = r; save();
  document.querySelectorAll('.role-btn').forEach(b => {
    const on = b.dataset.role === r;
    b.classList.toggle('bg-forest', on); b.classList.toggle('text-white', on); b.setAttribute('aria-pressed', on);
  });
  $('#panelPenerima').classList.toggle('hidden', r !== 'penerima');
  $('#panelPenyalur').classList.toggle('hidden', r !== 'penyalur');
  render();
}

function renderStats() {
  $('#statAvail').textContent = state.items.filter(i => i.status === 'tersedia').length;
  $('#statGiven').textContent = state.items.filter(i => i.status === 'tersalurkan').length;
}

function renderGrid() {
  const q = $('#fSearch').value.trim().toLowerCase(), k = $('#fKategori').value, c = $('#fKondisi').value, s = $('#fSort').value;
  let list = state.items.filter(i => i.status === 'tersedia' && (!q || q.split(/\s+/).every(w => `${i.nama} ${i.kategori} ${i.deskripsi || ''} ${i.lokasi}`.toLowerCase().includes(w))) && (!k || i.kategori === k) && (!c || i.kondisi === c));
  list = s === 'nama' ? list.sort((a, b) => a.nama.localeCompare(b.nama)) : list.sort((a, b) => b.id - a.id);
  $('#resultInfo').textContent = `${list.length} barang ditemukan`;
  $('#empty').classList.toggle('hidden', list.length > 0);
  $('#grid').innerHTML = list.map(i => {
    const asked = state.requests.some(r => r.itemId === i.id && r.status === 'menunggu');
    return `<article class="group flex flex-col overflow-hidden rounded-2xl border border-forest/10 bg-mist transition duration-300 hover:-translate-y-1.5 hover:border-forest/30 hover:shadow-xl">
      <button type="button" data-zoom="${i.id}" class="zoom-btn relative block w-full cursor-zoom-in overflow-hidden focus-ring" aria-label="Lihat foto ${esc(i.nama)} secara utuh">
  ${imgTag(i.foto, 'h-48 w-full object-cover bg-forest-soft transition duration-500 group-hover:scale-105')}
  <span class="pointer-events-none absolute inset-x-0 bottom-0 flex items-center justify-center gap-2 bg-gradient-to-t from-ink/70 to-transparent py-3 text-sm font-medium text-white opacity-0 transition group-hover:opacity-100">Lihat foto utuh</span>
</button>
      <div class="flex flex-1 flex-col p-5">
        <div class="flex items-start justify-between gap-2"><h3 class="font-display text-xl font-bold">${esc(i.nama)}</h3>
        <span class="shrink-0 rounded-full bg-forest-soft px-3 py-1 text-xs text-forest">${esc(i.kategori)}</span></div>
        <p class="mt-1 text-sm text-ink/60">Kondisi ${esc(i.kondisi)}, ${esc(i.lokasi)}</p>
        <p class="mt-3 flex-1 text-sm text-ink/80">${esc(i.deskripsi || '')}</p>
        <div class="mt-4 flex flex-wrap gap-2">
          <button data-ask="${i.id}" ${asked ? 'disabled' : ''} class="focus-ring rounded-full bg-forest px-5 py-2.5 text-sm font-medium text-white transition hover:bg-forest-dark disabled:bg-ink/20 disabled:text-ink/60">${asked ? 'Pengajuan terkirim' : 'Ajukan pengambilan'}</button>
          ${i.wa ? `<a href="${waLink(i, `Halo ${i.owner}, saya melihat "${i.nama}" di Revault. Apakah masih tersedia?`)}" target="_blank" rel="noopener" class="focus-ring inline-flex items-center gap-2 rounded-full border border-forest px-4 py-2.5 text-sm font-medium text-forest transition hover:bg-forest-soft">${WA_ICON} Chat WA</a>` : ''}
        </div>
      </div></article>`;
  }).join('');
}

function renderMyRequests() {
  const list = state.requests.slice().reverse();
  $('#myRequests').innerHTML = list.length ? list.map(r => {
    const it = state.items.find(i => i.id === r.itemId);
    return `<div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl bg-white p-4 border border-forest/10 transition hover:border-forest/30 hover:shadow-md">
      <div><p class="font-medium">${esc(it ? it.nama : 'Barang dihapus')}</p><p class="text-sm text-ink/60">Pesan: ${esc(r.pesan)}</p>
      ${r.status === 'diterima' ? `<p class="mt-1 text-sm text-forest">Disetujui. Hubungi ${esc(it.owner)} untuk mengatur serah terima.</p>
        ${it.wa ? `<a href="${waLink(it, `Halo ${it.owner}, permintaan saya untuk "${it.nama}" sudah disetujui. Kapan dan di mana saya bisa mengambilnya?`)}" target="_blank" rel="noopener" class="focus-ring mt-2 inline-flex items-center gap-2 rounded-full bg-forest px-4 py-2 text-sm font-medium text-white hover:bg-forest-dark">${WA_ICON} Hubungi via WhatsApp</a>` : ''}` : ''}</div>
      <div class="flex items-center gap-3">${badge(r.status)}
      ${r.status === 'menunggu' ? `<button data-cancel="${r.id}" class="focus-ring text-sm text-red-700 underline">Batalkan</button>` : ''}</div></div>`;
  }).join('') : `<p class="rounded-2xl border border-dashed border-forest/30 p-6 text-sm text-ink/60">Kamu belum mengajukan barang apa pun. Pilih barang di atas untuk memulai.</p>`;
}

function renderPenyalur() {
  const mine = state.items.filter(i => i.owner === OWNER).reverse();
  $('#myItems').innerHTML = mine.length ? mine.map(i => `
    <div class="flex items-center gap-4 rounded-2xl border border-forest/10 bg-white p-3 transition hover:border-forest/30 hover:shadow-md">
      ${imgTag(i.foto, 'h-16 w-16 shrink-0 rounded-xl object-cover bg-forest-soft')}
      <div class="min-w-0 flex-1"><p class="truncate font-medium">${esc(i.nama)}</p><p class="text-sm text-ink/60">${esc(i.kategori)}, ${esc(i.kondisi)}</p>
      ${i.wa ? `<p class="text-xs text-ink/50">WA: +${esc(i.wa)}</p>` : ''}</div>
      ${badge(i.status)}
      <button data-del="${i.id}" class="focus-ring text-sm text-red-700 underline">Hapus</button>
    </div>`).join('') : `<p class="rounded-2xl border border-dashed border-forest/30 p-6 text-sm text-ink/60">Belum ada barang yang kamu posting. Isi formulir di atas untuk menyalurkan barang pertamamu.</p>`;

  const ids = mine.map(i => i.id);
  const inc = state.requests.filter(r => ids.includes(r.itemId)).reverse();
  $('#incoming').innerHTML = inc.length ? inc.map(r => {
    const it = state.items.find(i => i.id === r.itemId);
    return `<div class="rounded-2xl border border-forest/10 bg-white p-4 transition hover:border-forest/30 hover:shadow-md">
      <div class="flex items-start justify-between gap-2"><p class="font-medium">${esc(r.nama)} ingin mengambil ${esc(it.nama)}</p>${badge(r.status)}</div>
      <p class="mt-1 text-sm text-ink/70">${esc(r.pesan)}</p>
      ${r.status === 'menunggu' ? `<div class="mt-3 flex gap-2">
        <button data-accept="${r.id}" class="focus-ring rounded-full bg-forest px-4 py-1.5 text-sm text-white hover:bg-forest-dark">Terima</button>
        <button data-reject="${r.id}" class="focus-ring rounded-full border border-ink/20 px-4 py-1.5 text-sm hover:bg-mist">Tolak</button></div>` : ''}
    </div>`;
  }).join('') : `<p class="rounded-2xl border border-dashed border-forest/30 p-6 text-sm text-ink/60">Belum ada permintaan. Pindah ke peran Penerima untuk mencoba mengajukan barang.</p>`;
}

function render() { renderStats(); state.role === 'penerima' ? (renderGrid(), renderMyRequests()) : renderPenyalur(); }

/* Modal */
function openModal(id) {
  const it = state.items.find(i => i.id === id); if (!it) return; activeItem = it;
  $('#mTitle').textContent = it.nama;
  $('#mMeta').textContent = `${it.kategori}, kondisi ${it.kondisi}, ${it.lokasi}`;
  $('#mDesc').textContent = it.deskripsi || '';
  $('#mNama').value = state.me.nama || ''; $('#mPesan').value = ''; $('#mErr').classList.add('hidden');
  $('#modal').classList.replace('hidden', 'flex'); $('#mNama').focus();
}
const closeModal = () => $('#modal').classList.replace('flex', 'hidden');

/* Events */
document.querySelectorAll('.role-btn').forEach(b => b.addEventListener('click', () => setRole(b.dataset.role)));
document.querySelectorAll('[data-goto]').forEach(b => b.addEventListener('click', () => {
  setRole(b.dataset.goto);
  document.getElementById(b.dataset.goto === 'penerima' ? 'barang' : 'posting').scrollIntoView({ behavior: 'smooth' });
}));
$('#menuBtn').addEventListener('click', () => $('#mobileNav').classList.toggle('hidden'));
$('#mobileNav').addEventListener('click', e => { if (e.target.tagName === 'A') $('#mobileNav').classList.add('hidden'); });

['#fKategori', '#fKondisi', '#fSort'].forEach(s => $(s).addEventListener('change', renderGrid));
$('#fSearch').addEventListener('input', renderGrid);
$('#resetFilter').addEventListener('click', () => { $('#fSearch').value = ''; $('#fKategori').value = ''; $('#fKondisi').value = ''; $('#fSort').value = 'baru'; renderGrid(); });

$('#grid').addEventListener('click', e => {
  const z = e.target.closest('[data-zoom]'); if (z) return openLightbox(+z.dataset.zoom);
  const b = e.target.closest('[data-ask]'); if (b) openModal(+b.dataset.ask);
});

/* Lightbox: lihat foto utuh */
function openLightbox(id) {
  const it = state.items.find(i => i.id === id); if (!it || !it.foto) return;
  $('#lbImg').src = it.foto; $('#lbImg').alt = it.nama;
  $('#lbCap').textContent = `${it.nama} - ${it.kategori}, kondisi ${it.kondisi}`;
  $('#lightbox').classList.replace('hidden', 'flex');
  document.body.classList.add('overflow-hidden');
  $('#lbClose').focus();
}
function closeLightbox() {
  $('#lightbox').classList.replace('flex', 'hidden');
  document.body.classList.remove('overflow-hidden');
}
$('#lbClose').addEventListener('click', closeLightbox);
$('#lightbox').addEventListener('click', e => { if (e.target.id === 'lightbox') closeLightbox(); });
$('#mCancel').addEventListener('click', closeModal);
$('#modal').addEventListener('click', e => { if (e.target === $('#modal')) closeModal(); });
document.addEventListener('keydown', e => { if(e.key==='Escape'){ closeModal(); closeLightbox(); } });
$('#mSend').addEventListener('click', () => {
  const nama = $('#mNama').value.trim(), pesan = $('#mPesan').value.trim();
  if (!nama || !pesan) { $('#mErr').classList.remove('hidden'); return; }
  state.me.nama = nama;
  state.requests.push({ id: Date.now(), itemId: activeItem.id, nama, pesan, status: 'menunggu' });
  save(); closeModal(); render(); toast('Pengajuan terkirim ke penyalur');
});
$('#myRequests').addEventListener('click', e => {
  const b = e.target.closest('[data-cancel]'); if (!b) return;
  state.requests = state.requests.filter(r => r.id !== +b.dataset.cancel); save(); render(); toast('Pengajuan dibatalkan');
});

$('#foto').addEventListener('change', e => {
  const f = e.target.files[0]; const p = $('#preview');
  if (!f) { photoData = ''; p.classList.add('hidden'); return; }
  const rd = new FileReader();
  rd.onload = () => { photoData = rd.result; p.src = photoData; p.classList.remove('hidden'); };
  rd.readAsDataURL(f);
});
$('#postForm').addEventListener('submit', e => {
  e.preventDefault();
  const nama = $('#nama').value.trim();
  $('[data-err="nama"]').classList.toggle('hidden', !!nama);
  const wa = normWa($('#wa').value);
  $('[data-err="wa"]').classList.toggle('hidden', validWa(wa));
  if (!nama) return $('#nama').focus();
  if (!validWa(wa)) return $('#wa').focus();
  state.items.push({
    id: Date.now(), nama, kategori: $('#kategori').value, kondisi: $('#kondisi').value, lokasi: $('#lokasi').value.trim() || 'Malang',
    deskripsi: $('#deskripsi').value.trim(), foto: photoData || `https://picsum.photos/seed/${encodeURIComponent(nama)}/600/420`, status: 'tersedia', wa, owner: OWNER
  });
  save(); e.target.reset(); photoData = ''; $('#preview').classList.add('hidden'); render(); toast('Barang berhasil diposting');
});
$('#myItems').addEventListener('click', e => {
  const b = e.target.closest('[data-del]'); if (!b) return;
  const id = +b.dataset.del;
  state.items = state.items.filter(i => i.id !== id); state.requests = state.requests.filter(r => r.itemId !== id);
  save(); render(); toast('Barang dihapus');
});
$('#incoming').addEventListener('click', e => {
  const a = e.target.closest('[data-accept]'), r = e.target.closest('[data-reject]');
  if (!a && !r) return;
  const req = state.requests.find(x => x.id === +(a || r).dataset[a ? 'accept' : 'reject']);
  if (a) {
    req.status = 'diterima';
    state.items.find(i => i.id === req.itemId).status = 'tersalurkan';
    state.requests.forEach(x => { if (x.itemId === req.itemId && x.status === 'menunggu') x.status = 'ditolak'; });
    toast('Permintaan diterima, barang ditandai tersalurkan');
  } else { req.status = 'ditolak'; toast('Permintaan ditolak'); }
  save(); render();
});

/* Pencarian dari hero */
function goSearch() {
  setRole('penerima');
  renderGrid();
  document.getElementById('barang').scrollIntoView({ behavior: 'smooth' });
}
$('#heroSearch').addEventListener('submit', e => {
  e.preventDefault();
  $('#fSearch').value = $('#heroQ').value.trim();
  $('#fKategori').value = ''; $('#fKondisi').value = '';
  goSearch();
});
document.querySelectorAll('[data-chip]').forEach(b => b.addEventListener('click', () => {
  $('#fSearch').value = ''; $('#heroQ').value = ''; $('#fKondisi').value = '';
  $('#fKategori').value = b.dataset.chip;
  goSearch();
}));

setRole(state.role);
