<?php
declare(strict_types=1);

/* =====================================================================
   MODEL: class Situs  -  data identitas situs Revault
   (nama, tagline, kota default, menu navigasi). Dipakai semua halaman.
   ===================================================================== */
class Situs
{
    private string $nama = 'Revault';
    private string $tagline = 'Give Things Another Life';
    private string $kotaDefault = 'Malang';

    // Menu navigasi (sama dengan index.html)
    private array $menu = [
        ['label' => 'Beranda',           'href' => 'index.html'],
        ['label' => 'Cari Barang',       'href' => 'index.html#barang'],
        ['label' => 'Inspirasi',         'href' => 'index.html#inspirasi'],
        ['label' => 'Program Prioritas', 'href' => 'program.php'],
        ['label' => 'Cara Kerja',        'href' => 'index.html#cara-kerja'],
        ['label' => 'Tentang',           'href' => 'index.html#tentang'],
    ];

    public function getNama(): string        { return $this->nama; }
    public function getTagline(): string     { return $this->tagline; }
    public function getKotaDefault(): string { return $this->kotaDefault; }
    public function getMenu(): array         { return $this->menu; }
    public function getTahun(): string       { return date('Y'); }
}
