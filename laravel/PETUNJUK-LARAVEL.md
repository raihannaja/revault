# Revault - Laravel (Pertemuan 9 dan 10)

- **Pertemuan 9 (Sprint 07):** Laravel + Database + Migration + Model + Eloquent + CRUD
- **Pertemuan 10 (Sprint 08):** Controller + Routing + Validation + REST API

Folder `laravel-files/` berisi **hanya file buatan kita**. Project Laravel-nya sendiri (`vendor`, `artisan`, dll.) dibuat oleh Composer di komputer Anda, lalu file-file ini disalin ke dalamnya.

> Penting: file-file ini sudah diperiksa sintaksnya, tetapi **belum pernah dijalankan** di dalam Laravel (lingkungan pembuatnya tidak bisa menginstal Laravel).
> Ikuti langkah di bawah dan coba semuanya sebelum merekam video. Jika ada error, kirim pesan error-nya apa adanya.

---

## BAGIAN A. Setup Laravel dan database dari nol

### A1. Instal alat (sekali saja)

Anda butuh **PHP 8.2+**, **Composer**, dan **Git**. Cara termudah di Windows: instal **Laragon (Full)** dari laragon.org. Laragon membawa PHP, Composer, dan Git sekaligus.

1. Instal Laragon, lalu jalankan.
2. Klik tombol **Terminal** di Laragon (jangan pakai CMD biasa, supaya PHP dan Composer pasti terbaca).
3. Cek versi:

```
php -v
composer -V
git --version
```

`php -v` harus menunjukkan 8.2 atau lebih baru. Jika versi PHP lebih lama, di Laragon pilih menu **Menu > PHP > Version** lalu pilih yang lebih baru (atau unduh PHP baru dari dalam Laragon).

### A2. Pastikan SQLite aktif

Database memakai **SQLite**: satu file database, tanpa instal MySQL, tanpa buat database lewat phpMyAdmin.

```
php -m
```

Di daftar hasilnya harus ada **`pdo_sqlite`** dan **`sqlite3`**. Jika belum ada, di Laragon: **Menu > PHP > Extensions > centang sqlite3 dan pdo_sqlite**, lalu restart Laragon. (Tanpa Laragon: buka `php.ini`, hapus tanda `;` di depan `extension=pdo_sqlite` dan `extension=sqlite3`.)

### A3. Buat project Laravel

Buka terminal di **folder utama repo Revault** (folder yang berisi `index.html`). Di Laragon: klik kanan folder tersebut atau gunakan `cd`.

```
composer create-project laravel/laravel laravel
```

Tunggu sampai selesai (beberapa menit, butuh internet). Hasilnya folder baru bernama `laravel/`.

### A4. Aktifkan jalur API (untuk Pertemuan 10)

```
cd laravel
php artisan install:api
```

Perintah ini membuat file `routes/api.php` dan mendaftarkannya di Laravel. Jika ditanya "Would you like to run all pending migrations?", jawab **yes**.

> `install:api` ada di Laravel 11 ke atas. Cek versi dengan `php artisan --version`. Jika perintah ini tidak dikenal, kirim hasil `php artisan --version` ke saya.

### A5. Salin file kita ke project Laravel

Kembali ke folder utama repo, lalu salin dan **timpa jika ditanya**:

- Windows (CMD/terminal Laragon): `xcopy laravel-files laravel /E /Y`
- Mac/Linux: `cp -R laravel-files/. laravel/`

File Laravel bawaan yang ikut tertimpa: `routes/web.php`, `routes/api.php`, dan `database/seeders/DatabaseSeeder.php`. Itu memang disengaja.

### A6. Hubungkan database, buat tabel, isi data

```
cd laravel
```

Buka file `.env` di folder `laravel/` dan cek baris ini:

```
DB_CONNECTION=sqlite
```

(Pada Laravel 11 ke atas sudah begitu secara bawaan. Jika masih `mysql`, ubah ke `sqlite` dan hapus baris `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.)

Lalu jalankan:

```
php artisan migrate
php artisan db:seed
```

- `migrate` membuat tabel (termasuk tabel `programs` dari migration kita). Jika ditanya "database does not exist, create it?", jawab **yes**: Laravel membuat file `database/database.sqlite`.
- `db:seed` mengisi tabel `programs` dengan 6 program Revault.

Cek bahwa database terhubung dan tabelnya ada:

```
php artisan migrate:status
php artisan db:table programs
```

### A7. Jalankan aplikasi

```
php artisan serve
```

- Web: **http://127.0.0.1:8000** (otomatis ke halaman `/program`)
- API: **http://127.0.0.1:8000/api/programs**

Hentikan server dengan `Ctrl + C`.

### A8. Masalah yang sering muncul

| Pesan / gejala | Penyebab dan solusi |
|---|---|
| `could not find driver` | Ekstensi `pdo_sqlite` belum aktif. Lihat A2. |
| `php` / `composer` tidak dikenal | Pakai terminal Laragon, atau tambahkan PHP dan Composer ke PATH. |
| `composer create-project` lambat atau gagal | Cek internet, ulangi perintahnya. Jika error `ext-zip`/`ext-fileinfo`, aktifkan ekstensi itu di Laragon. |
| `Address already in use` | Port 8000 terpakai. Pakai `php artisan serve --port=8001`. |
| `Unknown command install:api` | Laravel Anda di bawah versi 11. Kirim `php artisan --version`. |
| Halaman tampil tanpa gaya (polos) | Tailwind dimuat dari internet (CDN). Pastikan online. |
| `419 Page Expired` saat simpan | Halaman form sudah terlalu lama dibuka. Refresh dan kirim ulang. |
| Ingin mengulang data dari awal | `php artisan migrate:fresh --seed` (menghapus semua data lalu mengisi ulang). |

---

## BAGIAN B. Pertemuan 9 - Database dan CRUD

### Peta file

| File | Tugas | Fungsi |
|---|---|---|
| `database/migrations/2026_11_13_000001_create_programs_table.php` | P9 Task 01 | Mendefinisikan struktur tabel `programs` lewat kode |
| `app/Models/Program.php` | P9 Task 02 | Model Eloquent: `$fillable`, nilai bawaan, `persen`, `status`, `badge` |
| `database/seeders/ProgramSeeder.php` dan `DatabaseSeeder.php` | Data awal | Mengisi 6 program Revault ke database |
| `resources/views/layouts/app.blade.php` | P9 Task 03 | Kerangka halaman bertema Revault |
| `resources/views/program/index.blade.php` | P9 Task 03 | Tabel program dari database, tombol Edit dan Hapus |
| `resources/views/program/form.blade.php` | P9 Task 03 | Satu form untuk tambah dan edit |
| `public/Assets/*` | Tampilan | Foto program dan logo dari project Revault |

Tabel `programs`: `id`, `nama`, `deskripsi`, `kategori`, `lokasi`, `foto`, `target`, `tercapai`, `created_at`, `updated_at`. Status program (Segera / Berjalan / Selesai) dihitung dari `target` dan `tercapai`, tidak disimpan sebagai kolom.

### Cara membuktikan

- **Task 01:** `php artisan migrate:status` dan `php artisan db:table programs`.
- **Task 02:** buka `http://127.0.0.1:8000/uji-eloquent` (hasil `Program::all()` dan `Program::find(1)`), lalu `/uji-eloquent?tambah=1` untuk `Program::create([...])`. Alternatif: `php artisan tinker`, lalu `App\Models\Program::all();`.
- **Task 03:** halaman `/program`: tambah (Create), tabel (Read), Edit (Update), Hapus (Delete). Tidak ada satu pun nama program di file `.blade.php`; semuanya dari `@forelse ($programs as $program)`.

### Jawaban review Pertemuan 9

1. **Fungsi Migration?** Membuat dan mengubah struktur tabel database lewat kode PHP, sehingga struktur database ikut tersimpan di Git dan bisa dibuat ulang di komputer mana pun dengan `php artisan migrate`.
2. **Fungsi Model?** Class yang mewakili satu tabel. Satu object `Program` mewakili satu baris di tabel `programs`.
3. **Fungsi Eloquent?** ORM bawaan Laravel yang membuat kita mengelola data database sebagai object PHP (`Program::all()`, `find()`, `create()`) tanpa menulis query SQL manual.
4. **Beda `Program::all()` dan `Program::find(1)`?** `all()` mengambil semua baris (banyak object, berupa Collection). `find(1)` mengambil satu baris berdasarkan id (satu object, atau `null` jika tidak ada).
5. **Apa itu CRUD?** Empat operasi dasar data: Create (tambah), Read (baca), Update (ubah), Delete (hapus).

**Pertanyaan utama: apa yang berubah saat Revault memakai database?** Data tidak lagi hard-code di file PHP atau HTML. Data tersimpan di database, dibaca Laravel lewat Model dan Eloquent, lalu ditampilkan oleh View.

---

## BAGIAN C. Pertemuan 10 - Controller, Routing, Validation, REST API

### Yang berubah dari Pertemuan 9

| Pertemuan 9 | Pertemuan 10 |
|---|---|
| Route berisi logika (atau `Route::resource`) | Route hanya **menunjuk** ke method Controller, ditulis satu per satu dengan HTTP method yang jelas |
| Validasi dasar, pesan bahasa Inggris | Validasi dengan aturan dan **pesan bahasa Indonesia** (`app/Support/AturanProgram.php`) |
| Hanya halaman web (HTML) | Ditambah **REST API** yang menjawab dengan JSON dan status code |

### Peta file Pertemuan 10

| File | Tugas | Fungsi |
|---|---|---|
| `routes/web.php` | Task 01 | GET, POST, PUT/PATCH, DELETE ke `ProgramController`: `index`, `create`, `store`, `edit`, `update`, `destroy` |
| `app/Http/Controllers/ProgramController.php` | Task 01 dan 02 | Seluruh proses CRUD web. `store` dan `update` menerima `Request` lalu memvalidasi |
| `app/Support/AturanProgram.php` | Task 02 | Aturan validasi (`required`, `string`, `max:100`, `integer`, `min`, `in`) dan pesannya, dipakai web dan API |
| `routes/api.php` | Task 03 | `Route::apiResource('programs', ...)`: 5 endpoint di bawah `/api` |
| `app/Http/Controllers/Api/ProgramController.php` | Task 03 | CRUD yang menjawab JSON dengan status code 200, 201, 404, 422 |
| `postman/Revault-Program-API.postman_collection.json` | Testing | 8 request siap impor ke Postman atau Insomnia |

### Task 01 - Move CRUD into Controller

Alur: **Route -> Controller -> Eloquent -> Database**. Lihat `routes/web.php` dan `ProgramController.php`.

| CRUD | HTTP method | URL | Method controller |
|---|---|---|---|
| READ | GET | `/program` | `index()` |
| FORM | GET | `/program/create` | `create()` |
| CREATE | POST | `/program` | `store()` |
| FORM EDIT | GET | `/program/{id}/edit` | `edit($id)` |
| UPDATE | PUT / PATCH | `/program/{id}` | `update(Request $request, $id)` |
| DELETE | DELETE | `/program/{id}` | `destroy($id)` |

Lihat semua route: `php artisan route:list`

### Task 02 - Request dan Validation (uji di browser)

1. Buka `/program/create`, **kosongkan** nama dan deskripsi, klik Simpan. Muncul "Nama wajib diisi." dan "Deskripsi wajib diisi." dan data tidak masuk database.
2. Isi nama lebih dari 100 karakter. Muncul "Nama maksimal 100 karakter."
3. Isi data benar. Tersimpan dan muncul di tabel.
4. Coba juga **Edit** dengan data kosong (validasi di Update), dan isi "tersalurkan" lebih besar dari target.

### Task 03 - RESTful Program API

| Method | Endpoint | Fungsi | Status |
|---|---|---|---|
| GET | `/api/programs` | Semua program | 200 |
| POST | `/api/programs` | Buat program | 201 (422 jika tidak valid) |
| GET | `/api/programs/{id}` | Detail program | 200 (404 jika tidak ada) |
| PUT / PATCH | `/api/programs/{id}` | Ubah program | 200 (404 / 422) |
| DELETE | `/api/programs/{id}` | Hapus program | 200 (404 jika tidak ada) |

Body POST dan PUT cukup berisi `nama` dan `deskripsi` (kolom lain memakai nilai bawaan atau data lama), sama seperti contoh di materi.

**Testing dengan Postman atau Insomnia**

1. Jalankan `php artisan serve`.
2. Impor `postman/Revault-Program-API.postman_collection.json` (Postman: **Import**; Insomnia: **Import > From File**).
3. Jalankan request 01 sampai 08 berurutan. Variabel `id` bernilai 1 (ubah di tab Variables jika program 1 sudah dihapus pada langkah 05).
4. Perhatikan **angka status** di kanan atas hasil response: 200, 201, 422, dan 404.

**Alternatif lewat terminal** (Mac/Linux/Git Bash; di CMD Windows pakai `curl.exe` dan tanda kutip berbeda):

```
curl -s http://127.0.0.1:8000/api/programs
curl -s -X POST http://127.0.0.1:8000/api/programs -H "Content-Type: application/json" -d '{"nama":"Lingkungan","deskripsi":"Program di bidang lingkungan."}'
curl -s http://127.0.0.1:8000/api/programs/1
curl -s -X PUT http://127.0.0.1:8000/api/programs/1 -H "Content-Type: application/json" -d '{"nama":"Pakaian Layak untuk Semua","deskripsi":"Program terbaru."}'
curl -s -X DELETE http://127.0.0.1:8000/api/programs/1
curl -s -i -X POST http://127.0.0.1:8000/api/programs -H "Content-Type: application/json" -d '{"nama":"","deskripsi":""}'
```

Contoh hasil validasi gagal (422):

```
{
  "message": "The given data was invalid.",
  "errors": {
    "nama": ["Nama wajib diisi."],
    "deskripsi": ["Deskripsi wajib diisi."]
  }
}
```

### Jawaban review Pertemuan 10

1. **Fungsi Controller?** Pengatur alur aplikasi: menerima request dari route, memproses (memvalidasi, memakai Model/Eloquent), lalu menghasilkan response (view atau JSON).
2. **Fungsi Route?** Menghubungkan URL dan HTTP method dengan Controller dan method yang dijalankan. URL tidak langsung menuju View.
3. **Apa yang diterima Controller?** Object `Request` yang berisi input pengguna (dari form, query string, atau body JSON), contoh `$request->nama`.
4. **Mengapa validation dilakukan?** Memastikan data memenuhi aturan sebelum disimpan, supaya database tidak berisi data kosong, salah format, atau terlalu panjang, dan supaya pengguna mendapat pesan kesalahan yang jelas.
5. **Beda HTML response dan JSON response?** HTML response adalah halaman jadi (dari View) untuk dibaca manusia di browser. JSON response adalah data mentah terstruktur untuk dipakai program lain (frontend, aplikasi mobile).
6. **Fungsi HTTP method?** Menentukan jenis operasi pada data: GET baca, POST tambah, PUT/PATCH ubah, DELETE hapus.
7. **Arti status 200, 201, 404, 422?** 200 OK (berhasil), 201 Created (data berhasil dibuat), 404 Not Found (data tidak ditemukan), 422 (data tidak memenuhi aturan validasi).
8. **Alur request sampai database?** User -> Route -> Controller -> Validation -> Model -> Eloquent -> Database -> Response.

**Pertanyaan utama: apa yang berubah saat CRUD memiliki Controller, Routing, dan Validation?** Logika tidak lagi tercampur di route. Route hanya mengarahkan, Controller mengatur proses, Validation menjaga kualitas data sebelum masuk database. Hasilnya aplikasi lebih rapi, aman, dan siap dipakai juga oleh frontend atau aplikasi mobile lewat REST API.

---

## BAGIAN D. Commit dan push

Laravel sudah punya `.gitignore` yang mengabaikan `vendor/`, `.env`, dan file `*.sqlite`. Jadi yang masuk ke Git hanya kode. Teman satu kelompok cukup menjalankan: `composer install`, salin `.env.example` menjadi `.env`, `php artisan key:generate`, `php artisan migrate --seed`.

Dari folder utama repo:

```
git status
git pull origin main
git add .
git commit -m "Pertemuan 9 dan 10: Laravel, database, CRUD, controller, validation, REST API"
git push origin main
```

Pastikan `vendor/`, `.env`, dan `database.sqlite` tidak muncul di `git status` sebelum `git add .`.

## BAGIAN E. Urutan demo yang disarankan

1. Tunjukkan `php artisan migrate:status` dan `php artisan db:table programs` (database dan migration).
2. Tunjukkan `Program.php` (model, `$fillable`) dan `/uji-eloquent`.
3. Tunjukkan `routes/web.php` dan `ProgramController.php` (route menunjuk ke controller).
4. Di browser: Create, Read, Update, Delete di `/program`.
5. Uji validasi: form kosong, nama lebih dari 100 karakter.
6. Buka `routes/api.php` dan jalankan `php artisan route:list --path=api`.
7. Di Postman: request 01 sampai 08, tunjukkan status 200, 201, 422, 404, lalu kembali ke `/program` untuk melihat data berubah.
8. Jawab pertanyaan review dan pertanyaan utama.
