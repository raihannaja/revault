<?php
declare(strict_types=1);

/* Uji sederhana OOP Revault.  Jalankan:  php tests/uji-program.php */
require_once __DIR__ . '/../classes/DaftarProgram.php';

$gagal = 0;
function cek(string $nama, bool $hasil): void
{
    global $gagal;
    echo ($hasil ? '[LULUS] ' : '[GAGAL] ') . $nama . PHP_EOL;
    if (!$hasil) { $gagal++; }
}

$data = require __DIR__ . '/../includes/data-program.php';
$daftar = new DaftarProgram($data);
$p1 = $daftar->semua()[0];

// Task 01: class dan object
cek('Object Program berhasil dibuat', $p1 instanceof Program);
cek('Jumlah object sama dengan jumlah data', $daftar->total() === count($data));

// Task 02: property dan method
cek('Getter mengembalikan data (getNama)', $p1->getNama() === 'Pakaian Layak untuk Semua');
cek('Method getPersen benar (64/100)', $p1->getPersen() === 64);
cek('Method getSisa benar', $p1->getSisa() === 36);
cek('Method getNomor berformat 01', $p1->getNomor() === '01');
cek('Method getProfile benar', $p1->getProfile() === 'Malang · oleh Rina');
cek('Method getTahap mengembalikan array', count($p1->getTahap()) === 3);

// Status kondisional
$status = array_map(fn(Program $p) => $p->getStatus(), $daftar->semua());
cek('Status berjalan / selesai / segera terdeteksi',
    in_array('berjalan', $status, true) && in_array('selesai', $status, true) && in_array('segera', $status, true));
cek('Badge berisi 3 elemen', count($p1->getBadge()) === 3);

// Constructor + setter menjaga nilai
$uji = new Program(['id' => 99, 'nama' => 'Uji', 'kategori' => 'Lainnya', 'deskripsi' => '-', 'foto' => '',
    'lokasi' => '', 'target' => 10, 'tercapai' => 999, 'tahap' => [],
    'penanggung' => ['nama' => 'A', 'wa' => '62811']], 1);
cek('Constructor: tercapai berlebih dibatasi ke target', $uji->getTercapai() === 10);
cek('Constructor: lokasi kosong memakai kota default', $uji->getLokasi() === 'Malang');
$uji->setTercapai(-5);
cek('Setter: nilai negatif menjadi 0', $uji->getTercapai() === 0);

// Encapsulation: property private tidak bisa diakses langsung
try {
    $dummy = $p1->tercapai;
    cek('Encapsulation: akses langsung property private ditolak', false);
} catch (Error $e) {
    cek('Encapsulation: akses langsung property private ditolak', true);
}

// Pengolahan data banyak object
cek('Filter status berjalan hanya berisi berjalan',
    count($daftar->filterStatus('berjalan')) === $daftar->jumlahStatus('berjalan')
    && array_reduce($daftar->filterStatus('berjalan'), fn($c, Program $p) => $c && $p->getStatus() === 'berjalan', true));
cek('Statistik total tercapai/target konsisten', $daftar->persenTotal() === (int) round($daftar->totalTercapai() / $daftar->totalTarget() * 100));

echo PHP_EOL . ($gagal === 0 ? 'SEMUA UJI LULUS' : "$gagal uji GAGAL") . PHP_EOL;
exit($gagal === 0 ? 0 : 1);
