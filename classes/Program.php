<?php
declare(strict_types=1);

/* =====================================================================
   class Program  -  satu object mewakili satu program prioritas Revault
   (padanan class President di materi Pertemuan 6)

   Task 01 : Create a Class        -> class Program
   Task 02 : Properties & Methods  -> property + method di bawah ini
   Materi  : Constructor           -> __construct()
             Encapsulation         -> property private, diakses lewat
                                      getter/setter yang public
   ===================================================================== */
class Program
{
    // ---- PROPERTY (data milik object). private = hanya bisa diubah dari dalam class ----
    private int $id;
    private int $no;
    private string $nama;
    private string $kategori;
    private string $deskripsi;
    private string $foto;
    private string $lokasi;
    private int $target;
    private int $tercapai = 0;
    private array $tahap;
    private string $penanggungNama;
    private string $penanggungWa;

    // ---- CONSTRUCTOR: dijalankan otomatis saat `new Program(...)` ----
    public function __construct(array $data, int $no, string $kotaDefault = 'Malang')
    {
        $this->id             = (int) $data['id'];
        $this->no             = $no;
        $this->nama           = $data['nama'];
        $this->kategori       = $data['kategori'];
        $this->deskripsi      = $data['deskripsi'];
        $this->foto           = $data['foto'];
        $this->lokasi         = $data['lokasi'] !== '' ? $data['lokasi'] : $kotaDefault;
        $this->target         = max(0, (int) $data['target']);
        $this->tahap          = $data['tahap'];
        $this->penanggungNama = $data['penanggung']['nama'];
        $this->penanggungWa   = $data['penanggung']['wa'];
        $this->setTercapai((int) $data['tercapai']); // lewat setter agar nilainya divalidasi
    }

    // ---- GETTER: cara aman membaca property private ----
    public function getId(): int            { return $this->id; }
    public function getNama(): string       { return $this->nama; }
    public function getKategori(): string   { return $this->kategori; }
    public function getDeskripsi(): string  { return $this->deskripsi; }
    public function getFoto(): string       { return $this->foto; }
    public function getLokasi(): string     { return $this->lokasi; }
    public function getTarget(): int        { return $this->target; }
    public function getTercapai(): int      { return $this->tercapai; }
    public function getPenanggungNama(): string { return $this->penanggungNama; }
    public function getPenanggungWa(): string   { return $this->penanggungWa; }

    // Daftar tahap kerja (padanan getPrograms() di materi)
    public function getTahap(): array       { return $this->tahap; }

    // Nomor urut dua digit: 01, 02, ...
    public function getNomor(): string
    {
        return str_pad((string) $this->no, 2, '0', STR_PAD_LEFT);
    }

    // ---- SETTER: cara aman mengubah data. Nilai dijaga 0 sampai target ----
    public function setTercapai(int $nilai): void
    {
        $this->tercapai = max(0, min($nilai, $this->target));
    }

    // ---- METHOD (perilaku object) ----

    // Ringkasan singkat (padanan getProfile() di materi): "Malang · oleh Rina"
    public function getProfile(): string
    {
        return $this->lokasi . ' · oleh ' . $this->penanggungNama;
    }

    public function getPersen(): int
    {
        if ($this->target <= 0) {
            return 0;
        }
        return min(100, (int) round($this->tercapai / $this->target * 100));
    }

    public function getSisa(): int
    {
        return max(0, $this->target - $this->tercapai);
    }

    // Status kondisional dihitung dari progres
    public function getStatus(): string
    {
        if ($this->getPersen() >= 100) {
            return 'selesai';
        } elseif ($this->tercapai > 0) {
            return 'berjalan';
        }
        return 'segera';
    }

    // Badge sesuai status: ['kelas badge', 'label', 'kelas progress bar']
    public function getBadge(): array
    {
        return match ($this->getStatus()) {
            'selesai'  => ['bg-forest-soft text-forest', 'Selesai',  'bg-forest'],
            'berjalan' => ['bg-sun/25 text-amber-900',   'Berjalan', 'bg-sun'],
            default    => ['bg-ink/10 text-ink',         'Segera',   'bg-ink/20'],
        };
    }

    // Tautan WhatsApp ke penanggung jawab program
    public function getWaLink(string $namaSitus): string
    {
        $pesan = 'Halo ' . $this->penanggungNama . ', saya tertarik dengan program "'
            . $this->nama . '" di ' . $namaSitus . '.';
        return 'https://wa.me/' . $this->penanggungWa . '?text=' . rawurlencode($pesan);
    }
}
