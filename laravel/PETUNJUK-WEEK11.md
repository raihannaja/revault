# Revault - Pertemuan 11 (Sprint 09): JWT Authentication dengan Laravel

Tugas: mengamankan REST API Program (Pertemuan 10) dengan autentikasi berbasis token JWT.

> Penting: kode ini sudah diperiksa sintaksnya, tetapi **belum pernah dijalankan** di Laravel (lingkungan pembuatnya tidak bisa menginstal Laravel dan paket JWT).
> Ikuti langkah di bawah dan coba semuanya sebelum merekam video. Jika ada error, kirim pesan error-nya apa adanya.

Prasyarat: Pertemuan 9 dan 10 sudah berjalan di folder `laravel/` (database sudah `migrate` dan `db:seed`).

---

## BAGIAN A. Pemasangan (urut, jangan dilompati)

Semua perintah dijalankan di **terminal Laragon**.

### A1. Cek versi PHP

Paket JWT versi terbaru butuh **PHP 8.3 atau lebih baru**.

```
php -v
```

Jika masih 8.2, ganti di Laragon: **Menu > PHP > Version**, pilih 8.3 atau lebih baru, lalu buka terminal baru dan cek lagi.

### A2. Pasang paket JWT

```
cd "D:\Semester 5\Pemrograman Web\revault\laravel"
composer require php-open-source-saver/jwt-auth
```

Tunggu sampai selesai. Jika Composer menolak karena versi PHP atau Laravel tidak cocok, kirim pesan lengkapnya.

### A3. Publikasikan konfigurasi dan buat kunci rahasia

```
php artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret
```

- Perintah pertama membuat file `config/jwt.php`. Jika ada pilihan nomor, pilih provider yang namanya mengandung `JWTAuth`.
- Perintah kedua menulis `JWT_SECRET=...` ke file `.env`. Kunci inilah yang dipakai Laravel untuk **menandatangani** token (bagian Signature). Kunci ini rahasia dan **tidak ikut di-commit** karena `.env` diabaikan Git.

### A4. Salin file Week 11 ke project Laravel

Ekstrak `week11-jwt.zip`. Salin isi foldernya (`app`, `database`, `routes`, `postman`) ke dalam folder `laravel/`, **timpa jika ditanya**.

Contoh di terminal, jika hasil ekstrak ada di `C:\Users\NAMA\Downloads\week11-jwt`:
```
xcopy "C:\Users\NAMA\Downloads\week11-jwt" "D:\Semester 5\Pemrograman Web\revault\laravel" /E /Y
```

File Laravel yang tertimpa (memang disengaja): `app/Models/User.php`, `routes/api.php`, `database/seeders/DatabaseSeeder.php`.
File baru: `AuthController.php`, `JwtMiddleware.php`, `UserSeeder.php`, dan koleksi Postman.

### A5. Buat user untuk login

```
php artisan db:seed
```

Hasilnya menampilkan `ProgramSeeder ... DONE` dan `UserSeeder ... DONE`. Program tidak dobel karena seeder memakai `updateOrCreate`.

User untuk login: **user@example.com** dengan password **password**.

### A6. Cek route

```
php artisan route:list --path=api
```

Harus terlihat `POST api/login` dan lima route `api/programs`.

### A7. Jalankan

```
php artisan serve
```

Jika port 8000 ditolak Windows, pakai port lain, misalnya `php artisan serve --port=9001`. Alamat dasar API menjadi `http://127.0.0.1:9001`.

---

## BAGIAN B. Peta file

| File | Tugas | Fungsi |
|---|---|---|
| `app/Models/User.php` | Persiapan | User menjadi `JWTSubject`: menentukan apa yang tersimpan di token (id user) |
| `app/Http/Controllers/Api/AuthController.php` | Task 01 | `POST /api/login`: validasi, cek email dan password, buat JWT, kirim response |
| `app/Http/Middleware/JwtMiddleware.php` | Task 02 | Penjaga: memeriksa header `Authorization: Bearer <token>` sebelum Controller |
| `routes/api.php` | Task 02 | `/api/login` terbuka; `/api/programs` dibungkus middleware JWT |
| `database/seeders/UserSeeder.php` dan `DatabaseSeeder.php` | Data uji | Membuat user `user@example.com` |
| `postman/Revault-Program-API-JWT.postman_collection.json` | Task 03 | 13 request: login, API dengan token, dan tes penolakan 401 |

---

## BAGIAN C. Task 01 - JWT Login

`POST /api/login`
Body (JSON):
```
{ "email": "user@example.com", "password": "password" }
```
Response 200:
```
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "token_type": "bearer",
  "expires_in": 3600,
  "user": { "id": 1, "name": "User", "email": "user@example.com" }
}
```

| Pengujian (slide 13) | Hasil yang diharapkan |
|---|---|
| Email benar dan password benar | 200 + `access_token` |
| Password salah | 401 `Email atau password salah.` |
| User tidak ditemukan | 401 `Email atau password salah.` (pesan sengaja sama, supaya email yang terdaftar tidak bisa ditebak) |
| JWT berhasil diperoleh | Token terdiri dari tiga bagian dipisah titik: header.payload.signature |

Tip untuk video: tempel token di **jwt.io** untuk menunjukkan tiga bagiannya (Header, Payload berisi `sub` = id user, dan Signature).

## BAGIAN D. Task 02 - Protect Program API

Semua endpoint `/api/programs` (GET, POST, PUT, DELETE) kini dilindungi `JwtMiddleware`.

| Kondisi | Hasil |
|---|---|
| Tanpa header `Authorization` | 401 `{"message": "Unauthenticated."}` |
| Token asal-asalan (`Bearer invalidtoken`) | 401 `{"message": "Token is invalid."}` |
| Token kedaluwarsa (default 60 menit) | 401 `{"message": "Token has expired."}` |
| Token valid | Diteruskan ke Controller, response seperti Pertemuan 10 (200, 201, dst.) |

Catatan: yang dilindungi hanya **API**. Halaman web `/program` (Blade) tetap terbuka, sesuai ruang lingkup tugas.

## BAGIAN E. Task 03 - Test JWT Authentication (Postman atau Insomnia)

1. Impor `postman/Revault-Program-API-JWT.postman_collection.json`.
2. Jika port bukan 8000, ubah variabel koleksi `base_url`.
3. Jalankan **A1 Login benar**. Token otomatis tersimpan di variabel `token` dan dipakai oleh semua request lain (Authorization > Bearer Token).
4. Jalankan folder **B** (hasil 200 dan 201), lalu folder **C** (hasil 401).

**Alternatif dengan curl** (CMD Windows memakai `curl.exe`; ganti port jika perlu):

```
curl.exe -X POST http://127.0.0.1:8000/api/login -H "Content-Type: application/json" -H "Accept: application/json" -d "{\"email\":\"user@example.com\",\"password\":\"password\"}"

curl.exe http://127.0.0.1:8000/api/programs -H "Accept: application/json" -H "Authorization: Bearer ISI_TOKEN_DI_SINI"

curl.exe -i http://127.0.0.1:8000/api/programs -H "Accept: application/json"

curl.exe -i http://127.0.0.1:8000/api/programs -H "Accept: application/json" -H "Authorization: Bearer invalidtoken"
```

Hasil: perintah kedua 200 dengan data, ketiga 401 `Unauthenticated.`, keempat 401 `Token is invalid.`

## BAGIAN F. Urutan demo (slide 15)

1. **Login:** `POST /api/login` dengan email dan password.
2. **JWT:** tunjukkan response 200 berisi `access_token`.
3. **Protected API:** `GET /api/programs`, tab Authorization > Bearer Token > tempel token.
4. **Valid token:** 200 dan data program tampil.
5. **No token:** hapus token, kirim lagi, hasil 401 `Unauthenticated.`
6. **Invalid token:** isi `invalidtoken`, hasil 401 `Token is invalid.`

Tunjukkan juga kode: `AuthController.php`, `JwtMiddleware.php`, dan `routes/api.php` (bagian `Route::middleware(...)`).

## BAGIAN G. Jawaban review (slide 16)

1. **Apa itu authentication?** Proses memastikan identitas pengguna (siapa kamu?) sebelum boleh mengakses resource tertentu, misalnya lewat email dan password.
2. **Beda session dan token?** Pada session, server menyimpan data login pengguna (di server) dan client membawa ID session. Pada token, server tidak menyimpan apa pun: semua informasi ada di token yang dibawa client pada setiap request. Untuk REST API dipakai token.
3. **Apa itu JWT?** JSON Web Token: token yang membawa informasi autentikasi antara client dan server. Terdiri dari Header (algoritma), Payload (data, mis. id user) dan Signature (tanda tangan yang membuktikan token asli).
4. **Mengapa JWT dipakai di REST API?** REST API bersifat stateless (tanpa session), dan client bisa bermacam-macam (web, aplikasi mobile). Token dibawa di setiap request sehingga server tidak perlu menyimpan status login, dan mudah dipakai oleh banyak jenis client.
5. **Dari mana JWT diperoleh?** Dibuat oleh server Laravel setelah login berhasil (`POST /api/login`, dibuat dengan `JWTAuth::fromUser($user)`), lalu dikirim ke client dalam JSON response.
6. **Bagaimana client mengirim JWT?** Lewat header HTTP pada setiap request: `Authorization: Bearer <token>`.
7. **Fungsi middleware?** Lapisan pemeriksa yang berjalan sebelum Controller. Di sini `JwtMiddleware` memeriksa token; jika tidak valid, request dihentikan dan Controller tidak dijalankan.
8. **Apa yang terjadi jika token tidak valid?** Middleware menolak request dengan status 401 Unauthorized dan pesan `Token is invalid.` (atau `Unauthenticated.` jika tidak ada token).
9. **Hubungan JWT dengan protected route?** Protected route adalah route yang dibungkus middleware JWT. Route hanya bisa diakses jika request membawa JWT yang valid.
10. **Alur login sampai API dapat diakses?** User login (email + password) -> Laravel memverifikasi -> JWT dibuat dan dikirim ke client -> client mengirim request dengan `Authorization: Bearer <token>` -> middleware memeriksa token -> Controller -> Model -> Database -> JSON response.

**Pertanyaan utama: apa yang berubah ketika REST API Revault mulai menggunakan JWT authentication?** Sebelumnya (Pertemuan 10) siapa pun yang tahu alamat API bisa membaca, menambah, mengubah, dan menghapus program. Sekarang pengguna harus login lebih dulu untuk mendapat token, dan setiap request harus membawa token yang valid. Request tanpa token atau dengan token palsu ditolak dengan 401 sebelum menyentuh Controller dan database.

## BAGIAN H. Checklist (slide 17)

- [ ] Login API dibuat, credential diverifikasi, JWT dibuat dan dikembalikan (`AuthController`)
- [ ] Token dapat dipakai sebagai Bearer token pada request (Postman A1 lalu B1)
- [ ] Protected route dan JWT middleware (`routes/api.php`, `JwtMiddleware`)
- [ ] Token valid diterima (200), token invalid ditolak (401), tanpa token ditolak (401)
- [ ] Response diperiksa lewat Postman atau Insomnia
- [ ] Commit dan push (Bagian I)

## BAGIAN I. Commit dan push

Dari folder utama repo (`D:\Semester 5\Pemrograman Web\revault`):

```
git status
git add .
git status
git commit -m "Pertemuan 11: JWT authentication (login, middleware, protected API)"
git push origin main
```

Sebelum `git add .`, pastikan `.env` dan `vendor/` tidak muncul di `git status`. `composer.json` dan `composer.lock` di `laravel/` **harus ikut** (berisi paket JWT).

Teman satu kelompok yang meng-clone perlu menjalankan di folder `laravel/`:
```
composer install
copy .env.example .env
php artisan key:generate
php artisan jwt:secret
php artisan migrate --seed
```
(`JWT_SECRET` tidak ikut di-commit, jadi setiap komputer membuat kuncinya sendiri.)

## BAGIAN J. Jika ada masalah

| Gejala | Penyebab dan solusi |
|---|---|
| `composer require` gagal soal versi PHP | Butuh PHP 8.3+. Ganti versi PHP di Laragon (A1) |
| `Interface "...JWTSubject" not found` | Paket belum terpasang. Ulangi A2 |
| Login 500, pesan `Secret is not set` | Belum `php artisan jwt:secret`. Jalankan lalu `php artisan config:clear` |
| `404` pada `/api/login` | `routes/api.php` belum tertimpa, atau `php artisan install:api` (Pertemuan 10) belum dijalankan. Cek `php artisan route:list --path=api` |
| Login 401 padahal benar | User belum dibuat: `php artisan db:seed`. Cek tabel dengan `php artisan db:table users` |
| Token benar tetapi tetap 401 | Header harus persis `Authorization: Bearer <token>` (ada kata Bearer, satu spasi, tanpa tanda kutip). Token berlaku 60 menit; login ulang jika sudah lewat |
| Perubahan `.env` tidak terbaca | `php artisan config:clear`, lalu jalankan ulang `php artisan serve` |
| `Address already in use` atau `forbidden by its access permissions` | Pakai port lain: `php artisan serve --port=9001` |
