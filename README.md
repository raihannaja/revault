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
| 1 Halaman PHP | Variabel halaman (nama situs, tagline, menu) | `Model/Situs.php`, `View/layout/` |
| 2 Proses data | Array multidimensi, status/badge kondisional, loop penomoran | `Model/data-program.php`, `program.php` |
| 3 SSR dynamic | Grid card program dirender di server dengan `foreach` | `View/program-page.php` |

Jalankan dengan `php -S localhost:8000` lalu buka `http://localhost:8000/program.php`
(Live Server tidak bisa menjalankan PHP). Filter: `program.php?status=berjalan`.

## 🧱 PHP OOP (Pertemuan 6: From Procedural to Object-Oriented)

Halaman `program.php` kini memakai object. Class `President` di materi menjadi class `Program` di Revault.

| Task | Isi | Lokasi |
|------|-----|--------|
| 01 Create a Class | Class `Program` mewakili satu program prioritas | `Model/Program.php` |
| 02 Properties & Methods | Property (nama, kategori, target, tercapai, dst.) dan method (`getProfile()`, `getTahap()`, `getPersen()`, `getStatus()`, `getBadge()`) | `Model/Program.php` |
| 03 Dynamic Content with Object | Object diolah PHP menjadi grid card HTML | `View/program-page.php`, `Model/DaftarProgram.php` |

Konsep yang dipakai: class, object, property, method, constructor (`__construct`), encapsulation
(property `private` + getter/setter `public`), dan pengolahan banyak object lewat `DaftarProgram`.

Uji: `php tests/uji-program.php`

### Jawaban pertanyaan demo
1. **Class apa yang dibuat?** `Program` (satu program) dan `DaftarProgram` (kumpulan program).
2. **Object apa yang digunakan?** Satu object `Program` untuk tiap baris di `Model/data-program.php`, dan satu object `DaftarProgram`.
3. **Property apa yang dimiliki object?** `id`, `no`, `nama`, `kategori`, `deskripsi`, `foto`, `lokasi`, `target`, `tercapai`, `tahap`, `penanggungNama`, `penanggungWa` (semua `private`).
4. **Method apa yang dibuat?** `getProfile()`, `getTahap()`, `getNomor()`, `getPersen()`, `getSisa()`, `getStatus()`, `getBadge()`, `getWaLink()`, `setTercapai()`, serta getter dasar.
5. **Bagaimana object menghasilkan data pada halaman?** Array data menjadi object `Program` lewat constructor, `program.php` memanggil method seperti `$p->getNama()` di dalam `foreach`, lalu PHP mencetaknya sebagai HTML card.

### Review: bagian mana yang lebih mudah dikelola dengan object?
Perhitungan persen, status, dan warna badge dulu berupa fungsi terpisah yang menerima array. Sekarang semuanya ada di dalam `Program`, jadi
aturannya ada di satu tempat, nilai yang salah dijaga oleh setter (misalnya `tercapai` tidak bisa melebihi `target`), dan tampilan cukup memanggil method.

## 🏗️ MVC (Pertemuan 7: From OOP to MVC)

Bagian PHP (halaman Program Prioritas) dipisah menjadi **Model, View, Controller**.

```
revault/
├── program.php                  <- pintu masuk (route), hanya memanggil Controller
├── Controller/
│   └── ProgramController.php    <- index(), show(), process()
├── Model/
│   ├── Program.php              <- satu program (property, method, aturan status/badge)
│   ├── DaftarProgram.php        <- kumpulan program (ambil data, cari, filter, statistik)
│   ├── Situs.php                <- identitas situs (nama, tagline, menu)
│   └── data-program.php         <- sumber data (edit di sini untuk menambah program)
├── View/
│   ├── program-page.php         <- halaman daftar program
│   ├── program-detail.php       <- halaman detail satu program
│   ├── not-found.php            <- halaman 404
│   ├── helpers.php              <- fungsi e() untuk escape HTML
│   ├── layout/header.php, footer.php
│   └── partials/program-card.php
└── tests/uji-program.php
```

| Task | Isi | Lokasi |
|------|-----|--------|
| 01 Design the MVC Structure | Model = data, View = tampilan, Controller = proses request | folder `Model/`, `View/`, `Controller/` |
| 02 Build the Controller | `index()` daftar, `show($id)` detail, `process()` menyiapkan data | `Controller/ProgramController.php` |
| 03 Connect Model, Controller & View | Controller memanggil Model lalu mengirim data ke View | `program.php` -> Controller -> Model -> View |

Alamat: `program.php` (daftar), `program.php?status=berjalan` (filter), `program.php?id=3` (detail).
Jalankan dengan `php -S localhost:8000`. Uji dengan `php tests/uji-program.php`.

### Alur MVC
`User -> Request -> Controller -> Model -> Data -> Controller -> View -> HTML -> User`

### Jawaban pertanyaan demo
1. **Apa fungsi Controller?** Menerima request (`program.php?id=3`), membersihkan inputnya, meminta data ke Model, lalu meneruskan hasilnya ke View.
2. **Apa data yang dikelola Model?** Data program prioritas (nama, kategori, target, tercapai, tahap, penanggung jawab), aturan status dan badge, statistik, serta identitas situs.
3. **Apa yang ditampilkan oleh View?** Halaman daftar program (tabel ringkasan dan grid card), halaman detail, dan halaman 404 dalam bentuk HTML.
4. **Bagaimana Controller berkomunikasi dengan Model?** Controller membuat object `DaftarProgram` dan `Situs`, lalu memanggil method-nya, misalnya `cari($id)`, `filterStatus($filter)`, dan `total()`.
5. **Bagaimana data akhirnya sampai ke View?** Controller menyusun array data di `process()`, lalu `render()` mengubah tiap kunci array menjadi variabel (`$statistik`, `$programTampil`, dst.) pada file View.

### Review: bagian mana yang lebih mudah dikelola setelah dipisah?
- **Menambah atau mengubah program:** cukup edit `Model/data-program.php`, tampilan tidak perlu disentuh.
- **Mengubah tampilan card:** cukup edit `View/partials/program-card.php`, aturan status tidak ikut berubah.
- **Menambah halaman baru** (misalnya detail): tambah method di Controller dan satu file View. Model dipakai ulang.
- **Menguji:** Model dan Controller bisa diuji lewat terminal tanpa membuka browser.

---

## Pertemuan 9 - Laravel + Database + CRUD (Sprint 07)

Fitur **Program Management** dibuat dengan Laravel di folder `laravel/`. Project lama (HTML, `api.js`, PHP MVC) tidak diubah.

- Folder `laravel-files/` berisi file buatan kita: migration `create_programs_table`, model `Program`, `ProgramController`, route, view Blade, dan seeder.
- Langkah membuat project Laravel, menjalankan, bukti per task, jawaban review, dan skrip demo ada di **`laravel-files/PETUNJUK-LARAVEL.md`**.
- Database: SQLite. Tabel `programs` (`id`, `nama`, `deskripsi`, `kategori`, `lokasi`, `foto`, `target`, `tercapai`, `created_at`, `updated_at`). Status program dihitung dari `target` dan `tercapai`, tidak disimpan sebagai kolom.
