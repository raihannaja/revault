<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

/*
 * Mengisi tabel programs dengan program prioritas Revault.
 * Data awal ini sama dengan Model/data-program.php pada versi PHP MVC (Week 7),
 * tetapi sekarang disimpan di DATABASE, bukan ditulis langsung di HTML.
 *
 * Jalankan:  php artisan db:seed
 * Aman dijalankan berulang kali: updateOrCreate mencocokkan berdasarkan kolom nama,
 * jadi data tidak dobel.
 */
class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            [
                'nama' => 'Pakaian Layak untuk Semua',
                'kategori' => 'Pakaian',
                'deskripsi' => 'Mengumpulkan kemeja, kaos, dan jaket yang masih bagus agar bisa dipakai lagi oleh yang membutuhkan.',
                'foto' => 'Assets/baju2.jpg',
                'lokasi' => 'Malang',
                'target' => 100,
                'tercapai' => 64,
            ],
            [
                'nama' => 'Kardus Pindahan Gratis',
                'kategori' => 'Kardus',
                'deskripsi' => 'Kardus bekas yang masih kuat dibagikan untuk keperluan pindahan dan pengemasan barang.',
                'foto' => 'Assets/kardus.jpg',
                'lokasi' => 'Batu',
                'target' => 150,
                'tercapai' => 150,
            ],
            [
                'nama' => 'Buku untuk Pelajar',
                'kategori' => 'Buku',
                'deskripsi' => 'Buku kuliah dan bacaan bekas disalurkan kepada pelajar yang membutuhkan bahan belajar.',
                'foto' => 'Assets/buku.jpg',
                'lokasi' => 'Malang',
                'target' => 200,
                'tercapai' => 35,
            ],
            [
                'nama' => 'Elektronik Layak Pakai',
                'kategori' => 'Elektronik',
                'deskripsi' => 'Kipas, lampu, dan perangkat kecil yang masih menyala diperiksa lalu diberikan kembali.',
                'foto' => 'Assets/kipas.jpg',
                'lokasi' => 'Malang',
                'target' => 50,
                'tercapai' => 0,
            ],
            [
                'nama' => 'Perlengkapan Harian',
                'kategori' => 'Lainnya',
                'deskripsi' => 'Tas, sepatu, dan perlengkapan harian yang masih layak dipakai untuk sekolah dan bekerja.',
                'foto' => 'Assets/bg2.png',
                'lokasi' => 'Surabaya',
                'target' => 80,
                'tercapai' => 12,
            ],
            [
                'nama' => 'Sudut Barang Serbaguna',
                'kategori' => 'Lainnya',
                'deskripsi' => 'Titik kumpul barang rumah tangga dan dekorasi yang masih bernilai guna di tiap kota.',
                'foto' => 'Assets/bg3.jpg',
                'lokasi' => 'Malang',
                'target' => 60,
                'tercapai' => 0,
            ],
        ];

        foreach ($programs as $data) {
            Program::updateOrCreate([nama => $data[nama]], $data);
        }
    }
}