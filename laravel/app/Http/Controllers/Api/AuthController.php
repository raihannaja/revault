<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

/*
 * PERTEMUAN 11 - Task 01: Implement JWT Login
 *
 * POST /api/login
 *
 * Alur:  Request -> Controller -> Verify User (cek email + password di database)
 *        -> Generate JWT -> JSON Response berisi token
 *
 * Request body:  { "email": "user@example.com", "password": "password" }
 * Response 200:  { "access_token": "eyJ...", "token_type": "bearer", "expires_in": 3600, "user": {...} }
 * Response 401:  { "message": "Email atau password salah." }
 * Response 422:  data tidak lengkap / format email salah
 */
class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        // 1. Validasi input
        $validator = Validator::make($request->all(), [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // 2. Verifikasi user: cari berdasarkan email, lalu cocokkan password dengan hash di database.
        //    Pesan error SENGAJA sama untuk "email tidak ada" dan "password salah",
        //    supaya penyerang tidak bisa menebak email mana yang terdaftar.
        $user = User::where('email', $request->input('email'))->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }

        // 3. Buat JWT untuk user ini
        $token = JWTAuth::fromUser($user);

        // 4. Kirim token sebagai JSON response
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => (int) config('jwt.ttl', 60) * 60, // detik
            'user'         => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ], 200);
    }
}
