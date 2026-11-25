<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Support\AturanProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/*
 * PERTEMUAN 10 - Task 01 (Move CRUD into Controller) dan Task 02 (Request & Validation)
 *
 * Alur: User -> Route -> Controller -> (Validation) -> Model/Eloquent -> Database -> View -> User
 *
 * Route (routes/web.php) hanya MENUNJUK ke method di sini. Semua proses CRUD ada di Controller:
 *
 *   index()    GET    /program             READ    daftar program
 *   create()   GET    /program/create      FORM    form tambah
 *   store()    POST   /program             CREATE  validasi + simpan
 *   edit()     GET    /program/{id}/edit   FORM    form edit
 *   update()   PUT    /program/{id}        UPDATE  validasi + ubah
 *   destroy()  DELETE /program/{id}        DELETE  hapus
 *
 * Versi REST API (JSON) ada di app/Http/Controllers/Api/ProgramController.php
 */
class ProgramController extends Controller
{
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

    // FORM: tampilkan form kosong
    public function create(): View
    {
        return view('program.form', $this->dataForm(new Program()));
    }

    // CREATE: terima input lewat Request, validasi, lalu simpan
    public function store(Request $request): RedirectResponse
    {
        // Jika tidak valid, Laravel otomatis kembali ke form membawa pesan error dan input lama.
        $data = $request->validate(
            AturanProgram::rules($request->all()),
            AturanProgram::messages()
        );

        Program::create($data);

        return redirect()->route('program.index')
            ->with('sukses', 'Program "' . $data['nama'] . '" berhasil ditambahkan.');
    }

    // FORM EDIT: tampilkan form berisi data lama
    public function edit($id): View
    {
        $program = Program::findOrFail($id); // otomatis 404 jika id tidak ada

        return view('program.form', $this->dataForm($program));
    }

    // UPDATE: validasi lalu ubah data
    public function update(Request $request, $id): RedirectResponse
    {
        $program = Program::findOrFail($id);

        $data = $request->validate(
            AturanProgram::rules($request->all(), $program->target),
            AturanProgram::messages()
        );

        $program->update($data);

        return redirect()->route('program.index')
            ->with('sukses', 'Program "' . $program->nama . '" berhasil diperbarui.');
    }

    // DELETE: hapus dari database
    public function destroy($id): RedirectResponse
    {
        $program = Program::findOrFail($id);
        $nama = $program->nama;
        $program->delete();

        return redirect()->route('program.index')
            ->with('sukses', 'Program "' . $nama . '" berhasil dihapus.');
    }

    // Demo Eloquent (Pertemuan 9, Task 02): /uji-eloquent dan /uji-eloquent?tambah=1
    public function ujiEloquent(Request $request)
    {
        $hasil = [];

        $hasil['all']  = Program::all();     // BANYAK object
        $hasil['find'] = Program::find(1);   // SATU object (null jika tidak ada)

        if ($request->boolean('tambah')) {
            $hasil['create'] = Program::create([
                'nama'      => 'Program Uji Eloquent',
                'deskripsi' => 'Program contoh yang dibuat dengan Program::create().',
                'target'    => 10,
                'tercapai'  => 0,
            ]);
        }

        return response()->json($hasil, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    // ---------------- bantuan (private) ----------------

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
            'kategori' => AturanProgram::KATEGORI,
            'fotos'    => $fotos,
        ];
    }
}
