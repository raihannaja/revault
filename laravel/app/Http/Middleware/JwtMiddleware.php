<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

/*
 * PERTEMUAN 11 - Task 02: JWT Middleware (penjaga Protected Route)
 *
 * Middleware = lapisan pemeriksa yang berjalan SEBELUM Controller.
 *
 *   Request -> [JwtMiddleware] -> Controller -> Model -> Database -> JSON
 *                   |
 *                   +-- token tidak ada / tidak valid -> 401 Unauthorized (Controller tidak dijalankan)
 *
 * Client mengirim token lewat header:   Authorization: Bearer <token>
 *
 * Hasil pemeriksaan:
 *   tanpa token       -> 401 {"message": "Unauthenticated."}
 *   token tidak valid -> 401 {"message": "Token is invalid."}
 *   token kedaluwarsa -> 401 {"message": "Token has expired."}
 *   token valid       -> request diteruskan ke Controller
 */
class JwtMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil token dari header "Authorization: Bearer <token>"
        $token = $request->bearerToken();

        if (! $token) {
            return $this->tolak('Unauthenticated.');
        }

        try {
            // Memeriksa tanda tangan (signature) dan masa berlaku token
            $payload = JWTAuth::setToken($token)->getPayload();
        } catch (TokenExpiredException $e) {
            return $this->tolak('Token has expired.');
        } catch (JWTException $e) {
            return $this->tolak('Token is invalid.');
        } catch (\Throwable $e) {
            // Token rusak dalam bentuk lain (misalnya bukan format JWT)
            return $this->tolak('Token is invalid.');
        }

        // Pastikan user di dalam token masih ada di database
        $user = User::find($payload->get('sub'));

        if (! $user) {
            return $this->tolak('Unauthenticated.');
        }

        // Jadikan user ini sebagai user request saat ini ($request->user())
        $request->setUserResolver(fn () => $user);

        return $next($request);
    }

    // 401 Unauthorized dalam bentuk JSON (selalu JSON, tidak bergantung header Accept dari client)
    private function tolak(string $pesan): Response
    {
        return response()->json(['message' => $pesan], 401);
    }
}
