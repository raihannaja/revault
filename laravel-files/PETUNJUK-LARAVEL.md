# Revault - Pertemuan 9 (Sprint 07): Laravel + Database + CRUD

Folder `laravel-files/` berisi **hanya file buatan kita** (migration, model, controller, route, view, seeder).
Project Laravel-nya sendiri (folder `vendor`, `artisan`, dll.) dibuat oleh Composer di komputer Anda, lalu file-file ini disalin ke dalamnya.

> Penting: file-file ini sudah diperiksa sintaksnya, tetapi **belum pernah dijalankan** di dalam Laravel (lingkungan pembuatnya tidak bisa menginstal Laravel).
> Jalankan langkah di bawah dan lihat hasilnya sebelum merekam video. Jika ada error, kirim pesan error-nya apa adanya.

## 1. Persiapan (sekali saja)

Anda butuh **PHP 8.2+** dan **Composer**. Cara termudah di Windows: instal **Laragon (Full)**, yang sudah membawa PHP, Composer, dan Git. Setelah instal, buka terminal Laragon, lalu cek:

```
php -v
composer -V
```

Database memakai **SQLite**, jadi tidak perlu MySQL. Pastikan ekstensi `pdo_sqlite` aktif (`php -m` harus memuat `pdo_sqlite`). Di Laragon dan XAMPP biasanya sudah aktif.

## 2. Membuat project Laravel dan memasang file kita

Jalankan dari **folder utama repo Revault** (folder yang berisi `index.html`):

```
composer create-project laravel/laravel laravel
```

Salin file kita ke dalamnya, **timpa jika ditanya**:

- Windows (CMD): `xcopy laravel-files laravel /E /Y`
- Mac/Linux: `cp -R laravel-files/. laravel/`

File Laravel bawaan yang ikut tertimpa: `routes/web.php` dan `database/seeders/DatabaseSeeder.php`. Itu memang disengaja.

Lalu:

```
cd laravel
php artisan --version
php artisan migrate
php artisan db:seed
php artisan serve
```

- Jika `migrate` bertanya "database does not exist, create it?", jawab **yes**.
- Buka **http://127.0.0.1:8000**. Anda akan diarahkan ke `/program`.
- Jika `.env` Anda masih memakai MySQL (`DB_CONNECTION=mysql`), ubah menjadi `DB_CONNECTION=sqlite` dan hapus baris `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.
- Setelah berhasil, folder `laravel-files/` boleh dihapus.

## 3. Peta file (apa fungsi tiap file)

| File | Tugas | Fungsi |
|---|---|---|
| `database/migrations/2026_11_13_000001_create_programs_table.php` | Task 01 | Mendefinisikan struktur tabel `programs` lewat kode |
| `app/Models/Program.php` | Task 02 | Model Eloquent: `$fillable`, dan penghitung `persen`, `status`, `badge` |
| `routes/web.php` | Task 02 dan 03 | Route `/uji-eloquent` (demo `all`, `find`, `create`) dan `Route::resource` untuk CRUD |
| `app/Http/Controllers/ProgramController.php` | Task 03 | `index`, `create`, `store`, `edit`, `update`, `destroy` |
| `resources/views/layouts/app.blade.php` | Task 03 | Kerangka halaman bertema Revault (header, footer, Tailwind) |
| `resources/views/program/index.blade.php` | Task 03 (Read dan Delete) | Tabel program dari database, tombol Edit dan Hapus |
| `resources/views/program/form.blade.php` | Task 03 (Create dan Update) | Satu form untuk tambah dan edit |
| `database/seeders/ProgramSeeder.php` | Data awal | Mengisi 6 program Revault ke database |
| `database/seeders/DatabaseSeeder.php` | Data awal | Memanggil `ProgramSeeder` |
| `public/Assets/*` | Tampilan | Foto program dan logo (dari project Revault) |

## 4. Cara membuktikan tiap checklist

**Task 01 - struktur database**
```
php artisan migrate:status
php artisan db:table programs
```
Harus terlihat tabel `programs` dengan kolom `id`, `nama`, `deskripsi`, `kategori`, `lokasi`, `foto`, `target`, `tercapai`, `created_at`, `updated_at`.

**Task 02 - Model dan Eloquent**
- Buka `http://127.0.0.1:8000/uji-eloquent` : hasil `Program::all()` (banyak object) dan `Program::find(1)` (satu object).
- Buka `http://127.0.0.1:8000/uji-eloquent?tambah=1` : `Program::create([...])` menambah satu baris. Refresh halaman `/program`, program baru muncul.
- Alternatif lewat terminal: `php artisan tinker`, lalu ketik `App\Models\Program::all();` dan `App\Models\Program::find(1);`.

**Task 03 - CRUD**
- **Create:** tombol **+ Tambah Program**, isi form, **Simpan**.
- **Read:** halaman `/program` menampilkan tabel dari database.
- **Update:** tombol **Edit**, ubah data, **Update**.
- **Delete:** tombol **Hapus**, konfirmasi, data hilang dari tabel.
- Bukti "bukan hard-code": tidak ada satu pun nama program di file `.blade.php`. Semuanya dari `@forelse ($programs as $program)`.

## 5. Jawaban review (Slide 15)

1. **Fungsi Migration?** Membuat dan mengubah struktur tabel database lewat kode PHP, sehingga struktur database ikut tersimpan di Git dan bisa dibuat ulang di komputer mana pun dengan `php artisan migrate`.
2. **Fungsi Model?** Class yang mewakili satu tabel. Satu object `Program` mewakili satu baris di tabel `programs`. Model juga menyimpan aturan data (`$fillable`, status, persen).
3. **Fungsi Eloquent?** ORM bawaan Laravel yang membuat kita mengelola data database sebagai object PHP (`Program::all()`, `find()`, `create()`), tanpa menulis query SQL manual.
4. **Beda `Program::all()` dan `Program::find(1)`?** `all()` mengambil semua baris dan mengembalikan banyak object (Collection). `find(1)` mengambil satu baris berdasarkan id dan mengembalikan satu object (atau `null` jika tidak ada).
5. **Apa itu CRUD?** Empat operasi dasar data: Create (tambah), Read (baca), Update (ubah), Delete (hapus).

**Pertanyaan utama: apa yang berubah saat Revault memakai database?** Data tidak lagi hard-code di file PHP atau HTML. Data tersimpan di database, dibaca Laravel lewat Model dan Eloquent, lalu ditampilkan oleh View. Tambah, ubah, atau hapus program kini tidak perlu mengedit kode.

## 6. Dari PHP manual ke Laravel (untuk dijelaskan di video)

| PHP MVC manual (Week 7) | Laravel (Week 9) |
|---|---|
| `Model/data-program.php` (array di file) | Tabel `programs` di database |
| `class Program` ditulis sendiri | `class Program extends Model` (Eloquent) |
| `DaftarProgram::muatDariFile()` | `Program::all()` |
| `program.php` (route buatan sendiri) | `routes/web.php` |
| `ProgramController` ditulis sendiri | `ProgramController` dibantu framework |
| `View/*.php` + `htmlspecialchars` | `resources/views/*.blade.php` (`{{ }}` otomatis di-escape) |
| Tidak ada tambah/ubah/hapus | CRUD lengkap |

## 7. Commit dan push

Laravel sudah punya `.gitignore` yang mengabaikan `vendor/`, `.env`, dan file `*.sqlite`. Jadi yang masuk ke Git hanya kode. Orang lain cukup `composer install`, salin `.env.example` menjadi `.env`, `php artisan key:generate`, `php artisan migrate --seed`.

```
git add laravel
git commit -m "Pertemuan 9: Laravel + migration + model Program + CRUD"
git push
```

## 8. Urutan demo yang disarankan (Slide 14)

1. Tunjukkan struktur: `php artisan db:table programs` dan file migration.
2. Tunjukkan model `Program.php` (`$fillable`) dan `/uji-eloquent` (`all`, `find`, `create`).
3. Tunjukkan halaman `/program`: data dari database.
4. Create: tambah program, lihat baris baru.
5. Update: edit program, lihat perubahan.
6. Delete: hapus program, lihat tabel berkurang.
7. Jawab 5 pertanyaan review dan pertanyaan utama.
