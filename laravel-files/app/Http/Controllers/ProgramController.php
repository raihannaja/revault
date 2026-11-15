<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/*
 * TASK 03 - Program CRUD
 *
 * Controller menerima request dari route, memakai Model (Eloquent) untuk bicara
 * dengan database, lalu mengirim hasilnya ke View (Blade).
 * Catatan: Controller, Routing dan Validation dibahas lebih dalam di Pertemuan 10;
 * di sini dibuat versi sederhana yang cukup supaya CRUD berjalan.
 *
 *   C  create + store   -> tambah program
 *   R  index            -> tampilkan program
 *   U  edit + update    -> ubah program
 *   D  destroy          -> hapus program
 */
class ProgramController extends Controller
{
    private const KATEGORI = ['Pakaian', 'Kardus', 'Buku', 'Elektronik', 'Lainnya'];

    // READ: daftar semua program (data dari database, bukan hard-code)
    public function index(): View
    {
        $programs = Program::orderBy('id')->get();

        return view('program.index', [
            'programs'      => $programs,
            'totalTarget'   => $programs->sum('target'),
            'totalTercapai' => $programs->sum('tercapai'),
        ]);
    }

    // CREATE: tampilkan form kosong
    public function create(): View
    {
        $program = new Program([
            'kategori' => 'Lainnya',
            'lokasi'   => 'Malang',
            'target'   => 0,
            'tercapai' => 0,
        ]);

        return view('program.form', $this->dataForm($program));
    }

    // CREATE: simpan ke database
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->aturan());

        Program::create($data);

        return redirect()->route('program.index')
            ->with('sukses', 'Program "' . $data['nama'] . '" berhasil ditambahkan.');
    }

    // UPDATE: tampilkan form berisi data lama
    public function edit(Program $program): View
    {
        return view('program.form', $this->dataForm($program));
    }

    // UPDATE: simpan perubahan
    public function update(Request $request, Program $program): RedirectResponse
    {
        $data = $request->validate($this->aturan());

        $program->update($data);

        return redirect()->route('program.index')
            ->with('sukses', 'Program "' . $program->nama . '" berhasil diperbarui.');
    }

    // DELETE: hapus dari database
    public function destroy(Program $program): RedirectResponse
    {
        $nama = $program->nama;
        $program->delete();

        return redirect()->route('program.index')
            ->with('sukses', 'Program "' . $nama . '" berhasil dihapus.');
    }

    // ---------------- bantuan (private) ----------------

    private function aturan(): array
    {
        return [
            'nama'      => ['required', 'string', 'max:100'],
            'deskripsi' => ['required', 'string'],
            'kategori'  => ['required', 'in:' . implode(',', self::KATEGORI)],
            'lokasi'    => ['required', 'string', 'max:50'],
            'foto'      => ['nullable', 'string', 'max:255'],
            'target'    => ['required', 'integer', 'min:0'],
            'tercapai'  => ['required', 'integer', 'min:0', 'lte:target'],
        ];
    }

    private function dataForm(Program $program): array
    {
        // Foto yang tersedia = gambar di public/Assets (kecuali logo)
        $fotos = [];
        foreach (scandir(public_path('Assets')) ?: [] as $nama) {
            $ext = strtolower(pathinfo($nama, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true) && $nama !== 'logo.png') {
                $fotos[] = 'Assets/' . $nama;
            }
        }

        return [
            'program'  => $program,
            'kategori' => self::KATEGORI,
            'fotos'    => $fotos,
        ];
    }
}
