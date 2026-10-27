<?php
/* =====================================================================
   DATA PROGRAM PRIORITAS REVAULT  (array multidimensi)
   Untuk menambah / mengubah program, cukup edit file ini.

   Setiap program adalah satu array dengan kunci:
     id, nama, kategori, deskripsi, foto, lokasi
     target     : jumlah barang yang ditargetkan
     tercapai   : jumlah barang yang sudah tersalurkan
     tahap      : array daftar tahap kerja (array di dalam array)
     penanggung : array berisi 'nama' dan 'wa' (array di dalam array)

   Nama kunci sengaja sama dengan data JavaScript (nama, kategori,
   deskripsi, foto, lokasi) supaya mudah dipahami.
   Status (Berjalan / Selesai / Segera) TIDAK ditulis di sini, tetapi
   dihitung otomatis oleh class Program (classes/Program.php) dari target dan tercapai.
   ===================================================================== */
return [
    [
        'id'         => 1,
        'nama'       => 'Pakaian Layak untuk Semua',
        'kategori'   => 'Pakaian',
        'deskripsi'  => 'Mengumpulkan kemeja, kaos, dan jaket yang masih bagus agar bisa dipakai lagi oleh yang membutuhkan.',
        'foto'       => 'Assets/baju2.jpg',
        'lokasi'     => 'Malang',
        'target'     => 100,
        'tercapai'   => 64,
        'tahap'      => ['Kumpulkan pakaian', 'Cuci dan pilah', 'Salurkan ke penerima'],
        'penanggung' => ['nama' => 'Rina', 'wa' => '6281234567801'],
    ],
    [
        'id'         => 2,
        'nama'       => 'Kardus Pindahan Gratis',
        'kategori'   => 'Kardus',
        'deskripsi'  => 'Kardus bekas yang masih kuat dibagikan untuk keperluan pindahan dan pengemasan barang.',
        'foto'       => 'Assets/kardus.jpg',
        'lokasi'     => 'Batu',
        'target'     => 150,
        'tercapai'   => 150,
        'tahap'      => ['Terima kardus bekas', 'Rapikan dan lipat', 'Bagikan ke pemohon'],
        'penanggung' => ['nama' => 'Budi', 'wa' => '6281234567802'],
    ],
    [
        'id'         => 3,
        'nama'       => 'Buku untuk Pelajar',
        'kategori'   => 'Buku',
        'deskripsi'  => 'Buku kuliah dan bacaan bekas disalurkan kepada pelajar yang membutuhkan bahan belajar.',
        'foto'       => 'Assets/buku.jpg',
        'lokasi'     => 'Malang',
        'target'     => 200,
        'tercapai'   => 35,
        'tahap'      => ['Kumpulkan buku', 'Cek kelengkapan halaman', 'Salurkan ke pelajar'],
        'penanggung' => ['nama' => 'Rina', 'wa' => '6281234567801'],
    ],
    [
        'id'         => 4,
        'nama'       => 'Elektronik Layak Pakai',
        'kategori'   => 'Elektronik',
        'deskripsi'  => 'Kipas, lampu, dan perangkat kecil yang masih menyala diperiksa lalu diberikan kembali.',
        'foto'       => 'Assets/kipas.jpg',
        'lokasi'     => 'Malang',
        'target'     => 50,
        'tercapai'   => 0,
        'tahap'      => ['Terima perangkat', 'Uji fungsi', 'Salurkan dengan catatan kondisi'],
        'penanggung' => ['nama' => 'Sari', 'wa' => '6281234567803'],
    ],
    [
        'id'         => 5,
        'nama'       => 'Perlengkapan Harian',
        'kategori'   => 'Lainnya',
        'deskripsi'  => 'Tas, sepatu, dan perlengkapan harian yang masih layak dipakai untuk sekolah dan bekerja.',
        'foto'       => 'Assets/bg2.png',
        'lokasi'     => 'Surabaya',
        'target'     => 80,
        'tercapai'   => 12,
        'tahap'      => ['Kumpulkan perlengkapan', 'Bersihkan', 'Salurkan'],
        'penanggung' => ['nama' => 'Dimas', 'wa' => '6281234567804'],
    ],
    [
        'id'         => 6,
        'nama'       => 'Sudut Barang Serbaguna',
        'kategori'   => 'Lainnya',
        'deskripsi'  => 'Titik kumpul barang rumah tangga dan dekorasi yang masih bernilai guna di tiap kota.',
        'foto'       => 'Assets/bg3.jpg',
        'lokasi'     => 'Malang',
        'target'     => 60,
        'tercapai'   => 0,
        'tahap'      => ['Tentukan titik kumpul', 'Buka pendaftaran', 'Mulai penyaluran'],
        'penanggung' => ['nama' => 'Nadia', 'wa' => '6281234567805'],
    ],
];
