<?php

use App\Http\Controllers\ProgramController;
use App\Models\Program;
use Illuminate\Support\Facades\Route;

/*
 * Route = pintu masuk aplikasi: mencocokkan URL dengan kode yang dijalankan.
 * (Routing dibahas lebih dalam di Pertemuan 10; di sini cukup yang dibutuhkan CRUD.)
 */

// Halaman utama -> daftar program
Route::get('/', fn () => redirect()->route('program.index'));

// TASK 02 - Uji Eloquent (untuk demo). Buka di browser:
//   /uji-eloquent            -> Program::all() dan Program::find(1)
//   /uji-eloquent?tambah=1   -> ditambah Program::create([...]), data benar-benar masuk database
Route::get('/uji-eloquent', function () {
    $hasil = [];

    // Mengambil semua data  -> BANYAK object
    $hasil['all']  = Program::all();

    // Mengambil satu data   -> SATU object (null jika id tidak ada)
    $hasil['find'] = Program::find(1);

    // Menyimpan data baru
    if (request()->boolean('tambah')) {
        $hasil['create'] = Program::create([
            'nama'      => 'Program Uji Eloquent',
            'deskripsi' => 'Program contoh yang dibuat dengan Program::create().',
            'target'    => 10,
            'tercapai'  => 0,
        ]);
    }

    return response()->json($hasil, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
});

// TASK 03 - CRUD Program. Satu baris ini membuat 6 route sekaligus:
//   GET    /program              program.index    (Read: daftar)
//   GET    /program/create       program.create   (Create: form)
//   POST   /program              program.store    (Create: simpan)
//   GET    /program/{id}/edit    program.edit     (Update: form)
//   PUT    /program/{id}         program.update   (Update: simpan)
//   DELETE /program/{id}         program.destroy  (Delete)
Route::resource('program', ProgramController::class)->except(['show']);
