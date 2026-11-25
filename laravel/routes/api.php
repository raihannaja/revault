<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;

/*
 * ROUTE REST API (otomatis berawalan /api, tanpa CSRF/session)
 *
 * PERTEMUAN 11 - JWT Authentication
 *
 *   PUBLIK (tanpa token)
 *     POST   /api/login            AuthController@login      -> mengembalikan JWT
 *
 *   PROTECTED (wajib header  Authorization: Bearer <token>)
 *     GET    /api/programs         ProgramController@index
 *     POST   /api/programs         ProgramController@store
 *     GET    /api/programs/{id}    ProgramController@show
 *     PUT    /api/programs/{id}    ProgramController@update
 *     DELETE /api/programs/{id}    ProgramController@destroy
 *
 * Perubahan dari Pertemuan 10: endpoint program yang tadinya terbuka untuk siapa saja
 * kini dibungkus middleware JWT (Route::middleware(...)->group(...)).
 *
 * Lihat daftar route:  php artisan route:list --path=api
 */

// Login: boleh diakses tanpa token (karena dari sinilah token didapat)
Route::post('/login', [AuthController::class, 'login']);

// Protected routes: hanya bisa diakses dengan JWT yang valid
Route::middleware(JwtMiddleware::class)->group(function () {
    Route::apiResource('programs', ProgramController::class);
});
