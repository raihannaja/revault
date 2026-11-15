<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*
 * TASK 02 - Build Program Model
 *
 * Model = class PHP yang mewakili satu tabel. Class Program <-> tabel "programs"
 * (Laravel otomatis menghubungkannya dari namanya). Satu object Program = satu baris data.
 *
 * Eloquent ORM memungkinkan kita mengelola data database sebagai object:
 *   Program::all()        -> semua baris (banyak object / Collection)
 *   Program::find(1)      -> satu baris berdasarkan id (satu object, atau null jika tidak ada)
 *   Program::create([...]) -> menyimpan baris baru (hanya kolom di $fillable yang boleh diisi)
 */
class Program extends Model
{
    // Kolom yang boleh diisi lewat create() / update() (perlindungan mass assignment).
    protected $fillable = [
        'nama',
        'deskripsi',
        'kategori',
        'lokasi',
        'foto',
        'target',
        'tercapai',
    ];

    // Pastikan angka dibaca sebagai integer, bukan string.
    protected $casts = [
        'target'   => 'integer',
        'tercapai' => 'integer',
    ];

    // ------------------------------------------------------------------
    // ACCESSOR: properti "virtual" yang dihitung dari kolom lain.
    // Dipanggil seperti kolom biasa:  $program->persen, $program->status, $program->badge
    // (logikanya sama dengan class Program di PHP OOP/MVC sebelumnya)
    // ------------------------------------------------------------------

    // Persentase progres 0-100
    public function getPersenAttribute(): int
    {
        if ($this->target <= 0) {
            return 0;
        }
        return (int) min(100, round($this->tercapai / $this->target * 100));
    }

    // Status kondisional dari progres
    public function getStatusAttribute(): string
    {
        if ($this->persen >= 100) {
            return 'selesai';
        } elseif ($this->tercapai > 0) {
            return 'berjalan';
        }
        return 'segera';
    }

    // Badge sesuai status: [kelas badge, label, kelas progress bar]
    public function getBadgeAttribute(): array
    {
        return match ($this->status) {
            'selesai'  => ['bg-forest-soft text-forest', 'Selesai',  'bg-forest'],
            'berjalan' => ['bg-sun/25 text-amber-900',   'Berjalan', 'bg-sun'],
            default    => ['bg-ink/10 text-ink',         'Segera',   'bg-ink/20'],
        };
    }
}
