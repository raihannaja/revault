<?php

use App\Http\Controllers\Api\ProgramController;
use Illuminate\Support\Facades\Route;

/*
 * PERTEMUAN 10 - Task 03: ROUTE REST API
 *
 * File ini otomatis diberi awalan /api oleh Laravel, dan tidak memakai CSRF/session,
 * sehingga cocok dipanggil dari Postman, Insomnia, atau aplikasi frontend/mobile.
 *
 * Satu baris di bawah membuat 5 endpoint:
 *
 *   GET    /api/programs         ProgramController@index    ambil semua
 *   POST   /api/programs         ProgramController@store    buat baru
 *   GET    /api/programs/{id}    ProgramController@show     ambil detail
 *   PUT    /api/programs/{id}    ProgramController@update   ubah (PATCH juga bisa)
 *   DELETE /api/programs/{id}    ProgramController@destroy  hapus
 *
 * Lihat daftar lengkapnya dengan:  php artisan route:list --path=api
 *
 * (Controller API ada di app/Http/Controllers/Api/ProgramController.php,
 *  dibedakan dari ProgramController web yang menghasilkan halaman HTML.)
 */
Route::apiResource('programs', ProgramController::class);
