<?php
declare(strict_types=1);

require_once __DIR__ . '/Program.php';

/* =====================================================================
   MODEL: class DaftarProgram  -  kumpulan object Program (data access)
   Peran Model sesuai materi: menyimpan data, mengambil data (muatDariFile,
   semua, cari, filterStatus), dan menghitung data bisnis (statistik).
   ===================================================================== */
class DaftarProgram
{
    /** @var Program[] */
    private array $items = [];

    // Setiap baris array data diubah menjadi satu object Program
    public function __construct(array $dataMentah, string $kotaDefault = 'Malang')
    {
        $no = 1;
        foreach ($dataMentah as $baris) {
            $this->items[] = new Program($baris, $no, $kotaDefault);
            $no++;
        }
    }

    // Data access: membaca array data dari Model/data-program.php
    public static function muatDariFile(string $kotaDefault = 'Malang'): self
    {
        return new self(require __DIR__ . '/data-program.php', $kotaDefault);
    }

    // Mengambil satu program berdasarkan id (null jika tidak ada)
    public function cari(int $id): ?Program
    {
        foreach ($this->items as $p) {
            if ($p->getId() === $id) {
                return $p;
            }
        }
        return null;
    }

    /** @return Program[] */
    public function semua(): array
    {
        return $this->items;
    }

    public function total(): int
    {
        return count($this->items);
    }

    // Program dengan status tertentu; 'semua' mengembalikan seluruh program
    /** @return Program[] */
    public function filterStatus(string $status): array
    {
        if ($status === 'semua') {
            return $this->items;
        }
        return array_values(array_filter(
            $this->items,
            fn(Program $p): bool => $p->getStatus() === $status
        ));
    }

    public function jumlahStatus(string $status): int
    {
        return count($this->filterStatus($status));
    }

    public function totalTercapai(): int
    {
        return array_sum(array_map(fn(Program $p): int => $p->getTercapai(), $this->items));
    }

    public function totalTarget(): int
    {
        return array_sum(array_map(fn(Program $p): int => $p->getTarget(), $this->items));
    }

    public function persenTotal(): int
    {
        $target = $this->totalTarget();
        return $target > 0 ? min(100, (int) round($this->totalTercapai() / $target * 100)) : 0;
    }
}
