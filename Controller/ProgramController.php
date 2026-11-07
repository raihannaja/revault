<?php
declare(strict_types=1);

require_once __DIR__ . '/../Model/DaftarProgram.php';
require_once __DIR__ . '/../Model/Situs.php';

/* =====================================================================
   CONTROLLER: class ProgramController  (padanan PresidentController)

   Tugas Controller sesuai materi Pertemuan 7:
     1. menerima request dari pengguna          -> index(), show()
     2. menjalankan proses dan meminta data ke Model -> process()
     3. meneruskan hasilnya ke View             -> render()

   Alur:  USER -> REQUEST -> CONTROLLER -> MODEL -> DATA
          -> CONTROLLER -> VIEW -> HTML -> USER
   ===================================================================== */
class ProgramController
{
    // Pilihan filter yang diizinkan (kunci = nilai di URL ?status=...)
    private const OPSI_FILTER = [
        'semua' => 'Semua', 'berjalan' => 'Berjalan', 'selesai' => 'Selesai', 'segera' => 'Segera',
    ];

    private Situs $situs;
    private DaftarProgram $daftar;

    // Controller memanggil Model: membuat object Situs dan DaftarProgram
    public function __construct(?Situs $situs = null, ?DaftarProgram $daftar = null)
    {
        $this->situs  = $situs ?? new Situs();
        $this->daftar = $daftar ?? DaftarProgram::muatDariFile($this->situs->getKotaDefault());
    }

    // REQUEST: program.php  atau  program.php?status=berjalan
    public function index(): void
    {
        $data = $this->process($_GET);        // proses request + ambil data dari Model
        $this->render('program-page', $data); // teruskan data ke View
    }

    // REQUEST: program.php?id=3  (detail satu program)
    public function show(int $id): void
    {
        $program = $this->daftar->cari($id);  // minta data ke Model

        if ($program === null) {
            if (!headers_sent()) {
                http_response_code(404);
            }
            $this->render('not-found', $this->dataDasar('Program tidak ditemukan'));
            return;
        }

        $this->render('program-detail', $this->dataDasar($program->getNama(), $program->getDeskripsi()) + [
            'p' => $program,
        ]);
    }

    // PROCESS: membersihkan input request, mengambil data dari Model, menyusun data untuk View
    public function process(array $query): array
    {
        $filter = $query['status'] ?? 'semua';
        if (!is_string($filter) || !array_key_exists($filter, self::OPSI_FILTER)) {
            $filter = 'semua';                // nilai di luar daftar dianggap "semua"
        }

        return $this->dataDasar() + [
            'filter'        => $filter,
            'opsiFilter'    => self::OPSI_FILTER,
            'semuaProgram'  => $this->daftar->semua(),
            'programTampil' => $this->daftar->filterStatus($filter),
            'statistik'     => [
                'total'    => $this->daftar->total(),
                'berjalan' => $this->daftar->jumlahStatus('berjalan'),
                'selesai'  => $this->daftar->jumlahStatus('selesai'),
                'tercapai' => $this->daftar->totalTercapai(),
                'target'   => $this->daftar->totalTarget(),
                'persen'   => $this->daftar->persenTotal(),
            ],
        ];
    }

    // Data yang dibutuhkan semua halaman (kepala dan kaki halaman)
    private function dataDasar(
        string $judul = 'Program Prioritas',
        string $deskripsi = 'Program yang kami dahulukan agar barang yang masih bernilai guna sampai ke orang yang membutuhkannya.'
    ): array {
        return [
            'situs'            => $this->situs,
            'judulHalaman'     => $judul,
            'deskripsiHalaman' => $deskripsi,
            'halamanAktif'     => 'program.php',
        ];
    }

    // Menyerahkan data ke View: tiap kunci array menjadi variabel di file View
    private function render(string $view, array $data): void
    {
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../View/' . $view . '.php';
    }
}
