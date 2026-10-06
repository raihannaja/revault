/* =====================================================================
   Implementasi REST API & Fetch API (Single Page Application)
   File ini berdiri sendiri dan tidak mengubah script.js.
   Memakai helper yang sudah ada di script.js: $, esc, imgTag, goSearch.

   Task 01: Connect to API (fetch API & parsing JSON)
   Task 02: Display API Data (DOM manipulation dinamis)
   Task 03: Handle API State (loading, success, error, tombol Coba Lagi)
   ===================================================================== */

/* ---------- TASK 01: Connect to API ---------- */
const API_URL = 'data/barang.json';              // endpoint data barang
const API_URL_SALAH = 'data/tidak-ditemukan.json'; // hanya untuk demo error (?demo=error)
const API_TIMEOUT = 8000;                        // batas waktu permintaan (ms)
const API_MIN_LOADING = 700;                     // loading minimal agar spinner terlihat (ms)
let apiDemoError = new URLSearchParams(location.search).get('demo') === 'error';

// Pesan untuk status HTTP (mirip contoh error: { "status": 404, "message": "Data tidak ditemukan" })
const pesanStatus = st =>
  st === 404 ? 'Data tidak ditemukan' :
  st >= 500 ? 'Terjadi gangguan pada server' :
  'Permintaan ditolak oleh server';

// Error khusus agar tiap kegagalan punya pesan yang jelas untuk pengguna.
class ApiError extends Error {
  constructor(message, type) { super(message); this.type = type; }
}

async function fetchBarang() {
  const ctrl = new AbortController();
  const timer = setTimeout(() => ctrl.abort(), API_TIMEOUT);
  try {
    const url = apiDemoError ? API_URL_SALAH : API_URL;
    console.log(`[API] GET ${url} ... mengirim request`);
    const res = await fetch(url, {
      signal: ctrl.signal, cache: 'no-store', headers: { Accept: 'application/json' }
    });
    // fetch() tidak melempar error untuk status 404/500, jadi res.ok dicek manual.
    if (!res.ok) throw new ApiError(`Status ${res.status}: ${pesanStatus(res.status)}`, 'http');
    console.log(`[API] GET ${url} -> ${res.status} OK (request berhasil)`);
    const json = await res.json(); // parsing body JSON menjadi objek JavaScript
    if (!json || !Array.isArray(json.data)) throw new ApiError('Format data dari server tidak sesuai.', 'format');
    console.log('[API] JSON berhasil dibaca:', json);
    return json.data;
  } catch (err) {
    if (err instanceof ApiError) throw err;
    if (err.name === 'AbortError') throw new ApiError('Permintaan terlalu lama. Periksa koneksi internetmu.', 'timeout');
    if (err instanceof SyntaxError) throw new ApiError('Data dari server tidak bisa dibaca (bukan JSON yang valid).', 'parse');
    throw new ApiError('Tidak dapat terhubung ke server. Periksa koneksimu dan pastikan website dibuka lewat Live Server.', 'network');
  } finally {
    clearTimeout(timer);
  }
}

// Rapikan data dari API agar aman dipakai (buang data tanpa nama, beri nilai default).
const normalisasiBarang = list => list
  .filter(b => b && b.nama)
  .map(b => ({
    id: b.id, nama: b.nama, kategori: b.kategori || 'Lainnya', kondisi: b.kondisi || '-',
    lokasi: b.lokasi || '-', deskripsi: b.deskripsi || '', foto: b.foto || '', penyalur: b.penyalur || ''
  }));

/* ---------- TASK 02: Display API Data ---------- */
function renderApiBarang(items) {
  $('#apiGrid').innerHTML = items.map(i => `
    <article class="group flex flex-col overflow-hidden rounded-2xl border border-forest/10 bg-mist transition duration-300 hover:-translate-y-1.5 hover:border-forest/30 hover:shadow-xl">
      <div class="overflow-hidden">
        ${imgTag(i.foto, 'h-48 w-full object-cover bg-forest-soft transition duration-500 group-hover:scale-105')}
      </div>
      <div class="flex flex-1 flex-col p-5">
        <div class="flex items-start justify-between gap-2">
          <h3 class="font-display text-xl font-bold">${esc(i.nama)}</h3>
          <span class="shrink-0 rounded-full bg-forest-soft px-3 py-1 text-xs text-forest">${esc(i.kategori)}</span>
        </div>
        <p class="mt-1 text-sm text-ink/60">Kondisi ${esc(i.kondisi)}, ${esc(i.lokasi)}${i.penyalur ? ` &middot; oleh ${esc(i.penyalur)}` : ''}</p>
        <p class="mt-3 flex-1 text-sm text-ink/80">${esc(i.deskripsi)}</p>
        <button type="button" data-cari="${esc(i.kategori)}" class="focus-ring mt-4 self-start rounded-full border border-forest px-5 py-2.5 text-sm font-medium text-forest hover:bg-forest-soft">Cari serupa</button>
      </div>
    </article>`).join('');
}

// Tombol "Cari serupa" memakai fitur pencarian Revault yang sudah ada.
$('#apiGrid').addEventListener('click', e => {
  const b = e.target.closest('[data-cari]'); if (!b) return;
  $('#fSearch').value = ''; $('#heroQ').value = ''; $('#fKondisi').value = '';
  $('#fKategori').value = b.dataset.cari;
  goSearch();
});

/* ---------- TASK 03: Handle API State ---------- */
const API_VIEWS = { loading: '#apiLoading', error: '#apiError', empty: '#apiEmpty', success: '#apiGrid' };
const API_PESAN = {
  loading: 'Mengambil data', error: 'Gagal mengambil data',
  empty: 'Belum ada data barang', success: 'Data barang berhasil dimuat'
};

// Hanya satu tampilan yang terlihat dalam satu waktu.
function setApiState(state) {
  Object.entries(API_VIEWS).forEach(([k, sel]) => $(sel).classList.toggle('hidden', k !== state));
  $('#apiInfo').classList.toggle('hidden', state !== 'success');
  $('#apiStatus').textContent = API_PESAN[state];
}

let apiBusy = false;
async function loadApi() {
  if (apiBusy) return; // cegah request ganda saat tombol diklik berulang
  apiBusy = true;
  setApiState('loading');
  try {
    const [data] = await Promise.all([fetchBarang(), new Promise(r => setTimeout(r, API_MIN_LOADING))]);
    const items = normalisasiBarang(data);
    if (!items.length) return setApiState('empty');
    renderApiBarang(items);
    // Feedback sukses: keterangan di bawah daftar + toast bawaan Revault
    $('#apiInfo').textContent = `Request berhasil: ${items.length} barang diterima dari ${API_URL}`;
    setApiState('success');
    toast('Data barang berhasil dimuat');
  } catch (err) {
    console.error('[API] Request gagal:', err.message);
    $('#apiErrMsg').textContent = err.message; // detail, mis. "Status 404: Data tidak ditemukan"
    setApiState('error');
  } finally {
    apiBusy = false;
  }
}

// Kerangka (skeleton) kartu yang tampil saat loading
$('#apiSkeleton').innerHTML = Array.from({ length: 3 }, () => `
  <div class="animate-pulse overflow-hidden rounded-2xl border border-forest/10 bg-mist">
    <div class="h-48 bg-forest-soft"></div>
    <div class="space-y-3 p-5"><div class="h-5 w-2/3 rounded bg-forest-soft"></div><div class="h-3 rounded bg-forest-soft"></div><div class="h-3 w-5/6 rounded bg-forest-soft"></div></div>
  </div>`).join('');

// Coba Lagi: pada mode demo, error hanya muncul sekali agar alur pemulihan terlihat.
const cobaLagi = () => { apiDemoError = false; loadApi(); };
$('#apiRetry').addEventListener('click', cobaLagi);
$('#apiRetryEmpty').addEventListener('click', cobaLagi);

loadApi();
