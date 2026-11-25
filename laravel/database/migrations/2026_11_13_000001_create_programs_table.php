<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * TASK 01 - Create Database Structure
 *
 * Migration = cara Laravel mendefinisikan struktur tabel lewat KODE (bukan klik-klik di phpMyAdmin).
 * Dijalankan dengan:  php artisan migrate
 *
 * Kolom wajib dari materi : id, nama, deskripsi, created_at, updated_at
 * Kolom tambahan Revault  : kategori, lokasi, foto, target, tercapai
 *
 * Catatan: "status" (Segera / Berjalan / Selesai) TIDAK disimpan sebagai kolom.
 * Status dihitung otomatis dari target dan tercapai (lihat app/Models/Program.php),
 * sama seperti aturan di versi PHP MVC (Week 5-7), sehingga tidak mungkin tidak sinkron.
 */
return new class extends Migration
{
    // up()   : dijalankan saat  php artisan migrate           -> membuat tabel
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();                                   // id (primary key, auto increment)
            $table->string('nama');                         // nama program
            $table->text('deskripsi');                      // deskripsi (teks panjang)

            // ---- kolom khas Revault ----
            $table->string('kategori')->default('Lainnya'); // Pakaian / Kardus / Buku / Elektronik / Lainnya
            $table->string('lokasi')->default('Malang');    // kota penyaluran
            $table->string('foto')->nullable();             // path gambar, contoh: Assets/baju2.jpg
            $table->unsignedInteger('target')->default(0);  // target jumlah barang
            $table->unsignedInteger('tercapai')->default(0); // barang yang sudah tersalurkan

            $table->timestamps();                           // created_at dan updated_at
        });
    }

    // down() : dijalankan saat  php artisan migrate:rollback  -> menghapus tabel
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
