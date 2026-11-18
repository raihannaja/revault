<?php

use App\Http\Controllers\ProgramController;
use Illuminate\Support\Facades\Route;

/*
 * PERTEMUAN 10 - ROUTING (web)
 *
 * Route menghubungkan URL + HTTP method dengan Controller dan method yang dijalankan.
 * Route TIDAK lagi berisi logika; prosesnya ada di ProgramController.
 *
 *   CRUD     HTTP method   URL                    Controller@method
 *   ------   -----------   --------------------   -----------------
 *   READ     GET           /program               index
 *   FORM     GET           /program/create        create
 *   CREATE   POST          /program               store
 *   FORM     GET           /program/{id}/edit     edit
 *   UPDATE   PUT / PATCH   /program/{id}          update
 *   DELETE   DELETE        /program/{id}          destroy
 *
 * (Semua baris CRUD di bawah setara dengan satu baris:
 *    Route::resource('program', ProgramController::class)->except(['show']);
 *  Ditulis satu per satu supaya jelas hubungan URL, HTTP method, dan method controller.)
 */

// Halaman utama -> daftar program
Route::get('/', fn () => redirect()->route('program.index'));

Route::get('/program',              [ProgramController::class, 'index'])->name('program.index');     // READ
Route::get('/program/create',       [ProgramController::class, 'create'])->name('program.create');   // FORM
Route::post('/program',             [ProgramController::class, 'store'])->name('program.store');     // CREATE
Route::get('/program/{id}/edit',    [ProgramController::class, 'edit'])->name('program.edit');       // FORM EDIT
Route::match(['put', 'patch'], '/program/{id}', [ProgramController::class, 'update'])->name('program.update'); // UPDATE
Route::delete('/program/{id}',      [ProgramController::class, 'destroy'])->name('program.destroy'); // DELETE

// Demo Eloquent Pertemuan 9: Program::all(), find(1), create()
Route::get('/uji-eloquent', [ProgramController::class, 'ujiEloquent']);
