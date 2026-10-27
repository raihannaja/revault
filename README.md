# Revault

Revault adalah sebuah website yang dibuat untuk membantu mengurangi barang-barang yang masih memiliki nilai guna agar tidak langsung dibuang.

Melalui Revault, pengguna dapat membagikan informasi mengenai barang yang sudah tidak digunakan, seperti kardus, botol, barang elektronik, pakaian, dan barang lainnya yang masih layak digunakan. Barang tersebut kemudian dapat dimanfaatkan oleh orang lain yang membutuhkan.

## 🎯 Tujuan

Revault dibuat sebagai solusi sederhana untuk:

- Mengurangi barang yang terbuang sia-sia.
- Memanfaatkan kembali barang yang masih memiliki nilai guna.
- Mempermudah seseorang dalam memberikan barang yang sudah tidak digunakan.
- Membantu orang lain mendapatkan barang yang sedang mereka butuhkan.
- Mendorong kebiasaan menggunakan kembali barang sebelum membuangnya.

## ✨ Fitur

- Menampilkan daftar barang yang tersedia.
- Informasi detail mengenai barang.
- Pengguna dapat membagikan barang yang sudah tidak digunakan.
- Pengguna lain dapat melihat barang yang tersedia.
- Tampilan sederhana dan mudah digunakan.
- Konsep berbagi barang berdasarkan kebutuhan.

## 👥 Pengguna

Revault memiliki dua jenis pengguna:

### 1. Penyalur

Penyalur adalah pengguna yang memiliki barang yang sudah tidak digunakan tetapi masih layak dimanfaatkan.

Penyalur dapat:
- Menambahkan barang.
- Memberikan informasi mengenai kondisi barang.
- Menambahkan foto barang.
- Menentukan lokasi barang.

### 2. Penerima

Penerima adalah pengguna yang membutuhkan barang yang tersedia.

Penerima dapat:
- Melihat barang yang tersedia.
- Melihat detail dan kondisi barang.
- Mengajukan permintaan terhadap barang yang dibutuhkan.

## 🛠️ Teknologi

Project ini dikembangkan menggunakan:

- HTML
- CSS
- JavaScript

## 📁 Struktur Project

```text
revault/
│
├── index.html
├── style.css
└── README.md

## 🗂️ Data Card Barang Tersedia

Semua card di bagian "Barang yang tersedia" berasal dari satu file: `data/barang-tersedia.js`.
Untuk menambah, mengubah, atau menghapus card, edit file itu saja lalu naikkan `DATA_VERSION`.

## 🐘 Halaman PHP (Program Prioritas)

| Task | Isi | Lokasi |
|------|-----|--------|
| 1 Halaman PHP | Variabel halaman (`$namaSitus`, `$tagline`, `$menu`, dst.) | `program.php` |
| 2 Proses data | Array multidimensi, status/badge kondisional, loop penomoran | `includes/data-program.php`, `program.php` |
| 3 SSR dynamic | Grid card program dirender di server dengan `foreach` | `program.php` |

Jalankan dengan `php -S localhost:8000` lalu buka `http://localhost:8000/program.php`
(Live Server tidak bisa menjalankan PHP). Filter: `program.php?status=berjalan`.

## 🧱 PHP OOP (Pertemuan 6: From Procedural to Object-Oriented)

Halaman `program.php` kini memakai object. Class `President` di materi menjadi class `Program` di Revault.

| Task | Isi | Lokasi |
|------|-----|--------|
| 01 Create a Class | Class `Program` mewakili satu program prioritas | `classes/Program.php` |
| 02 Properties & Methods | Property (nama, kategori, target, tercapai, dst.) dan method (`getProfile()`, `getTahap()`, `getPersen()`, `getStatus()`, `getBadge()`) | `classes/Program.php` |
| 03 Dynamic Content with Object | Object diolah PHP menjadi grid card HTML | `program.php`, `classes/DaftarProgram.php` |

Konsep yang dipakai: class, object, property, method, constructor (`__construct`), encapsulation
(property `private` + getter/setter `public`), dan pengolahan banyak object lewat `DaftarProgram`.

Uji: `php tests/uji-program.php`

### Jawaban pertanyaan demo
1. **Class apa yang dibuat?** `Program` (satu program) dan `DaftarProgram` (kumpulan program).
2. **Object apa yang digunakan?** Satu object `Program` untuk tiap baris di `includes/data-program.php`, dan satu object `DaftarProgram`.
3. **Property apa yang dimiliki object?** `id`, `no`, `nama`, `kategori`, `deskripsi`, `foto`, `lokasi`, `target`, `tercapai`, `tahap`, `penanggungNama`, `penanggungWa` (semua `private`).
4. **Method apa yang dibuat?** `getProfile()`, `getTahap()`, `getNomor()`, `getPersen()`, `getSisa()`, `getStatus()`, `getBadge()`, `getWaLink()`, `setTercapai()`, serta getter dasar.
5. **Bagaimana object menghasilkan data pada halaman?** Array data menjadi object `Program` lewat constructor, `program.php` memanggil method seperti `$p->getNama()` di dalam `foreach`, lalu PHP mencetaknya sebagai HTML card.

### Review: bagian mana yang lebih mudah dikelola dengan object?
Perhitungan persen, status, dan warna badge dulu berupa fungsi terpisah yang menerima array. Sekarang semuanya ada di dalam `Program`, jadi
aturannya ada di satu tempat, nilai yang salah dijaga oleh setter (misalnya `tercapai` tidak bisa melebihi `target`), dan tampilan cukup memanggil method.
