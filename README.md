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
