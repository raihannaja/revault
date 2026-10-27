/* =====================================================================
   DATA CARD "BARANG YANG TERSEDIA"
   Semua card di bagian "Barang yang tersedia" berasal dari daftar ini.
   Untuk menambah / mengubah / menghapus card, cukup edit file ini saja.
   (script.js tidak perlu disentuh.)

   Cara menambah card baru: salin satu blok { ... } lalu ubah isinya.
     id        : angka unik, tidak boleh sama dengan card lain
     nama      : judul card
     kategori  : Pakaian | Elektronik | Buku | Kardus | Lainnya
     kondisi   : Baik | Cukup | Perlu perbaikan
     lokasi    : kota / daerah barang
     deskripsi : keterangan singkat
     foto      : path foto di folder Assets, mis. 'Assets/baju2.jpg'
                 (kosongkan '' kalau belum ada foto)
     status    : 'tersedia' (tampil di daftar) | 'tersalurkan'
     wa        : nomor WhatsApp penyalur, format 62xxxxxxxxxx
     owner     : nama penyalur

   PENTING: data card disimpan di localStorage browser. Setelah mengubah
   file ini, naikkan DATA_VERSION (mis. '3' -> '4') supaya browser memuat
   data terbaru. Catatan: pengajuan dan posting yang tersimpan ikut
   ter-reset saat versi dinaikkan.
   ===================================================================== */
const DATA_VERSION = '3';

const BARANG_TERSEDIA = [
  {
    id: 1,
    nama: 'Pakaian layak pakai',
    kategori: 'Pakaian',
    kondisi: 'Baik',
    lokasi: 'Malang',
    deskripsi: 'Satu kantong kemeja dan kaos, sudah dicuci.',
    foto: 'Assets/baju2.jpg',
    status: 'tersedia',
    wa: '6281234567801',
    owner: 'Rina'
  },
  {
    id: 2,
    nama: 'Kardus bekas',
    kategori: 'Kardus',
    kondisi: 'Baik',
    lokasi: 'Malang',
    deskripsi: '12 kardus ukuran sedang, cocok untuk pindahan.',
    foto: 'Assets/kardus.jpg',
    status: 'tersedia',
    wa: '6281234567802',
    owner: 'Budi'
  },
  {
    id: 3,
    nama: 'Buku bekas kuliah',
    kategori: 'Buku',
    kondisi: 'Baik',
    lokasi: 'Malang',
    deskripsi: 'Buku teknik dan manajemen semester 1-4.',
    foto: 'Assets/buku.jpg',
    status: 'tersedia',
    wa: '6281234567801',
    owner: 'Rina'
  },
  {
    id: 4,
    nama: 'Kipas angin meja',
    kategori: 'Elektronik',
    kondisi: 'Cukup',
    lokasi: 'Malang',
    deskripsi: 'Masih menyala, kecepatan 2 kurang stabil.',
    foto: 'Assets/kipas.jpg',
    status: 'tersedia',
    wa: '6281234567803',
    owner: 'Sari'
  }
];
